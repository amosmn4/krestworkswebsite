<?php
$admin_title  = 'Recruitment — Applications';
$admin_active = 'recruitment';
require_once __DIR__ . '/../admin-header.php';

$pdo = db();

// ---- Action handlers ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    csrfAbortIfInvalid();

    $appId = (int)($_POST['app_id'] ?? 0);
    $action = $_POST['action'];

    try {
        if ($action === 'update_status') {
            $newStatus = $_POST['status'] ?? '';
            $allowed   = ['submitted','screening','shortlisted','interview_scheduled','interview_done','offered','rejected','withdrawn'];
            if (!in_array($newStatus, $allowed)) throw new Exception('Invalid status.');
            $setExtra  = '';
            $extraVals = [];
            if ($newStatus === 'shortlisted') {
                $setExtra  = ', shortlisted_at = NOW(), shortlisted_by = ?';
                $extraVals = [$_SESSION['user_id']];
            }
            $pdo->prepare("UPDATE job_applications SET status = ?, reviewed_by = ?, reviewed_at = NOW() {$setExtra} WHERE id = ?")
                ->execute(array_merge([$newStatus, $_SESSION['user_id']], $extraVals, [$appId]));
            echo json_encode(['success' => true, 'message' => 'Status updated to ' . ucfirst(str_replace('_',' ',$newStatus)) . '.']);

        } elseif ($action === 'save_screening') {
            $score = max(0, min(100, (int)($_POST['score'] ?? 0)));
            $notes = trim(htmlspecialchars($_POST['notes'] ?? '', ENT_QUOTES, 'UTF-8'));
            $pdo->prepare('UPDATE job_applications SET screening_score=?, screening_notes=?, status="screening", reviewed_by=?, reviewed_at=NOW() WHERE id=?')
                ->execute([$score, $notes, $_SESSION['user_id'], $appId]);
            echo json_encode(['success' => true, 'message' => 'Screening saved.']);

        } elseif ($action === 'schedule_interview') {
            $date     = trim($_POST['interview_date'] ?? '');
            $location = trim(htmlspecialchars($_POST['interview_location'] ?? '', ENT_QUOTES, 'UTF-8'));
            $type     = in_array($_POST['interview_type']??'', ['in_person','video','phone']) ? $_POST['interview_type'] : 'in_person';
            $link     = trim(htmlspecialchars($_POST['meeting_link'] ?? '', ENT_QUOTES, 'UTF-8'));
            if (!$date || !strtotime($date)) throw new Exception('Invalid interview date.');

            $pdo->prepare("INSERT INTO application_interviews (application_id, scheduled_by, interview_date, location, interview_type, meeting_link) VALUES (?,?,?,?,?,?)")
                ->execute([$appId, $_SESSION['user_id'], date('Y-m-d H:i:s', strtotime($date)), $location, $type, $link ?: null]);

            $pdo->prepare("UPDATE job_applications SET status='interview_scheduled', interview_date=?, interview_location=? WHERE id=?")
                ->execute([date('Y-m-d H:i:s', strtotime($date)), $location, $appId]);

            // Send invitation email
            $app = $pdo->prepare('SELECT ja.*, jp.title as job_title FROM job_applications ja JOIN job_positions jp ON jp.id=ja.job_position_id WHERE ja.id=?');
            $app->execute([$appId]);
            $appRow = $app->fetch(PDO::FETCH_ASSOC);
            if ($appRow) {
                $typeLabel = ['in_person'=>'In-Person','video'=>'Video Call','phone'=>'Phone Call'][$type];
                $inviteBody = "
                <h2>Interview Invitation — " . e($appRow['job_title']) . "</h2>
                <p>Dear <strong>" . e($appRow['first_name']) . "</strong>,</p>
                <p>Congratulations! You have been shortlisted for an interview for the position of <strong>" . e($appRow['job_title']) . "</strong> at <strong>" . APP_NAME . "</strong>.</p>
                <div style='background:#f9fafb;border-radius:10px;padding:20px;margin:20px 0;border:1px solid #e5e7eb;'>
                  <div class='info-row'><span class='info-label'>Interview Type</span><span class='info-value'>{$typeLabel}</span></div>
                  <div class='info-row'><span class='info-label'>Date & Time</span><span class='info-value' style='color:#F5A800;font-weight:700;'>" . date('D, d M Y \a\t H:i', strtotime($date)) . " EAT</span></div>
                  " . ($location ? "<div class='info-row'><span class='info-label'>Location</span><span class='info-value'>" . e($location) . "</span></div>" : "") . "
                  " . ($link ? "<div class='info-row' style='border:none;'><span class='info-label'>Meeting Link</span><span class='info-value'><a href='" . e($link) . "'>" . e($link) . "</a></span></div>" : "") . "
                </div>
                <div class='alert-box'><p>📋 Please bring your original ID and academic certificates. Arrive 10 minutes early.</p></div>
                <p>To confirm your attendance or reschedule, reply to this email or contact us at <a href='mailto:careers@krestworks.com'>careers@krestworks.com</a></p>
                <a href='mailto:careers@krestworks.com' class='kw-btn-email'>Confirm Attendance</a>
                ";
                sendMail($appRow['email'], $appRow['first_name'] . ' ' . $appRow['last_name'],
                    "Interview Invitation — " . $appRow['job_title'] . " [" . APP_NAME . "]", $inviteBody);

                $pdo->prepare("UPDATE application_interviews SET invitation_sent=1, invitation_sent_at=NOW() WHERE application_id=? ORDER BY id DESC LIMIT 1")
                    ->execute([$appId]);
            }
            echo json_encode(['success' => true, 'message' => 'Interview scheduled and invitation email sent.']);

        } elseif ($action === 'save_interview_notes') {
            $score   = max(0, min(100, (int)($_POST['score'] ?? 0)));
            $notes   = trim(htmlspecialchars($_POST['notes'] ?? '', ENT_QUOTES, 'UTF-8'));
            $outcome = in_array($_POST['outcome']??'', ['pass','fail','pending']) ? $_POST['outcome'] : 'pending';
            $intId   = (int)($_POST['interview_id'] ?? 0);
            $pdo->prepare('UPDATE application_interviews SET score=?, notes=?, outcome=? WHERE id=? AND application_id=?')->execute([$score,$notes,$outcome,$intId,$appId]);
            $pdo->prepare("UPDATE job_applications SET status='interview_done', interview_score=?, interview_notes=? WHERE id=?")->execute([$score,$notes,$appId]);
            echo json_encode(['success' => true, 'message' => 'Interview report saved.']);

        } elseif ($action === 'delete_application') {
            $pdo->prepare('DELETE FROM job_applications WHERE id=?')->execute([$appId]);
            echo json_encode(['success' => true, 'message' => 'Application deleted.']);
        }
    } catch (Exception $e) {
        error_log('ATS admin action error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// ---- Filters ----
$jobFilter    = (int)($_GET['job']    ?? 0);
$statusFilter = $_GET['status']  ?? '';
$search       = trim($_GET['q']  ?? '');
$page_num     = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 15;
$offset       = ($page_num - 1) * $perPage;

$where  = ['ja.status != "draft"'];
$params = [];
if ($jobFilter)    { $where[] = 'ja.job_position_id = ?'; $params[] = $jobFilter; }
if ($statusFilter) { $where[] = 'ja.status = ?';          $params[] = $statusFilter; }
if ($search)       { $where[] = '(ja.first_name LIKE ? OR ja.last_name LIKE ? OR ja.email LIKE ? OR ja.reference_number LIKE ?)'; $params = array_merge($params, ["%$search%","%$search%","%$search%","%$search%"]); }
$wsql = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM job_applications ja WHERE $wsql");
$countStmt->execute($params);
$total      = (int)$countStmt->fetchColumn();
$totalPages = max(1, ceil($total / $perPage));

$apps = $pdo->prepare("
    SELECT ja.*, jp.title AS job_title, jp.slug AS job_slug,
           CONCAT(ja.first_name,' ',COALESCE(ja.second_name,''),' ',ja.last_name) AS full_name,
           (SELECT COUNT(*) FROM application_documents WHERE application_id=ja.id) AS doc_count
    FROM job_applications ja
    JOIN job_positions jp ON jp.id = ja.job_position_id
    WHERE $wsql
    ORDER BY ja.submitted_at DESC
    LIMIT ? OFFSET ?
");
$apps->execute(array_merge($params, [$perPage, $offset]));
$applications = $apps->fetchAll(PDO::FETCH_ASSOC);

// All jobs for filter dropdown
$allJobs = $pdo->query("SELECT id, title FROM job_positions ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);

// Selected application detail
$selectedId  = (int)($_GET['id'] ?? 0);
$selectedApp = null;
$selAcad = $selProf = $selExp = $selDocs = $selInterviews = [];
if ($selectedId) {
    $sa = $pdo->prepare("SELECT ja.*, jp.title AS job_title, jp.slug AS job_slug FROM job_applications ja JOIN job_positions jp ON jp.id=ja.job_position_id WHERE ja.id=?");
    $sa->execute([$selectedId]);
    $selectedApp = $sa->fetch(PDO::FETCH_ASSOC);
    if ($selectedApp) {
        $s2 = $pdo->prepare('SELECT * FROM application_academic_qualifications WHERE application_id=? ORDER BY year_completed DESC'); $s2->execute([$selectedId]); $selAcad = $s2->fetchAll(PDO::FETCH_ASSOC);
        $s3 = $pdo->prepare('SELECT * FROM application_professional_qualifications WHERE application_id=?'); $s3->execute([$selectedId]); $selProf = $s3->fetchAll(PDO::FETCH_ASSOC);
        $s4 = $pdo->prepare('SELECT * FROM application_work_experience WHERE application_id=? ORDER BY year_from DESC'); $s4->execute([$selectedId]); $selExp = $s4->fetchAll(PDO::FETCH_ASSOC);
        $s5 = $pdo->prepare('SELECT * FROM application_documents WHERE application_id=?'); $s5->execute([$selectedId]); $selDocs = $s5->fetchAll(PDO::FETCH_ASSOC);
        $s6 = $pdo->prepare('SELECT * FROM application_interviews WHERE application_id=? ORDER BY created_at DESC'); $s6->execute([$selectedId]); $selInterviews = $s6->fetchAll(PDO::FETCH_ASSOC);
    }
}

$statusColors = [
    'submitted'           => '#3B82F6',
    'screening'           => '#F5A800',
    'shortlisted'         => '#22C55E',
    'interview_scheduled' => '#A855F7',
    'interview_done'      => '#06B6D4',
    'offered'             => '#22C55E',
    'rejected'            => '#EF4444',
    'withdrawn'           => '#6B7280',
];
$qualLabels = ['kcse'=>'KCSE','certificate'=>'Certificate','diploma'=>'Diploma','degree'=>'Degree','masters'=>'Masters','phd'=>'PhD'];
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
  <div>
    <h1 style="font-size:1.1rem;margin:0;">Applications</h1>
    <p style="font-size:0.72rem;color:var(--kw-text-muted);"><?= number_format($total) ?> applications<?= $jobFilter ? ' for selected position' : '' ?></p>
  </div>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
    <!-- Search -->
    <form method="GET" style="display:flex;gap:0;">
      <?php if ($jobFilter): ?><input type="hidden" name="job" value="<?= $jobFilter ?>"><?php endif; ?>
      <?php if ($statusFilter): ?><input type="hidden" name="status" value="<?= e($statusFilter) ?>"><?php endif; ?>
      <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search name, email, ref…" style="padding:0.4rem 0.75rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-right:none;border-radius:var(--kw-radius-md) 0 0 var(--kw-radius-md);font-size:0.78rem;color:var(--kw-text-primary);outline:none;width:180px;">
      <button type="submit" style="padding:0.4rem 0.75rem;background:var(--kw-primary);border:none;border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;cursor:pointer;color:#0A0F1A;"><i class="fa-solid fa-search"></i></button>
    </form>
    <!-- Job filter -->
    <form method="GET" style="display:flex;">
      <?php if ($search): ?><input type="hidden" name="q" value="<?= e($search) ?>"><?php endif; ?>
      <?php if ($statusFilter): ?><input type="hidden" name="status" value="<?= e($statusFilter) ?>"><?php endif; ?>
      <select name="job" onchange="this.form.submit()" style="padding:0.4rem 0.65rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.78rem;color:var(--kw-text-primary);outline:none;cursor:pointer;">
        <option value="0">All Positions</option>
        <?php foreach ($allJobs as $j): ?><option value="<?=$j['id']?>" <?=$jobFilter===$j['id']?'selected':''?>><?= e($j['title']) ?></option><?php endforeach; ?>
      </select>
    </form>
    <!-- Export button -->
    <a href="?<?= http_build_query(array_merge($_GET, ['export'=>'xlsx'])) ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-file-excel"></i> Export</a>
    <!-- Add application -->
    <a href="<?= url('admin/recruitment/add') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-plus"></i> Add</a>
  </div>
</div>

<!-- Status filter tabs -->
<div style="display:flex;gap:0.4rem;margin-bottom:1.1rem;flex-wrap:wrap;">
  <?php
  $statuses = [''=> 'All', 'submitted'=>'Submitted','screening'=>'Screening','shortlisted'=>'Shortlisted','interview_scheduled'=>'Interview Scheduled','interview_done'=>'Interview Done','offered'=>'Offered','rejected'=>'Rejected'];
  foreach ($statuses as $sv => $sl):
    $active = $statusFilter === $sv;
    $color  = $sv ? ($statusColors[$sv] ?? '#6B7280') : 'var(--kw-primary)';
  ?>
  <a href="?status=<?= urlencode($sv) ?><?= $jobFilter?"&job=$jobFilter":'' ?><?= $search?"&q=".urlencode($search):'' ?>"
     style="padding:0.3rem 0.75rem;border-radius:999px;border:1px solid <?= $active?"$color":"var(--kw-border)" ?>;background:<?= $active?"{$color}15":"none" ?>;color:<?= $active?$color:"var(--kw-text-muted)" ?>;font-size:0.7rem;font-weight:700;text-decoration:none;white-space:nowrap;">
    <?= $sl ?>
  </a>
  <?php endforeach; ?>
</div>

<div style="display:grid;grid-template-columns:1fr <?= $selectedApp ? '440px' : '' ?>;gap:1.25rem;align-items:flex-start;">

  <!-- Applications table -->
  <div class="adm-card" style="padding:0;overflow:hidden;">
    <?php if (isset($_GET['export']) && $_GET['export'] === 'xlsx'): ?>
    <?php
    // Simple CSV export
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="applications_'.date('Y-m-d').'.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Reference','Name','Email','Phone','ID Number','Gender','County','DOB','PLWD','Position','Status','Applied Date','Screening Score']);
    $expStmt = $pdo->prepare("SELECT ja.reference_number,CONCAT(ja.first_name,' ',COALESCE(ja.second_name,''),' ',ja.last_name) as full_name,ja.email,ja.phone_primary,ja.id_number,ja.gender,ja.home_county,ja.date_of_birth,ja.is_plwd,jp.title,ja.status,ja.submitted_at,ja.screening_score FROM job_applications ja JOIN job_positions jp ON jp.id=ja.job_position_id WHERE $wsql ORDER BY ja.submitted_at DESC");
    $expStmt->execute($params);
    while ($row = $expStmt->fetch(PDO::FETCH_ASSOC)) fputcsv($out, $row);
    fclose($out); exit;
    ?>
    <?php endif; ?>

    <div style="overflow-x:auto;">
    <table class="adm-table" id="apps-table">
      <thead>
        <tr>
          <th><input type="checkbox" id="select-all" onchange="toggleAll(this)" style="accent-color:var(--kw-primary);"></th>
          <th>Reference</th>
          <th>Applicant</th>
          <th>Position</th>
          <th>Status</th>
          <th>Score</th>
          <th>Applied</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($applications as $app):
        $color = $statusColors[$app['status']] ?? '#6B7280';
        $sel   = $selectedApp && $selectedApp['id'] === $app['id'];
      ?>
      <tr style="<?= $sel ? 'background:rgba(245,168,0,0.05);' : '' ?>" class="app-row" data-status="<?= $app['status'] ?>">
        <td><input type="checkbox" class="app-checkbox" value="<?= $app['id'] ?>" style="accent-color:var(--kw-primary);"></td>
        <td style="font-size:0.68rem;font-family:monospace;font-weight:700;white-space:nowrap;"><?= e($app['reference_number']) ?></td>
        <td>
          <div style="font-weight:700;font-size:0.78rem;white-space:nowrap;"><?= e(trim($app['full_name'])) ?></div>
          <div style="font-size:0.62rem;color:var(--kw-text-muted);"><?= e($app['email']) ?></div>
          <div style="font-size:0.6rem;color:var(--kw-text-muted);"><?= e($app['home_county']) ?><?= $app['is_plwd'] ? ' · <span style="color:#A855F7;">PLWD</span>' : '' ?></div>
        </td>
        <td style="font-size:0.75rem;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= e($app['job_title']) ?>"><?= e($app['job_title']) ?></td>
        <td>
          <span style="background:<?= $color ?>15;color:<?= $color ?>;border-radius:999px;padding:0.15rem 0.55rem;font-size:0.62rem;font-weight:700;white-space:nowrap;">
            <?= ucwords(str_replace('_', ' ', $app['status'])) ?>
          </span>
        </td>
        <td>
          <?php if ($app['screening_score'] !== null): ?>
          <div style="display:flex;align-items:center;gap:0.35rem;">
            <span style="font-weight:700;font-size:0.78rem;color:<?= $app['screening_score']>=70?'#22C55E':($app['screening_score']>=50?'#F5A800':'#EF4444') ?>;"><?= $app['screening_score'] ?></span>
            <div style="width:40px;height:4px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
              <div style="height:100%;width:<?= $app['screening_score'] ?>%;background:<?= $app['screening_score']>=70?'#22C55E':($app['screening_score']>=50?'#F5A800':'#EF4444') ?>;border-radius:999px;"></div>
            </div>
          </div>
          <?php else: ?>
          <span style="font-size:0.65rem;color:var(--kw-text-muted);">—</span>
          <?php endif; ?>
        </td>
        <td style="font-size:0.68rem;white-space:nowrap;"><?= $app['submitted_at'] ? date('d M Y', strtotime($app['submitted_at'])) : '—' ?></td>
        <td>
          <div style="display:flex;gap:0.3rem;">
            <a href="?id=<?= $app['id'] ?><?= $jobFilter?"&job=$jobFilter":'' ?><?= $statusFilter?"&status=$statusFilter":'' ?>" style="padding:0.22rem 0.45rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);text-decoration:none;" title="View">
              <i class="fa-solid fa-eye"></i>
            </a>
            <button onclick="quickStatus(<?= $app['id'] ?>,'shortlisted')" title="Shortlist" style="padding:0.22rem 0.45rem;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:4px;font-size:0.65rem;color:#22C55E;cursor:pointer;">
              <i class="fa-solid fa-check"></i>
            </button>
            <button onclick="quickStatus(<?= $app['id'] ?>,'rejected')" title="Reject" style="padding:0.22rem 0.45rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.2);border-radius:4px;font-size:0.65rem;color:#EF4444;cursor:pointer;">
              <i class="fa-solid fa-times"></i>
            </button>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (empty($applications)): ?>
      <tr><td colspan="8" style="text-align:center;padding:2.5rem;color:var(--kw-text-muted);font-size:0.82rem;">No applications found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div style="padding:0.75rem 1rem;border-top:1px solid var(--kw-border);display:flex;gap:0.35rem;flex-wrap:wrap;align-items:center;">
      <span style="font-size:0.68rem;color:var(--kw-text-muted);margin-right:0.5rem;">Page <?= $page_num ?> of <?= $totalPages ?></span>
      <?php for ($p = max(1,$page_num-2); $p <= min($totalPages,$page_num+2); $p++): ?>
      <a href="?page=<?= $p ?><?= $jobFilter?"&job=$jobFilter":'' ?><?= $statusFilter?"&status=$statusFilter":'' ?><?= $search?"&q=".urlencode($search):'' ?>"
         style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;border-radius:4px;border:1px solid <?= $p===$page_num?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= $p===$page_num?'var(--kw-primary)':'transparent' ?>;color:<?= $p===$page_num?'#0A0F1A':'var(--kw-text-muted)' ?>;font-size:0.68rem;font-weight:700;text-decoration:none;"><?= $p ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Application detail panel -->
  <?php if ($selectedApp): ?>
  <div class="adm-card" style="padding:0;overflow:hidden;position:sticky;top:calc(var(--adm-topbar)+0.75rem);max-height:90vh;overflow-y:auto;">

    <!-- Detail header -->
    <div style="padding:0.9rem 1rem;border-bottom:1px solid var(--kw-border);display:flex;justify-content:space-between;align-items:flex-start;gap:0.5rem;">
      <div>
        <div style="font-size:0.62rem;color:var(--kw-text-muted);font-family:monospace;"><?= e($selectedApp['reference_number']) ?></div>
        <div style="font-weight:700;font-size:0.875rem;"><?= e(trim($selectedApp['first_name'].' '.($selectedApp['second_name']??'').' '.$selectedApp['last_name'])) ?></div>
        <div style="font-size:0.68rem;color:var(--kw-text-muted);"><?= e($selectedApp['job_title']) ?></div>
      </div>
      <a href="?<?= $jobFilter?"job=$jobFilter&":'' ?><?= $statusFilter?"status=$statusFilter&":'' ?>" style="font-size:0.7rem;color:var(--kw-text-muted);text-decoration:none;">✕</a>
    </div>

    <!-- Status + quick actions -->
    <div style="padding:0.85rem 1rem;border-bottom:1px solid var(--kw-border);display:flex;gap:0.4rem;flex-wrap:wrap;">
      <?php foreach (['submitted'=>'Submit','screening'=>'Screen','shortlisted'=>'Shortlist','interview_scheduled'=>'Schedule','interview_done'=>'Done','offered'=>'Offer','rejected'=>'Reject'] as $sv=>$sl):
        $isActive = $selectedApp['status'] === $sv;
        $c = $statusColors[$sv] ?? '#6B7280';
      ?>
      <button onclick="quickStatus(<?= $selectedApp['id'] ?>,'<?= $sv ?>',true)" style="padding:0.2rem 0.55rem;border-radius:999px;border:1px solid <?= $isActive?"$c":"var(--kw-border)" ?>;background:<?= $isActive?"{$c}15":"none" ?>;color:<?= $isActive?$c:"var(--kw-text-muted)" ?>;font-size:0.62rem;font-weight:700;cursor:pointer;white-space:nowrap;"><?= $sl ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Tabs -->
    <div style="display:flex;border-bottom:1px solid var(--kw-border);">
      <?php foreach (['info'=>'Details','academic'=>'Academic','experience'=>'Experience','docs'=>'Documents','screening'=>'Screening','interview'=>'Interview'] as $tabId=>$tabLabel): ?>
      <button class="detail-tab" data-tab="<?= $tabId ?>" onclick="switchTab('<?= $tabId ?>')" style="padding:0.55rem 0.75rem;border:none;background:none;font-size:0.7rem;font-weight:700;color:var(--kw-text-muted);cursor:pointer;border-bottom:2px solid transparent;transition:all 0.15s;"><?= $tabLabel ?></button>
      <?php endforeach; ?>
    </div>

    <div style="padding:1rem;">

      <!-- Info tab -->
      <div id="tab-info" class="detail-tab-panel">
        <?php foreach ([
          ['Name', trim($selectedApp['first_name'].' '.($selectedApp['second_name']??'').' '.$selectedApp['last_name'])],
          ['Email', $selectedApp['email']],
          ['Primary Phone', $selectedApp['phone_primary']],
          ['Alternate Phone', $selectedApp['phone_alternate'] ?? '—'],
          ['ID Number', $selectedApp['id_number']],
          ['Gender', ucfirst(str_replace('_',' ',$selectedApp['gender']))],
          ['Date of Birth', $selectedApp['date_of_birth'] ? date('d M Y', strtotime($selectedApp['date_of_birth'])) : '—'],
          ['Age', $selectedApp['age'] ?? '—'],
          ['County', $selectedApp['home_county']],
          ['PLWD', $selectedApp['is_plwd'] ? '<span style="color:#A855F7;font-weight:700;">Yes</span>' : 'No'],
          ['Applied', $selectedApp['submitted_at'] ? date('d M Y H:i', strtotime($selectedApp['submitted_at'])) : '—'],
        ] as $row): ?>
        <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);font-size:0.72rem;">
          <span style="color:var(--kw-text-muted);font-weight:600;"><?= $row[0] ?></span>
          <span style="font-weight:700;text-align:right;max-width:60%;word-break:break-word;"><?= is_string($row[1]) ? e($row[1]) : $row[1] ?></span>
        </div>
        <?php endforeach; ?>
        <div style="margin-top:0.75rem;display:flex;gap:0.4rem;flex-wrap:wrap;">
          <a href="mailto:<?= e($selectedApp['email']) ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-envelope"></i> Email</a>
          <?php if ($selectedApp['phone_primary']): ?><a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$selectedApp['phone_primary']) ?>" target="_blank" class="kw-btn kw-btn-sm" style="background:#25D366;color:#fff;border:none;"><i class="fa-brands fa-whatsapp"></i></a><?php endif; ?>
          <button onclick="admDelete('admin/recruitment/applications',<?= $selectedApp['id'] ?>,()=>location.href='?')" class="kw-btn kw-btn-sm" style="border-color:rgba(239,68,68,0.3);color:#EF4444;background:rgba(239,68,68,0.06);"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>

      <!-- Academic tab -->
      <div id="tab-academic" class="detail-tab-panel" style="display:none;">
        <?php if (empty($selAcad)): ?><p style="font-size:0.78rem;color:var(--kw-text-muted);">No academic qualifications recorded.</p><?php endif; ?>
        <?php foreach ($selAcad as $acad): ?>
        <div style="background:var(--kw-bg-alt);border-radius:8px;padding:0.85rem;margin-bottom:0.65rem;">
          <div style="font-weight:700;font-size:0.82rem;color:var(--kw-primary);margin-bottom:0.4rem;"><?= $qualLabels[$acad['level']] ?? $acad['level'] ?></div>
          <?php foreach ([['Course',$acad['course_name']],['Institution',$acad['institution']],['Year',$acad['year_completed']],['Grade',$acad['grade']??'—']] as $row): ?>
          <div style="display:flex;justify-content:space-between;font-size:0.72rem;padding:0.2rem 0;">
            <span style="color:var(--kw-text-muted);"><?= $row[0] ?></span><span style="font-weight:600;"><?= e($row[1]) ?></span>
          </div>
          <?php endforeach; ?>
          <?php if ($acad['document_path']): ?><a href="<?= url('admin/recruitment/download?file='.urlencode($acad['document_path']).'&app_id='.$selectedApp['id']) ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="margin-top:0.5rem;font-size:0.65rem;padding:0.2rem 0.5rem;" target="_blank"><i class="fa-solid fa-download"></i> Certificate</a><?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php if (!empty($selProf)): ?>
        <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--kw-text-muted);margin:0.75rem 0 0.5rem;">Professional</div>
        <?php foreach ($selProf as $prof): ?>
        <div style="background:var(--kw-bg-alt);border-radius:8px;padding:0.85rem;margin-bottom:0.5rem;">
          <div style="font-weight:700;font-size:0.82rem;color:#3B82F6;margin-bottom:0.3rem;"><?= e($prof['qualification_name']) ?></div>
          <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= e($prof['institution']) ?> · <?= e($prof['year_obtained']) ?></div>
          <?php if ($prof['certification_number']): ?><div style="font-size:0.68rem;color:var(--kw-text-muted);">Cert #<?= e($prof['certification_number']) ?></div><?php endif; ?>
          <?php if ($prof['document_path']): ?><a href="<?= url('admin/recruitment/download?file='.urlencode($prof['document_path']).'&app_id='.$selectedApp['id']) ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="margin-top:0.4rem;font-size:0.65rem;padding:0.2rem 0.5rem;" target="_blank"><i class="fa-solid fa-download"></i> Certificate</a><?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Experience tab -->
      <div id="tab-experience" class="detail-tab-panel" style="display:none;">
        <?php if (empty($selExp)): ?><p style="font-size:0.78rem;color:var(--kw-text-muted);">No work experience recorded.</p><?php endif; ?>
        <?php foreach ($selExp as $exp): ?>
        <div style="background:var(--kw-bg-alt);border-radius:8px;padding:0.85rem;margin-bottom:0.65rem;">
          <div style="font-weight:700;font-size:0.82rem;color:#22C55E;margin-bottom:0.25rem;"><?= e($exp['job_title']) ?></div>
          <div style="font-size:0.78rem;font-weight:600;margin-bottom:0.2rem;"><?= e($exp['employer_name']) ?></div>
          <?php if ($exp['employer_address']): ?><div style="font-size:0.68rem;color:var(--kw-text-muted);margin-bottom:0.25rem;"><?= e($exp['employer_address']) ?></div><?php endif; ?>
          <div style="font-size:0.68rem;color:var(--kw-text-muted);margin-bottom:0.5rem;"><?= $exp['year_from'] ?> – <?= $exp['is_current'] ? 'Present' : ($exp['year_to'] ?? '?') ?><?= $exp['is_current'] ? ' <span style="background:rgba(34,197,94,0.1);color:#22C55E;border-radius:999px;padding:0.05rem 0.4rem;font-size:0.62rem;font-weight:700;">Current</span>' : '' ?></div>
          <?php if ($exp['responsibilities']): ?><div style="font-size:0.72rem;color:var(--kw-text-secondary);line-height:1.55;"><?= nl2br(e($exp['responsibilities'])) ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Documents tab -->
      <div id="tab-docs" class="detail-tab-panel" style="display:none;">
        <?php if (empty($selDocs)): ?><p style="font-size:0.78rem;color:var(--kw-text-muted);">No documents uploaded.</p><?php endif; ?>
        <?php foreach ($selDocs as $doc):
          $docColors = ['cv'=>'#3B82F6','national_id'=>'#F5A800','academic_cert'=>'#22C55E','professional_cert'=>'#A855F7','additional'=>'#6B7280'];
          $c = $docColors[$doc['document_type']] ?? '#6B7280';
        ?>
        <div style="display:flex;align-items:center;gap:0.65rem;padding:0.65rem;background:var(--kw-bg-alt);border-radius:8px;margin-bottom:0.5rem;">
          <div style="width:32px;height:32px;border-radius:6px;background:<?=$c?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid fa-file-<?= str_contains($doc['mime_type'],'pdf')?'pdf':'image' ?>" style="color:<?=$c?>;font-size:0.82rem;"></i>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:0.75rem;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($doc['original_name']) ?></div>
            <div style="font-size:0.62rem;color:var(--kw-text-muted);"><?= ucwords(str_replace('_',' ',$doc['document_type'])) ?> · <?= $doc['file_size'] ? number_format($doc['file_size']/1024,1).' KB' : '' ?></div>
          </div>
          <a href="<?= url('admin/recruitment/download?file='.urlencode($doc['file_path']).'&app_id='.$selectedApp['id']) ?>" target="_blank" style="padding:0.25rem 0.5rem;background:var(--kw-bg);border:1px solid var(--kw-border);border-radius:4px;font-size:0.65rem;color:var(--kw-primary);text-decoration:none;" title="Download">
            <i class="fa-solid fa-download"></i>
          </a>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Screening tab -->
      <div id="tab-screening" class="detail-tab-panel" style="display:none;">
        <form id="screening-form">
          <?= csrfField() ?>
          <input type="hidden" name="action" value="save_screening">
          <input type="hidden" name="app_id" value="<?= $selectedApp['id'] ?>">
          <div style="margin-bottom:0.85rem;">
            <label class="adm-label">Screening Score (0–100)</label>
            <div style="display:flex;align-items:center;gap:0.75rem;">
              <input type="range" name="score" min="0" max="100" value="<?= $selectedApp['screening_score'] ?? 50 ?>" id="score-range"
                style="flex:1;accent-color:var(--kw-primary);" oninput="document.getElementById('score-val').textContent=this.value">
              <span id="score-val" style="font-size:1rem;font-weight:800;color:var(--kw-primary);min-width:32px;"><?= $selectedApp['screening_score'] ?? 50 ?></span>
            </div>
          </div>
          <div style="margin-bottom:0.85rem;">
            <label class="adm-label">Screening Notes</label>
            <textarea name="notes" class="adm-input" rows="4" placeholder="Observations, red flags, strengths…"><?= e($selectedApp['screening_notes'] ?? '') ?></textarea>
          </div>
          <div id="screening-alert"></div>
          <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" id="screening-btn"><i class="fa-solid fa-save"></i> Save Screening</button>
        </form>
      </div>

      <!-- Interview tab -->
      <div id="tab-interview" class="detail-tab-panel" style="display:none;">
        <?php if (empty($selInterviews)): ?>
        <!-- Schedule form -->
        <form id="interview-form">
          <?= csrfField() ?>
          <input type="hidden" name="action" value="schedule_interview">
          <input type="hidden" name="app_id" value="<?= $selectedApp['id'] ?>">
          <div style="display:grid;gap:0.75rem;margin-bottom:0.85rem;">
            <div><label class="adm-label">Date & Time <span style="color:#EF4444;">*</span></label>
              <input type="datetime-local" name="interview_date" class="adm-input" required min="<?= date('Y-m-d\TH:i') ?>"></div>
            <div><label class="adm-label">Interview Type</label>
              <select name="interview_type" class="adm-input adm-select">
                <option value="in_person">In-Person</option>
                <option value="video">Video Call</option>
                <option value="phone">Phone Call</option>
              </select></div>
            <div><label class="adm-label">Location / Address</label>
              <input type="text" name="interview_location" class="adm-input" placeholder="e.g. Westlands Office, Nairobi" maxlength="255"></div>
            <div><label class="adm-label">Meeting Link (Video)</label>
              <input type="url" name="meeting_link" class="adm-input" placeholder="https://meet.google.com/…" maxlength="500"></div>
          </div>
          <div id="interview-alert"></div>
          <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" id="int-btn"><i class="fa-solid fa-calendar-plus"></i> Schedule & Send Invitation</button>
        </form>
        <?php else: ?>
        <!-- Show existing interviews + add notes -->
        <?php foreach ($selInterviews as $int): ?>
        <div style="background:var(--kw-bg-alt);border-radius:8px;padding:0.85rem;margin-bottom:0.75rem;">
          <div style="font-weight:700;font-size:0.78rem;margin-bottom:0.35rem;color:var(--kw-primary);"><?= date('d M Y H:i', strtotime($int['interview_date'])) ?></div>
          <div style="font-size:0.72rem;margin-bottom:0.25rem;"><?= ucfirst($int['interview_type']) ?> <?= $int['location'] ? '· '.e($int['location']) : '' ?></div>
          <div style="font-size:0.65rem;margin-bottom:0.5rem;color:var(--kw-text-muted);">Invitation: <?= $int['invitation_sent'] ? '✅ Sent '.date('d M',strtotime($int['invitation_sent_at'])) : '⏳ Pending' ?></div>
          <?php if ($int['score'] !== null): ?>
          <div style="font-size:0.72rem;">Score: <strong style="color:<?= $int['score']>=70?'#22C55E':($int['score']>=50?'#F5A800':'#EF4444') ?>"><?= $int['score'] ?>/100</strong> · Outcome: <strong><?= ucfirst($int['outcome']) ?></strong></div>
          <?php if ($int['notes']): ?><div style="font-size:0.72rem;color:var(--kw-text-muted);margin-top:0.3rem;"><?= nl2br(e($int['notes'])) ?></div><?php endif; ?>
          <?php else: ?>
          <!-- Add interview notes form -->
          <form class="interview-notes-form" style="margin-top:0.5rem;">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="save_interview_notes">
            <input type="hidden" name="app_id" value="<?= $selectedApp['id'] ?>">
            <input type="hidden" name="interview_id" value="<?= $int['id'] ?>">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:0.5rem;">
              <div><label class="adm-label" style="font-size:0.6rem;">Score (0-100)</label>
                <input type="number" name="score" class="adm-input" min="0" max="100" placeholder="75" style="font-size:0.78rem;padding:0.3rem 0.5rem;"></div>
              <div><label class="adm-label" style="font-size:0.6rem;">Outcome</label>
                <select name="outcome" class="adm-input adm-select" style="font-size:0.78rem;padding:0.3rem 0.5rem;">
                  <option value="pending">Pending</option><option value="pass">Pass</option><option value="fail">Fail</option>
                </select></div>
            </div>
            <textarea name="notes" class="adm-input" rows="2" placeholder="Interview notes…" style="margin-bottom:0.4rem;font-size:0.75rem;"></textarea>
            <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" style="font-size:0.7rem;padding:0.25rem 0.65rem;"><i class="fa-solid fa-save"></i> Save Report</button>
          </form>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div><!-- /pad -->
  </div><!-- /detail panel -->
  <?php endif; ?>

</div>

<script>
// Tab switching
function switchTab(id) {
  document.querySelectorAll('.detail-tab-panel').forEach(el => el.style.display='none');
  document.querySelectorAll('.detail-tab').forEach(btn => { btn.style.color='var(--kw-text-muted)'; btn.style.borderBottomColor='transparent'; });
  document.getElementById('tab-'+id).style.display='';
  document.querySelectorAll(`.detail-tab[data-tab="${id}"]`).forEach(btn => { btn.style.color='var(--kw-primary)'; btn.style.borderBottomColor='var(--kw-primary)'; });
}
switchTab('info');

// Quick status update
async function quickStatus(id, status, reload=false) {
  const fd = new FormData(); fd.append('action','update_status'); fd.append('app_id',id); fd.append('status',status);
  fd.append('<?= CSRF_TOKEN_NAME ?>',document.querySelector('meta[name="csrf-token"]')?.content||'');
  const r = await fetch(location.pathname,{method:'POST',body:fd}); const d = await r.json();
  window.Krest?.toast(d.message,d.success?'success':'error');
  if(d.success) { reload ? location.reload() : setTimeout(()=>location.reload(),800); }
}

// Bulk selection
function toggleAll(cb) { document.querySelectorAll('.app-checkbox').forEach(el => el.checked = cb.checked); }

// Screening form
document.getElementById('screening-form')?.addEventListener('submit', async function(e){
  e.preventDefault(); const btn=document.getElementById('screening-btn'); btn.disabled=true;
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
  document.getElementById('screening-alert').innerHTML=`<div class="kw-alert kw-alert-${d.success?'success':'danger'}" style="margin-bottom:0.75rem;">${d.message}</div>`;
  btn.disabled=false; if(d.success) setTimeout(()=>location.reload(),700);
});

// Interview scheduling form
document.getElementById('interview-form')?.addEventListener('submit', async function(e){
  e.preventDefault(); const btn=document.getElementById('int-btn'); btn.disabled=true; btn.innerHTML='⏳ Scheduling…';
  const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
  document.getElementById('interview-alert').innerHTML=`<div class="kw-alert kw-alert-${d.success?'success':'danger'}" style="margin-bottom:0.75rem;">${d.message}</div>`;
  btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-calendar-plus"></i> Schedule & Send Invitation';
  if(d.success) setTimeout(()=>location.reload(),800);
});

// Interview notes forms
document.querySelectorAll('.interview-notes-form').forEach(form => {
  form.addEventListener('submit', async function(e){
    e.preventDefault(); const btn=this.querySelector('[type=submit]'); btn.disabled=true;
    const r=await fetch(location.pathname,{method:'POST',body:new FormData(this)}); const d=await r.json();
    window.Krest?.toast(d.message,d.success?'success':'error'); btn.disabled=false;
    if(d.success) setTimeout(()=>location.reload(),700);
  });
});
</script>

<style>@media(max-width:1100px){div[style*="1fr 440px"]{grid-template-columns:1fr!important;}}</style>
<?php require_once __DIR__ . '/../admin-footer.php'; ?>