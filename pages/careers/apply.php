<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: ' . url('careers')); exit; }

require_once __DIR__ . '/../../includes/header.php';

$pdo = db();

// Load job
$jstmt = $pdo->prepare('SELECT * FROM job_positions WHERE slug = ? AND is_active = 1 LIMIT 1');
$jstmt->execute([$slug]);
$job = $jstmt->fetch(PDO::FETCH_ASSOC);

if (!$job) { header('Location: ' . url('careers')); exit; }

// Closed check
$isClosed = $job['deadline'] && strtotime($job['deadline']) < time();
if ($isClosed) { header('Location: ' . url('careers/' . $slug)); exit; }

$page_title = 'Apply — ' . e($job['title']) . ' — ' . APP_NAME;

// ---- Session-based draft saving ----
$draftKey = 'app_draft_' . $job['id'];
if (!isset($_SESSION['app_drafts'])) $_SESSION['app_drafts'] = [];
$draft = $_SESSION['app_drafts'][$draftKey] ?? [];

// ---- Duplicate application check (same IP + job, or same email + job) ----
// We check on submit — see apply.php endpoint

// Load Kenya counties
$counties = ['Baringo','Bomet','Bungoma','Busia','Elgeyo-Marakwet','Embu','Garissa','Homa Bay','Isiolo','Kajiado','Kakamega','Kericho','Kiambu','Kilifi','Kirinyaga','Kisii','Kisumu','Kitui','Kwale','Laikipia','Lamu','Machakos','Makueni','Mandera','Marsabit','Meru','Migori','Mombasa','Murang\'a','Nairobi','Nakuru','Nandi','Narok','Nyamira','Nyandarua','Nyeri','Samburu','Siaya','Taita-Taveta','Tana River','Tharaka-Nithi','Trans Nzoia','Turkana','Uasin Gishu','Vihiga','Wajir','West Pokot'];
$qualLevels = ['kcse'=>'KCSE','certificate'=>'Certificate','diploma'=>'Diploma','degree'=>'Bachelor\'s Degree','masters'=>'Master\'s Degree','phd'=>'PhD'];
?>

