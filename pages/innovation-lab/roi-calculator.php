<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'ROI Calculator — Innovation Lab — ' . APP_NAME;
$page_description = 'Model the financial return of a software investment: hours saved, cost reduction, and payback period calculation.';

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">ROI Calculator</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-calculator"></i> Calculator</span>
      <h1>Software ROI Calculator</h1>
      <p>Model the financial return of a software investment before you commit. See payback period, annual savings, and 3-year ROI in real time.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:flex-start;">

      <!-- Inputs -->
      <div class="kw-card" style="padding:2rem;">
        <h3 style="margin-bottom:1.5rem;font-size:1rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Input Your Numbers</h3>

        <?php
        $inputs = [
          ['staff_count',      'Number of Staff Using System',   'number', '20',  '1',   '', 'employees'],
          ['avg_salary',       'Average Monthly Salary (KES)',   'number', '55000','1000','', 'KES/month'],
          ['hrs_manual',       'Hours/week lost to manual work', 'number', '8',   '0.5', '', 'hrs/week/person'],
          ['system_cost',      'System Implementation Cost',     'number', '150000','10000','', 'KES one-time'],
          ['monthly_sub',      'Monthly Subscription/Support',   'number', '8000', '0',  '', 'KES/month'],
          ['error_cost',       'Monthly cost of errors/rework',  'number', '25000','0',  '', 'KES/month'],
          ['efficiency_gain',  'Expected efficiency improvement', 'range',  '30',  '5',  '80', '%'],
        ];
        foreach ($inputs as $inp): ?>
        <div style="margin-bottom:1.5rem;">
          <label for="roi-<?= $inp[0] ?>" style="display:flex;justify-content:space-between;font-size:0.82rem;font-weight:600;margin-bottom:0.5rem;color:var(--kw-text-secondary);">
            <span><?= $inp[1] ?></span>
            <span style="color:var(--kw-primary);" id="roi-<?= $inp[0] ?>-display"><?= $inp[3] ?> <small style="color:var(--kw-text-muted);font-weight:400;"><?= $inp[6] ?></small></span>
          </label>
          <?php if ($inp[2] === 'range'): ?>
          <div style="display:flex;align-items:center;gap:0.75rem;">
            <span style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $inp[4] ?>%</span>
            <input type="range" id="roi-<?= $inp[0] ?>" min="<?= $inp[4] ?>" max="<?= $inp[5] ?>" value="<?= $inp[3] ?>" step="5"
                   style="flex:1;accent-color:var(--kw-primary);">
            <span style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $inp[5] ?>%</span>
          </div>
          <?php else: ?>
          <input type="number" id="roi-<?= $inp[0] ?>" value="<?= $inp[3] ?>" min="<?= $inp[4] ?>"
                 style="width:100%;padding:0.65rem 1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-family:var(--font-body);font-size:0.875rem;color:var(--kw-text-primary);outline:none;"
                 onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Results -->
      <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:calc(var(--kw-nav-height)+1rem);">

        <!-- Key metrics -->
        <div class="kw-card" style="padding:2rem;">
          <h3 style="margin-bottom:1.5rem;font-size:1rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Your ROI Analysis</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
            <?php
            $metrics = [
              ['roi-monthly-savings',  'Monthly Savings',    '#22C55E'],
              ['roi-annual-savings',   'Annual Savings',     '#F5A800'],
              ['roi-payback',          'Payback Period',     '#3B82F6'],
              ['roi-3yr-roi',          '3-Year ROI',         '#A855F7'],
            ];
            foreach ($metrics as $m): ?>
            <div style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;text-align:center;">
              <div id="<?= $m[0] ?>" style="font-size:1.4rem;font-weight:800;color:<?= $m[2] ?>;font-family:var(--font-heading);">—</div>
              <div style="font-size:0.72rem;color:var(--kw-text-muted);margin-top:0.15rem;"><?= $m[1] ?></div>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Bar chart via canvas -->
          <canvas id="roi-chart" height="160"></canvas>
        </div>

        <!-- Breakdown -->
        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Savings Breakdown</h5>
          <div id="roi-breakdown" style="font-size:0.82rem;display:flex;flex-direction:column;gap:0.4rem;"></div>
        </div>

        <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary" style="justify-content:center;">
          <i class="fa-solid fa-calendar-check"></i> Discuss Your ROI With Us
        </a>
      </div>
    </div>
  </div>
</section>

<script>
let roiChart;

