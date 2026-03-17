<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'AI Hub — ' . APP_NAME;
$page_description = 'Free and premium AI tools for business — document summariser, resume analyser, code assistant, business strategy AI, financial analysis, sales forecasting, and more.';


$free_tools = [
  ['document-summarizer','fa-file-lines','#F5A800','Document Summarizer',
   'Paste any document and get a structured summary with key points, action items, and insights extracted in seconds.',
   ['Extracts key points','Action items detection','Topic categorisation','Multiple output formats'],
   'No signup required'],
  ['resume-analyzer','fa-id-card','#3B82F6','Resume Analyzer',
   'Upload or paste a resume to get a professional analysis — strengths, gaps, ATS score, and improvement recommendations.',
   ['ATS compatibility score','Skills gap analysis','Formatting review','Improvement suggestions'],
   'No signup required'],
  ['code-assistant','fa-code','#22C55E','Code Assistant',
   'Get code reviewed, debugged, explained, or written from scratch. Supports PHP, Python, JavaScript, SQL, and more.',
   ['Code review & bugs','Write code from description','Explain complex code','Refactoring suggestions'],
   'No signup required'],
  ['meeting-notes','fa-microphone','#A855F7','Meeting Notes Generator',
   'Paste raw meeting transcripts or rough notes and get structured minutes with decisions, owners, and next steps.',
   ['Structured minutes','Action item extraction','Decision summary','Attendee tracking'],
   'No signup required'],
  ['email-writer','fa-envelope','#F97316','Professional Email Writer',
   'Describe the situation and tone. Get a professionally written email ready to send.',
   ['Multiple tones','Follow-up emails','Cold outreach','Complaint handling'],
   'No signup required'],
  ['job-description','fa-briefcase','#EF4444','Job Description Generator',
   'Enter a role title, industry, and key responsibilities. Get a complete, ATS-optimised job description.',
   ['ATS-optimised','Skill requirements','Company culture section','Multiple seniority levels'],
   'No signup required'],
];

$premium_tools = [
  ['business-strategy-ai','fa-chess','#F5A800','Business Strategy AI',
   'Input your business context, goals, and constraints. Get a structured strategic analysis with SWOT, competitive positioning, and a 90-day action plan.',
   ['SWOT Analysis','Market positioning','90-day action plan','Risk assessment','Revenue model review'],
   'Pro Plan'],
  ['financial-analysis-ai','fa-calculator','#3B82F6','Financial Analysis AI',
   'Enter your financial data (revenue, costs, margins) and get AI-powered analysis with trends, ratios, anomalies, and CFO-level recommendations.',
   ['Ratio analysis','Trend detection','Budget variance','Cash flow insights','CFO recommendations'],
   'Pro Plan'],
  ['sales-forecasting','fa-chart-line','#22C55E','Sales Forecasting AI',
   'Feed in your sales history, pipeline, and market context. Get AI-powered revenue forecasts with confidence intervals and scenario modelling.',
   ['Revenue forecasting','Pipeline analysis','Scenario modelling','Seasonality detection','Growth projections'],
   'Pro Plan'],
  ['data-insight-generator','fa-magnifying-glass-chart','#A855F7','Data Insight Generator',
   'Paste tabular data (CSV format). Get automated statistical analysis, pattern detection, outlier alerts, and business narrative.',
   ['Statistical analysis','Pattern detection','Outlier alerts','Business narrative','Chart recommendations'],
   'Pro Plan'],
];
?>

<!-- Hero -->
<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span class="current">AI Hub</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-robot"></i> Krest AI Hub</span>
      <h1>AI Tools Built for<br><span style="color:var(--kw-primary);">Real Business Work</span></h1>
      <p>Not demos. Not toys. Production-grade AI tools that save hours, surface insights, and make your team smarter — free and premium tiers available.</p>
      <div style="display:flex;gap:0.85rem;flex-wrap:wrap;margin-top:1.5rem;">
        <a href="#free-tools" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-gift"></i> Free Tools</a>
        <a href="#premium-tools" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.2);"><i class="fa-solid fa-crown"></i> Premium Tools</a>
      </div>
    </div>
  </div>
</section>

<!-- Stats strip -->
<div style="background:var(--kw-bg-card);border-bottom:1px solid var(--kw-border);padding:1.25rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;text-align:center;">
      <?php foreach ([['10','AI Tools Available'],['6','Free Forever'],['4','Premium Tools'],['AI-Powered','By Claude']] as $s): ?>
      <div>
        <div style="font-size:1.5rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);"><?= $s[0] ?></div>
        <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $s[1] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Free Tools -->
