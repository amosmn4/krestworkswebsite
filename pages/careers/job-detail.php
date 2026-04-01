<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: ' . url('careers')); exit; }

require_once __DIR__ . '/../../includes/header.php';

$pdo = db();
$stmt = $pdo->prepare('SELECT * FROM job_positions WHERE slug = ? AND is_active = 1 LIMIT 1');
$stmt->execute([$slug]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) { header('HTTP/1.0 404 Not Found'); header('Location: ' . url('careers')); exit; }

$page_title       = e($job['title']) . ' — Careers — ' . APP_NAME;
$page_description = e(truncate(strip_tags($job['description'] ?? ''), 160));

$qualLabels = ['kcse'=>'KCSE','certificate'=>'Certificate','diploma'=>'Diploma','degree'=>'Bachelor\'s Degree','masters'=>'Master\'s Degree','phd'=>'PhD'];
$typeLabels = ['full-time'=>'Full Time','part-time'=>'Part Time','contract'=>'Contract','internship'=>'Internship','volunteer'=>'Volunteer'];
$typeColors = ['full-time'=>'#22C55E','part-time'=>'#3B82F6','contract'=>'#F5A800','internship'=>'#A855F7','volunteer'=>'#F97316'];

$applicantCount = (int)$pdo->prepare('SELECT COUNT(*) FROM job_applications WHERE job_position_id = ? AND status != "draft"')->execute([$job['id']]) ?
    (function() use($pdo, $job){ $s=$pdo->prepare('SELECT COUNT(*) FROM job_applications WHERE job_position_id=? AND status!="draft"'); $s->execute([$job['id']]); return (int)$s->fetchColumn(); })() : 0;

$deadline = $job['deadline'] ? date('d F Y', strtotime($job['deadline'])) : null;
$daysLeft = $job['deadline'] ? max(0, (int)ceil((strtotime($job['deadline']) - time()) / 86400)) : null;
$isClosed = $job['deadline'] && strtotime($job['deadline']) < time();

