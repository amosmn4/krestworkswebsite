<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Innovation Lab — ' . APP_NAME;
$page_description = 'Interactive tools, simulators, and engineering experiences — workflow builders, ROI calculators, system selectors, SDLC simulators, dev quizzes, and more.';


$lab_tools = [
  // Builders
  ['workflow-builder',     'fa-diagram-project','#F5A800','Workflow Builder',      'Build visual automation workflows with drag-and-drop nodes. Export to JSON.','Builder','Free'],
  ['dashboard-generator',  'fa-chart-pie',     '#3B82F6','Dashboard Generator',   'Pick chart types and datasets — see a live Chart.js dashboard generated instantly.','Builder','Free'],
  ['form-builder',         'fa-wpforms',       '#22C55E','Form Builder',          'Design forms visually and generate the HTML/PHP code automatically.','Builder','Free'],
  ['db-schema-designer',   'fa-table-columns', '#A855F7','DB Schema Designer',    'Design database tables, relationships, and get auto-generated SQL CREATE statements.','Builder','Free'],
  ['api-tester',           'fa-plug',          '#F97316','API Tester',            'Test REST API endpoints directly in the browser — like a lightweight Postman.','Tool','Free'],
  // Decision Tools
  ['system-selector',      'fa-robot',         '#EF4444','AI System Selector',    'Answer 8 questions about your business — get an AI recommendation of the right enterprise system.','AI Tool','Free'],
  ['roi-calculator',       'fa-calculator',    '#06B6D4','ROI Calculator',        'Model the financial return of a software investment: hours saved, cost reduction, payback period.','Calculator','Free'],
  ['tech-stack-advisor',   'fa-layer-group',   '#8B5CF6','Tech Stack Advisor',    'Describe your project — get an AI-recommended tech stack with rationale and tradeoff analysis.','AI Tool','Free'],
  // Learning
  ['sdlc-simulator',       'fa-rotate',        '#F5A800','SDLC Simulator',        'Walk through a realistic software project lifecycle — make decisions at each phase and see outcomes.','Simulator','Free'],
  ['dev-quiz',             'fa-graduation-cap','#3B82F6','Developer Skills Quiz',  'Test your knowledge across PHP, SQL, system design, APIs, and security with scored adaptive quizzes.','Quiz','Free'],
  ['security-threat-lab',  'fa-shield-halved', '#EF4444','Security Threat Lab',   'Identify vulnerabilities in sample code snippets — learn OWASP Top 10 through real examples.','Learning','Free'],
  ['software-decision-sim','fa-chess',         '#22C55E','Software Decision Sim', 'Run a business scenario and make real-world software procurement and build-vs-buy decisions.','Simulator','Free'],
];
?>

<!-- Hero -->
<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span class="current">Innovation Lab</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-flask"></i> Interactive Lab</span>
      <h1>Krestworks<br><span style="color:var(--kw-primary);">Innovation Lab</span></h1>
      <p>Hands-on engineering tools, simulators, and AI-powered decision tools. Build things. Test ideas. Learn by doing. No account required for most tools.</p>
      <div style="display:flex;gap:0.85rem;flex-wrap:wrap;margin-top:1.5rem;">
        <a href="#lab-tools" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-flask"></i> Explore Tools</a>
        <a href="<?= url('ai-hub') ?>" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.2);"><i class="fa-solid fa-robot"></i> AI Hub</a>
      </div>
    </div>
  </div>
</section>

<!-- Stats -->
<div style="background:var(--kw-bg-card);border-bottom:1px solid var(--kw-border);padding:1.25rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;text-align:center;">
      <?php foreach (['12','4','8','All Free'] as $i => $s): ?>
      <?php $labels = ['Interactive Tools','Simulators','AI-Powered Tools','No Signup Required']; ?>
      <div>
        <div style="font-size:1.5rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);"><?= $s ?></div>
        <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $labels[$i] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Tools Grid -->
