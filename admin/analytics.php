<?php
$admin_title  = 'Analytics';
$admin_active = 'analytics';
require_once __DIR__ . '/admin-header.php';
$pdo = db();

try {
  // Traffic by day (30 days)
  $traffic30 = $pdo->query("SELECT DATE(created_at) as day, COUNT(*) as visits FROM site_events WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY day ASC")->fetchAll(PDO::FETCH_ASSOC);

  // Inquiries by day (30)
  $inqDaily = $pdo->query("SELECT DATE(created_at) as day, COUNT(*) as cnt FROM inquiries WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(created_at) ORDER BY day ASC")->fetchAll(PDO::FETCH_ASSOC);

  // AI tool usage by tool
  $aiByTool = $pdo->query("SELECT tool_slug, COUNT(*) as uses FROM ai_tool_usage GROUP BY tool_slug ORDER BY uses DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);

  // AI usage by day
  $aiDaily = $pdo->query("SELECT DATE(used_at) as day, COUNT(*) as uses FROM ai_tool_usage WHERE used_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY DATE(used_at) ORDER BY day ASC")->fetchAll(PDO::FETCH_ASSOC);

  // Registrations by month (6 months)
  $regMonthly = $pdo->query("SELECT DATE_FORMAT(created_at,'%b %Y') as month, COUNT(*) as cnt FROM users WHERE role='client' AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH) GROUP BY month ORDER BY MIN(created_at) ASC")->fetchAll(PDO::FETCH_ASSOC);

  // Inquiry types
  $inqTypes = $pdo->query("SELECT type, COUNT(*) as cnt FROM inquiries GROUP BY type ORDER BY cnt DESC")->fetchAll(PDO::FETCH_ASSOC);

  // Top pages
  $topPages = $pdo->query("SELECT page, COUNT(*) as visits FROM site_events WHERE page IS NOT NULL GROUP BY page ORDER BY visits DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

  // Summary totals
  $totVisits  = (int)$pdo->query("SELECT COUNT(*) FROM site_events")->fetchColumn();
  $totVisits30 = (int)$pdo->query("SELECT COUNT(*) FROM site_events WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
  $totUsers   = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn();
  $totInqs    = (int)$pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
  $totAI      = (int)$pdo->query("SELECT COUNT(*) FROM ai_tool_usage")->fetchColumn();

} catch(Exception $e) {
  $traffic30=$inqDaily=$aiByTool=$aiDaily=$regMonthly=$inqTypes=$topPages=[];
  $totVisits=$totVisits30=$totUsers=$totInqs=$totAI=0;
}

// Fill 30 day labels
$labels30=[]; for($i=29;$i>=0;$i--) $labels30[]=date('M j',strtotime("-$i days"));
function fillDays(array $data, string $keyCol, string $valCol, int $days=30): array {
  $out=[]; $map=[];
  foreach($data as $r) $map[$r[$keyCol]]=$r[$valCol];
  for($i=$days-1;$i>=0;$i--) { $d=date('Y-m-d',strtotime("-$i days")); $out[]=$map[$d]??0; }
  return $out;
}
$trafficData = fillDays($traffic30,'day','visits');
$inqData     = fillDays($inqDaily,'day','cnt');
$aiData      = fillDays($aiDaily,'day','uses');
?>

<div style="margin-bottom:1.25rem;"><h1 style="font-size:1.1rem;margin:0;">Analytics</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);">Site traffic, leads, and AI tool usage overview</p></div>

<!-- KPI strip -->
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0.85rem;margin-bottom:1.25rem;">
  <?php foreach ([
    ['fa-eye','#F5A800',$totVisits30,'Page Views (30d)'],
    ['fa-users','#3B82F6',$totUsers,'Registered Users'],
    ['fa-inbox','#22C55E',$totInqs,'Total Inquiries'],
    ['fa-robot','#A855F7',$totAI,'AI Tool Uses'],
    ['fa-chart-line','#F97316',$totVisits,'Total Events'],
  ] as $k): ?>
  <div class="adm-stat" style="border-top:2px solid <?= $k[1] ?>;">
    <div class="adm-stat-icon" style="background:<?= $k[1] ?>15;width:32px;height:32px;">
      <i class="fa-solid <?= $k[0] ?>" style="color:<?= $k[1] ?>;font-size:0.78rem;"></i>
    </div>
    <div>
      <div class="adm-stat-value" style="font-size:1.3rem;"><?= number_format($k[2]) ?></div>
      <div class="adm-stat-label" style="font-size:0.62rem;"><?= $k[3] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Traffic + Inquiries charts -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="adm-card">
    <div class="adm-card-head">
      <h4 class="adm-card-title">Traffic & Lead Conversion (30 Days)</h4>
    </div>
    <div class="adm-card-body"><canvas id="traffic-chart" height="120"></canvas></div>
  </div>
  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">Inquiry Breakdown</h4></div>
    <div class="adm-card-body" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
      <canvas id="inq-pie" width="110" height="110" style="flex-shrink:0;"></canvas>
      <div>
        <?php $pColors=['#3B82F6','#F5A800','#22C55E','#A855F7']; foreach($inqTypes as $i=>$t): ?>
        <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.7rem;margin-bottom:0.3rem;">
          <span style="width:8px;height:8px;border-radius:50%;background:<?= $pColors[$i%count($pColors)] ?>;flex-shrink:0;"></span>
          <?= ucfirst($t['type']) ?> <strong style="margin-left:auto;"><?= $t['cnt'] ?></strong>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- AI usage + Registrations -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">AI Tool Usage (30 Days)</h4></div>
    <div class="adm-card-body"><canvas id="ai-chart" height="120"></canvas></div>
  </div>
  <div class="adm-card">
    <div class="adm-card-head"><h4 class="adm-card-title">Top AI Tools</h4></div>
    <div class="adm-card-body">
      <?php $maxAI=max(array_column($aiByTool,'uses')?:[1]);
      foreach($aiByTool as $i=>$t): $pct=round(($t['uses']/$maxAI)*100); $c=['#A855F7','#3B82F6','#22C55E','#F5A800','#EF4444'][$i%5]; ?>
      <div style="margin-bottom:0.65rem;">
        <div style="display:flex;justify-content:space-between;font-size:0.7rem;margin-bottom:0.2rem;">
          <span><?= e(ucwords(str_replace(['-','_'],' ',$t['tool_slug']))) ?></span>
          <strong style="color:<?=$c?>"><?= $t['uses'] ?></strong>
        </div>
        <div style="height:5px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
          <div style="height:100%;width:<?=$pct?>%;background:<?=$c?>;border-radius:999px;"></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Top pages -->
<div class="adm-card">
  <div class="adm-card-head"><h4 class="adm-card-title">Top Pages</h4></div>
  <div style="overflow-x:auto;">
  <table class="adm-table">
    <thead><tr><th>#</th><th>Page</th><th>Views</th><th>Share</th></tr></thead>
    <tbody>
    <?php $totalPV=array_sum(array_column($topPages,'visits'))?:1;
    foreach($topPages as $i=>$pg): $pct=round(($pg['visits']/$totalPV)*100); ?>
    <tr>
      <td style="color:var(--kw-text-muted);font-size:0.7rem;"><?= $i+1 ?></td>
      <td style="font-size:0.78rem;font-weight:600;"><?= e($pg['page']) ?></td>
      <td style="font-weight:700;font-size:0.82rem;"><?= number_format($pg['visits']) ?></td>
      <td>
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <div style="flex:1;height:6px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;max-width:120px;">
            <div style="height:100%;width:<?=$pct?>%;background:var(--kw-primary);border-radius:999px;"></div>
          </div>
          <span style="font-size:0.65rem;color:var(--kw-text-muted);"><?=$pct?>%</span>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
    <?php if(empty($topPages)): ?><tr><td colspan="4" style="text-align:center;padding:1.5rem;color:var(--kw-text-muted);font-size:0.78rem;">No page data yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
</div>

<script>
const labels30 = <?= json_encode($labels30) ?>;
const tData = <?= json_encode($trafficData) ?>;
const iData = <?= json_encode($inqData) ?>;
const aData = <?= json_encode($aiData) ?>;

// Traffic + Inquiries
new Chart(document.getElementById('traffic-chart').getContext('2d'), {
  data: { labels: labels30, datasets: [
    { type:'line', label:'Page Views', data:tData, borderColor:'#F5A800', backgroundColor:'rgba(245,168,0,0.06)', fill:true, tension:0.4, borderWidth:2, pointRadius:2 },
    { type:'bar',  label:'Inquiries',  data:iData, backgroundColor:'rgba(59,130,246,0.3)', borderColor:'#3B82F6', borderWidth:1 }
  ]},
  options:{ responsive:true, plugins:{ legend:{ labels:{ font:{size:10}, color:'#6B7280' }}}, scales:{ x:{ grid:{color:'rgba(255,255,255,0.04)'}, ticks:{color:'#6B7280',font:{size:9},maxTicksLimit:10}}, y:{ grid:{color:'rgba(255,255,255,0.04)'}, ticks:{color:'#6B7280',font:{size:9}}, beginAtZero:true }}}
});

// Inquiry pie
const pColors = <?= json_encode($pColors ?? ['#3B82F6','#F5A800','#22C55E','#A855F7']) ?>;
const inqTypes = <?= json_encode($inqTypes) ?>;
if (inqTypes.length) new Chart(document.getElementById('inq-pie').getContext('2d'), {
  type:'doughnut', data:{ labels:inqTypes.map(t=>t.type), datasets:[{ data:inqTypes.map(t=>t.cnt), backgroundColor:pColors, borderWidth:2, borderColor:'var(--kw-bg-card)' }] },
  options:{ responsive:false, plugins:{ legend:{display:false} }, cutout:'60%' }
});

// AI usage
new Chart(document.getElementById('ai-chart').getContext('2d'), {
  type:'bar', data:{ labels:labels30, datasets:[{ label:'AI Uses', data:aData, backgroundColor:'rgba(168,85,247,0.4)', borderColor:'#A855F7', borderWidth:1 }] },
  options:{ responsive:true, plugins:{ legend:{display:false} }, scales:{ x:{ grid:{color:'rgba(255,255,255,0.04)'}, ticks:{color:'#6B7280',font:{size:9},maxTicksLimit:10}}, y:{ grid:{color:'rgba(255,255,255,0.04)'}, ticks:{color:'#6B7280',font:{size:9}}, beginAtZero:true }}}
});
</script>
<style>@media(max-width:900px){div[style*="2fr 1fr"],div[style*="1fr 1fr"],div[style*="repeat(5,1fr)"]{grid-template-columns:1fr!important;}}</style>
<?php require_once __DIR__ . '/admin-footer.php'; ?>