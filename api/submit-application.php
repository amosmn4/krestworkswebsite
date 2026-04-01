<?php
/**
 * api/submit-application.php
 * Handles full job application submission with file uploads.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/rate_limiter.php';
require_once __DIR__ . '/../mail/mailer.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// CSRF check
csrfAbortIfInvalid();

// Honeypot bot check
if (!empty($_POST['website_url'])) {
    echo json_encode(['success' => true, 'reference' => 'KW-BOT-' . time()]);
    exit;
}

// Rate limiting: max 5 submissions per IP per hour
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
if (!checkRateLimit('job_application', $ip, 5, 3600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many submissions from this IP address. Please try again later.']);
    exit;
}

$pdo = db();

// ============================================================
// COLLECT & SANITIZE INPUTS
// ============================================================
$jobId       = (int)($_POST['job_id'] ?? 0);
$firstName   = trim(htmlspecialchars($_POST['first_name']   ?? '', ENT_QUOTES, 'UTF-8'));
$secondName  = trim(htmlspecialchars($_POST['second_name']  ?? '', ENT_QUOTES, 'UTF-8'));
$lastName    = trim(htmlspecialchars($_POST['last_name']    ?? '', ENT_QUOTES, 'UTF-8'));
$idNumber    = trim(preg_replace('/[^0-9A-Za-z]/', '', $_POST['id_number'] ?? ''));
$gender      = trim($_POST['gender'] ?? '');
$phonePrimary   = trim(htmlspecialchars($_POST['phone_primary']   ?? '', ENT_QUOTES, 'UTF-8'));
$phoneAlternate = trim(htmlspecialchars($_POST['phone_alternate'] ?? '', ENT_QUOTES, 'UTF-8'));
$email       = strtolower(trim($_POST['email'] ?? ''));
$dob         = trim($_POST['date_of_birth'] ?? '');
$homeCounty  = trim(htmlspecialchars($_POST['home_county'] ?? '', ENT_QUOTES, 'UTF-8'));
$isPlwd      = isset($_POST['is_plwd']) ? 1 : 0;
$declaration = isset($_POST['declaration']);

// ============================================================
// VALIDATE
// ============================================================
$errors = [];

if ($jobId <= 0) {
    $errors[] = ['field' => null, 'message' => 'Invalid job position.', 'step' => 1];
}

// Verify job exists and is active
if ($jobId > 0) {
    $jcheck = $pdo->prepare('SELECT id, deadline, max_applicants FROM job_positions WHERE id = ? AND is_active = 1 LIMIT 1');
    $jcheck->execute([$jobId]);
    $jobRow = $jcheck->fetch();
    if (!$jobRow) {
        echo json_encode(['success' => false, 'message' => 'This position is no longer available.', 'step' => 1]);
        exit;
    }
    if ($jobRow['deadline'] && strtotime($jobRow['deadline']) < time()) {
        echo json_encode(['success' => false, 'message' => 'The application deadline for this position has passed.', 'step' => 1]);
        exit;
    }
    if ($jobRow['max_applicants']) {
        $appCount = (int)$pdo->prepare('SELECT COUNT(*) FROM job_applications WHERE job_position_id=? AND status!="draft"')->execute([$jobId]) ?
            (function() use($pdo,$jobId){ $s=$pdo->prepare('SELECT COUNT(*) FROM job_applications WHERE job_position_id=? AND status!="draft"'); $s->execute([$jobId]); return (int)$s->fetchColumn(); })() : 0;
        if ($appCount >= $jobRow['max_applicants']) {
            echo json_encode(['success' => false, 'message' => 'This position has reached its maximum number of applications.']);
            exit;
        }
    }
}

// Duplicate check: same email + job (already applied)
if ($email && $jobId) {
    $dup = $pdo->prepare('SELECT id FROM job_applications WHERE email = ? AND job_position_id = ? AND status != "draft" LIMIT 1');
    $dup->execute([$email, $jobId]);
    if ($dup->fetch()) {
        echo json_encode(['success' => false, 'message' => 'You have already submitted an application for this position with this email address.', 'step' => 1, 'field' => 'email']);
        exit;
    }
}

// Personal details validation
if (mb_strlen($firstName) < 2)                    $errors[] = ['field' => 'first_name',    'message' => 'First name is required (min 2 characters).',     'step' => 1];
if (mb_strlen($lastName) < 2)                     $errors[] = ['field' => 'last_name',     'message' => 'Last name is required (min 2 characters).',      'step' => 1];
if (!preg_match('/^\d{5,20}$/', $idNumber))        $errors[] = ['field' => 'id_number',     'message' => 'ID number must be 5–20 digits.',                 'step' => 1];
if (!in_array($gender, ['male','female','prefer_not_to_say'])) $errors[] = ['field' => 'gender', 'message' => 'Please select a gender.', 'step' => 1];
if (!filter_var($email, FILTER_VALIDATE_EMAIL))    $errors[] = ['field' => 'email',         'message' => 'Please enter a valid email address.',            'step' => 1];
if (strlen($email) > 150)                         $errors[] = ['field' => 'email',         'message' => 'Email address is too long.',                     'step' => 1];
if (!preg_match('/^[\+\d\s\-\(\)]{7,20}$/', $phonePrimary)) $errors[] = ['field' => 'phone_primary', 'message' => 'Please enter a valid phone number.', 'step' => 1];
if (empty($homeCounty))                           $errors[] = ['field' => 'home_county',   'message' => 'Please select your home county.',               'step' => 1];
if (!$dob || !strtotime($dob))                    $errors[] = ['field' => 'date_of_birth', 'message' => 'Please enter a valid date of birth.',           'step' => 1];
if (!$declaration)                                $errors[] = ['field' => 'declaration',   'message' => 'You must agree to the declaration.',            'step' => 5];

// Validate DOB age
if ($dob && strtotime($dob)) {
    $age = (int)floor((time() - strtotime($dob)) / 31557600);
    if ($age < 16 || $age > 80) $errors[] = ['field' => 'date_of_birth', 'message' => 'Date of birth is outside the valid range.', 'step' => 1];
}

// Academic qualifications
$acadLevels   = $_POST['acad_level']       ?? [];
$acadCourses  = $_POST['acad_course']      ?? [];
$acadInstitutions = $_POST['acad_institution'] ?? [];
$acadYears    = $_POST['acad_year']        ?? [];
$acadGrades   = $_POST['acad_grade']       ?? [];

if (empty($acadLevels) || empty($acadLevels[0])) {
    $errors[] = ['field' => null, 'message' => 'At least one academic qualification is required.', 'step' => 2];
}

foreach ($acadLevels as $i => $level) {
    if (empty($level)) continue;
    if (empty($acadCourses[$i]))      $errors[] = ['field' => null, 'message' => "Course name is required for qualification ".($i+1).".", 'step' => 2];
    if (empty($acadInstitutions[$i])) $errors[] = ['field' => null, 'message' => "Institution name is required for qualification ".($i+1).".", 'step' => 2];
    if (empty($acadYears[$i]) || $acadYears[$i] < 1960 || $acadYears[$i] > date('Y'))
        $errors[] = ['field' => null, 'message' => "Valid year required for qualification ".($i+1).".", 'step' => 2];
}

// File validation
$allowedMime = ['application/pdf','image/jpeg','image/png','image/jpg','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
$maxFileSize = 5 * 1024 * 1024; // 5MB

$cvFile  = $_FILES['doc_cv'] ?? null;
$nidFile = $_FILES['doc_national_id'] ?? null;

if (!$cvFile || $cvFile['error'] !== UPLOAD_ERR_OK) {
    $errors[] = ['field' => 'doc_cv', 'message' => 'CV upload is required.', 'step' => 4];
} elseif ($cvFile['size'] > $maxFileSize) {
    $errors[] = ['field' => 'doc_cv', 'message' => 'CV file exceeds 5MB limit.', 'step' => 4];
} elseif (!in_array(mime_content_type($cvFile['tmp_name']), $allowedMime)) {
    $errors[] = ['field' => 'doc_cv', 'message' => 'CV must be PDF, DOC, DOCX, JPG, or PNG.', 'step' => 4];
}

if (!$nidFile || $nidFile['error'] !== UPLOAD_ERR_OK) {
    $errors[] = ['field' => 'doc_national_id', 'message' => 'National ID upload is required.', 'step' => 4];
} elseif ($nidFile['size'] > $maxFileSize) {
    $errors[] = ['field' => 'doc_national_id', 'message' => 'National ID file exceeds 5MB limit.', 'step' => 4];
} elseif (!in_array(mime_content_type($nidFile['tmp_name']), ['application/pdf','image/jpeg','image/png','image/jpg'])) {
    $errors[] = ['field' => 'doc_national_id', 'message' => 'National ID must be PDF, JPG, or PNG.', 'step' => 4];
}

// Validate academic certificate files
$acadFiles = $_FILES['acad_document'] ?? [];
foreach ($acadLevels as $i => $level) {
    if (empty($level)) continue;
    $fileError = $acadFiles['error'][$i] ?? UPLOAD_ERR_NO_FILE;
    if ($fileError !== UPLOAD_ERR_OK) {
        $errors[] = ['field' => null, 'message' => "Certificate upload required for qualification ".($i+1).".", 'step' => 2];
    } elseif (($acadFiles['size'][$i] ?? 0) > $maxFileSize) {
        $errors[] = ['field' => null, 'message' => "Certificate for qualification ".($i+1)." exceeds 5MB.", 'step' => 2];
    }
}

// Return first error
if (!empty($errors)) {
    $firstError = $errors[0];
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => $firstError['message'],
        'field'   => $firstError['field'] ?? null,
        'step'    => $firstError['step'] ?? 1,
    ]);
    exit;
}

// ============================================================
// PROCESS FILE UPLOADS
// ============================================================
$uploadBase = UPLOAD_PATH . 'applications/';
if (!is_dir($uploadBase)) mkdir($uploadBase, 0755, true);

function secureUpload(array $file, string $basePath, string $prefix): ?string {
    $ext   = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $name  = $prefix . '_' . uniqid() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest  = $basePath . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) return null;
    chmod($dest, 0644);
    return $name;
}

$uploadedFiles = [];

try {
    // Generate reference number
    $year = date('Y');
    $lastRef = $pdo->query("SELECT MAX(id) FROM job_applications")->fetchColumn();
    $refNum = 'KW-' . $year . '-' . str_pad(($lastRef ?? 0) + 1, 4, '0', STR_PAD_LEFT);

    // ---- Insert master application record ----
    $pdo->beginTransaction();

    $appStmt = $pdo->prepare("
        INSERT INTO job_applications
            (reference_number, job_position_id, status, first_name, second_name, last_name,
             id_number, gender, phone_primary, phone_alternate, email, date_of_birth,
             home_county, is_plwd, ip_address, user_agent, submitted_at)
        VALUES (?, ?, 'submitted', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $appStmt->execute([
        $refNum, $jobId, $firstName, $secondName ?: null, $lastName,
        $idNumber, $gender, $phonePrimary, $phoneAlternate ?: null,
        $email, $dob, $homeCounty, $isPlwd,
        $ip, substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500)
    ]);
    $applicationId = (int)$pdo->lastInsertId();

    // ---- Academic qualifications ----
    foreach ($acadLevels as $i => $level) {
        if (empty($level)) continue;
        $docPath = null;
        if (isset($acadFiles['tmp_name'][$i]) && $acadFiles['error'][$i] === UPLOAD_ERR_OK) {
            $stored = secureUpload([
                'name'     => $acadFiles['name'][$i],
                'tmp_name' => $acadFiles['tmp_name'][$i],
                'size'     => $acadFiles['size'][$i],
            ], $uploadBase, "acad_{$applicationId}_{$i}");
            if ($stored) { $docPath = 'applications/' . $stored; $uploadedFiles[] = $uploadBase . $stored; }
        }
        $pdo->prepare("INSERT INTO application_academic_qualifications (application_id, level, course_name, institution, year_completed, grade, document_path) VALUES (?,?,?,?,?,?,?)")
            ->execute([$applicationId, $level,
                trim(htmlspecialchars($acadCourses[$i]  ?? '', ENT_QUOTES, 'UTF-8')),
                trim(htmlspecialchars($acadInstitutions[$i] ?? '', ENT_QUOTES, 'UTF-8')),
                (int)($acadYears[$i] ?? 0),
                trim(htmlspecialchars($acadGrades[$i]   ?? '', ENT_QUOTES, 'UTF-8')) ?: null,
                $docPath
            ]);
    }

    // ---- Professional qualifications ----
    $profNames  = $_POST['prof_name']        ?? [];
    $profInsts  = $_POST['prof_institution'] ?? [];
    $profYears  = $_POST['prof_year']        ?? [];
    $profCertNs = $_POST['prof_cert_number'] ?? [];
    $profFiles  = $_FILES['prof_document']   ?? [];
    foreach ($profNames as $i => $pName) {
        $pName = trim(htmlspecialchars($pName ?? '', ENT_QUOTES, 'UTF-8'));
        if (empty($pName)) continue;
        $docPath = null;
        if (isset($profFiles['tmp_name'][$i]) && ($profFiles['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
            $stored = secureUpload([
                'name'     => $profFiles['name'][$i],
                'tmp_name' => $profFiles['tmp_name'][$i],
                'size'     => $profFiles['size'][$i],
            ], $uploadBase, "prof_{$applicationId}_{$i}");
            if ($stored) { $docPath = 'applications/' . $stored; $uploadedFiles[] = $uploadBase . $stored; }
        }
        $pdo->prepare("INSERT INTO application_professional_qualifications (application_id, qualification_name, institution, year_obtained, certification_number, document_path) VALUES (?,?,?,?,?,?)")
            ->execute([$applicationId, $pName,
                trim(htmlspecialchars($profInsts[$i]  ?? '', ENT_QUOTES, 'UTF-8')),
                (int)($profYears[$i] ?? 0) ?: null,
                trim(htmlspecialchars($profCertNs[$i] ?? '', ENT_QUOTES, 'UTF-8')) ?: null,
                $docPath
            ]);
    }

    // ---- Work experience ----
    $expTitles  = $_POST['exp_title']           ?? [];
    $expEmployers = $_POST['exp_employer']      ?? [];
    $expAddresses = $_POST['exp_address']       ?? [];
    $expFroms   = $_POST['exp_year_from']       ?? [];
    $expTos     = $_POST['exp_year_to']         ?? [];
    $expCurrents = $_POST['exp_is_current']     ?? [];
    $expResps   = $_POST['exp_responsibilities'] ?? [];
    foreach ($expTitles as $i => $title) {
        $title    = trim(htmlspecialchars($title ?? '', ENT_QUOTES, 'UTF-8'));
        $employer = trim(htmlspecialchars($expEmployers[$i] ?? '', ENT_QUOTES, 'UTF-8'));
        if (empty($title) && empty($employer)) continue;
        $isCurrent = !empty($expCurrents[$i]) ? 1 : 0;
        $pdo->prepare("INSERT INTO application_work_experience (application_id, job_title, employer_name, employer_address, year_from, year_to, is_current, responsibilities) VALUES (?,?,?,?,?,?,?,?)")
            ->execute([$applicationId, $title ?: '—', $employer ?: '—',
                trim(htmlspecialchars($expAddresses[$i] ?? '', ENT_QUOTES, 'UTF-8')) ?: null,
                (int)($expFroms[$i] ?? 0) ?: null,
                (!$isCurrent && !empty($expTos[$i])) ? (int)$expTos[$i] : null,
                $isCurrent,
                trim(htmlspecialchars($expResps[$i] ?? '', ENT_QUOTES, 'UTF-8')) ?: null,
            ]);
    }

    // ---- Upload CV ----
    $cvStored = secureUpload($cvFile, $uploadBase, "cv_{$applicationId}");
    if (!$cvStored) throw new Exception('CV upload failed.');
    $uploadedFiles[] = $uploadBase . $cvStored;
    $pdo->prepare("INSERT INTO application_documents (application_id, document_type, original_name, stored_name, file_path, file_size, mime_type) VALUES (?,?,?,?,?,?,?)")
        ->execute([$applicationId, 'cv',
            htmlspecialchars(basename($cvFile['name']), ENT_QUOTES, 'UTF-8'),
            $cvStored, 'applications/' . $cvStored, $cvFile['size'],
            mime_content_type($cvFile['tmp_name'])
        ]);

    // ---- Upload National ID ----
    $nidStored = secureUpload($nidFile, $uploadBase, "nid_{$applicationId}");
    if (!$nidStored) throw new Exception('National ID upload failed.');
    $uploadedFiles[] = $uploadBase . $nidStored;
    $pdo->prepare("INSERT INTO application_documents (application_id, document_type, original_name, stored_name, file_path, file_size, mime_type) VALUES (?,?,?,?,?,?,?)")
        ->execute([$applicationId, 'national_id',
            htmlspecialchars(basename($nidFile['name']), ENT_QUOTES, 'UTF-8'),
            $nidStored, 'applications/' . $nidStored, $nidFile['size'],
            mime_content_type($nidFile['tmp_name'])
        ]);

    // ---- Additional documents ----
    if (!empty($_FILES['doc_additional']['name'][0])) {
        $addFiles = $_FILES['doc_additional'];
        $maxAdd   = min(count($addFiles['name']), 3);
        for ($a = 0; $a < $maxAdd; $a++) {
            if ($addFiles['error'][$a] !== UPLOAD_ERR_OK) continue;
            if ($addFiles['size'][$a] > $maxFileSize) continue;
            $addStored = secureUpload([
                'name'     => $addFiles['name'][$a],
                'tmp_name' => $addFiles['tmp_name'][$a],
                'size'     => $addFiles['size'][$a],
            ], $uploadBase, "add_{$applicationId}_{$a}");
            if ($addStored) {
                $uploadedFiles[] = $uploadBase . $addStored;
                $pdo->prepare("INSERT INTO application_documents (application_id, document_type, original_name, stored_name, file_path, file_size, mime_type) VALUES (?,?,?,?,?,?,?)")
                    ->execute([$applicationId, 'additional',
                        htmlspecialchars(basename($addFiles['name'][$a]), ENT_QUOTES, 'UTF-8'),
                        $addStored, 'applications/' . $addStored, $addFiles['size'][$a],
                        mime_content_type($addFiles['tmp_name'][$a])
                    ]);
            }
        }
    }

    $pdo->commit();

    // ---- Send confirmation emails ----
    // Fetch job title
    $jobTitle = $pdo->prepare('SELECT title FROM job_positions WHERE id = ?');
    $jobTitle->execute([$jobId]);
    $jTitle = $jobTitle->fetchColumn() ?: 'Position';

    $emailSent = false;
    try {
        // Applicant confirmation
        $userBody = "
        <h2>Application Received — {$jTitle}</h2>
        <p>Dear <strong>" . e($firstName) . "</strong>,</p>
        <p>Thank you for applying for the position of <strong>" . e($jTitle) . "</strong> at <strong>" . APP_NAME . "</strong>. We have received your application.</p>
        <div style='background:#f9fafb;border-radius:10px;padding:20px;margin:20px 0;border:1px solid #e5e7eb;'>
          <div class='info-row'><span class='info-label'>Reference No.</span><span class='info-value' style='color:#F5A800;font-weight:700;'>{$refNum}</span></div>
          <div class='info-row'><span class='info-label'>Position</span><span class='info-value'>" . e($jTitle) . "</span></div>
          <div class='info-row' style='border:none;'><span class='info-label'>Submitted</span><span class='info-value'>" . date('d M Y, H:i') . "</span></div>
        </div>
        <div class='alert-box'><p>📋 <strong>Keep your reference number safe: {$refNum}</strong><br>You'll need it to track your application status.</p></div>
        <p>Our recruitment team will review your application and contact you if you are shortlisted. Shortlisting typically takes 1–2 weeks after the application deadline.</p>
        <p>If you have any questions, contact us at <a href='mailto:careers@krestworks.com'>careers@krestworks.com</a></p>
        <a href='" . url('careers') . "' class='kw-btn-email'>View All Open Positions</a>
        ";
        sendMail($email, $firstName . ' ' . $lastName, "Application Received — {$jTitle} [Ref: {$refNum}]", $userBody);

        // Admin notification to careers@
        $adminBody = "
        <h2 style='color:#F5A800;'>🎯 New Job Application</h2>
        <p>A new application has been submitted for <strong>" . e($jTitle) . "</strong></p>
        <div style='background:#f9fafb;border-radius:10px;padding:20px;margin:20px 0;border:1px solid #e5e7eb;'>
          <div class='info-row'><span class='info-label'>Reference</span><span class='info-value' style='font-weight:700;'>{$refNum}</span></div>
          <div class='info-row'><span class='info-label'>Name</span><span class='info-value'>" . e($firstName . ' ' . $lastName) . "</span></div>
          <div class='info-row'><span class='info-label'>Email</span><span class='info-value'><a href='mailto:{$email}'>{$email}</a></span></div>
          <div class='info-row'><span class='info-label'>Phone</span><span class='info-value'>" . e($phonePrimary) . "</span></div>
          <div class='info-row'><span class='info-label'>County</span><span class='info-value'>" . e($homeCounty) . "</span></div>
          <div class='info-row' style='border:none;'><span class='info-label'>PLWD</span><span class='info-value'>" . ($isPlwd ? 'Yes' : 'No') . "</span></div>
        </div>
        <a href='" . url('admin/recruitment/applications') . "?id={$applicationId}' class='kw-btn-email'>View Application</a>
        ";
        sendMail('careers@krestworks.com', APP_NAME . ' Recruitment', "New Application: {$jTitle} — {$refNum}", $adminBody);
        $emailSent = true;
    } catch (Exception $mailEx) {
        error_log('ATS mail error for ' . $refNum . ': ' . $mailEx->getMessage());
    }

    // Update email sent flag
    $pdo->prepare('UPDATE job_applications SET email_sent = ? WHERE id = ?')->execute([$emailSent ? 1 : 0, $applicationId]);

    // Clear draft session
    unset($_SESSION['app_drafts']['app_draft_' . $jobId]);

    echo json_encode([
        'success'   => true,
        'message'   => 'Application submitted successfully.',
        'reference' => $refNum,
        'id'        => $applicationId,
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // Clean up any uploaded files
    foreach ($uploadedFiles as $f) { if (file_exists($f)) unlink($f); }
    error_log('ATS submission error: ' . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'We encountered an error processing your application. Please try again. If the problem persists, email careers@krestworks.com',
    ]);
}