<section class="kw-section" id="lab-tools" style="background:var(--kw-bg);">
  <div class="kw-container">

    <!-- Filter tabs -->
    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-bottom:2.5rem;" id="lab-filters">
      <?php foreach (['All','Builder','AI Tool','Simulator','Calculator','Quiz','Learning','Tool'] as $i => $f): ?>
      <button class="kw-btn kw-btn-sm lab-filter-btn <?= $i===0?'active':'' ?>"
              data-filter="<?= $f ?>"
              style="<?= $i===0?'background:var(--kw-primary);color:#0A0F1A;':'' ?>font-size:0.78rem;">
        <?= $f ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Grid -->
    <div id="lab-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
      <?php foreach ($lab_tools as $i => $tool): ?>
      <div class="kw-card lab-tool-item"
           data-type="<?= strtolower($tool[5]) ?>"
           data-aos="fade-up"
           data-aos-delay="<?= ($i % 3) * 60 ?>"
           style="padding:1.75rem;border-top:3px solid <?= $tool[2] ?>;position:relative;overflow:hidden;">

        <!-- Category badge -->
        <div style="position:absolute;top:1rem;right:1rem;">
          <span style="background:<?= $tool[2] ?>15;color:<?= $tool[2] ?>;border:1px solid <?= $tool[2] ?>30;border-radius:999px;padding:0.18rem 0.6rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;"><?= $tool[5] ?></span>
        </div>

        <div style="width:52px;height:52px;border-radius:var(--kw-radius-md);background:<?= $tool[2] ?>15;color:<?= $tool[2] ?>;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:1.25rem;">
          <i class="fa-solid <?= $tool[1] ?>"></i>
        </div>

        <h3 style="font-size:1rem;margin-bottom:0.4rem;"><?= $tool[3] ?></h3>
        <p style="font-size:0.82rem;color:var(--kw-text-muted);margin-bottom:1.25rem;line-height:1.55;"><?= $tool[4] ?></p>

        <a href="<?= url('innovation-lab/' . $tool[0]) ?>" class="kw-btn kw-btn-sm" style="background:<?= $tool[2] ?>;color:#fff;border-color:<?= $tool[2] ?>;">
          <i class="fa-solid fa-arrow-right"></i> Launch Tool
        </a>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- No results -->
    <div id="lab-no-results" style="display:none;text-align:center;padding:4rem 0;">
      <i class="fa-solid fa-flask" style="font-size:2.5rem;color:var(--kw-text-muted);margin-bottom:1rem;display:block;"></i>
      <h4>No tools match this filter</h4>
      <button onclick="resetLabFilters()" class="kw-btn kw-btn-ghost" style="margin-top:1rem;">Show All Tools</button>
    </div>

  </div>
</section>

<!-- Purpose section -->
<section class="kw-section" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" data-aos="fade-up">
      <div>
        <span class="label"><i class="fa-solid fa-lightbulb"></i> Why the Lab?</span>
        <h2 style="margin-bottom:1rem;">The Website is the Demo</h2>
        <p style="margin-bottom:1rem;">The Innovation Lab exists to prove a point: Krestworks doesn't just build software — we think in systems, simulations, and tools.</p>
        <p style="margin-bottom:1.5rem;">Every tool in this lab was built with the same codebase we use for client systems. Use them, break them, learn from them.</p>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
          <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-calendar-check"></i> Work With Us</a>
          <a href="<?= url('community') ?>" class="kw-btn kw-btn-ghost"><i class="fa-solid fa-users"></i> Join Community</a>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <?php foreach ([['fa-brain','AI-Powered','Tools backed by Claude AI'],['fa-code','Real Code','Working implementations'],['fa-graduation-cap','Learn & Build','Hands-on skill development'],['fa-gift','Free Access','No signup for most tools']] as $f): ?>
        <div class="kw-card" style="padding:1.25rem;text-align:center;" data-aos="zoom-in">
          <i class="fa-solid <?= $f[0] ?>" style="font-size:1.5rem;color:var(--kw-primary);margin-bottom:0.75rem;display:block;"></i>
          <div style="font-weight:700;font-size:0.85rem;margin-bottom:0.2rem;"><?= $f[1] ?></div>
          <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $f[2] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<script>
const labFilterBtns = document.querySelectorAll('.lab-filter-btn');
const labItems      = document.querySelectorAll('.lab-tool-item');
const labNoResults  = document.getElementById('lab-no-results');

labFilterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    labFilterBtns.forEach(b => { b.classList.remove('active'); b.style.background=''; b.style.color=''; });
    btn.classList.add('active');
    btn.style.background = 'var(--kw-primary)';
    btn.style.color = '#0A0F1A';

    const filter = btn.dataset.filter.toLowerCase();
    let visible = 0;
    labItems.forEach(item => {
      const match = filter === 'all' || item.dataset.type === filter;
      item.style.display = match ? '' : 'none';
      if (match) visible++;
    });
    labNoResults.style.display = visible === 0 ? 'block' : 'none';
  });
});

function resetLabFilters() {
  labFilterBtns.forEach((b,i) => { b.classList.toggle('active',i===0); b.style.background=i===0?'var(--kw-primary)':''; b.style.color=i===0?'#0A0F1A':''; });
  labItems.forEach(i => i.style.display='');
  labNoResults.style.display='none';
}
</script>
<style>
@media(max-width:1024px){ #lab-grid{ grid-template-columns:repeat(2,1fr)!important; } }
@media(max-width:640px) { #lab-grid{ grid-template-columns:1fr!important; } }
@media(max-width:768px) { .kw-container > div[style*="grid-template-columns:1fr 1fr"]{ grid-template-columns:1fr!important; } }
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>