// Similar jobs
$similar = $pdo->prepare('SELECT id, slug, title, department, employment_type, location FROM job_positions WHERE department=? AND slug!=? AND is_active=1 LIMIT 3');
$similar->execute([$job['department'], $slug]);
$similarJobs = $similar->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="kw-page-hero" style="min-height:320px;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('careers') ?>">Careers</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current"><?= e($job['title']) ?></span>
    </div>
    <div style="padding:2rem 0 2.5rem;max-width:700px;" data-aos="fade-up">
      <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:0.75rem;flex-wrap:wrap;">
        <span style="background:<?= $typeColors[$job['employment_type']] ?? '#6B7280' ?>15;color:<?= $typeColors[$job['employment_type']] ?? '#6B7280' ?>;border-radius:999px;padding:0.2rem 0.7rem;font-size:0.68rem;font-weight:700;"><?= $typeLabels[$job['employment_type']] ?? $job['employment_type'] ?></span>
        <?php if ($job['is_remote']): ?><span style="background:rgba(59,130,246,0.15);color:#3B82F6;border-radius:999px;padding:0.2rem 0.7rem;font-size:0.68rem;font-weight:700;"><i class="fa-solid fa-wifi" style="font-size:0.55rem;margin-right:0.25rem;"></i>Remote OK</span><?php endif; ?>
        <span style="font-size:0.75rem;color:rgba(255,255,255,0.5);"><?= e($job['department']) ?></span>
        <?php if ($isClosed): ?><span style="background:rgba(239,68,68,0.15);color:#EF4444;border-radius:999px;padding:0.2rem 0.7rem;font-size:0.68rem;font-weight:700;">Applications Closed</span><?php endif; ?>
      </div>
      <h1 style="font-size:clamp(1.5rem,3.5vw,2.5rem);margin:0 0 1rem;line-height:1.2;"><?= e($job['title']) ?></h1>
      <div style="display:flex;gap:1.25rem;flex-wrap:wrap;font-size:0.8rem;color:rgba(255,255,255,0.6);">
        <span><i class="fa-solid fa-location-dot" style="color:var(--kw-primary);margin-right:0.3rem;"></i><?= e($job['location'] ?? 'Kenya') ?></span>
        <span><i class="fa-solid fa-graduation-cap" style="color:var(--kw-primary);margin-right:0.3rem;"></i><?= $qualLabels[$job['min_qualification']] ?? $job['min_qualification'] ?> minimum</span>
        <?php if ($job['experience_years'] > 0): ?><span><i class="fa-solid fa-briefcase" style="color:var(--kw-primary);margin-right:0.3rem;"></i><?= $job['experience_years'] ?>+ years experience</span><?php endif; ?>
        <?php if ($deadline): ?><span style="color:<?= $daysLeft !== null && $daysLeft <= 5 ? '#F97316' : 'rgba(255,255,255,0.6)' ?>;"><i class="fa-solid fa-clock" style="margin-right:0.3rem;"></i>Deadline: <?= $deadline ?><?= $daysLeft !== null ? " ($daysLeft days left)" : '' ?></span><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 320px;gap:2.5rem;align-items:flex-start;">

      <!-- JD Content -->
      <article>
        <!-- Description -->
        <?php if ($job['description']): ?>
        <div style="margin-bottom:2rem;" data-aos="fade-up">
          <h2 style="font-size:1.1rem;margin-bottom:0.85rem;">About This Role</h2>
          <div style="font-size:0.9rem;line-height:1.85;color:var(--kw-text-secondary);"><?= nl2br(e($job['description'])) ?></div>
        </div>
        <?php endif; ?>

        <!-- Responsibilities -->
        <?php if ($job['responsibilities']): ?>
        <div style="margin-bottom:2rem;" data-aos="fade-up">
          <h2 style="font-size:1.1rem;margin-bottom:1rem;">Key Responsibilities</h2>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.5rem;">
            <?php foreach (array_filter(explode("\n", $job['responsibilities'])) as $item): ?>
            <li style="display:flex;align-items:flex-start;gap:0.65rem;font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.7;">
              <i class="fa-solid fa-check" style="color:var(--kw-primary);flex-shrink:0;margin-top:4px;font-size:0.72rem;"></i>
              <?= e(ltrim($item, '- ')) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Requirements -->
        <?php if ($job['requirements']): ?>
        <div style="margin-bottom:2rem;" data-aos="fade-up">
          <h2 style="font-size:1.1rem;margin-bottom:1rem;">Requirements</h2>
          <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.5rem;">
            <?php foreach (array_filter(explode("\n", $job['requirements'])) as $item): ?>
            <li style="display:flex;align-items:flex-start;gap:0.65rem;font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.7;">
              <i class="fa-solid fa-arrow-right" style="color:var(--kw-primary);flex-shrink:0;margin-top:4px;font-size:0.68rem;"></i>
              <?= e(ltrim($item, '- ')) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Benefits -->
        <?php if ($job['benefits']): ?>
        <div style="margin-bottom:2rem;padding:1.5rem;background:rgba(245,168,0,0.05);border:1px solid rgba(245,168,0,0.15);border-radius:var(--kw-radius-xl);" data-aos="fade-up">
          <h2 style="font-size:1.1rem;margin-bottom:1rem;color:var(--kw-primary);">What We Offer</h2>
          <ul style="list-style:none;padding:0;margin:0;display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
            <?php foreach (array_filter(explode("\n", $job['benefits'])) as $item): ?>
            <li style="display:flex;align-items:flex-start;gap:0.6rem;font-size:0.82rem;color:var(--kw-text-secondary);">
              <i class="fa-solid fa-star" style="color:var(--kw-primary);flex-shrink:0;margin-top:3px;font-size:0.62rem;"></i>
              <?= e(ltrim($item, '- ')) ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <!-- Apply CTA (mobile) -->
        <div style="display:none;" class="mobile-apply-cta">
          <?php if (!$isClosed): ?>
          <a href="<?= url('careers/' . $job['slug'] . '/apply') ?>" class="kw-btn kw-btn-primary kw-btn-lg" style="width:100%;justify-content:center;">
            <i class="fa-solid fa-paper-plane"></i> Apply for This Position
          </a>
          <?php else: ?>
          <div class="kw-card" style="padding:1.25rem;text-align:center;">
            <p style="color:#EF4444;font-weight:700;">Applications for this position are closed.</p>
          </div>
          <?php endif; ?>
        </div>

        <!-- Similar jobs -->
        <?php if (!empty($similarJobs)): ?>
        <div style="margin-top:2.5rem;" data-aos="fade-up">
          <h3 style="font-size:0.95rem;margin-bottom:1rem;">Similar Positions</h3>
          <div style="display:flex;flex-direction:column;gap:0.65rem;">
            <?php foreach ($similarJobs as $sj):
              $sjColor = $typeColors[$sj['employment_type']] ?? '#6B7280';
            ?>
            <a href="<?= url('careers/' . $sj['slug']) ?>" style="display:flex;align-items:center;justify-content:space-between;padding:0.9rem 1.1rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);text-decoration:none;transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--kw-primary)40'" onmouseout="this.style.borderColor=''">
              <div>
                <div style="font-size:0.875rem;font-weight:700;color:var(--kw-text-primary);"><?= e($sj['title']) ?></div>
                <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= e($sj['location'] ?? 'Kenya') ?></div>
              </div>
              <span style="background:<?= $sjColor ?>15;color:<?= $sjColor ?>;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.62rem;font-weight:700;white-space:nowrap;"><?= $typeLabels[$sj['employment_type']] ?? '' ?></span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </article>

      <!-- Sticky sidebar -->
      <aside style="position:sticky;top:calc(var(--kw-nav-height)+1.5rem);">

        <!-- Apply card -->
        <?php if (!$isClosed): ?>
        <div class="kw-card" style="padding:1.5rem;margin-bottom:1rem;border-top:3px solid var(--kw-primary);">
          <?php if ($job['show_salary'] && $job['salary_min']): ?>
          <div style="margin-bottom:1rem;padding-bottom:1rem;border-bottom:1px solid var(--kw-border);">
            <div style="font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.2rem;">Salary Range</div>
            <div style="font-size:1.2rem;font-weight:800;color:#22C55E;"><?= $job['salary_currency'] ?> <?= number_format($job['salary_min']) ?><?= $job['salary_max'] ? ' – '.number_format($job['salary_max']) : '+' ?><span style="font-size:0.7rem;font-weight:400;color:var(--kw-text-muted);">/month</span></div>
          </div>
          <?php endif; ?>
          <a href="<?= url('careers/' . $job['slug'] . '/apply') ?>" class="kw-btn kw-btn-primary kw-btn-lg" style="width:100%;justify-content:center;margin-bottom:0.6rem;">
            <i class="fa-solid fa-paper-plane"></i> Apply Now
          </a>
          <p style="font-size:0.72rem;color:var(--kw-text-muted);text-align:center;margin:0;">Takes about 15–20 minutes</p>
        </div>
        <?php else: ?>
        <div class="kw-card" style="padding:1.5rem;margin-bottom:1rem;border-top:3px solid #EF4444;">
          <p style="color:#EF4444;font-weight:700;text-align:center;margin:0;font-size:0.875rem;"><i class="fa-solid fa-times-circle"></i> Applications Closed</p>
          <p style="font-size:0.72rem;color:var(--kw-text-muted);text-align:center;margin-top:0.5rem;">This position has passed its deadline.</p>
        </div>
        <?php endif; ?>

        <!-- Job details card -->
        <div class="kw-card" style="padding:1.5rem;margin-bottom:1rem;">
          <h4 style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Job Details</h4>
          <?php foreach ([
            ['fa-briefcase','Employment','<strong>'.($typeLabels[$job['employment_type']] ?? $job['employment_type']).'</strong>'],
            ['fa-location-dot','Location', e($job['location'] ?? 'Kenya') . ($job['is_remote'] ? ' · Remote OK' : '')],
            ['fa-graduation-cap','Min. Qualification', $qualLabels[$job['min_qualification']] ?? $job['min_qualification']],
            ['fa-clock','Experience', $job['experience_years'] > 0 ? $job['experience_years'].'+ years' : 'Entry level'],
            ['fa-calendar','Deadline', $deadline ?? 'Open until filled'],
            ['fa-users','Applicants', number_format($applicantCount) . ' applied'],
          ] as $detail): ?>
          <div style="display:flex;align-items:flex-start;gap:0.65rem;padding:0.55rem 0;border-bottom:1px solid var(--kw-border);font-size:0.8rem;">
            <i class="fa-solid <?= $detail[0] ?>" style="color:var(--kw-primary);width:14px;flex-shrink:0;margin-top:2px;font-size:0.72rem;"></i>
            <div>
              <div style="font-size:0.62rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.1rem;"><?= $detail[1] ?></div>
              <div><?= $detail[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Share -->
        <div class="kw-card" style="padding:1.25rem;">
          <h4 style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:var(--kw-text-muted);margin-bottom:0.75rem;">Share This Role</h4>
          <div style="display:flex;gap:0.5rem;">
            <?php
            $shareUrl   = urlencode(url('careers/' . $job['slug']));
            $shareTitle = urlencode($job['title'] . ' at ' . APP_NAME);
            foreach ([
              ['fa-brands fa-linkedin','#0A66C2','https://www.linkedin.com/sharing/share-offsite/?url='.$shareUrl],
              ['fa-brands fa-twitter','#1DA1F2','https://twitter.com/intent/tweet?url='.$shareUrl.'&text='.$shareTitle],
              ['fa-brands fa-whatsapp','#25D366','https://wa.me/?text='.$shareTitle.'%20'.$shareUrl],
            ] as $s): ?>
            <a href="<?= $s[2] ?>" target="_blank" style="flex:1;display:flex;align-items:center;justify-content:center;padding:0.5rem;border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);color:<?= $s[1] ?>;font-size:0.875rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='<?= $s[1] ?>20'" onmouseout="this.style.background=''">
              <i class="<?= $s[0] ?>"></i>
            </a>
            <?php endforeach; ?>
            <button onclick="navigator.clipboard.writeText(window.location.href);window.Krest?.toast('Link copied!','success')" style="flex:1;display:flex;align-items:center;justify-content:center;padding:0.5rem;border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);color:var(--kw-text-muted);cursor:pointer;background:none;font-size:0.875rem;" title="Copy link">
              <i class="fa-solid fa-link"></i>
            </button>
          </div>
        </div>

      </aside>
    </div>
  </div>
</section>

<style>
@media(max-width:1024px){
  .kw-container>div[style*="1fr 320px"]{grid-template-columns:1fr!important;}
  aside[style*="sticky"]{position:static!important;}
  .mobile-apply-cta{display:block!important;margin-bottom:1.5rem;}
}
@media(max-width:640px){ ul[style*="1fr 1fr"]{grid-template-columns:1fr!important;} }
</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>