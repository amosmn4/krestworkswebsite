<?php
$admin_title  = 'Dashboard';
$admin_active = 'dashboard';
require_once __DIR__ . '/admin-header.php';

$pdo = db();

// Core KPIs
try {
    $totalUsers       = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn();
    $newUsersWeek     = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='client' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    $totalInquiries   = (int)$pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
    $newInqs30        = (int)$pdo->query("SELECT COUNT(*) FROM inquiries WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
    $activeSubs       = (int)$pdo->query("SELECT COUNT(*) FROM subscriptions WHERE status='active'")->fetchColumn();
    $aiToolUsage30    = (int)$pdo->query("SELECT COUNT(*) FROM ai_tool_usage WHERE used_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
    $openTickets      = (int)$pdo->query("SELECT COUNT(*) FROM support_tickets WHERE status='open'")->fetchColumn();
    $communityPosts   = (int)$pdo->query("SELECT COUNT(*) FROM community_posts WHERE is_active=1")->fetchColumn();

    // Recent inquiries
    $recentInqs = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);

    // Recent users
    $recentUsers = $pdo->query("SELECT id, name, email, company, role, created_at FROM users ORDER BY created_at DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

    // Support tickets
    $recentTickets = $pdo->query("SELECT t.*, u.name as user_name FROM support_tickets t LEFT JOIN users u ON u.id=t.user_id ORDER BY t.created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

    // Daily site events (last 14 days) for chart
    $eventsData = $pdo->query("SELECT DATE(created_at) as day, COUNT(*) as total FROM site_events WHERE created_at >= DATE_SUB(NOW(), INTERVAL 14 DAY) GROUP BY DATE(created_at) ORDER BY day ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Inquiry types breakdown
    $inqTypes = $pdo->query("SELECT type, COUNT(*) as cnt FROM inquiries GROUP BY type ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);

    // Top AI tools
    $topTools = $pdo->query("SELECT tool_slug, COUNT(*) as uses FROM ai_tool_usage GROUP BY tool_slug ORDER BY uses DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $totalUsers=$newUsersWeek=$totalInquiries=$newInqs30=$activeSubs=$aiToolUsage30=$openTickets=$communityPosts=0;
    $recentInqs=$recentUsers=$recentTickets=$eventsData=$inqTypes=$topTools=[];
}

// Build chart data
$chartLabels = $chartData = [];
$start = strtotime('-13 days');
for ($i = 0; $i < 14; $i++) {
    $d = date('Y-m-d', $start + $i * 86400);
    $chartLabels[] = date('M j', $start + $i * 86400);
    $found = array_filter($eventsData, fn($r) => $r['day'] === $d);
    $chartData[]   = $found ? array_values($found)[0]['total'] : 0;
}
?>

<!-- Header -->
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.2rem;margin:0 0 0.2rem;">Dashboard</h1>
    <p style="font-size:0.75rem;color:var(--kw-text-muted);">Welcome back, <?= e(explode(' ', $adminUser['name'])[0]) ?>. <?= date('l, d M Y') ?></p>
  </div>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
    <a href="<?= url('admin/inquiries') ?>" class="kw-btn kw-btn-sm kw-btn-ghost"><i class="fa-solid fa-inbox"></i> Inquiries <?php if($newInquiries>0): ?><span style="background:#EF4444;color:#fff;border-radius:999px;padding:0.05rem 0.4rem;font-size:0.58rem;margin-left:0.2rem;"><?= $newInquiries ?></span><?php endif; ?></a>
    <a href="<?= url('admin/users') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-users"></i> Users</a>
  </div>
</div>

<!-- KPI cards -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  <?php foreach ([
    ['fa-users','#F5A800',$totalUsers,'Total Clients',"↑ {$newUsersWeek} this week"],
    ['fa-inbox','#3B82F6',$totalInquiries,'Total Inquiries',"{$newInqs30} in last 30 days"],
    ['fa-cube','#22C55E',$activeSubs,'Active Subscriptions','Paying clients'],
    ['fa-robot','#A855F7',$aiToolUsage30,'AI Uses (30 days)','Across all tools'],
  ] as $kpi): ?>
  <div class="adm-stat" style="border-top:3px solid <?= $kpi[1] ?>;">
    <div class="adm-stat-icon" style="background:<?= $kpi[1] ?>15;">
      <i class="fa-solid <?= $kpi[0] ?>" style="color:<?= $kpi[1] ?>;"></i>
    </div>
    <div>
      <div class="adm-stat-value"><?= number_format($kpi[2]) ?></div>
      <div class="adm-stat-label"><?= $kpi[3] ?></div>
      <div style="font-size:0.62rem;color:var(--kw-primary);margin-top:0.15rem;"><?= $kpi[4] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Secondary stats -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
  <?php foreach ([
    ['fa-headset','#EF4444',$openTickets,'Open Tickets','admin/support'],
    ['fa-comments','#06B6D4',$communityPosts,'Community Posts','admin/community'],
    ['fa-newspaper','#8B5CF6',(int)$pdo->query("SELECT COUNT(*) FROM blog_posts WHERE is_published=1")->fetchColumn(),'Published Articles','admin/content'],
    ['fa-envelope','#F97316',(int)$pdo->query("SELECT COUNT(*) FROM newsletter_subscribers WHERE is_active=1")->fetchColumn(),'Newsletter Subs','admin/content'],
  ] as $kpi): ?>
  <a href="<?= url($kpi[4]) ?>" style="text-decoration:none;">
    <div class="adm-stat" style="cursor:pointer;transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
      <div class="adm-stat-icon" style="background:<?= $kpi[1] ?>15;">
        <i class="fa-solid <?= $kpi[0] ?>" style="color:<?= $kpi[1] ?>;font-size:0.82rem;"></i>
      </div>
      <div>
        <div class="adm-stat-value" style="font-size:1.25rem;"><?= number_format($kpi[2]) ?></div>
        <div class="adm-stat-label"><?= $kpi[3] ?></div>
      </div>
    </div>
  </a>
  <?php endforeach; ?>
</div>

<!-- Charts + lists -->
<div style="display:grid;grid-template-columns:1fr 360px;gap:1.25rem;margin-bottom:1.25rem;">

  <!-- Traffic chart -->
  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title"><i class="fa-solid fa-chart-line" style="color:var(--kw-primary);margin-right:0.4rem;"></i>Site Activity (Last 14 Days)</h4>
      <a href="<?= url('admin/analytics') ?>" style="font-size:0.72rem;color:var(--kw-primary);">Full analytics →</a>
    </div>
    <div class="adm-card-body">
      <canvas id="activity-chart" height="120"></canvas>
    </div>
  </div>

  <!-- Inquiry breakdown -->
  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title"><i class="fa-solid fa-chart-pie" style="color:#3B82F6;margin-right:0.4rem;"></i>Inquiry Types</h4>
    </div>
    <div class="adm-card-body" style="display:flex;align-items:center;gap:1rem;">
      <canvas id="inq-chart" width="120" height="120" style="flex-shrink:0;"></canvas>
      <div style="flex:1;display:flex;flex-direction:column;gap:0.45rem;">
        <?php
        $typeColors = ['contact'=>'#3B82F6','demo'=>'#F5A800','consultation'=>'#22C55E','newsletter'=>'#A855F7'];
        foreach ($inqTypes as $t):
          $color = $typeColors[$t['type']] ?? '#6B7280';
        ?>
        <div style="display:flex;align-items:center;justify-content:space-between;font-size:0.72rem;">
          <span style="display:flex;align-items:center;gap:0.35rem;">
            <span style="width:8px;height:8px;border-radius:50%;background:<?= $color ?>;flex-shrink:0;"></span>
            <?= ucfirst($t['type']) ?>
          </span>
          <span style="font-weight:700;"><?= $t['cnt'] ?></span>
        </div>
        <?php endforeach; ?>
        <?php if (empty($inqTypes)): ?>
        <p style="font-size:0.72rem;color:var(--kw-text-muted);">No inquiries yet.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

</div>

<!-- Recent data tables -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

  <!-- Recent inquiries -->
  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title"><i class="fa-solid fa-inbox" style="color:#3B82F6;margin-right:0.4rem;"></i>Recent Inquiries</h4>
      <a href="<?= url('admin/inquiries') ?>" style="font-size:0.72rem;color:var(--kw-primary);">View all →</a>
    </div>
    <?php if (empty($recentInqs)): ?>
    <div style="padding:2rem;text-align:center;color:var(--kw-text-muted);font-size:0.78rem;">No inquiries yet.</div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="adm-table">
        <thead><tr><th>Name</th><th>Type</th><th>Status</th><th>Date</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($recentInqs as $inq):
          $statClass = $inq['status']==='new' ? 'badge-new' : ($inq['status']==='resolved' ? 'badge-resolved' : 'badge-open');
        ?>
        <tr>
          <td>
            <div style="font-weight:700;font-size:0.78rem;"><?= e($inq['name'] ?? '—') ?></div>
            <div style="font-size:0.65rem;color:var(--kw-text-muted);"><?= e($inq['email'] ?? '') ?></div>
          </td>
          <td><span class="adm-badge-inline badge-info"><?= ucfirst($inq['type']) ?></span></td>
          <td><span class="adm-badge-inline <?= $statClass ?>"><?= ucfirst($inq['status'] ?? 'new') ?></span></td>
          <td style="font-size:0.7rem;white-space:nowrap;"><?= date('d M', strtotime($inq['created_at'])) ?></td>
          <td><a href="<?= url('admin/inquiries') ?>?id=<?= $inq['id'] ?>" style="color:var(--kw-primary);font-size:0.7rem;">View</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <!-- Recent users -->
  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title"><i class="fa-solid fa-users" style="color:#22C55E;margin-right:0.4rem;"></i>Recent Registrations</h4>
      <a href="<?= url('admin/users') ?>" style="font-size:0.72rem;color:var(--kw-primary);">View all →</a>
    </div>
    <?php if (empty($recentUsers)): ?>
    <div style="padding:2rem;text-align:center;color:var(--kw-text-muted);font-size:0.78rem;">No users yet.</div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="adm-table">
        <thead><tr><th>User</th><th>Role</th><th>Joined</th></tr></thead>
        <tbody>
        <?php foreach ($recentUsers as $user): ?>
        <tr>
          <td>
            <div style="font-weight:700;font-size:0.78rem;"><?= e($user['name']) ?></div>
            <div style="font-size:0.65rem;color:var(--kw-text-muted);"><?= e($user['email']) ?></div>
          </td>
          <td><span class="adm-badge-inline badge-<?= $user['role'] ?>"><?= ucfirst($user['role']) ?></span></td>
          <td style="font-size:0.7rem;white-space:nowrap;"><?= date('d M Y', strtotime($user['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

</div>

<!-- Support tickets + AI tools -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title"><i class="fa-solid fa-headset" style="color:#EF4444;margin-right:0.4rem;"></i>Open Support Tickets</h4>
      <a href="<?= url('admin/support') ?>" style="font-size:0.72rem;color:var(--kw-primary);">View all →</a>
    </div>
    <?php if (empty($recentTickets)): ?>
    <div style="padding:2rem;text-align:center;color:var(--kw-text-muted);font-size:0.78rem;"><i class="fa-solid fa-check-circle" style="color:#22C55E;margin-right:0.3rem;"></i>No open tickets.</div>
    <?php else: ?>
    <div style="overflow-x:auto;">
      <table class="adm-table">
        <thead><tr><th>Subject</th><th>User</th><th>Priority</th><th>Status</th></tr></thead>
        <tbody>
        <?php foreach ($recentTickets as $t):
          $priColor = ['critical'=>'#EF4444','high'=>'#F97316','medium'=>'#3B82F6','low'=>'#22C55E'][$t['priority']??'medium'];
        ?>
        <tr>
          <td style="font-size:0.78rem;font-weight:600;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($t['subject']) ?></td>
          <td style="font-size:0.72rem;"><?= e($t['user_name'] ?? '—') ?></td>
          <td><span style="background:<?= $priColor ?>15;color:<?= $priColor ?>;border-radius:999px;padding:0.12rem 0.45rem;font-size:0.6rem;font-weight:700;"><?= ucfirst($t['priority']??'medium') ?></span></td>
          <td><span class="adm-badge-inline badge-open"><?= ucwords(str_replace('_',' ',$t['status']??'open')) ?></span></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title"><i class="fa-solid fa-robot" style="color:#A855F7;margin-right:0.4rem;"></i>Top AI Tools (All Time)</h4>
      <a href="<?= url('admin/ai-tools') ?>" style="font-size:0.72rem;color:var(--kw-primary);">Manage →</a>
    </div>
    <div class="adm-card-body">
      <?php if (empty($topTools)): ?>
      <p style="font-size:0.78rem;color:var(--kw-text-muted);text-align:center;">No AI tool usage yet.</p>
      <?php else: ?>
      <?php
      $maxUses = max(array_column($topTools, 'uses')) ?: 1;
      $toolColors = ['#A855F7','#3B82F6','#22C55E','#F5A800','#EF4444'];
      foreach ($topTools as $i => $tool):
        $pct = round(($tool['uses'] / $maxUses) * 100);
        $color = $toolColors[$i % count($toolColors)];
      ?>
      <div style="margin-bottom:0.85rem;">
        <div style="display:flex;justify-content:space-between;font-size:0.72rem;margin-bottom:0.25rem;">
          <span><?= e(ucwords(str_replace(['-','_'],' ',$tool['tool_slug']))) ?></span>
          <span style="font-weight:700;color:<?= $color ?>;"><?= number_format($tool['uses']) ?> uses</span>
        </div>
        <div style="height:6px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
          <div style="height:100%;width:<?= $pct ?>%;background:<?= $color ?>;border-radius:999px;transition:width 0.8s;"></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

</div>

<script>
// Activity line chart
const actCtx = document.getElementById('activity-chart')?.getContext('2d');
if (actCtx) {
  new Chart(actCtx, {
    type: 'line',
    data: {
      labels: <?= json_encode($chartLabels) ?>,
      datasets: [{ label: 'Page events', data: <?= json_encode($chartData) ?>,
        borderColor: '#F5A800', backgroundColor: 'rgba(245,168,0,0.08)',
        borderWidth: 2, pointRadius: 3, pointBackgroundColor: '#F5A800', tension: 0.4, fill: true }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: {
      x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6B7280', font: { size: 10 } } },
      y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#6B7280', font: { size: 10 } }, beginAtZero: true }
    }}
  });
}

// Inquiry donut chart
const inqCtx = document.getElementById('inq-chart')?.getContext('2d');
const inqData = <?= json_encode(array_column($inqTypes, 'cnt')) ?>;
const inqLabels = <?= json_encode(array_map(fn($t) => ucfirst($t['type']), $inqTypes)) ?>;
const inqColors = ['#3B82F6','#F5A800','#22C55E','#A855F7','#EF4444'];
if (inqCtx && inqData.length) {
  new Chart(inqCtx, {
    type: 'doughnut',
    data: { labels: inqLabels, datasets: [{ data: inqData, backgroundColor: inqColors.slice(0, inqData.length), borderWidth: 2, borderColor: 'var(--kw-bg-card)' }] },
    options: { responsive: false, plugins: { legend: { display: false } }, cutout: '65%' }
  });
}
</script>

<style>@media(max-width:1200px){div[style*="repeat(4,1fr)"]{grid-template-columns:1fr 1fr!important;}}
@media(max-width:900px){div[style*="1fr 360px"],div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}}
@media(max-width:480px){div[style*="repeat(4,1fr)"]{grid-template-columns:1fr!important;}}</style>

<?php require_once __DIR__ . '/admin-footer.php'; ?>