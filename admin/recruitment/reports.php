<?php
$admin_title  = 'Recruitment — Reports';
$admin_active = 'recruitment';
require_once __DIR__ . '/../admin-header.php';

$pdo = db();

$jobFilter = (int)($_GET['job'] ?? 0);

// All jobs for selector
$allJobs = $pdo->query("SELECT id, title FROM job_positions ORDER BY is_active DESC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// ---- Aggregate stats ----
try {
    $statsWhere  = $jobFilter ? 'AND ja.job_position_id = ' . $jobFilter : '';

    $totalApps   = (int)$pdo->query("SELECT COUNT(*) FROM job_applications ja WHERE ja.status != 'draft' $statsWhere")->fetchColumn();
    $shortlisted = (int)$pdo->query("SELECT COUNT(*) FROM job_applications ja WHERE ja.status IN ('shortlisted','interview_scheduled','interview_done','offered') $statsWhere")->fetchColumn();
    $interviews  = (int)$pdo->query("SELECT COUNT(*) FROM job_applications ja WHERE ja.status IN ('interview_scheduled','interview_done') $statsWhere")->fetchColumn();
    $offered     = (int)$pdo->query("SELECT COUNT(*) FROM job_applications ja WHERE ja.status = 'offered' $statsWhere")->fetchColumn();
    $rejected    = (int)$pdo->query("SELECT COUNT(*) FROM job_applications ja WHERE ja.status = 'rejected' $statsWhere")->fetchColumn();
    $avgScore    = (float)$pdo->query("SELECT AVG(screening_score) FROM job_applications ja WHERE screening_score IS NOT NULL $statsWhere")->fetchColumn();

    // Applications per position
    $perPosition = $pdo->query("
        SELECT jp.title, jp.slug,
               COUNT(ja.id) AS total,
               SUM(CASE WHEN ja.status IN ('shortlisted','interview_scheduled','interview_done','offered') THEN 1 ELSE 0 END) AS shortlisted,
               SUM(CASE WHEN ja.status = 'rejected' THEN 1 ELSE 0 END) AS rejected,
               SUM(CASE WHEN ja.status = 'offered' THEN 1 ELSE 0 END) AS offered,
               AVG(ja.screening_score) AS avg_score
        FROM job_positions jp
        LEFT JOIN job_applications ja ON ja.job_position_id = jp.id AND ja.status != 'draft'
        GROUP BY jp.id, jp.title, jp.slug
        ORDER BY total DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Applications per day (30d)
    $daily = $pdo->query("
        SELECT DATE(submitted_at) AS day, COUNT(*) AS cnt
        FROM job_applications
        WHERE status != 'draft' AND submitted_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(submitted_at)
        ORDER BY day ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Gender breakdown
    $genderBreak = $pdo->query("SELECT gender, COUNT(*) AS cnt FROM job_applications WHERE status != 'draft' $statsWhere GROUP BY gender")->fetchAll(PDO::FETCH_ASSOC);

    // County breakdown (top 10)
    $countyBreak = $pdo->query("SELECT home_county, COUNT(*) AS cnt FROM job_applications WHERE status != 'draft' $statsWhere GROUP BY home_county ORDER BY cnt DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

    // Qualification breakdown
    $qualBreak = $pdo->query("SELECT level, COUNT(*) AS cnt FROM application_academic_qualifications aq JOIN job_applications ja ON ja.id=aq.application_id WHERE ja.status != 'draft' $statsWhere GROUP BY level ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);

    // PLWD count
    $plwdCount = (int)$pdo->query("SELECT COUNT(*) FROM job_applications ja WHERE is_plwd=1 AND status!='draft' $statsWhere")->fetchColumn();

    // Shortlist for selected/all positions
    $shortlistWhere = $jobFilter ? 'ja.job_position_id = ' . $jobFilter : '1=1';
    $shortlistApps  = $pdo->query("
        SELECT ja.reference_number,
               CONCAT(ja.first_name,' ',COALESCE(ja.second_name,''),' ',ja.last_name) AS full_name,
               ja.email, ja.phone_primary, ja.home_county, ja.screening_score,
               ja.status, jp.title AS job_title, ja.shortlisted_at
        FROM job_applications ja
        JOIN job_positions jp ON jp.id = ja.job_position_id
        WHERE $shortlistWhere
          AND ja.status IN ('shortlisted','interview_scheduled','interview_done','offered')
        ORDER BY ja.screening_score DESC, ja.shortlisted_at ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log('ATS report error: ' . $e->getMessage());
    $totalApps=$shortlisted=$interviews=$offered=$rejected=0; $avgScore=0;
    $perPosition=$daily=$genderBreak=$countyBreak=$qualBreak=$shortlistApps=[];
    $plwdCount=0;
}

// Export shortlist as CSV
if (isset($_GET['export']) && $_GET['export'] === 'shortlist') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="shortlist_'.date('Y-m-d').'.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Reference','Name','Email','Phone','County','Position','Score','Status','Shortlisted On']);
    foreach ($shortlistApps as $row) {
        fputcsv($out, [
            $row['reference_number'], trim($row['full_name']), $row['email'], $row['phone_primary'],
            $row['home_county'], $row['job_title'], $row['screening_score'] ?? 'N/A',
            ucwords(str_replace('_',' ',$row['status'])),
            $row['shortlisted_at'] ? date('d M Y', strtotime($row['shortlisted_at'])) : '—'
        ]);
    }
    fclose($out);
    exit;
}

// Build chart arrays
$chartDays = $chartCounts = [];
for ($i = 29; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $chartDays[]   = date('M j', strtotime("-$i days"));
    $found = array_filter($daily, fn($r) => $r['day'] === $d);
    $chartCounts[] = $found ? array_values($found)[0]['cnt'] : 0;
}

$qualLabels = ['kcse'=>'KCSE','certificate'=>'Certificate','diploma'=>'Diploma','degree'=>'Degree','masters'=>'Masters','phd'=>'PhD'];
$statusColors = ['shortlisted'=>'#22C55E','interview_scheduled'=>'#A855F7','interview_done'=>'#06B6D4','offered'=>'#F5A800'];
?>

<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1.25rem;">
  <div>
    <h1 style="font-size:1.1rem;margin:0;">Recruitment Reports</h1>
    <p style="font-size:0.72rem;color:var(--kw-text-muted);">Analytics and shortlist summaries</p>
  </div>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap;align-items:center;">
    <form method="GET" style="display:flex;gap:0.4rem;align-items:center;">
      <select name="job" onchange="this.form.submit()" style="padding:0.4rem 0.65rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.78rem;color:var(--kw-text-primary);outline:none;">
        <option value="0">All Positions</option>
        <?php foreach ($allJobs as $j): ?>
        <option value="<?= $j['id'] ?>" <?= $jobFilter===$j['id']?'selected':'' ?>><?= e($j['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </form>
    <a href="?<?= $jobFilter?"job=$jobFilter&":'' ?>export=shortlist" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-file-excel"></i> Export Shortlist</a>
    <a href="<?= url('admin/recruitment/applications') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-inbox"></i> All Applications</a>
  </div>
</div>

<!-- KPI strip -->
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:0.85rem;margin-bottom:1.25rem;">
  <?php foreach ([
    ['fa-inbox','#3B82F6',$totalApps,'Total Applied'],
    ['fa-list-check','#22C55E',$shortlisted,'Shortlisted'],
    ['fa-video','#A855F7',$interviews,'Interviewed'],
    ['fa-handshake','#F5A800',$offered,'Offered'],
    ['fa-times-circle','#EF4444',$rejected,'Rejected'],
    ['fa-star-half-stroke','#F97316',round($avgScore,1),'Avg Score'],
  ] as $k): ?>
  <div class="adm-stat" style="flex-direction:column;text-align:center;padding:1rem 0.75rem;border-top:2px solid <?= $k[1] ?>;">
    <i class="fa-solid <?= $k[0] ?>" style="color:<?= $k[1] ?>;font-size:1.1rem;margin-bottom:0.4rem;"></i>
    <div class="adm-stat-value" style="font-size:1.35rem;"><?= $k[2] ?></div>
    <div class="adm-stat-label"><?= $k[3] ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts row -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">Applications per Day (30 days)</h4></div>
    <div class="adm-card-body"><canvas id="daily-chart" height="110"></canvas></div>
  </div>
  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">Gender Breakdown</h4></div>
    <div class="adm-card-body" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
      <canvas id="gender-chart" width="100" height="100" style="flex-shrink:0;"></canvas>
      <div>
        <?php $gColors=['male'=>'#3B82F6','female'=>'#F5A800','prefer_not_to_say'=>'#6B7280'];
        foreach ($genderBreak as $g): $c=$gColors[$g['gender']]??'#6B7280'; ?>
        <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;margin-bottom:0.3rem;">
          <span style="width:8px;height:8px;border-radius:50%;background:<?=$c?>;flex-shrink:0;"></span>
          <?= ucwords(str_replace('_',' ',$g['gender'])) ?> <strong style="margin-left:auto;"><?= $g['cnt'] ?></strong>
        </div>
        <?php endforeach; ?>
        <?php if ($plwdCount > 0): ?>
        <div style="margin-top:0.5rem;padding-top:0.5rem;border-top:1px solid var(--kw-border);font-size:0.68rem;color:#A855F7;font-weight:700;">
          <i class="fa-solid fa-wheelchair" style="margin-right:0.25rem;"></i><?= $plwdCount ?> PLWD applicant<?= $plwdCount!==1?'s':'' ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Qual + County breakdown -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">Qualification Levels</h4></div>
    <div class="adm-card-body">
      <?php $maxQ = max(array_column($qualBreak,'cnt')??[1]);
      $qColors=['#3B82F6','#22C55E','#F5A800','#A855F7','#F97316','#EF4444'];
      foreach ($qualBreak as $i=>$q): $pct=round(($q['cnt']/$maxQ)*100); $c=$qColors[$i%count($qColors)]; ?>
      <div style="margin-bottom:0.65rem;">
        <div style="display:flex;justify-content:space-between;font-size:0.72rem;margin-bottom:0.2rem;">
          <span><?= $qualLabels[$q['level']] ?? $q['level'] ?></span><strong style="color:<?=$c?>"><?= $q['cnt'] ?></strong>
        </div>
        <div style="height:6px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
          <div style="height:100%;width:<?=$pct?>%;background:<?=$c?>;border-radius:999px;"></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($qualBreak)): ?><p style="font-size:0.78rem;color:var(--kw-text-muted);">No data yet.</p><?php endif; ?>
    </div>
  </div>

  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">Top Counties</h4></div>
    <div class="adm-card-body">
      <?php $maxC = max(array_column($countyBreak,'cnt')??[1]);
      foreach ($countyBreak as $i=>$county): $pct=round(($county['cnt']/$maxC)*100); ?>
      <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:0.5rem;font-size:0.72rem;">
        <span style="width:80px;flex-shrink:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($county['home_county']) ?></span>
        <div style="flex:1;height:6px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
          <div style="height:100%;width:<?=$pct?>%;background:var(--kw-primary);border-radius:999px;"></div>
        </div>
        <span style="font-weight:700;min-width:20px;text-align:right;"><?= $county['cnt'] ?></span>
      </div>
      <?php endforeach; ?>
      <?php if (empty($countyBreak)): ?><p style="font-size:0.78rem;color:var(--kw-text-muted);">No data yet.</p><?php endif; ?>
    </div>
  </div>
</div>

<!-- Per-position table -->
<div class="adm-card" style="padding:0;overflow:hidden;margin-bottom:1.25rem;">
  <div class="adm-card-head"><h4 class="adm-card-title">Applications by Position</h4></div>
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>Position</th><th>Total</th><th>Shortlisted</th><th>Rejected</th><th>Offered</th><th>Avg Score</th><th>Conversion</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($perPosition as $pos): $conv = $pos['total'] > 0 ? round(($pos['shortlisted']/$pos['total'])*100) : 0; ?>
    <tr>
      <td style="font-weight:700;font-size:0.82rem;"><?= e($pos['title']) ?></td>
      <td style="font-weight:800;color:var(--kw-primary);"><?= $pos['total'] ?></td>
      <td><span style="background:rgba(34,197,94,0.12);color:#22C55E;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.65rem;font-weight:700;"><?= $pos['shortlisted'] ?></span></td>
      <td><span style="background:rgba(239,68,68,0.1);color:#EF4444;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.65rem;font-weight:700;"><?= $pos['rejected'] ?></span></td>
      <td><span style="background:rgba(245,168,0,0.12);color:var(--kw-primary);border-radius:999px;padding:0.1rem 0.45rem;font-size:0.65rem;font-weight:700;"><?= $pos['offered'] ?></span></td>
      <td style="font-size:0.78rem;"><?= $pos['avg_score'] ? round($pos['avg_score'],1) : '—' ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;">
          <div style="width:60px;height:5px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
            <div style="height:100%;width:<?=$conv?>%;background:<?= $conv>=50?'#22C55E':($conv>=25?'#F5A800':'#EF4444') ?>;border-radius:999px;"></div>
          </div>
          <span style="font-weight:700;"><?=$conv?>%</span>
        </div>
      </td>
      <td>
        <a href="<?= url('admin/recruitment/applications') ?>?job=<?= array_search($pos['slug'], array_column($allJobs,'id')) ?>" style="font-size:0.68rem;color:var(--kw-primary);">View</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
</div>

<!-- Shortlist table -->
<div class="adm-card" style="padding:0;overflow:hidden;">
  <div class="adm-card-head">
    <h4 class="adm-card-title"><i class="fa-solid fa-list-check" style="color:#22C55E;margin-right:0.4rem;"></i>Current Shortlist<?= $jobFilter ? ' — '.e($allJobs[array_search($jobFilter, array_column($allJobs,'id'))]['title'] ?? '') : '' ?> (<?= count($shortlistApps) ?>)</h4>
    <a href="?<?= $jobFilter?"job=$jobFilter&":'' ?>export=shortlist" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.7rem;"><i class="fa-solid fa-download"></i> Export CSV</a>
  </div>
  <?php if (empty($shortlistApps)): ?>
  <div style="padding:2rem;text-align:center;color:var(--kw-text-muted);font-size:0.82rem;">No shortlisted candidates yet.</div>
  <?php else: ?>
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>#</th><th>Reference</th><th>Name</th><th>Email</th><th>Phone</th><th>County</th><th>Position</th><th>Score</th><th>Status</th><th>Shortlisted</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($shortlistApps as $i => $app):
      $sc = $statusColors[$app['status']] ?? '#22C55E';
    ?>
    <tr>
      <td style="font-size:0.7rem;color:var(--kw-text-muted);"><?= $i+1 ?></td>
      <td style="font-size:0.68rem;font-family:monospace;font-weight:700;"><?= e($app['reference_number']) ?></td>
      <td style="font-weight:700;font-size:0.78rem;white-space:nowrap;"><?= e(trim($app['full_name'])) ?></td>
      <td style="font-size:0.72rem;"><a href="mailto:<?= e($app['email']) ?>" style="color:var(--kw-primary);"><?= e($app['email']) ?></a></td>
      <td style="font-size:0.72rem;"><?= e($app['phone_primary']) ?></td>
      <td style="font-size:0.72rem;"><?= e($app['home_county']) ?></td>
      <td style="font-size:0.72rem;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($app['job_title']) ?></td>
      <td>
        <?php if ($app['screening_score'] !== null): ?>
        <span style="font-weight:800;font-size:0.82rem;color:<?= $app['screening_score']>=70?'#22C55E':($app['screening_score']>=50?'#F5A800':'#EF4444') ?>;"><?= $app['screening_score'] ?></span>
        <?php else: ?><span style="color:var(--kw-text-muted);font-size:0.65rem;">—</span><?php endif; ?>
      </td>
      <td><span style="background:<?=$sc?>15;color:<?=$sc?>;border-radius:999px;padding:0.12rem 0.5rem;font-size:0.62rem;font-weight:700;white-space:nowrap;"><?= ucwords(str_replace('_',' ',$app['status'])) ?></span></td>
      <td style="font-size:0.65rem;white-space:nowrap;"><?= $app['shortlisted_at'] ? date('d M Y',strtotime($app['shortlisted_at'])) : '—' ?></td>
      <td>
        <a href="<?= url('admin/recruitment/applications') ?>?id=<?= rawurlencode($app['reference_number']) ?>" style="font-size:0.68rem;color:var(--kw-primary);">View</a>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>
</div>

<script>
// Daily applications chart
new Chart(document.getElementById('daily-chart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($chartDays) ?>,
    datasets: [{ label:'Applications', data: <?= json_encode($chartCounts) ?>,
      backgroundColor: 'rgba(245,168,0,0.4)', borderColor:'#F5A800', borderWidth:1 }]
  },
  options: { responsive:true, plugins:{legend:{display:false}},
    scales:{ x:{grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'#6B7280',font:{size:9},maxTicksLimit:10}},
             y:{grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'#6B7280',font:{size:9}},beginAtZero:true} }}
});

// Gender donut
const gData  = <?= json_encode(array_column($genderBreak,'cnt')) ?>;
const gLabels = <?= json_encode(array_map(fn($g)=>ucwords(str_replace('_',' ',$g['gender'])), $genderBreak)) ?>;
const gColors = <?= json_encode(array_map(fn($g) => ['male'=>'#3B82F6','female'=>'#F5A800','prefer_not_to_say'=>'#6B7280'][$g['gender']] ?? '#6B7280', $genderBreak)) ?>;
if (gData.length) new Chart(document.getElementById('gender-chart').getContext('2d'), {
  type:'doughnut', data:{ labels:gLabels, datasets:[{data:gData, backgroundColor:gColors, borderWidth:2, borderColor:'var(--kw-bg-card)'}] },
  options:{ responsive:false, plugins:{legend:{display:false}}, cutout:'65%' }
});
</script>
<style>@media(max-width:1100px){div[style*="repeat(6,1fr)"]{grid-template-columns:repeat(3,1fr)!important;} div[style*="2fr 1fr"],div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}} @media(max-width:640px){div[style*="repeat(6,1fr)"]{grid-template-columns:repeat(2,1fr)!important;}}</style>
<?php require_once __DIR__ . '/../admin-footer.php'; ?>