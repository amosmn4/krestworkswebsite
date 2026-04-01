<?php
$portal_title  = 'My Applications';
$portal_active = 'applications';
require_once __DIR__ . '/portal-header.php';

$pdo = db();
$uid = $_SESSION['user_id'];

// Fetch this user's applications (matched by email in session)
// Get user email first
$userEmail = $portalUser['email'];

$myApps = $pdo->prepare("
    SELECT ja.*, jp.title AS job_title, jp.slug AS job_slug, jp.department,
           jp.employment_type, jp.location,
           (SELECT COUNT(*) FROM application_documents WHERE application_id = ja.id) AS doc_count,
           (SELECT COUNT(*) FROM application_interviews WHERE application_id = ja.id) AS interview_count
    FROM job_applications ja
    JOIN job_positions jp ON jp.id = ja.job_position_id
    WHERE ja.email = ? AND ja.status != 'draft'
    ORDER BY ja.submitted_at DESC
");
$myApps->execute([$userEmail]);
$applications = $myApps->fetchAll(PDO::FETCH_ASSOC);

$statusColors = [
    'submitted'           => ['#3B82F6', 'fa-clock', 'Under Review'],
    'screening'           => ['#F5A800', 'fa-magnifying-glass', 'Being Screened'],
    'shortlisted'         => ['#22C55E', 'fa-list-check', 'Shortlisted! 🎉'],
    'interview_scheduled' => ['#A855F7', 'fa-calendar-check', 'Interview Scheduled'],
    'interview_done'      => ['#06B6D4', 'fa-microphone', 'Interview Completed'],
    'offered'             => ['#22C55E', 'fa-handshake', 'Offer Extended! 🎊'],
    'rejected'            => ['#EF4444', 'fa-times-circle', 'Not Selected'],
    'withdrawn'           => ['#6B7280', 'fa-minus-circle', 'Withdrawn'],
];

$typeColors = ['full-time'=>'#22C55E','part-time'=>'#3B82F6','contract'=>'#F5A800','internship'=>'#A855F7','volunteer'=>'#F97316'];
?>

<!-- Header -->
<div style="margin-bottom:1.75rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
  <div>
    <h1 style="font-size:1.25rem;margin:0 0 0.25rem;">My Applications</h1>
    <p style="font-size:0.82rem;color:var(--kw-text-muted);">Track the status of your job applications with <?= APP_NAME ?>.</p>
  </div>
  <a href="<?= url('careers') ?>" class="kw-btn kw-btn-primary kw-btn-sm">
    <i class="fa-solid fa-briefcase"></i> Browse Open Positions
  </a>
</div>

<?php if (empty($applications)): ?>
<!-- Empty state -->
<div style="text-align:center;padding:4rem 2rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-xl);">
  <i class="fa-solid fa-file-circle-question" style="font-size:3rem;color:var(--kw-text-muted);opacity:0.25;margin-bottom:1.25rem;display:block;"></i>
  <h3 style="margin-bottom:0.5rem;">No Applications Yet</h3>
  <p style="color:var(--kw-text-muted);font-size:0.875rem;margin-bottom:1.5rem;max-width:400px;margin-left:auto;margin-right:auto;">
    You haven't applied to any positions yet. Browse our open roles and find your next opportunity.
  </p>
  <a href="<?= url('careers') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-search"></i> View Open Positions</a>
</div>

<?php else: ?>

<!-- Summary strip -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.75rem;">
  <?php
  $total    = count($applications);
  $active   = count(array_filter($applications, fn($a) => in_array($a['status'], ['submitted','screening','shortlisted','interview_scheduled','interview_done'])));
  $shortCnt = count(array_filter($applications, fn($a) => in_array($a['status'], ['shortlisted','interview_scheduled','interview_done','offered'])));
  $offCnt   = count(array_filter($applications, fn($a) => $a['status'] === 'offered'));
  foreach ([
    ['fa-file-alt','#F5A800',$total,'Total Applied'],
    ['fa-spinner','#3B82F6',$active,'In Progress'],
    ['fa-list-check','#22C55E',$shortCnt,'Shortlisted'],
    ['fa-handshake','#A855F7',$offCnt,'Offers Received'],
  ] as $s): ?>
  <div class="portal-stat" style="display:flex;align-items:center;gap:0.85rem;">
    <div class="portal-stat-icon" style="background:<?=$s[1]?>15;">
      <i class="fa-solid <?=$s[0]?>" style="color:<?=$s[1]?>;font-size:0.9rem;"></i>
    </div>
    <div>
      <div class="portal-stat-value"><?=$s[2]?></div>
      <div class="portal-stat-label"><?=$s[3]?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Application cards -->
<div style="display:flex;flex-direction:column;gap:1.1rem;">
  <?php foreach ($applications as $app):
    $sc    = $statusColors[$app['status']] ?? ['#6B7280','fa-circle-question','Unknown'];
    $tc    = $typeColors[$app['employment_type']] ?? '#6B7280';
    $hasInterview = $app['status'] === 'interview_scheduled' && $app['interview_date'];
  ?>
  <div class="kw-card" style="padding:1.5rem;<?= in_array($app['status'],['shortlisted','interview_scheduled','offered']) ? 'border-color:'.$sc[0].'40;' : '' ?>">
    <div style="display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:flex-start;">
      <div>
        <!-- Job title + badges -->
        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.5rem;flex-wrap:wrap;">
          <span style="background:<?=$tc?>15;color:<?=$tc?>;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.65rem;font-weight:700;"><?= ucfirst(str_replace('-',' ',$app['employment_type'])) ?></span>
          <span style="font-size:0.72rem;color:var(--kw-text-muted);"><?= e($app['department'] ?? '') ?></span>
          <?php if ($app['doc_count'] > 0): ?><span style="font-size:0.65rem;color:var(--kw-text-muted);"><i class="fa-solid fa-paperclip" style="margin-right:0.2rem;"></i><?= $app['doc_count'] ?> docs</span><?php endif; ?>
        </div>

        <h3 style="font-size:1rem;margin:0 0 0.35rem;line-height:1.3;"><?= e($app['job_title']) ?></h3>

        <div style="display:flex;align-items:center;gap:1rem;font-size:0.75rem;color:var(--kw-text-muted);flex-wrap:wrap;margin-bottom:0.75rem;">
          <span><i class="fa-solid fa-location-dot" style="color:var(--kw-primary);margin-right:0.25rem;"></i><?= e($app['location'] ?? 'Kenya') ?></span>
          <span><i class="fa-solid fa-calendar" style="color:var(--kw-primary);margin-right:0.25rem;"></i>Applied <?= $app['submitted_at'] ? date('d M Y', strtotime($app['submitted_at'])) : '—' ?></span>
          <span style="font-family:monospace;font-weight:700;color:var(--kw-text-muted);font-size:0.68rem;"><?= e($app['reference_number']) ?></span>
        </div>

        <!-- Status badge -->
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <div style="display:flex;align-items:center;gap:0.45rem;background:<?=$sc[0]?>15;border:1px solid <?=$sc[0]?>30;border-radius:999px;padding:0.3rem 0.85rem;font-size:0.75rem;font-weight:700;color:<?=$sc[0]?>;">
            <i class="fa-solid <?=$sc[1]?>" style="font-size:0.65rem;"></i>
            <?= $sc[2] ?>
          </div>
        </div>

        <?php if ($hasInterview): ?>
        <div style="margin-top:0.85rem;padding:0.85rem 1rem;background:rgba(168,85,247,0.06);border:1px solid rgba(168,85,247,0.2);border-radius:var(--kw-radius-lg);">
          <div style="font-size:0.72rem;font-weight:700;color:#A855F7;margin-bottom:0.3rem;"><i class="fa-solid fa-calendar-check" style="margin-right:0.3rem;"></i>Interview Scheduled</div>
          <div style="font-size:0.82rem;font-weight:700;"><?= date('D, d M Y \a\t H:i', strtotime($app['interview_date'])) ?> EAT</div>
          <?php if ($app['interview_location']): ?><div style="font-size:0.72rem;color:var(--kw-text-muted);margin-top:0.15rem;"><i class="fa-solid fa-location-dot" style="margin-right:0.25rem;"></i><?= e($app['interview_location']) ?></div><?php endif; ?>
        </div>
        <?php endif; ?>

        <?php if ($app['status'] === 'offered'): ?>
        <div style="margin-top:0.85rem;padding:0.85rem 1rem;background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.2);border-radius:var(--kw-radius-lg);">
          <div style="font-size:0.75rem;color:#22C55E;font-weight:700;"><i class="fa-solid fa-star" style="margin-right:0.3rem;"></i>Congratulations! An offer has been extended. Please contact careers@krestworks.com to proceed.</div>
        </div>
        <?php endif; ?>

        <?php if ($app['rejection_reason'] && $app['status'] === 'rejected'): ?>
        <div style="margin-top:0.75rem;font-size:0.78rem;color:var(--kw-text-muted);padding:0.7rem 0.85rem;background:rgba(239,68,68,0.04);border-radius:var(--kw-radius-md);">
          <strong>Feedback:</strong> <?= e($app['rejection_reason']) ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Right: score + view link -->
      <div style="text-align:right;flex-shrink:0;">
        <?php if ($app['screening_score'] !== null): ?>
        <div style="margin-bottom:0.5rem;">
          <div style="font-size:0.62rem;color:var(--kw-text-muted);margin-bottom:0.2rem;">Screening</div>
          <div style="font-size:1.1rem;font-weight:800;color:<?= $app['screening_score']>=70?'#22C55E':($app['screening_score']>=50?'#F5A800':'#EF4444') ?>;"><?= $app['screening_score'] ?><span style="font-size:0.65rem;font-weight:400;color:var(--kw-text-muted);">/100</span></div>
        </div>
        <?php endif; ?>
        <a href="<?= url('careers/' . $app['job_slug']) ?>" class="kw-btn kw-btn-ghost kw-btn-sm" target="_blank" style="font-size:0.72rem;">
          <i class="fa-solid fa-eye"></i> Job
        </a>
      </div>
    </div>

    <!-- Progress tracker -->
    <div style="margin-top:1.1rem;padding-top:1rem;border-top:1px solid var(--kw-border);">
      <div style="display:flex;align-items:center;position:relative;">
        <?php
        $stages = ['submitted'=>'Applied','screening'=>'Screening','shortlisted'=>'Shortlisted','interview_scheduled'=>'Interview','offered'=>'Offered'];
        $stageList = array_keys($stages);
        $currentIdx = array_search($app['status'], $stageList);
        if ($currentIdx === false) $currentIdx = -1;
        foreach ($stages as $sv => $sl):
          $idx     = array_search($sv, $stageList);
          $passed  = $idx <= $currentIdx;
          $current = $sv === $app['status'];
          $stageColor = $passed ? '#22C55E' : 'var(--kw-border)';
          if ($app['status'] === 'rejected' || $app['status'] === 'withdrawn') { $stageColor = $idx <= $currentIdx ? '#EF4444' : 'var(--kw-border)'; }
        ?>
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;position:relative;z-index:1;">
          <div style="width:20px;height:20px;border-radius:50%;border:2px solid <?= $passed ? ($app['status']==='rejected'||$app['status']==='withdrawn' ? '#EF4444' : '#22C55E') : 'var(--kw-border)' ?>;background:<?= $passed ? ($app['status']==='rejected'||$app['status']==='withdrawn' ? '#EF4444' : '#22C55E') : 'var(--kw-bg)' ?>;display:flex;align-items:center;justify-content:center;margin-bottom:0.3rem;transition:all 0.3s;">
            <?php if ($passed): ?>
            <i class="fa-solid <?= ($app['status']==='rejected'||$app['status']==='withdrawn') ? 'fa-times' : 'fa-check' ?>" style="color:#fff;font-size:0.5rem;"></i>
            <?php endif; ?>
          </div>
          <div style="font-size:0.58rem;font-weight:<?= $current?'800':'600' ?>;color:<?= $passed ? ($app['status']==='rejected'||$app['status']==='withdrawn' ? '#EF4444' : '#22C55E') : 'var(--kw-text-muted)' ?>;text-align:center;white-space:nowrap;"><?= $sl ?></div>
        </div>
        <?php if ($sv !== 'offered'): ?>
        <div style="flex:1;height:2px;background:<?= $idx < $currentIdx ? ($app['status']==='rejected'||$app['status']==='withdrawn' ? '#EF4444' : '#22C55E') : 'var(--kw-border)' ?>;margin-bottom:1.1rem;transition:all 0.3s;"></div>
        <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
  <?php endforeach; ?>
</div>

<!-- CTA -->
<div class="kw-card" style="padding:1.5rem;margin-top:1.5rem;text-align:center;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
  <div>
    <div style="font-weight:700;margin-bottom:0.2rem;">Looking for more opportunities?</div>
    <p style="font-size:0.78rem;color:var(--kw-text-muted);margin:0;">Browse all our open positions and apply for roles that match your skills.</p>
  </div>
  <a href="<?= url('careers') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-briefcase"></i> Browse Careers</a>
</div>

<?php endif; ?>

<style>@media(max-width:640px){ div[style*="repeat(4,1fr)"]{grid-template-columns:1fr 1fr!important;} }</style>
<?php require_once __DIR__ . '/portal-footer.php'; ?>