<!-- Hero -->
<section class="kw-page-hero" style="min-height:220px;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('careers') ?>">Careers</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('careers/' . $slug) ?>"><?= e($job['title']) ?></a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Apply</span>
    </div>
    <div style="padding:1.75rem 0 2rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-paper-plane"></i> Job Application</span>
      <h1 style="font-size:clamp(1.3rem,3vw,2rem);">Apply: <?= e($job['title']) ?></h1>
      <p style="color:rgba(255,255,255,0.55);font-size:0.85rem;"><i class="fa-solid fa-location-dot" style="color:var(--kw-primary);margin-right:0.3rem;"></i><?= e($job['location'] ?? 'Kenya') ?> &nbsp;·&nbsp; Your progress is saved automatically as you fill in the form.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2.5rem 0 5rem;">
  <div class="kw-container" style="max-width:860px;">

    <!-- Progress bar -->
    <div style="margin-bottom:2rem;" data-aos="fade-up">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.6rem;">
        <?php foreach ([1=>'Personal Details',2=>'Qualifications',3=>'Work Experience',4=>'Documents',5=>'Review'] as $step => $label): ?>
        <div class="step-indicator" data-step="<?= $step ?>" style="flex:1;text-align:center;position:relative;">
          <div style="width:32px;height:32px;border-radius:50%;margin:0 auto 0.3rem;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;border:2px solid var(--kw-border);background:var(--kw-bg);color:var(--kw-text-muted);transition:all 0.3s;" class="step-circle"><?= $step ?></div>
          <div style="font-size:0.6rem;font-weight:600;color:var(--kw-text-muted);line-height:1.2;" class="step-label"><?= $label ?></div>
          <?php if ($step < 5): ?><div style="position:absolute;top:15px;left:50%;right:-50%;height:2px;background:var(--kw-border);z-index:-1;" class="step-line"></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <form id="application-form" novalidate enctype="multipart/form-data">
      <?= csrfField() ?>
      <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
      <input type="hidden" name="job_slug" value="<?= e($slug) ?>">
      <input type="hidden" name="step" id="current-step" value="1">

      <!-- ============================
           STEP 1: PERSONAL DETAILS
           ============================ -->
      <div class="form-step active" data-step="1">
        <div class="kw-card" style="padding:2rem;margin-bottom:1rem;">
          <h3 style="font-size:1.05rem;margin-bottom:0.25rem;"><i class="fa-solid fa-user" style="color:var(--kw-primary);margin-right:0.5rem;"></i>Personal Details</h3>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">All fields marked with <span style="color:#EF4444;">*</span> are required.</p>

          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">First Name <span style="color:#EF4444;">*</span></label>
              <input type="text" name="first_name" class="kw-input" placeholder="Jane" required maxlength="100"
                value="<?= e($draft['first_name'] ?? '') ?>" autocomplete="given-name">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Second Name</label>
              <input type="text" name="second_name" class="kw-input" placeholder="Wanjiku (optional)" maxlength="100"
                value="<?= e($draft['second_name'] ?? '') ?>" autocomplete="additional-name">
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Last Name <span style="color:#EF4444;">*</span></label>
              <input type="text" name="last_name" class="kw-input" placeholder="Muthoni" required maxlength="100"
                value="<?= e($draft['last_name'] ?? '') ?>" autocomplete="family-name">
              <div class="kw-field-error"></div>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">National ID Number <span style="color:#EF4444;">*</span></label>
              <input type="text" name="id_number" class="kw-input" placeholder="12345678" required maxlength="20"
                pattern="[0-9]{5,20}" value="<?= e($draft['id_number'] ?? '') ?>">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Gender <span style="color:#EF4444;">*</span></label>
              <select name="gender" class="kw-input" required>
                <option value="">— Select Gender —</option>
                <?php foreach (['male'=>'Male','female'=>'Female','prefer_not_to_say'=>'Prefer not to say'] as $k=>$v): ?>
                <option value="<?= $k ?>" <?= ($draft['gender']??'') === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
              </select>
              <div class="kw-field-error"></div>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">Date of Birth <span style="color:#EF4444;">*</span></label>
              <input type="date" name="date_of_birth" class="kw-input" required
                max="<?= date('Y-m-d', strtotime('-16 years')) ?>"
                min="<?= date('Y-m-d', strtotime('-80 years')) ?>"
                value="<?= e($draft['date_of_birth'] ?? '') ?>"
                onchange="updateAge(this.value)">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Age</label>
              <input type="text" id="age-display" class="kw-input" readonly placeholder="Auto-calculated"
                style="background:var(--kw-bg-alt);cursor:not-allowed;"
                value="<?= $draft['date_of_birth'] ? (date('Y') - date('Y', strtotime($draft['date_of_birth']))) . ' years' : '' ?>">
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">Primary Phone <span style="color:#EF4444;">*</span></label>
              <input type="tel" name="phone_primary" class="kw-input" placeholder="+254 700 000 000" required maxlength="20"
                value="<?= e($draft['phone_primary'] ?? '') ?>" autocomplete="tel">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Alternate Phone</label>
              <input type="tel" name="phone_alternate" class="kw-input" placeholder="+254 711 000 000 (optional)" maxlength="20"
                value="<?= e($draft['phone_alternate'] ?? '') ?>">
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="kw-form-group">
              <label class="kw-label">Email Address <span style="color:#EF4444;">*</span></label>
              <input type="email" name="email" class="kw-input" placeholder="jane@example.com" required maxlength="150"
                value="<?= e($draft['email'] ?? '') ?>" autocomplete="email">
              <div class="kw-field-error"></div>
            </div>
            <div class="kw-form-group">
              <label class="kw-label">Home County <span style="color:#EF4444;">*</span></label>
              <select name="home_county" class="kw-input" required>
                <option value="">— Select County —</option>
                <?php foreach ($counties as $county): ?>
                <option value="<?= e($county) ?>" <?= ($draft['home_county']??'') === $county ? 'selected' : '' ?>><?= e($county) ?></option>
                <?php endforeach; ?>
              </select>
              <div class="kw-field-error"></div>
            </div>
          </div>

          <div style="display:flex;align-items:center;gap:0.85rem;padding:1rem;background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:var(--kw-radius-lg);">
            <input type="checkbox" name="is_plwd" id="is_plwd" value="1" style="width:18px;height:18px;accent-color:var(--kw-primary);cursor:pointer;flex-shrink:0;" <?= !empty($draft['is_plwd']) ? 'checked' : '' ?>>
            <label for="is_plwd" style="font-size:0.82rem;cursor:pointer;line-height:1.5;">
              <strong>I am a Person Living With a Disability (PLWD)</strong><br>
              <span style="color:var(--kw-text-muted);font-size:0.72rem;">Krestworks is an equal opportunity employer and welcomes applications from persons with disabilities.</span>
            </label>
          </div>
        </div>
      </div>

      <!-- ============================
           STEP 2: QUALIFICATIONS
           ============================ -->
      <div class="form-step" data-step="2" style="display:none;">
        <div class="kw-card" style="padding:2rem;margin-bottom:1rem;">
          <h3 style="font-size:1.05rem;margin-bottom:0.25rem;"><i class="fa-solid fa-graduation-cap" style="color:var(--kw-primary);margin-right:0.5rem;"></i>Academic Qualifications</h3>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Add at least one academic qualification. Click "Add Another" to add more.</p>

          <div id="academic-entries">
            <div class="academic-entry" style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:0.85rem;position:relative;">
              <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-primary);margin-bottom:0.85rem;">Qualification 1</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:0.85rem;">
                <div class="kw-form-group" style="grid-column:span 2;">
                  <label class="kw-label">Qualification Level <span style="color:#EF4444;">*</span></label>
                  <select name="acad_level[]" class="kw-input" required onchange="handleAcadLevel(this)">
                    <option value="">— Select Level —</option>
                    <?php foreach ($qualLevels as $k=>$v): ?><option value="<?= $k ?>"><?= $v ?></option><?php endforeach; ?>
                  </select>
                  <div class="kw-field-error"></div>
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Course / Field of Study <span style="color:#EF4444;">*</span></label>
                  <input type="text" name="acad_course[]" class="kw-input" placeholder="e.g. Computer Science" required maxlength="200">
                  <div class="kw-field-error"></div>
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Institution Name <span style="color:#EF4444;">*</span></label>
                  <input type="text" name="acad_institution[]" class="kw-input" placeholder="e.g. University of Nairobi" required maxlength="200">
                  <div class="kw-field-error"></div>
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Year Completed <span style="color:#EF4444;">*</span></label>
                  <input type="number" name="acad_year[]" class="kw-input" placeholder="<?= date('Y') ?>" required min="1960" max="<?= date('Y') ?>">
                  <div class="kw-field-error"></div>
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Grade / GPA (Optional)</label>
                  <input type="text" name="acad_grade[]" class="kw-input" placeholder="e.g. Second Upper, 3.5 GPA" maxlength="50">
                </div>
                <div class="kw-form-group" style="grid-column:span 2;">
                  <label class="kw-label">Attach Certificate / Transcript <span style="color:#EF4444;">*</span></label>
                  <input type="file" name="acad_document[]" class="kw-input" accept=".pdf,.jpg,.jpeg,.png" style="padding:0.5rem;" required>
                  <div style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.25rem;">PDF, JPG, or PNG. Max 5MB.</div>
                  <div class="kw-field-error"></div>
                </div>
              </div>
            </div>
          </div>

          <button type="button" onclick="addAcademicEntry()" class="kw-btn kw-btn-ghost kw-btn-sm" style="margin-bottom:0;">
            <i class="fa-solid fa-plus"></i> Add Another Qualification
          </button>
        </div>

        <div class="kw-card" style="padding:2rem;margin-bottom:1rem;">
          <h3 style="font-size:1.05rem;margin-bottom:0.25rem;"><i class="fa-solid fa-certificate" style="color:#3B82F6;margin-right:0.5rem;"></i>Professional Qualifications</h3>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Optional. Add any professional certifications or licences.</p>

          <div id="professional-entries">
            <div class="professional-entry" style="background:var(--kw-bg-alt);border:1px dashed var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:0.85rem;">
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:0.85rem;">
                <div class="kw-form-group">
                  <label class="kw-label">Qualification Name</label>
                  <input type="text" name="prof_name[]" class="kw-input" placeholder="e.g. CISM, PMP, CPA" maxlength="200">
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Issuing Institution</label>
                  <input type="text" name="prof_institution[]" class="kw-input" placeholder="e.g. ICPAK, PMI" maxlength="200">
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Year Obtained</label>
                  <input type="number" name="prof_year[]" class="kw-input" placeholder="<?= date('Y') ?>" min="1980" max="<?= date('Y') ?>">
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Certification Number</label>
                  <input type="text" name="prof_cert_number[]" class="kw-input" placeholder="Optional" maxlength="100">
                </div>
                <div class="kw-form-group" style="grid-column:span 2;">
                  <label class="kw-label">Attach Certificate</label>
                  <input type="file" name="prof_document[]" class="kw-input" accept=".pdf,.jpg,.jpeg,.png" style="padding:0.5rem;">
                  <div style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.25rem;">PDF, JPG, or PNG. Max 5MB.</div>
                </div>
              </div>
            </div>
          </div>

          <button type="button" onclick="addProfessionalEntry()" class="kw-btn kw-btn-ghost kw-btn-sm">
            <i class="fa-solid fa-plus"></i> Add Professional Qualification
          </button>
        </div>
      </div>

      <!-- ============================
           STEP 3: WORK EXPERIENCE
           ============================ -->
      <div class="form-step" data-step="3" style="display:none;">
        <div class="kw-card" style="padding:2rem;margin-bottom:1rem;">
          <h3 style="font-size:1.05rem;margin-bottom:0.25rem;"><i class="fa-solid fa-briefcase" style="color:#22C55E;margin-right:0.5rem;"></i>Work Experience</h3>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Add your work history, starting with the most recent. Fresh graduates may skip this section.</p>

          <div id="experience-entries">
            <div class="experience-entry" style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:0.85rem;position:relative;">
              <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#22C55E;margin-bottom:0.85rem;">Experience 1</div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:0.85rem;">
                <div class="kw-form-group">
                  <label class="kw-label">Job Title</label>
                  <input type="text" name="exp_title[]" class="kw-input" placeholder="e.g. Software Engineer" maxlength="200">
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Employer / Company Name</label>
                  <input type="text" name="exp_employer[]" class="kw-input" placeholder="Company name" maxlength="200">
                </div>
                <div class="kw-form-group" style="grid-column:span 2;">
                  <label class="kw-label">Employer Address</label>
                  <input type="text" name="exp_address[]" class="kw-input" placeholder="City, Country" maxlength="300">
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Year From</label>
                  <input type="number" name="exp_year_from[]" class="kw-input" placeholder="2020" min="1970" max="<?= date('Y') ?>">
                </div>
                <div class="kw-form-group">
                  <label class="kw-label">Year To</label>
                  <div style="display:flex;align-items:center;gap:0.65rem;">
                    <input type="number" name="exp_year_to[]" class="kw-input" placeholder="2023" min="1970" max="<?= date('Y') ?>" id="exp-year-to-0" style="flex:1;">
                    <label style="display:flex;align-items:center;gap:0.4rem;font-size:0.78rem;cursor:pointer;white-space:nowrap;">
                      <input type="checkbox" name="exp_is_current[]" value="1" style="accent-color:var(--kw-primary);" onchange="toggleCurrentJob(this)"> Current
                    </label>
                  </div>
                </div>
                <div class="kw-form-group" style="grid-column:span 2;">
                  <label class="kw-label">Key Responsibilities</label>
                  <textarea name="exp_responsibilities[]" class="kw-input" rows="3" maxlength="2000" placeholder="Briefly describe your main responsibilities and achievements…"></textarea>
                </div>
              </div>
            </div>
          </div>

          <button type="button" onclick="addExperienceEntry()" class="kw-btn kw-btn-ghost kw-btn-sm">
            <i class="fa-solid fa-plus"></i> Add Another Position
          </button>
        </div>
      </div>

      <!-- ============================
           STEP 4: DOCUMENTS
           ============================ -->
      <div class="form-step" data-step="4" style="display:none;">
        <div class="kw-card" style="padding:2rem;margin-bottom:1rem;">
          <h3 style="font-size:1.05rem;margin-bottom:0.25rem;"><i class="fa-solid fa-paperclip" style="color:#F97316;margin-right:0.5rem;"></i>Document Uploads</h3>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Upload your CV and National ID. Additional documents are optional.</p>

          <div style="display:flex;flex-direction:column;gap:1.1rem;">

            <!-- CV -->
            <div class="kw-form-group">
              <label class="kw-label">Curriculum Vitae (CV) <span style="color:#EF4444;">*</span></label>
              <div class="file-upload-zone" id="cv-zone" onclick="document.getElementById('cv-file').click()" style="border:2px dashed var(--kw-border);border-radius:var(--kw-radius-lg);padding:2rem;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--kw-bg-alt);" onmouseover="this.style.borderColor='var(--kw-primary)'" onmouseout="this.style.borderColor='var(--kw-border)'" ondragover="event.preventDefault();this.style.borderColor='var(--kw-primary)'" ondrop="handleFileDrop(event,'cv-file','cv-name')">
                <i class="fa-solid fa-file-pdf" style="font-size:2rem;color:var(--kw-primary);margin-bottom:0.5rem;display:block;"></i>
                <div style="font-weight:700;font-size:0.875rem;margin-bottom:0.25rem;" id="cv-name">Click to upload or drag & drop your CV</div>
                <div style="font-size:0.72rem;color:var(--kw-text-muted);">PDF, DOC, DOCX — Max 5MB</div>
              </div>
              <input type="file" name="doc_cv" id="cv-file" accept=".pdf,.doc,.docx" required style="display:none;" onchange="showFileName(this,'cv-name')">
              <div class="kw-field-error"></div>
            </div>

            <!-- National ID -->
            <div class="kw-form-group">
              <label class="kw-label">National ID (Copy) <span style="color:#EF4444;">*</span></label>
              <div class="file-upload-zone" onclick="document.getElementById('nid-file').click()" style="border:2px dashed var(--kw-border);border-radius:var(--kw-radius-lg);padding:2rem;text-align:center;cursor:pointer;transition:all 0.2s;background:var(--kw-bg-alt);" onmouseover="this.style.borderColor='var(--kw-primary)'" onmouseout="this.style.borderColor='var(--kw-border)'" ondragover="event.preventDefault();this.style.borderColor='var(--kw-primary)'" ondrop="handleFileDrop(event,'nid-file','nid-name')">
                <i class="fa-solid fa-id-card" style="font-size:2rem;color:#3B82F6;margin-bottom:0.5rem;display:block;"></i>
                <div style="font-weight:700;font-size:0.875rem;margin-bottom:0.25rem;" id="nid-name">Click to upload your National ID</div>
                <div style="font-size:0.72rem;color:var(--kw-text-muted);">PDF, JPG, PNG — Max 5MB</div>
              </div>
              <input type="file" name="doc_national_id" id="nid-file" accept=".pdf,.jpg,.jpeg,.png" required style="display:none;" onchange="showFileName(this,'nid-name')">
              <div class="kw-field-error"></div>
            </div>

            <!-- Additional -->
            <div class="kw-form-group">
              <label class="kw-label">Additional Documents (Optional)</label>
              <input type="file" name="doc_additional[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                class="kw-input" style="padding:0.5rem;">
              <div style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.3rem;">Portfolio, references, cover letter, etc. Up to 3 files, 5MB each.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- ============================
           STEP 5: REVIEW & SUBMIT
           ============================ -->
      <div class="form-step" data-step="5" style="display:none;">
        <div class="kw-card" style="padding:2rem;margin-bottom:1rem;">
          <h3 style="font-size:1.05rem;margin-bottom:0.25rem;"><i class="fa-solid fa-clipboard-check" style="color:#22C55E;margin-right:0.5rem;"></i>Review & Submit</h3>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1.5rem;">Please review your application before submitting. You cannot edit after submission.</p>

          <div id="review-summary" style="display:flex;flex-direction:column;gap:0.75rem;">
            <!-- filled by JS -->
          </div>

          <div style="margin-top:1.5rem;padding:1.1rem;background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15);border-radius:var(--kw-radius-lg);">
            <label style="display:flex;align-items:flex-start;gap:0.75rem;cursor:pointer;">
              <input type="checkbox" name="declaration" id="declaration" required style="width:18px;height:18px;accent-color:var(--kw-primary);margin-top:2px;flex-shrink:0;">
              <span style="font-size:0.8rem;line-height:1.6;">
                I declare that all information provided in this application is true and accurate to the best of my knowledge.
                I understand that any false statement may result in my application being rejected or employment being terminated.
                I consent to Krestworks Solutions processing my personal data for recruitment purposes.
              </span>
            </label>
            <div class="kw-field-error" id="declaration-error"></div>
          </div>

          <!-- Honeypot -->
          <input type="text" name="website_url" style="display:none;" tabindex="-1" autocomplete="off">
        </div>
      </div>

      <!-- Navigation buttons -->
      <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;">
        <button type="button" id="btn-prev" onclick="changeStep(-1)" class="kw-btn kw-btn-ghost" style="display:none;">
          <i class="fa-solid fa-arrow-left"></i> Previous
        </button>
        <div style="flex:1;"></div>
        <div style="font-size:0.72rem;color:var(--kw-text-muted);display:flex;align-items:center;gap:0.4rem;" id="save-status">
          <i class="fa-solid fa-circle-dot" style="color:#22C55E;font-size:0.55rem;"></i> Draft saved
        </div>
        <button type="button" id="btn-next" onclick="changeStep(1)" class="kw-btn kw-btn-primary kw-btn-lg">
          Next <i class="fa-solid fa-arrow-right"></i>
        </button>
        <button type="submit" id="btn-submit" class="kw-btn kw-btn-primary kw-btn-lg" style="display:none;">
          <i class="fa-solid fa-paper-plane"></i> Submit Application
        </button>
      </div>

      <div id="global-alert" style="margin-top:1rem;"></div>

    </form>
  </div>