<section class="kw-section" id="free-tools" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-gift"></i> Free Tools</span>
      <h2>Free AI Tools — No Account Needed</h2>
      <p>Six powerful AI tools available to anyone, completely free. No signup, no credit card, no limit per session.</p>
      <div class="kw-divider"></div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;" id="free-tools-grid">
      <?php foreach ($free_tools as $i => $tool): ?>
      <div class="kw-card ai-tool-card" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>"
           style="padding:1.75rem;border-top:3px solid <?= $tool[2] ?>;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-10px;right:-10px;font-size:4rem;opacity:0.04;color:<?= $tool[2] ?>;font-family:var(--font-heading);font-weight:800;line-height:1;">AI</div>

        <div style="width:52px;height:52px;border-radius:var(--kw-radius-md);background:<?= $tool[2] ?>15;color:<?= $tool[2] ?>;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:1.25rem;">
          <i class="fa-solid <?= $tool[1] ?>"></i>
        </div>

        <div style="display:inline-flex;align-items:center;gap:0.35rem;background:#16a34a15;color:#16a34a;border:1px solid #16a34a30;border-radius:999px;padding:0.18rem 0.65rem;font-size:0.68rem;font-weight:700;margin-bottom:0.85rem;text-transform:uppercase;letter-spacing:0.06em;">
          <i class="fa-solid fa-circle-check" style="font-size:0.6rem;"></i> <?= $tool[6] ?>
        </div>

        <h3 style="font-size:1rem;margin-bottom:0.5rem;"><?= $tool[3] ?></h3>
        <p style="font-size:0.83rem;color:var(--kw-text-muted);margin-bottom:1rem;"><?= $tool[4] ?></p>

        <ul style="display:flex;flex-direction:column;gap:0.3rem;margin-bottom:1.25rem;">
          <?php foreach ($tool[5] as $feat): ?>
          <li style="display:flex;align-items:center;gap:0.45rem;font-size:0.78rem;color:var(--kw-text-secondary);">
            <i class="fa-solid fa-check" style="color:<?= $tool[2] ?>;font-size:0.65rem;flex-shrink:0;"></i><?= $feat ?>
          </li>
          <?php endforeach; ?>
        </ul>

        <a href="<?= url('ai-hub/' . $tool[0]) ?>" class="kw-btn kw-btn-sm" style="width:100%;justify-content:center;background:<?= $tool[2] ?>;color:#fff;border-color:<?= $tool[2] ?>;">
          <i class="fa-solid fa-wand-magic-sparkles"></i> Try Now — Free
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Premium Tools -->
<section class="kw-section" id="premium-tools" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-crown"></i> Premium Tools</span>
      <h2>Premium AI Tools</h2>
      <p>Advanced AI tools for serious business analysis — available on the Pro plan. Deeper models, longer context, business-grade outputs.</p>
      <div class="kw-divider"></div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;" id="premium-tools-grid">
      <?php foreach ($premium_tools as $i => $tool): ?>
      <div class="kw-card ai-tool-card" data-aos="fade-up" data-aos-delay="<?= ($i % 2) * 100 ?>"
           style="padding:2rem;border-left:4px solid <?= $tool[2] ?>;">
        <div style="display:flex;align-items:flex-start;gap:1rem;margin-bottom:1.25rem;">
          <div style="width:54px;height:54px;border-radius:var(--kw-radius-md);background:<?= $tool[2] ?>15;color:<?= $tool[2] ?>;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
            <i class="fa-solid <?= $tool[1] ?>"></i>
          </div>
          <div>
            <div style="display:inline-flex;align-items:center;gap:0.35rem;background:#f59e0b15;color:#f59e0b;border:1px solid #f59e0b30;border-radius:999px;padding:0.18rem 0.65rem;font-size:0.68rem;font-weight:700;margin-bottom:0.4rem;text-transform:uppercase;letter-spacing:0.06em;">
              <i class="fa-solid fa-crown" style="font-size:0.6rem;"></i> <?= $tool[6] ?>
            </div>
            <h3 style="font-size:1.05rem;margin:0;"><?= $tool[3] ?></h3>
          </div>
        </div>

        <p style="font-size:0.875rem;color:var(--kw-text-muted);margin-bottom:1.25rem;"><?= $tool[4] ?></p>

        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:1.5rem;">
          <?php foreach ($tool[5] as $feat): ?>
            <span style="background:<?= $tool[2] ?>10;color:<?= $tool[2] ?>;border:1px solid <?= $tool[2] ?>25;border-radius:999px;padding:0.2rem 0.65rem;font-size:0.72rem;font-weight:600;"><?= $feat ?></span>
          <?php endforeach; ?>
        </div>

        <?php if (!empty($_SESSION['user_id'])): ?>
          <a href="<?= url('ai-hub/' . $tool[0]) ?>" class="kw-btn kw-btn-sm" style="background:<?= $tool[2] ?>;color:#fff;border-color:<?= $tool[2] ?>;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> Open Tool
          </a>
        <?php else: ?>
          <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
            <button onclick="window.Krest?.openModal('ai-upgrade-modal')" class="kw-btn kw-btn-sm" style="background:<?= $tool[2] ?>;color:#fff;border-color:<?= $tool[2] ?>;">
              <i class="fa-solid fa-crown"></i> Unlock — Pro Plan
            </button>
            <a href="<?= url('pricing') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">View Pricing</a>
          </div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- How It Works -->