function fmt(n) {
  return 'KES ' + Math.round(n).toLocaleString();
}

function calcROI() {
  const staff       = parseFloat(document.getElementById('roi-staff_count').value)     || 0;
  const salary      = parseFloat(document.getElementById('roi-avg_salary').value)      || 0;
  const hrsManual   = parseFloat(document.getElementById('roi-hrs_manual').value)      || 0;
  const sysCost     = parseFloat(document.getElementById('roi-system_cost').value)     || 0;
  const monthlySub  = parseFloat(document.getElementById('roi-monthly_sub').value)     || 0;
  const errorCost   = parseFloat(document.getElementById('roi-error_cost').value)      || 0;
  const efficiency  = parseFloat(document.getElementById('roi-efficiency_gain').value) || 0;

  // Hourly rate
  const hrlyRate  = salary / (22 * 8); // 22 working days, 8 hrs
  const hrsPerMo  = hrsManual * 4.33;  // weeks per month
  const timeGain  = staff * hrsPerMo * hrlyRate * (efficiency / 100);
  const errGain   = errorCost * (efficiency / 100);
  const monthlySaving = timeGain + errGain - monthlySub;
  const annualSaving  = monthlySaving * 12;
  const paybackMo = monthlySaving > 0 ? sysCost / monthlySaving : 999;
  const roi3yr    = sysCost > 0 ? ((annualSaving * 3 - sysCost) / sysCost * 100) : 0;

  // Update metrics
  document.getElementById('roi-monthly-savings').textContent  = fmt(monthlySaving);
  document.getElementById('roi-annual-savings').textContent   = fmt(annualSaving);
  document.getElementById('roi-payback').textContent = paybackMo > 100 ? 'N/A' : paybackMo.toFixed(1) + ' mo';
  document.getElementById('roi-3yr-roi').textContent = roi3yr.toFixed(0) + '%';

  // Breakdown
  document.getElementById('roi-breakdown').innerHTML = `
    <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);">
      <span style="color:var(--kw-text-muted);">Labour time savings</span><span style="font-weight:700;color:#22C55E;">${fmt(timeGain)}/mo</span>
    </div>
    <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);">
      <span style="color:var(--kw-text-muted);">Error/rework reduction</span><span style="font-weight:700;color:#22C55E;">${fmt(errGain)}/mo</span>
    </div>
    <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);">
      <span style="color:var(--kw-text-muted);">Ongoing system cost</span><span style="font-weight:700;color:#EF4444;">-${fmt(monthlySub)}/mo</span>
    </div>
    <div style="display:flex;justify-content:space-between;padding:0.5rem 0;font-weight:700;">
      <span>Net Monthly Benefit</span><span style="color:#22C55E;">${fmt(monthlySaving)}/mo</span>
    </div>
  `;

  // Chart
  const labels = ['Y1 Cost','Y1 Savings','Y2 Net','Y3 Net'];
  const values = [sysCost, annualSaving, annualSaving - sysCost + monthlySub*12, annualSaving * 2 - sysCost + monthlySub*24];
  const colors = ['#EF4444','#22C55E','#F5A800','#A855F7'];

  if (roiChart) roiChart.destroy();
  const ctx = document.getElementById('roi-chart');
  if (!ctx || typeof Chart === 'undefined') return;
  roiChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [{ data: values, backgroundColor: colors, borderRadius: 6, borderSkipped: false }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => 'KES ' + ctx.raw.toLocaleString() } } },
      scales: {
        x: { ticks: { color: 'var(--kw-text-muted)', font: { size: 10 } }, grid: { display: false } },
        y: { ticks: { color: 'var(--kw-text-muted)', font: { size: 10 }, callback: v => 'KES ' + (v/1000).toFixed(0)+'K' }, grid: { color: 'var(--kw-border)' } }
      }
    }
  });
}

// Live update
document.querySelectorAll('[id^="roi-"]').forEach(el => {
  el.addEventListener('input', () => {
    // Update range display
    if (el.type === 'range') {
      const disp = document.getElementById(el.id + '-display');
      if (disp) disp.innerHTML = el.value + '% <small style="color:var(--kw-text-muted);font-weight:400;">efficiency gain</small>';
    }
    calcROI();
  });
});

// Init
document.addEventListener('DOMContentLoaded', calcROI);
</script>
<style>
@media(max-width:768px){
  .kw-container > div[style*="grid-template-columns:1fr 1fr"]{ grid-template-columns:1fr!important; }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>