</section>

<script>
let currentStep = 1;
const totalSteps = 5;

// ---- Step navigation ----
function changeStep(dir) {
  if (dir === 1 && !validateStep(currentStep)) return;
  currentStep += dir;
  if (currentStep < 1) currentStep = 1;
  if (currentStep > totalSteps) currentStep = totalSteps;
  updateStepUI();
  saveDraft();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepUI() {
  document.querySelectorAll('.form-step').forEach(el => el.style.display = 'none');
  const active = document.querySelector(`.form-step[data-step="${currentStep}"]`);
  if (active) active.style.display = '';

  document.getElementById('btn-prev').style.display  = currentStep > 1 ? '' : 'none';
  document.getElementById('btn-next').style.display  = currentStep < totalSteps ? '' : 'none';
  document.getElementById('btn-submit').style.display = currentStep === totalSteps ? '' : 'none';
  document.getElementById('current-step').value = currentStep;

  // Update step circles
  document.querySelectorAll('.step-indicator').forEach((el, i) => {
    const step = i + 1;
    const circle = el.querySelector('.step-circle');
    const label  = el.querySelector('.step-label');
    const line   = el.querySelector('.step-line');
    if (step < currentStep) {
      circle.style.cssText = 'width:32px;height:32px;border-radius:50%;margin:0 auto 0.3rem;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;border:2px solid #22C55E;background:#22C55E;color:#fff;transition:all 0.3s;';
      circle.innerHTML = '<i class="fa-solid fa-check" style="font-size:0.65rem;"></i>';
      if (label) label.style.color = '#22C55E';
      if (line)  line.style.background = '#22C55E';
    } else if (step === currentStep) {
      circle.style.cssText = 'width:32px;height:32px;border-radius:50%;margin:0 auto 0.3rem;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;border:2px solid var(--kw-primary);background:var(--kw-primary);color:#0A0F1A;transition:all 0.3s;';
      circle.innerHTML = step;
      if (label) label.style.color = 'var(--kw-primary)';
    } else {
      circle.style.cssText = 'width:32px;height:32px;border-radius:50%;margin:0 auto 0.3rem;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;border:2px solid var(--kw-border);background:var(--kw-bg);color:var(--kw-text-muted);transition:all 0.3s;';
      circle.innerHTML = step;
      if (label) label.style.color = 'var(--kw-text-muted)';
    }
  });

  if (currentStep === totalSteps) buildReviewSummary();
}

// ---- Validation ----
function validateStep(step) {
  let valid = true;
  document.querySelectorAll(`.form-step[data-step="${step}"] .kw-field-error`).forEach(el => el.textContent = '');
  document.querySelectorAll(`.form-step[data-step="${step}"] [required]`).forEach(field => {
    if (!field.value.trim()) {
      const err = field.closest('.kw-form-group')?.querySelector('.kw-field-error');
      const label = field.closest('.kw-form-group')?.querySelector('.kw-label')?.textContent.replace('*','').trim() || 'This field';
      if (err) { err.textContent = label + ' is required.'; err.style.display = 'block'; }
      if (valid) {
        field.focus();
        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
      valid = false;
    } else if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) {
      const err = field.closest('.kw-form-group')?.querySelector('.kw-field-error');
      if (err) { err.textContent = 'Please enter a valid email address.'; err.style.display = 'block'; }
      if (valid) field.focus();
      valid = false;
    } else if (field.name === 'id_number' && !/^\d{5,20}$/.test(field.value.replace(/\s/g,''))) {
      const err = field.closest('.kw-form-group')?.querySelector('.kw-field-error');
      if (err) { err.textContent = 'ID number must be 5–20 digits.'; err.style.display = 'block'; }
      if (valid) field.focus();
      valid = false;
    }
  });
  // File upload checks
  if (step === 4) {
    const cv = document.getElementById('cv-file');
    const nid = document.getElementById('nid-file');
    if (!cv.files.length) {
      const err = cv.closest('.kw-form-group')?.querySelector('.kw-field-error');
      if (err) err.textContent = 'Please upload your CV.';
      valid = false;
    } else if (cv.files[0].size > 5 * 1024 * 1024) {
      const err = cv.closest('.kw-form-group')?.querySelector('.kw-field-error');
      if (err) err.textContent = 'CV file exceeds 5MB limit.';
      valid = false;
    }
    if (!nid.files.length) {
      const err = nid.closest('.kw-form-group')?.querySelector('.kw-field-error');
      if (err) err.textContent = 'Please upload a copy of your National ID.';
      valid = false;
    } else if (nid.files[0].size > 5 * 1024 * 1024) {
      const err = nid.closest('.kw-form-group')?.querySelector('.kw-field-error');
      if (err) err.textContent = 'National ID file exceeds 5MB limit.';
      valid = false;
    }
  }
  return valid;
}

// ---- Age auto-calculate ----
function updateAge(dob) {
  if (!dob) return;
  const birth = new Date(dob);
  const today = new Date();
  let age = today.getFullYear() - birth.getFullYear();
  const m = today.getMonth() - birth.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
  document.getElementById('age-display').value = age >= 0 ? age + ' years' : '';
}

// ---- File handling ----
function showFileName(input, displayId) {
  const el = document.getElementById(displayId);
  if (el && input.files.length) {
    const f = input.files[0];
    el.textContent = f.name + ' (' + (f.size / 1024).toFixed(1) + ' KB)';
    el.style.color = 'var(--kw-primary)';
    const zone = input.previousElementSibling;
    if (zone) zone.style.borderColor = 'var(--kw-primary)';
  }
}
function handleFileDrop(e, inputId, nameId) {
  e.preventDefault();
  const input = document.getElementById(inputId);
  if (e.dataTransfer.files.length) {
    input.files = e.dataTransfer.files;
    showFileName(input, nameId);
  }
}

// ---- Dynamic entries ----
let acadCount = 1;
function addAcademicEntry() {
  acadCount++;
  const entry = `
  <div class="academic-entry" style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:0.85rem;position:relative;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.85rem;">
      <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-primary);">Qualification ${acadCount}</div>
      <button type="button" onclick="this.closest('.academic-entry').remove()" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#EF4444;padding:0.2rem 0.5rem;border-radius:4px;cursor:pointer;font-size:0.72rem;"><i class="fa-solid fa-times"></i></button>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;margin-bottom:0.85rem;">
      <div class="kw-form-group" style="grid-column:span 2;">
        <label class="kw-label">Qualification Level <span style="color:#EF4444;">*</span></label>
        <select name="acad_level[]" class="kw-input" required>
          <option value="">— Select Level —</option>
          <?php foreach ($qualLevels as $k=>$v): ?><option value="<?= $k ?>"><?= $v ?></option><?php endforeach; ?>
        </select>
        <div class="kw-field-error"></div>
      </div>
      <div class="kw-form-group"><label class="kw-label">Course <span style="color:#EF4444;">*</span></label><input type="text" name="acad_course[]" class="kw-input" required maxlength="200"><div class="kw-field-error"></div></div>
      <div class="kw-form-group"><label class="kw-label">Institution <span style="color:#EF4444;">*</span></label><input type="text" name="acad_institution[]" class="kw-input" required maxlength="200"><div class="kw-field-error"></div></div>
      <div class="kw-form-group"><label class="kw-label">Year Completed <span style="color:#EF4444;">*</span></label><input type="number" name="acad_year[]" class="kw-input" required min="1960" max="<?= date('Y') ?>"><div class="kw-field-error"></div></div>
      <div class="kw-form-group"><label class="kw-label">Grade / GPA</label><input type="text" name="acad_grade[]" class="kw-input" maxlength="50"></div>
      <div class="kw-form-group" style="grid-column:span 2;"><label class="kw-label">Certificate / Transcript <span style="color:#EF4444;">*</span></label><input type="file" name="acad_document[]" class="kw-input" accept=".pdf,.jpg,.jpeg,.png" style="padding:0.5rem;" required><div style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.25rem;">Max 5MB</div><div class="kw-field-error"></div></div>
    </div>
  </div>`;
  document.getElementById('academic-entries').insertAdjacentHTML('beforeend', entry);
}

let profCount = 1;
function addProfessionalEntry() {
  profCount++;
  const entry = `
  <div class="professional-entry" style="background:var(--kw-bg-alt);border:1px dashed var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:0.85rem;position:relative;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.85rem;">
      <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#3B82F6;">Professional Qualification ${profCount}</div>
      <button type="button" onclick="this.closest('.professional-entry').remove()" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#EF4444;padding:0.2rem 0.5rem;border-radius:4px;cursor:pointer;font-size:0.72rem;"><i class="fa-solid fa-times"></i></button>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
      <div class="kw-form-group"><label class="kw-label">Qualification Name</label><input type="text" name="prof_name[]" class="kw-input" maxlength="200"></div>
      <div class="kw-form-group"><label class="kw-label">Institution</label><input type="text" name="prof_institution[]" class="kw-input" maxlength="200"></div>
      <div class="kw-form-group"><label class="kw-label">Year Obtained</label><input type="number" name="prof_year[]" class="kw-input" min="1980" max="<?= date('Y') ?>"></div>
      <div class="kw-form-group"><label class="kw-label">Certification Number</label><input type="text" name="prof_cert_number[]" class="kw-input" maxlength="100"></div>
      <div class="kw-form-group" style="grid-column:span 2;"><label class="kw-label">Attach Certificate</label><input type="file" name="prof_document[]" class="kw-input" accept=".pdf,.jpg,.jpeg,.png" style="padding:0.5rem;"><div style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.25rem;">Max 5MB</div></div>
    </div>
  </div>`;
  document.getElementById('professional-entries').insertAdjacentHTML('beforeend', entry);
}

let expCount = 1;
function addExperienceEntry() {
  expCount++;
  const entry = `
  <div class="experience-entry" style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:0.85rem;position:relative;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.85rem;">
      <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#22C55E;">Experience ${expCount}</div>
      <button type="button" onclick="this.closest('.experience-entry').remove()" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);color:#EF4444;padding:0.2rem 0.5rem;border-radius:4px;cursor:pointer;font-size:0.72rem;"><i class="fa-solid fa-times"></i></button>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.85rem;">
      <div class="kw-form-group"><label class="kw-label">Job Title</label><input type="text" name="exp_title[]" class="kw-input" maxlength="200"></div>
      <div class="kw-form-group"><label class="kw-label">Employer Name</label><input type="text" name="exp_employer[]" class="kw-input" maxlength="200"></div>
      <div class="kw-form-group" style="grid-column:span 2;"><label class="kw-label">Employer Address</label><input type="text" name="exp_address[]" class="kw-input" maxlength="300"></div>
      <div class="kw-form-group"><label class="kw-label">Year From</label><input type="number" name="exp_year_from[]" class="kw-input" min="1970" max="<?= date('Y') ?>"></div>
      <div class="kw-form-group"><label class="kw-label">Year To</label><div style="display:flex;gap:0.5rem;align-items:center;"><input type="number" name="exp_year_to[]" class="kw-input" min="1970" max="<?= date('Y') ?>" style="flex:1;"><label style="display:flex;align-items:center;gap:0.35rem;font-size:0.75rem;white-space:nowrap;cursor:pointer;"><input type="checkbox" name="exp_is_current[]" value="1" style="accent-color:var(--kw-primary);" onchange="toggleCurrentJob(this)"> Current</label></div></div>
      <div class="kw-form-group" style="grid-column:span 2;"><label class="kw-label">Responsibilities</label><textarea name="exp_responsibilities[]" class="kw-input" rows="3" maxlength="2000"></textarea></div>
    </div>
  </div>`;
  document.getElementById('experience-entries').insertAdjacentHTML('beforeend', entry);
}

function toggleCurrentJob(checkbox) {
  const yearTo = checkbox.closest('.kw-form-group')?.querySelector('[name="exp_year_to[]"]');
  if (yearTo) { yearTo.disabled = checkbox.checked; yearTo.value = checkbox.checked ? '' : yearTo.value; }
}

// ---- Review summary ----
function buildReviewSummary() {
  const form = document.getElementById('application-form');
  const fd = new FormData(form);
  const summary = document.getElementById('review-summary');
  const rows = [
    ['Full Name', [fd.get('first_name'),fd.get('second_name'),fd.get('last_name')].filter(Boolean).join(' ')],
    ['ID Number', fd.get('id_number')],
    ['Gender', fd.get('gender')],
    ['Date of Birth', fd.get('date_of_birth')],
    ['Email', fd.get('email')],
    ['Primary Phone', fd.get('phone_primary')],
    ['Home County', fd.get('home_county')],
    ['PLWD', fd.get('is_plwd') ? 'Yes' : 'No'],
    ['Position', '<?= e($job['title']) ?>'],
  ];
  summary.innerHTML = rows.map(([k,v]) => `
    <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;">
      <span style="color:var(--kw-text-muted);font-weight:600;min-width:140px;">${k}</span>
      <span style="font-weight:700;text-align:right;">${v || '<em style="color:var(--kw-text-muted);">Not provided</em>'}</span>
    </div>
  `).join('');
}

// ---- Draft saving ----
function saveDraft() {
  const form = document.getElementById('application-form');
  const fd = new FormData(form);
  const draftData = {};
  for (const [k, v] of fd.entries()) {
    if (!['acad_document[]','prof_document[]','doc_cv','doc_national_id','doc_additional[]'].includes(k)) {
      draftData[k] = v;
    }
  }
  localStorage.setItem('kw_app_draft_<?= $job['id'] ?>', JSON.stringify(draftData));
  document.getElementById('save-status').innerHTML = '<i class="fa-solid fa-circle-dot" style="color:#22C55E;font-size:0.55rem;"></i> Draft saved ' + new Date().toLocaleTimeString();
  // Also save to server session
  fetch('<?= url('api/save-app-draft') ?>', { method:'POST', body: (() => { const f=new FormData(); f.append('job_id','<?= $job['id'] ?>'); f.append('data',JSON.stringify(draftData)); return f; })() }).catch(()=>{});
}

// ---- Load draft from localStorage ----
function loadDraft() {
  const saved = localStorage.getItem('kw_app_draft_<?= $job['id'] ?>');
  if (!saved) return;
  try {
    const data = JSON.parse(saved);
    const form = document.getElementById('application-form');
    Object.entries(data).forEach(([k, v]) => {
      const el = form.querySelector(`[name="${k}"]`);
      if (el && el.type !== 'file') {
        if (el.type === 'checkbox') el.checked = v === '1' || v === 'on';
        else el.value = v;
        if (k === 'date_of_birth' && v) updateAge(v);
      }
    });
    document.getElementById('save-status').innerHTML = '<i class="fa-solid fa-circle-dot" style="color:#F5A800;font-size:0.55rem;"></i> Draft restored';
  } catch(e) {}
}

// ---- Form submission ----
document.getElementById('application-form').addEventListener('submit', async function(e) {
  e.preventDefault();
  if (!validateStep(5)) return;
  if (!document.getElementById('declaration').checked) {
    document.getElementById('declaration-error').textContent = 'You must agree to the declaration before submitting.';
    return;
  }

  const btn = document.getElementById('btn-submit');
  const alert = document.getElementById('global-alert');
  alert.innerHTML = '';
  btn.disabled = true;
  btn.innerHTML = '<div class="kw-spinner" style="width:16px;height:16px;border-top-color:#0A0F1A;display:inline-block;margin-right:8px;"></div>Submitting application…';

  try {
    const resp = await fetch('<?= url('api/submit-application') ?>', {
      method: 'POST',
      body: new FormData(this)
    });
    const data = await resp.json();

    if (data.success) {
      localStorage.removeItem('kw_app_draft_<?= $job['id'] ?>');
      window.location.href = '<?= url('careers/application-success') ?>?ref=' + (data.reference || '');
    } else {
      alert.innerHTML = `<div class="kw-alert kw-alert-danger" style="margin-top:1rem;">${data.message}</div>`;
      if (data.step) { currentStep = data.step; updateStepUI(); }
      if (data.field) {
        const el = document.querySelector(`[name="${data.field}"]`);
        if (el) { el.focus(); el.scrollIntoView({ behavior:'smooth', block:'center' }); }
      }
    }
  } catch(err) {
    alert.innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-top:1rem;">Connection error. Your draft has been saved. Please try again.</div>';
    saveDraft();
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Application';
});

// Auto-save every 30 seconds
setInterval(saveDraft, 30000);

// Init
loadDraft();
updateStepUI();
</script>
<style>
.kw-field-error { display:none; font-size:0.72rem; color:#EF4444; margin-top:0.25rem; }
.kw-field-error:not(:empty) { display:block; }
@media(max-width:768px){
  div[style*="grid-template-columns:1fr 1fr 1fr"]{grid-template-columns:1fr!important;}
  div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}
  div[style*="grid-column:span 2"]{grid-column:span 1!important;}
}
</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>