<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-circle-info"></i> How It Works</span>
      <h2>Powered by Enterprise AI</h2>
      <p>Every tool in the Krest AI Hub is powered by Claude — Anthropic's most capable AI model — with business-specific system prompts engineered by our team.</p>
      <div class="kw-divider"></div>
    </div>
    <div class="kw-grid-3" data-aos="fade-up" data-aos-delay="100">
      <?php
      $steps = [
        ['fa-1','Input Your Content','Paste text, upload a document, or describe your scenario. No special formatting required.','#F5A800'],
        ['fa-2','AI Processes Instantly','Our enterprise AI model analyses your input using business-optimised prompts engineered for accuracy.','#3B82F6'],
        ['fa-3','Get Structured Output','Receive professionally formatted results you can act on immediately — copy, download, or integrate.','#22C55E'],
      ];
      foreach ($steps as $s): ?>
      <div class="kw-card" style="padding:2rem;text-align:center;" data-aos="zoom-in">
        <div style="width:56px;height:56px;border-radius:50%;background:<?= $s[3] ?>15;color:<?= $s[3] ?>;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin:0 auto 1.25rem;border:2px solid <?= $s[3] ?>30;">
          <i class="fa-solid <?= $s[0] ?>"></i>
        </div>
        <h4 style="margin-bottom:0.5rem;"><?= $s[1] ?></h4>
        <p style="font-size:0.85rem;color:var(--kw-text-muted);margin:0;"><?= $s[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Upgrade Modal -->
<div id="ai-upgrade-modal" class="kw-modal-overlay">
  <div class="kw-modal" style="max-width:500px;text-align:center;">
    <div class="kw-modal-header">
      <h3><i class="fa-solid fa-crown" style="color:#F5A800;"></i> Premium Tool</h3>
      <button class="kw-modal-close"><i class="fa-solid fa-times"></i></button>
    </div>
    <div class="kw-modal-body">
      <p style="margin-bottom:1.5rem;">Premium AI tools require a <strong>Pro Plan</strong> subscription. Get access to all 4 premium tools plus unlimited free tool usage.</p>
      <div style="background:var(--kw-bg-alt);border-radius:var(--kw-radius-md);padding:1.5rem;margin-bottom:1.5rem;">
        <div style="font-size:2rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);">KES 4,900<span style="font-size:1rem;color:var(--kw-text-muted);font-weight:400;">/month</span></div>
        <p style="font-size:0.85rem;color:var(--kw-text-muted);margin:0.5rem 0 0;">All 10 AI tools · Priority processing · API access</p>
      </div>
      <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
        <a href="<?= url('pricing') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-crown"></i> View Plans</a>
        <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-ghost">Create Free Account</a>
      </div>
    </div>
  </div>
</div>

<style>
@media(max-width:1024px){ #free-tools-grid{ grid-template-columns:repeat(2,1fr)!important; } }
@media(max-width:640px) { #free-tools-grid,#premium-tools-grid{ grid-template-columns:1fr!important; } }
@media(max-width:768px) { #premium-tools-grid{ grid-template-columns:1fr!important; } }
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>