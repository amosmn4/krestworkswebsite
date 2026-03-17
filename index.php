<?php
$page_title       = 'Krestworks Solutions — Digital Systems for Modern Businesses';
$page_description = 'AI-powered enterprise software engineered for growth. Custom ERP, HR, eLearning, Real Estate, Supply Chain systems built for African and global businesses.';

require_once 'includes/header.php';
?>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="kw-hero" id="hero">
  <canvas id="hero-canvas" class="hero-canvas"></canvas>

  <div class="kw-container" style="width:100%;">
    <div class="hero-content" data-aos="fade-up">

      <div class="hero-badge">
        <i class="fa-solid fa-bolt"></i>
        AI-Powered Enterprise Software
      </div>

      <h1 class="hero-title">
        Digital Systems for<br>
        <span class="highlight">Modern Businesses</span>
      </h1>

      <p class="hero-subtitle">
        We engineer intelligent enterprise software — ERP, HR, eLearning, Supply Chain, AI tools — built to scale your business and automate what slows you down.
      </p>

      <div class="hero-actions">
        <a href="<?= url('products') ?>" class="kw-btn kw-btn-primary kw-btn-lg">
          <i class="fa-solid fa-boxes-stacked"></i> Explore Products
        </a>
        <a href="<?= url('demo') ?>" class="kw-btn kw-btn-outline kw-btn-lg" style="color:#fff;border-color:rgba(255,255,255,0.3);">
          <i class="fa-solid fa-play-circle"></i> Request Demo
        </a>
        <button onclick="KrestAI.toggle()" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.07);color:#fff;border:1px solid rgba(255,255,255,0.15);">
          <i class="fa-solid fa-robot" style="color:var(--kw-primary);"></i> Talk to Krest AI
        </button>
      </div>

      <div class="hero-stats">
        <div>
          <span class="hero-stat-value" data-counter data-target="50" data-suffix="+">50+</span>
          <span class="hero-stat-label">Systems Deployed</span>
        </div>
        <div>
          <span class="hero-stat-value" data-counter data-target="12" data-suffix="+">12+</span>
          <span class="hero-stat-label">Industries Served</span>
        </div>
        <div>
          <span class="hero-stat-value" data-counter data-target="98" data-suffix="%">98%</span>
          <span class="hero-stat-label">Client Satisfaction</span>
        </div>
        <div>
          <span class="hero-stat-value" data-counter data-target="9" data-suffix="+">9+</span>
          <span class="hero-stat-label">Enterprise Products</span>
        </div>
      </div>

    </div>
  </div>

  <!-- Scroll cue -->
  <div class="hero-scroll" aria-hidden="true">
    <span>Scroll</span>
    <i class="fa-solid fa-chevron-down"></i>
  </div>
</section>

<!-- ============================================================
     TRUSTED BY / PARTNER LOGOS STRIP
     ============================================================ -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);border-bottom:1px solid var(--kw-border);padding:1.75rem 0;overflow:hidden;">
  <div class="kw-container">
    <p style="text-align:center;font-size:0.78rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;color:var(--kw-text-muted);margin-bottom:1.5rem;">
      Trusted by businesses across industries
    </p>
    <div class="logo-scroll-track" style="display:flex;gap:3rem;align-items:center;justify-content:center;flex-wrap:wrap;">
      <?php
      $industries = [
        ['fa-hospital','Healthcare'],['fa-industry','Manufacturing'],
        ['fa-building','Real Estate'],['fa-graduation-cap','Education'],
        ['fa-landmark','Government'],['fa-truck','Logistics'],
        ['fa-bolt','Utilities'],['fa-chart-pie','Finance'],
        ['fa-store','Retail'],['fa-hotel','Hospitality'],
      ];
      foreach ($industries as $ind): ?>
        <div style="display:flex;align-items:center;gap:0.5rem;color:var(--kw-text-muted);font-size:0.82rem;font-weight:600;white-space:nowrap;">
          <i class="fa-solid <?= $ind[0] ?>" style="color:var(--kw-primary);font-size:1.1rem;"></i>
          <?= $ind[1] ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     WHY KRESTWORKS
     ============================================================ -->
<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-star"></i> Why Choose Us</span>
      <h2>Engineering Excellence Meets Business Intelligence</h2>
      <p>We don't just build software — we architect solutions that grow with your business, backed by AI and real-world performance.</p>
      <div class="kw-divider"></div>
    </div>

    <div class="kw-grid-3" style="gap:1.25rem;">
      <?php
      $whys = [
        ['fa-brain','AI-Enabled by Default','Every system we build integrates intelligent automation — from smart analytics to AI-powered workflows that reduce manual overhead.','gold'],
        ['fa-bullseye','Business-Driven Development','We start with your business goals, not technical specs. Our systems solve real operational problems with measurable outcomes.','blue'],
        ['fa-shuffle','Industry Adaptable','From healthcare to logistics, our platforms are architected to adapt to unique industry workflows without heavy customization costs.','green'],
        ['fa-shield-halved','Secure & Compliant','Enterprise-grade security built in from day one. CSRF, SQL injection prevention, encrypted storage, role-based access, and audit trails.','red'],
        ['fa-cloud','Cloud-Ready Infrastructure','Deploy on cloud, on-premise, or hybrid. Our systems are containerized, scalable, and monitored with automatic failover.','purple'],
        ['fa-people-group','Expert Support Team','Dedicated support engineers, onboarding specialists, and continuous maintenance — we stay with you long after go-live.','gold'],
        ['fa-gauge-high','High Performance','Optimized queries, lazy loading, CDN delivery, and caching strategies ensure your systems run fast even under heavy load.','blue'],
        ['fa-code-branch','Modern Tech Stack','PHP, Laravel, React, Node.js, Python — we use battle-tested frameworks and modern languages to build robust, maintainable systems.','green'],
        ['fa-arrows-rotate','Continuous Improvement','Agile delivery with sprint-based updates, feature rollouts, and performance tuning long after initial deployment.','red'],
      ];
      foreach ($whys as $i => $w):
        $colors = ['gold'=>'245,168,0','blue'=>'59,130,246','green'=>'34,197,94','red'=>'239,68,68','purple'=>'139,92,246'];
        $rgb = $colors[$w[3]] ?? '245,168,0';
      ?>
      <div class="kw-card" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>">
        <div class="kw-card-icon" style="background:rgba(<?= $rgb ?>,0.1);color:rgb(<?= $rgb ?>);">
          <i class="fa-solid <?= $w[0] ?>"></i>
        </div>
        <h4><?= $w[1] ?></h4>
        <p style="font-size:0.875rem;margin:0;"><?= $w[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     INDUSTRIES SERVED
     ============================================================ -->
<section class="kw-section" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-globe"></i> Industries</span>
      <h2>Built for Your Industry</h2>
      <p>Our enterprise systems are pre-configured for the specific workflows, compliance requirements, and operational patterns of each sector.</p>
      <div class="kw-divider"></div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem;" data-aos="fade-up" data-aos-delay="100">
      <?php
      $industries = [
        ['fa-hospital','Healthcare','EMR, appointments, billing, pharmacy'],
        ['fa-industry','Manufacturing','ERP, production, inventory, QC'],
        ['fa-building','Real Estate','Listings, tenants, leases, rent'],
        ['fa-graduation-cap','Education','LMS, student portal, exams, certs'],
        ['fa-landmark','Government','Records, permits, compliance, portals'],
        ['fa-truck','Logistics','Fleet, shipments, tracking, warehousing'],
        ['fa-bolt','Utilities','Billing, meters, maintenance, faults'],
        ['fa-chart-pie','Finance & Banking','Accounts, transactions, reporting, risk'],
        ['fa-store','Retail & POS','Sales, inventory, multi-branch, loyalty'],
        ['fa-hotel','Hospitality','Reservations, rooms, billing, F&B'],
        ['fa-hard-hat','Construction','Projects, procurement, labour, cost tracking'],
        ['fa-seedling','Agriculture','Farm records, supply chain, market access'],
      ];
      foreach ($industries as $i => $ind): ?>
      <div class="kw-card" style="padding:1.25rem;text-align:center;" data-aos="zoom-in" data-aos-delay="<?= ($i % 4) * 50 ?>">
        <div style="width:48px;height:48px;border-radius:50%;background:rgba(245,168,0,0.1);display:flex;align-items:center;justify-content:center;margin:0 auto 0.85rem;font-size:1.2rem;color:var(--kw-primary);">
          <i class="fa-solid <?= $ind[0] ?>"></i>
        </div>
        <h5 style="font-size:0.9rem;margin-bottom:0.3rem;"><?= $ind[1] ?></h5>
        <p style="font-size:0.75rem;color:var(--kw-text-muted);margin:0;"><?= $ind[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     TECHNOLOGY STACK — SDLC FLOWCHART
     ============================================================ -->
<section class="kw-section" id="tech-stack" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-microchip"></i> Technology</span>
      <h2>Our Technology Stack & SDLC</h2>
      <p>From project inception to production monitoring — every phase powered by industry-leading tools and methodologies.</p>
      <div class="kw-divider"></div>
    </div>

    <!-- SDLC Flowchart -->
    <div class="sdlc-flow" data-aos="fade-up" data-aos-delay="100">
      <?php
      $sdlc = [
        ['01','fa-magnifying-glass','Planning & Discovery','Requirements gathering, feasibility analysis, project scoping, stakeholder mapping','#F5A800',
          ['Jira','Confluence','Figma','Notion']],
        ['02','fa-pen-ruler','UI/UX Design','Wireframing, prototyping, design systems, user journey mapping','#3B82F6',
          ['Figma','Adobe XD','Tailwind','Bootstrap']],
        ['03','fa-code','Development','Frontend, backend, API development, database architecture, AI integration','#22C55E',
          ['PHP','Laravel','React','Node.js','Python','TypeScript','HTML5','CSS3']],
        ['04','fa-vial','Testing & QA','Unit testing, integration testing, security audits, performance benchmarking','#A855F7',
          ['PHPUnit','Jest','Postman','OWASP']],
        ['05','fa-rocket','Deployment','CI/CD pipelines, containerization, cloud provisioning, domain & SSL setup','#F97316',
          ['cPanel','Docker','GitHub Actions','AWS','Nginx']],
        ['06','fa-chart-line','Monitoring & Growth','Real-time analytics, uptime monitoring, performance tuning, feature iteration','#EF4444',
          ['Grafana','New Relic','Google Analytics','Sentry']],
      ];
      ?>
      <div class="sdlc-grid">
        <?php foreach ($sdlc as $i => $step): ?>
        <div class="sdlc-step" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
          <div class="sdlc-step-header" style="border-left:3px solid <?= $step[4] ?>;">
            <div class="sdlc-step-num" style="background:<?= $step[4] ?>15;color:<?= $step[4] ?>;border:1px solid <?= $step[4] ?>40;">
              <?= $step[0] ?>
            </div>
            <div>
              <div class="sdlc-step-icon" style="color:<?= $step[4] ?>;font-size:1.2rem;">
                <i class="fa-solid <?= $step[1] ?>"></i>
              </div>
              <h4 style="font-size:0.95rem;margin:0.2rem 0;"><?= $step[2] ?></h4>
            </div>
          </div>
          <p style="font-size:0.82rem;color:var(--kw-text-muted);margin:0.75rem 0;"><?= $step[3] ?></p>
          <div class="sdlc-tools">
            <?php foreach ($step[5] as $tool): ?>
              <span class="tech-pill" style="font-size:0.72rem;padding:0.25rem 0.6rem;"><?= $tool ?></span>
            <?php endforeach; ?>
          </div>
          <?php if ($i < count($sdlc) - 1): ?>
            <div class="sdlc-arrow" aria-hidden="true">
              <i class="fa-solid fa-chevron-down" style="color:<?= $step[4] ?>;opacity:0.5;"></i>
            </div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Full tech stack icons -->
    <div style="margin-top:3.5rem;" data-aos="fade-up">
      <p style="text-align:center;font-size:0.8rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--kw-text-muted);margin-bottom:1.5rem;">
        Full Technology Arsenal
      </p>
      <div class="tech-pills-wrap" style="justify-content:center;">
        <?php
        $techStack = [
          ['fa-php','PHP','#777BB4'],['fa-laravel','Laravel','#FF2D20'],
          ['fa-node-js','Node.js','#339933'],['fa-react','React','#61DAFB'],
          ['fa-python','Python','#3776AB'],['fa-js','JavaScript','#F7DF1E'],
          ['fa-html5','HTML5','#E34F26'],['fa-css3-alt','CSS3','#1572B6'],
          ['fa-bootstrap','Bootstrap','#7952B3'],['fa-git-alt','Git','#F05032'],
          ['fa-docker','Docker','#2496ED'],['fa-aws','AWS','#FF9900'],
          ['fa-database','MySQL','#4479A1'],['fa-linux','Linux','#FCC624'],
        ];
        foreach ($techStack as $tech): ?>
          <span class="tech-pill">
            <i class="fa-brands <?= $tech[0] ?>" style="color:<?= $tech[2] ?>;font-size:0.9rem;"></i>
            <?= $tech[1] ?>
          </span>
        <?php endforeach; ?>
        <span class="tech-pill"><i class="fa-solid fa-brain" style="color:var(--kw-primary);"></i> AI/ML</span>
        <span class="tech-pill"><i class="fa-solid fa-wind" style="color:#38BDF8;"></i> Tailwind</span>
        <span class="tech-pill"><i class="fa-solid fa-flask" style="color:#000;"></i> Flask</span>
        <span class="tech-pill"><i class="fa-solid fa-layer-group" style="color:#DD0031;"></i> TypeScript</span>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     FEATURED PRODUCTS
     ============================================================ -->
<section class="kw-section" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-boxes-stacked"></i> Products</span>
      <h2>Enterprise Systems Built to Scale</h2>
      <p>Production-grade software platforms engineered for real business operations across every major sector.</p>
      <div class="kw-divider"></div>
    </div>

    <div class="kw-grid-3" data-aos="fade-up" data-aos-delay="100">
      <?php
      $featured = [
        ['hr-system','fa-users','HR Management System','#F5A800',
          'End-to-end HR lifecycle management from recruitment to retirement.',
          ['Payroll Processing','Leave Management','Performance Tracking','HR Analytics']],
        ['procurement-system','fa-shopping-cart','Procurement System','#3B82F6',
          'Automate procurement workflows from requisition to payment.',
          ['Supplier Management','Approval Workflows','Invoice Processing','Spend Analytics']],
        ['elearning-system','fa-graduation-cap','eLearning LMS','#22C55E',
          'Full-featured learning management for institutions and corporates.',
          ['Course Management','Online Exams','Progress Analytics','Certification']],
        ['real-estate-system','fa-building','Real Estate System','#A855F7',
          'Manage properties, tenants, leases, and rent collections in one place.',
          ['Tenant Management','Lease Tracking','Rent Automation','Maintenance']],
        ['crm-system','fa-handshake','CRM System','#F97316',
          'Turn leads into customers with AI-powered pipeline management.',
          ['Lead Management','Sales Pipeline','Email Integration','Deal Forecasting']],
        ['hospital-system','fa-hospital','Hospital Management','#EF4444',
          'Integrated healthcare operations from EMR to pharmacy and billing.',
          ['Patient Records','Appointment Booking','Billing & Insurance','Pharmacy']],
      ];
      foreach ($featured as $i => $p): ?>
      <div class="product-card" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 80 ?>">
        <div class="product-card-icon" style="background:<?= $p[3] ?>15;color:<?= $p[3] ?>;">
          <i class="fa-solid <?= $p[1] ?>"></i>
        </div>
        <h3><?= $p[2] ?></h3>
        <p class="tagline"><?= $p[4] ?></p>
        <ul class="product-card-features">
          <?php foreach ($p[5] as $f): ?>
            <li><?= $f ?></li>
          <?php endforeach; ?>
        </ul>
        <div class="product-card-actions">
          <a href="<?= url('products/' . $p[0]) ?>" class="kw-btn kw-btn-primary kw-btn-sm" style="flex:1;justify-content:center;">
            View Details
          </a>
          <a href="<?= url('demo') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">
            <i class="fa-solid fa-play"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:3rem;" data-aos="fade-up">
      <a href="<?= url('products') ?>" class="kw-btn kw-btn-outline kw-btn-lg">
        <i class="fa-solid fa-grid-2"></i> View All 9 Products
      </a>
    </div>
  </div>
</section>

<!-- ============================================================
     INTERACTIVE DEMO STRIP
     ============================================================ -->
<section class="kw-section" id="interactive-demo" style="background:var(--kw-bg-hero);position:relative;overflow:hidden;">
  <!-- Background glow -->
  <div style="position:absolute;top:-30%;left:50%;transform:translateX(-50%);width:80%;height:200%;background:radial-gradient(ellipse,rgba(245,168,0,0.05),transparent 65%);pointer-events:none;"></div>

  <div class="kw-container" style="position:relative;z-index:1;">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label" style="background:rgba(245,168,0,0.12);"><i class="fa-solid fa-bolt"></i> Live Demo</span>
      <h2 style="color:#fff;">Try It — Right Now</h2>
      <p style="color:rgba(255,255,255,0.6);">Experience Krestworks capabilities without signing up. Pick a tool and start building.</p>
      <div class="kw-divider"></div>
    </div>

    <!-- Tab selector -->
    <div class="kw-tabs" style="justify-content:center;border-bottom-color:rgba(255,255,255,0.1);margin-bottom:2.5rem;" data-aos="fade-up" data-aos-delay="100">
      <button class="kw-tab-btn active" data-tab="workflow" style="color:rgba(255,255,255,0.6);">
        <i class="fa-solid fa-diagram-project"></i> Workflow Builder
      </button>
      <button class="kw-tab-btn" data-tab="dashboard" style="color:rgba(255,255,255,0.6);">
        <i class="fa-solid fa-chart-pie"></i> Dashboard Generator
      </button>
      <button class="kw-tab-btn" data-tab="ai-sandbox" style="color:rgba(255,255,255,0.6);">
        <i class="fa-solid fa-robot"></i> AI Sandbox
      </button>
      <button class="kw-tab-btn" data-tab="roi-calc" style="color:rgba(255,255,255,0.6);">
        <i class="fa-solid fa-calculator"></i> ROI Calculator
      </button>
    </div>

    <!-- Workflow Builder -->
    <div class="kw-tab-panel active" data-tab-panel="workflow" data-tabs-container>
      <div class="kw-card kw-card-glass" style="padding:2rem;">
        <h4 style="color:#fff;margin-bottom:0.5rem;"><i class="fa-solid fa-diagram-project" style="color:var(--kw-primary);"></i> Visual Workflow Builder</h4>
        <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.5rem;">Click nodes to add steps to your workflow. Drag to rearrange. This mirrors how Krestworks builds automation logic.</p>
        <div id="workflow-canvas" style="min-height:320px;background:rgba(0,0,0,0.3);border-radius:var(--kw-radius-lg);border:1px solid rgba(245,168,0,0.15);padding:1.5rem;position:relative;overflow:auto;">
          <div id="workflow-nodes" style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:flex-start;min-height:260px;"></div>
          <div id="workflow-placeholder" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem;color:rgba(255,255,255,0.25);pointer-events:none;">
            <i class="fa-solid fa-diagram-project" style="font-size:2.5rem;"></i>
            <span style="font-size:0.875rem;">Add nodes below to build your workflow</span>
          </div>
        </div>
        <div style="margin-top:1.25rem;display:flex;flex-wrap:wrap;gap:0.6rem;">
          <?php
          $wfNodes = [
            ['fa-play-circle','Trigger','#22C55E'],
            ['fa-user-check','Approval','#3B82F6'],
            ['fa-envelope','Send Email','#F59E0B'],
            ['fa-database','Save to DB','#8B5CF6'],
            ['fa-robot','AI Process','#F5A800'],
            ['fa-bell','Notification','#EF4444'],
            ['fa-code-branch','Condition','#06B6D4'],
            ['fa-flag-checkered','End','#6B7280'],
          ];
          foreach ($wfNodes as $n): ?>
            <button class="kw-btn kw-btn-sm wf-add-node"
                    data-label="<?= $n[1] ?>"
                    data-icon="<?= $n[0] ?>"
                    data-color="<?= $n[2] ?>"
                    style="background:<?= $n[2] ?>15;color:<?= $n[2] ?>;border-color:<?= $n[2] ?>40;font-size:0.78rem;">
              <i class="fa-solid <?= $n[0] ?>"></i> <?= $n[1] ?>
            </button>
          <?php endforeach; ?>
          <button class="kw-btn kw-btn-sm" id="wf-clear" style="background:rgba(239,68,68,0.1);color:#ef4444;border-color:rgba(239,68,68,0.3);font-size:0.78rem;margin-left:auto;">
            <i class="fa-solid fa-trash"></i> Clear
          </button>
        </div>
        <div id="wf-export" style="display:none;margin-top:1rem;padding:1rem;background:rgba(0,0,0,0.4);border-radius:var(--kw-radius-md);border:1px solid rgba(245,168,0,0.2);">
          <p style="color:var(--kw-primary);font-size:0.8rem;font-weight:600;margin-bottom:0.5rem;">Generated Workflow JSON:</p>
          <pre id="wf-json" style="color:rgba(255,255,255,0.7);font-size:0.75rem;white-space:pre-wrap;margin:0;"></pre>
        </div>
      </div>
    </div>

    <!-- Dashboard Generator -->
    <div class="kw-tab-panel" data-tab-panel="dashboard">
      <div class="kw-card kw-card-glass" style="padding:2rem;">
        <h4 style="color:#fff;margin-bottom:0.5rem;"><i class="fa-solid fa-chart-pie" style="color:var(--kw-primary);"></i> Mini Dashboard Generator</h4>
        <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.5rem;">Select a chart type and dataset. See how Krestworks renders live analytics dashboards.</p>
        <div style="display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;align-items:center;">
          <div>
            <label style="font-size:0.78rem;color:rgba(255,255,255,0.5);display:block;margin-bottom:0.35rem;">Chart Type</label>
            <select id="chart-type" class="kw-select" style="width:auto;min-width:160px;background:#1F2937;color:#fff;border-color:rgba(255,255,255,0.15);">
              <option value="bar">Bar Chart</option>
              <option value="line">Line Chart</option>
              <option value="pie">Pie Chart</option>
              <option value="doughnut">Doughnut Chart</option>
              <option value="radar">Radar Chart</option>
              <option value="polarArea">Polar Area</option>
            </select>
          </div>
          <div>
            <label style="font-size:0.78rem;color:rgba(255,255,255,0.5);display:block;margin-bottom:0.35rem;">Dataset</label>
            <select id="chart-dataset" class="kw-select" style="width:auto;min-width:180px;background:#1F2937;color:#fff;border-color:rgba(255,255,255,0.15);">
              <option value="sales">Monthly Sales</option>
              <option value="hr">HR Headcount by Dept</option>
              <option value="inventory">Inventory Levels</option>
              <option value="performance">Employee Performance</option>
              <option value="revenue">Quarterly Revenue</option>
            </select>
          </div>
          <div style="margin-top:1.2rem;">
            <button id="chart-generate" class="kw-btn kw-btn-primary kw-btn-sm">
              <i class="fa-solid fa-wand-magic-sparkles"></i> Generate
            </button>
          </div>
        </div>
        <div style="background:rgba(0,0,0,0.3);border-radius:var(--kw-radius-lg);padding:1.25rem;border:1px solid rgba(245,168,0,0.15);">
          <canvas id="demo-chart" height="280"></canvas>
        </div>
        <div id="chart-stats" style="display:flex;gap:1.5rem;margin-top:1rem;flex-wrap:wrap;"></div>
      </div>
    </div>

    <!-- AI Sandbox -->
    <div class="kw-tab-panel" data-tab-panel="ai-sandbox">
      <div class="kw-card kw-card-glass" style="padding:2rem;">
        <h4 style="color:#fff;margin-bottom:0.5rem;"><i class="fa-solid fa-robot" style="color:var(--kw-primary);"></i> AI Sandbox</h4>
        <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.5rem;">Test AI tasks instantly. Paste text and pick a task — powered by the same AI engine used in Krestworks products.</p>
        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1rem;">
          <?php
          $aiTasks = [
            ['summarize','Summarize Text'],['bullet','Extract Key Points'],
            ['sentiment','Sentiment Analysis'],['improve','Improve Writing'],
            ['translate','Translate to Swahili'],['sow','Generate SOW Outline'],
          ];
          foreach ($aiTasks as $i => $t): ?>
            <button class="kw-btn kw-btn-sm ai-task-btn <?= $i === 0 ? 'active' : '' ?>"
                    data-task="<?= $t[0] ?>"
                    style="<?= $i === 0 ? 'background:var(--kw-primary);color:#0A0F1A;' : 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);border-color:rgba(255,255,255,0.12);' ?>font-size:0.78rem;">
              <?= $t[1] ?>
            </button>
          <?php endforeach; ?>
        </div>
        <textarea id="ai-sandbox-input" class="kw-textarea"
                  style="background:rgba(0,0,0,0.4);color:#fff;border-color:rgba(255,255,255,0.15);min-height:100px;"
                  placeholder="Paste any text here — a business document, email, report, or description..."></textarea>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-top:0.85rem;">
          <button id="ai-sandbox-run" class="kw-btn kw-btn-primary">
            <i class="fa-solid fa-bolt"></i> Run AI Task
          </button>
          <button id="ai-sandbox-clear" class="kw-btn kw-btn-ghost" style="color:rgba(255,255,255,0.5);border-color:rgba(255,255,255,0.12);">
            <i class="fa-solid fa-rotate"></i> Clear
          </button>
          <span id="ai-sandbox-chars" style="font-size:0.78rem;color:rgba(255,255,255,0.3);margin-left:auto;">0 / 2000 chars</span>
        </div>
        <div id="ai-sandbox-result" style="display:none;margin-top:1.25rem;padding:1.25rem;background:rgba(0,0,0,0.4);border-radius:var(--kw-radius-md);border:1px solid rgba(245,168,0,0.2);">
          <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
            <i class="fa-solid fa-robot" style="color:var(--kw-primary);"></i>
            <span style="font-size:0.78rem;font-weight:700;color:var(--kw-primary);text-transform:uppercase;letter-spacing:0.08em;">AI Result</span>
          </div>
          <div id="ai-sandbox-output" style="color:rgba(255,255,255,0.8);font-size:0.875rem;line-height:1.75;white-space:pre-wrap;"></div>
        </div>
      </div>
    </div>

    <!-- ROI Calculator -->
    <div class="kw-tab-panel" data-tab-panel="roi-calc">
      <div class="kw-card kw-card-glass" style="padding:2rem;">
        <h4 style="color:#fff;margin-bottom:0.5rem;"><i class="fa-solid fa-calculator" style="color:var(--kw-primary);"></i> ROI Calculator</h4>
        <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.75rem;">Estimate the return on investment from implementing a Krestworks enterprise system in your business.</p>
        <div class="kw-form-row">
          <div class="kw-form-group">
            <label style="color:rgba(255,255,255,0.7);">Number of Employees</label>
            <input type="number" id="roi-employees" class="kw-input" value="50" min="1" max="10000"
                   style="background:rgba(0,0,0,0.4);color:#fff;border-color:rgba(255,255,255,0.15);">
          </div>
          <div class="kw-form-group">
            <label style="color:rgba(255,255,255,0.7);">Current Monthly IT/HR Costs (KES)</label>
            <input type="number" id="roi-current-cost" class="kw-input" value="150000" min="0"
                   style="background:rgba(0,0,0,0.4);color:#fff;border-color:rgba(255,255,255,0.15);">
          </div>
          <div class="kw-form-group">
            <label style="color:rgba(255,255,255,0.7);">Hours Lost to Manual Processes / Month</label>
            <input type="number" id="roi-manual-hours" class="kw-input" value="200" min="0"
                   style="background:rgba(0,0,0,0.4);color:#fff;border-color:rgba(255,255,255,0.15);">
          </div>
          <div class="kw-form-group">
            <label style="color:rgba(255,255,255,0.7);">Average Hourly Rate (KES)</label>
            <input type="number" id="roi-hourly-rate" class="kw-input" value="800" min="0"
                   style="background:rgba(0,0,0,0.4);color:#fff;border-color:rgba(255,255,255,0.15);">
          </div>
        </div>
        <div class="kw-form-group">
          <label style="color:rgba(255,255,255,0.7);">System Type</label>
          <select id="roi-system" class="kw-select" style="background:rgba(0,0,0,0.4);color:#fff;border-color:rgba(255,255,255,0.15);">
            <option value="0.35">HR Management System (35% process automation)</option>
            <option value="0.40">Procurement System (40% workflow reduction)</option>
            <option value="0.50">eLearning LMS (50% training cost reduction)</option>
            <option value="0.45">Supply Chain System (45% efficiency gain)</option>
            <option value="0.38">CRM System (38% sales efficiency gain)</option>
            <option value="0.42">Hospital System (42% admin cost reduction)</option>
          </select>
        </div>
        <button id="roi-calculate" class="kw-btn kw-btn-primary">
          <i class="fa-solid fa-calculator"></i> Calculate ROI
        </button>
        <div id="roi-results" style="display:none;margin-top:1.75rem;">
          <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
            <div style="background:rgba(245,168,0,0.1);border:1px solid rgba(245,168,0,0.25);border-radius:var(--kw-radius-md);padding:1.25rem;text-align:center;">
              <div id="roi-monthly-savings" style="font-size:1.75rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);">KES 0</div>
              <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.25rem;">Monthly Savings</div>
            </div>
            <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.25);border-radius:var(--kw-radius-md);padding:1.25rem;text-align:center;">
              <div id="roi-annual-savings" style="font-size:1.75rem;font-weight:800;color:#22C55E;font-family:var(--font-heading);">KES 0</div>
              <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.25rem;">Annual Savings</div>
            </div>
            <div style="background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.25);border-radius:var(--kw-radius-md);padding:1.25rem;text-align:center;">
              <div id="roi-hours-saved" style="font-size:1.75rem;font-weight:800;color:#3B82F6;font-family:var(--font-heading);">0 hrs</div>
              <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.25rem;">Hours Saved / Month</div>
            </div>
            <div style="background:rgba(168,85,247,0.1);border:1px solid rgba(168,85,247,0.25);border-radius:var(--kw-radius-md);padding:1.25rem;text-align:center;">
              <div id="roi-payback" style="font-size:1.75rem;font-weight:800;color:#A855F7;font-family:var(--font-heading);">0 mo</div>
              <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.25rem;">Payback Period</div>
            </div>
          </div>
          <div style="margin-top:1.25rem;padding:1rem;background:rgba(245,168,0,0.06);border-radius:var(--kw-radius-md);border-left:3px solid var(--kw-primary);">
            <p id="roi-recommendation" style="color:rgba(255,255,255,0.7);font-size:0.85rem;margin:0;"></p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ============================================================
     SERVICES OVERVIEW
     ============================================================ -->
<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-cogs"></i> Services</span>
      <h2>What We Build For You</h2>
      <p>Full-spectrum software services — from ideation and architecture to deployment and ongoing support.</p>
      <div class="kw-divider"></div>
    </div>

    <div class="kw-grid-4" data-aos="fade-up" data-aos-delay="100">
      <?php
      $services = [
        ['fa-laptop-code','Custom Software','Enterprise systems, business automation, digital transformation, AI integration.','services/custom-software','#F5A800'],
        ['fa-globe','Web Development','Business websites, SaaS platforms, cloud applications, e-commerce solutions.','services/web-development','#3B82F6'],
        ['fa-mobile-alt','Mobile Apps','iOS and Android applications, cross-platform apps, mobile-first enterprise tools.','services/mobile-development','#22C55E'],
        ['fa-robot','AI Solutions','Intelligent copilots, predictive analytics, NLP tools, automation bots, AI integrations.','services/ai-solutions','#A855F7'],
        ['fa-plug','System Integration','ERP, API, payment gateways, biometric, data migration, third-party integrations.','services/system-integration','#F97316'],
        ['fa-lightbulb','Consulting','Architecture design, system modernization, digital transformation strategy, AI roadmaps.','services/consulting','#EF4444'],
        ['fa-cloud','Cloud Infrastructure','Hosting, scaling, monitoring, backup, CI/CD pipelines, DevOps as a service.','services/cloud-infrastructure','#06B6D4'],
        ['fa-shield-halved','Cybersecurity','Security audits, penetration testing, data protection, compliance, access management.','services/cybersecurity','#8B5CF6'],
      ];
      foreach ($services as $i => $s): ?>
      <div class="service-card" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 60 ?>">
        <div class="service-card-number"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
        <div class="kw-card-icon" style="background:<?= $s[4] ?>12;color:<?= $s[4] ?>;">
          <i class="fa-solid <?= $s[0] ?>"></i>
        </div>
        <h4 style="font-size:1rem;margin-bottom:0.4rem;"><?= $s[1] ?></h4>
        <p style="font-size:0.82rem;color:var(--kw-text-muted);margin-bottom:1rem;"><?= $s[2] ?></p>
        <a href="<?= url($s[3]) ?>" style="font-size:0.8rem;font-weight:600;color:<?= $s[4] ?>;display:flex;align-items:center;gap:0.35rem;">
          Learn more <i class="fa-solid fa-arrow-right" style="font-size:0.7rem;"></i>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============================================================
     AI HUB TEASER
     ============================================================ -->
<section class="kw-section" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;" data-aos="fade-up">
      <div>
        <span class="label" style="display:inline-block;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--kw-primary);background:rgba(245,168,0,0.1);border:1px solid rgba(245,168,0,0.25);padding:0.28rem 0.8rem;border-radius:999px;margin-bottom:1rem;">
          <i class="fa-solid fa-robot"></i> Krest AI Hub
        </span>
        <h2 style="margin-bottom:1rem;">AI Tools That Work<br>While You Work</h2>
        <p style="margin-bottom:1.5rem;">Access a growing library of AI-powered tools — from document summarizers to business strategy assistants. Free to try, subscription to unlock the full suite.</p>
        <div style="display:flex;flex-direction:column;gap:0.75rem;margin-bottom:2rem;">
          <?php
          $aiFeatures = [
            ['fa-file-alt','Document Summarizer','Instantly condense long documents into structured summaries.'],
            ['fa-id-card','Resume Analyzer','AI feedback on CVs — strengths, gaps, and keyword optimization.'],
            ['fa-code','Code Assistant','Debug, review, and generate code across PHP, Python, JS, and more.'],
            ['fa-chess','Business Strategy AI','AI-powered strategic analysis for your goals and market position.'],
          ];
          foreach ($aiFeatures as $f): ?>
          <div style="display:flex;gap:0.85rem;align-items:flex-start;">
            <div style="width:36px;height:36px;border-radius:var(--kw-radius-md);background:rgba(245,168,0,0.1);display:flex;align-items:center;justify-content:center;color:var(--kw-primary);font-size:0.85rem;flex-shrink:0;">
              <i class="fa-solid <?= $f[0] ?>"></i>
            </div>
            <div>
              <div style="font-size:0.9rem;font-weight:600;margin-bottom:0.15rem;"><?= $f[1] ?></div>
              <div style="font-size:0.82rem;color:var(--kw-text-muted);"><?= $f[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="<?= url('ai-hub') ?>" class="kw-btn kw-btn-primary kw-btn-lg">
          <i class="fa-solid fa-robot"></i> Explore AI Hub
        </a>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <?php
        $aiTools = [
          ['fa-file-alt','Document Summarizer','Free','#22C55E'],
          ['fa-id-card','Resume Analyzer','Free','#22C55E'],
          ['fa-code','Code Assistant','Free','#22C55E'],
          ['fa-clipboard-list','Meeting Notes','Free','#22C55E'],
          ['fa-chess','Strategy AI','Premium','#F5A800'],
          ['fa-chart-bar','Financial AI','Premium','#F5A800'],
          ['fa-trending-up','Sales Forecast','Premium','#F5A800'],
          ['fa-database','Data Insights','Premium','#F5A800'],
        ];
        foreach ($aiTools as $t): ?>
        <div class="kw-card" style="padding:1.1rem;text-align:center;" data-aos="zoom-in">
          <i class="fa-solid <?= $t[0] ?>" style="font-size:1.4rem;color:<?= $t[3] ?>;margin-bottom:0.5rem;display:block;"></i>
          <div style="font-size:0.8rem;font-weight:600;margin-bottom:0.25rem;"><?= $t[1] ?></div>
          <span class="kw-badge <?= $t[2] === 'Free' ? 'kw-badge-green' : 'kw-badge-gold' ?>" style="font-size:0.65rem;">
            <?= $t[2] ?>
          </span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     COMMUNITY TEASER
     ============================================================ -->
<section class="kw-section" style="background:var(--kw-bg);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;" data-aos="fade-up">
      <div>
        <div style="display:flex;flex-direction:column;gap:0.85rem;">
          <?php
          $samplePosts = [
            ['fa-question-circle','How do you structure role-based access in a multi-tenant SaaS?','12 replies · 3h ago','#3B82F6'],
            ['fa-lightbulb','Insight: Why most ERP implementations fail in Year 2','8 replies · 5h ago','#F5A800'],
            ['fa-comment-dots','Discussion: AI in procurement — hype or real ROI?','24 replies · 1d ago','#22C55E'],
            ['fa-graduation-cap','Tutorial: Building an approval workflow in PHP from scratch','6 replies · 2d ago','#A855F7'],
          ];
          foreach ($samplePosts as $p): ?>
          <div class="kw-card" style="padding:1.1rem;display:flex;gap:0.85rem;align-items:flex-start;">
            <i class="fa-solid <?= $p[0] ?>" style="color:<?= $p[3] ?>;font-size:1rem;margin-top:0.15rem;flex-shrink:0;"></i>
            <div>
              <div style="font-size:0.875rem;font-weight:600;margin-bottom:0.2rem;line-height:1.4;"><?= $p[1] ?></div>
              <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $p[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div>
        <span class="label" style="display:inline-block;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--kw-primary);background:rgba(245,168,0,0.1);border:1px solid rgba(245,168,0,0.25);padding:0.28rem 0.8rem;border-radius:999px;margin-bottom:1rem;">
          <i class="fa-solid fa-people-group"></i> Community
        </span>
        <h2 style="margin-bottom:1rem;">The Information Village — Global</h2>
        <p style="margin-bottom:1rem;">Ask questions. Share insights. Learn from fellow developers, business analysts, and system architects. Anyone can read — join to engage.</p>
        <ul style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:2rem;">
          <?php
          $communityPerks = [
            'Tech discussions across 20+ categories',
            'Question & answer threads with accepted solutions',
            'Share tutorials, insights, and case studies',
            'Connect with developers across Kenya and Africa',
            'Open to all — free to join and contribute',
          ];
          foreach ($communityPerks as $perk): ?>
          <li style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;color:var(--kw-text-secondary);">
            <i class="fa-solid fa-check" style="color:var(--kw-primary);font-size:0.8rem;"></i>
            <?= $perk ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
          <a href="<?= url('community') ?>" class="kw-btn kw-btn-primary">
            <i class="fa-solid fa-people-group"></i> Join the Community
          </a>
          <a href="<?= url('community') ?>" class="kw-btn kw-btn-ghost">
            Browse Discussions
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============================================================
     FINAL CTA
     ============================================================ -->
<section style="background:var(--kw-bg-hero);padding:6rem 0;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse at center, rgba(245,168,0,0.07), transparent 70%);pointer-events:none;"></div>
  <div class="kw-container" style="text-align:center;position:relative;z-index:1;" data-aos="fade-up">
    <span style="display:inline-block;font-size:0.72rem;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--kw-primary);background:rgba(245,168,0,0.1);border:1px solid rgba(245,168,0,0.25);padding:0.28rem 0.8rem;border-radius:999px;margin-bottom:1.25rem;">
      <i class="fa-solid fa-rocket"></i> Get Started Today
    </span>
    <h2 style="color:#fff;font-size:clamp(1.75rem,4vw,3rem);max-width:700px;margin:0 auto 1.25rem;">
      Ready to Transform Your Business Operations?
    </h2>
    <p style="color:rgba(255,255,255,0.6);max-width:540px;margin:0 auto 2.5rem;font-size:1.05rem;">
      Book a free consultation or request a live demo. Our team will design a system tailored to your exact needs — no generic software.
    </p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('demo') ?>" class="kw-btn kw-btn-primary kw-btn-lg">
        <i class="fa-solid fa-play-circle"></i> Request Free Demo
      </a>
      <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.07);color:#fff;border:1px solid rgba(255,255,255,0.2);">
        <i class="fa-solid fa-calendar-check"></i> Book Consultation
      </a>
      <a href="https://wa.me/<?= e(WHATSAPP_NUMBER) ?>" class="kw-btn kw-btn-lg" style="background:#25D366;color:#fff;border:none;" target="_blank" rel="noopener">
        <i class="fa-brands fa-whatsapp"></i> WhatsApp Us
      </a>
    </div>
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>

<!-- HOME PAGE SCRIPTS -->
<script>
document.addEventListener('DOMContentLoaded', function() {

  // ============================================================
  // WORKFLOW BUILDER
  // ============================================================
  const wfNodes    = document.getElementById('workflow-nodes');
  const wfPH       = document.getElementById('workflow-placeholder');
  const wfExport   = document.getElementById('wf-export');
  const wfJson     = document.getElementById('wf-json');
  const workflowData = [];

  document.querySelectorAll('.wf-add-node').forEach(btn => {
    btn.addEventListener('click', () => {
      const label = btn.dataset.label;
      const icon  = btn.dataset.icon;
      const color = btn.dataset.color;
      const id    = 'node_' + Date.now();

      workflowData.push({ id, label, icon: icon.replace('fa-','') });
      wfPH.style.display = 'none';

      // Build node
      const isFirst = workflowData.length === 1;
      const node = document.createElement('div');
      node.id = id;
      node.style.cssText = `
        display:flex;align-items:center;gap:0.45rem;
        padding:0.55rem 1rem;border-radius:999px;
        background:${color}18;border:1.5px solid ${color}50;
        color:${color};font-size:0.78rem;font-weight:600;
        animation:fadeIn 0.25s ease;cursor:default;position:relative;
      `;

      if (!isFirst) {
        const arrow = document.createElement('span');
        arrow.innerHTML = '<i class="fa-solid fa-arrow-right" style="font-size:0.65rem;margin-right:0.25rem;opacity:0.5;"></i>';
        wfNodes.appendChild(arrow);
      }

      node.innerHTML = `<i class="fa-solid ${icon}"></i>${label}<span onclick="removeWfNode('${id}',this)" style="margin-left:0.35rem;opacity:0.5;cursor:pointer;font-size:0.7rem;" title="Remove">✕</span>`;
      wfNodes.appendChild(node);

      // Update JSON
      wfExport.style.display = 'block';
      wfJson.textContent = JSON.stringify({ workflow: workflowData, steps: workflowData.length, created: new Date().toISOString() }, null, 2);
    });
  });

  document.getElementById('wf-clear')?.addEventListener('click', () => {
    workflowData.length = 0;
    wfNodes.innerHTML   = '';
    wfPH.style.display  = '';
    wfExport.style.display = 'none';
  });

  window.removeWfNode = function(id, el) {
    const idx = workflowData.findIndex(n => n.id === id);
    if (idx > -1) workflowData.splice(idx, 1);
    document.getElementById(id)?.remove();
    // remove adjacent arrow
    if (workflowData.length === 0) {
      wfNodes.innerHTML = '';
      wfPH.style.display = '';
      wfExport.style.display = 'none';
    }
    if (workflowData.length > 0) {
      wfJson.textContent = JSON.stringify({ workflow: workflowData, steps: workflowData.length }, null, 2);
    }
  };

  // ============================================================
  // DASHBOARD GENERATOR
  // ============================================================
  let demoChartInstance = null;

  const chartDatasets = {
    sales:       { labels:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'], data:[420,385,510,467,598,620,541,710,680,750,810,920], label:'Monthly Sales (KES 000s)' },
    hr:          { labels:['Engineering','Finance','HR','Operations','Sales','Support'], data:[28,12,8,35,22,15], label:'Headcount by Department' },
    inventory:   { labels:['Raw Materials','WIP','Finished Goods','Returns','Damaged'], data:[65,30,45,8,5], label:'Inventory Levels (Units 000s)' },
    performance: { labels:['Q1','Q2','Q3','Q4'], data:[72,81,78,88], label:'Employee Performance Score (%)' },
    revenue:     { labels:['Q1 2023','Q2 2023','Q3 2023','Q4 2023','Q1 2024','Q2 2024'], data:[1200,1450,1380,1820,2100,2450], label:'Quarterly Revenue (KES 000s)' },
  };

  const goldPalette = [
    '#F5A800','#3B82F6','#22C55E','#A855F7','#EF4444','#F97316',
    '#06B6D4','#8B5CF6','#EC4899','#14B8A6','#F59E0B','#6366F1',
  ];

  function generateChart() {
    const type    = document.getElementById('chart-type').value;
    const dsKey   = document.getElementById('chart-dataset').value;
    const ds      = chartDatasets[dsKey];
    const ctx     = document.getElementById('demo-chart').getContext('2d');

    if (demoChartInstance) demoChartInstance.destroy();

    const isMultiColor = ['pie','doughnut','polarArea','radar'].includes(type);

    demoChartInstance = new Chart(ctx, {
      type,
      data: {
        labels: ds.labels,
        datasets: [{
          label: ds.label,
          data: ds.data,
          backgroundColor: isMultiColor ? goldPalette.slice(0, ds.data.length).map(c => c + 'CC') : 'rgba(245,168,0,0.75)',
          borderColor: isMultiColor ? goldPalette.slice(0, ds.data.length) : '#F5A800',
          borderWidth: 2,
          tension: 0.4,
          fill: type === 'line',
          pointBackgroundColor: '#F5A800',
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { labels: { color: '#9CA3AF', font: { size: 11, family: 'Inter' } } },
          tooltip: {
            backgroundColor: '#111827',
            titleColor: '#F5A800',
            bodyColor: '#D1D5DB',
            borderColor: '#F5A80040',
            borderWidth: 1,
          },
        },
        scales: ['bar','line'].includes(type) ? {
          x: { ticks: { color: '#6B7280', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
          y: { ticks: { color: '#6B7280', font: { size: 10 } }, grid: { color: 'rgba(255,255,255,0.06)' } },
        } : {},
      },
    });

    // Stats bar
    const total = ds.data.reduce((a, b) => a + b, 0);
    const max   = Math.max(...ds.data);
    const avg   = (total / ds.data.length).toFixed(1);
    const statsEl = document.getElementById('chart-stats');
    statsEl.innerHTML = [
      ['Total', total.toLocaleString(), '#F5A800'],
      ['Average', avg, '#3B82F6'],
      ['Peak', max.toLocaleString(), '#22C55E'],
      ['Data Points', ds.data.length, '#A855F7'],
    ].map(([k, v, c]) => `
      <div style="background:${c}10;border:1px solid ${c}30;border-radius:8px;padding:0.6rem 1rem;text-align:center;min-width:100px;">
        <div style="font-size:1.1rem;font-weight:700;color:${c};font-family:var(--font-heading);">${v}</div>
        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);">${k}</div>
      </div>
    `).join('');
  }

  document.getElementById('chart-generate')?.addEventListener('click', generateChart);
  // Auto-generate on load if Chart.js available
  window.addEventListener('load', () => { if (window.Chart) generateChart(); });

  // ============================================================
  // AI SANDBOX
  // ============================================================
  let selectedAiTask = 'summarize';

  document.querySelectorAll('.ai-task-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.ai-task-btn').forEach(b => {
        b.style.cssText = 'background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);border-color:rgba(255,255,255,0.12);font-size:0.78rem;';
      });
      btn.style.cssText = 'background:var(--kw-primary);color:#0A0F1A;font-size:0.78rem;';
      selectedAiTask = btn.dataset.task;
    });
  });

  const sandboxInput = document.getElementById('ai-sandbox-input');
  const sandboxChars = document.getElementById('ai-sandbox-chars');

  sandboxInput?.addEventListener('input', () => {
    const len = sandboxInput.value.length;
    sandboxChars.textContent = `${len} / 2000 chars`;
    if (len > 2000) sandboxInput.value = sandboxInput.value.slice(0, 2000);
  });

  document.getElementById('ai-sandbox-clear')?.addEventListener('click', () => {
    sandboxInput.value = '';
    sandboxChars.textContent = '0 / 2000 chars';
    document.getElementById('ai-sandbox-result').style.display = 'none';
  });

  document.getElementById('ai-sandbox-run')?.addEventListener('click', async () => {
    const text = sandboxInput?.value.trim();
    if (!text) { window.Krest?.toast('Please enter some text first.', 'error'); return; }

    const btn = document.getElementById('ai-sandbox-run');
    const resultEl = document.getElementById('ai-sandbox-result');
    const outputEl = document.getElementById('ai-sandbox-output');

    window.Krest?.setButtonLoading(btn, true);
    resultEl.style.display = 'none';

    const taskPrompts = {
      summarize:  `Summarize the following text concisely in 3-5 sentences:\n\n${text}`,
      bullet:     `Extract 5-8 key points from this text as a bullet list:\n\n${text}`,
      sentiment:  `Analyze the sentiment of this text. State: Positive/Negative/Neutral, confidence %, and 2-3 reasons why:\n\n${text}`,
      improve:    `Improve this text for clarity, professionalism, and impact. Provide the improved version:\n\n${text}`,
      translate:  `Translate the following to Swahili:\n\n${text}`,
      sow:        `Based on this description, generate a concise Statement of Work (SOW) outline with: Scope, Deliverables, Timeline, Assumptions:\n\n${text}`,
    };

    try {
      const res = await fetch('/api/ai-assistant', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: JSON.stringify({
          messages: [{ role: 'user', content: taskPrompts[selectedAiTask] || taskPrompts.summarize }],
        }),
      });
      const data = await res.json();

      if (data.success && data.reply) {
        outputEl.textContent = data.reply;
        resultEl.style.display = 'block';
        resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      } else {
        window.Krest?.toast('AI request failed. Please try again.', 'error');
      }
    } catch {
      window.Krest?.toast('Connection error. Please try again.', 'error');
    } finally {
      window.Krest?.setButtonLoading(btn, false);
    }
  });

  // ============================================================
  // ROI CALCULATOR
  // ============================================================
  document.getElementById('roi-calculate')?.addEventListener('click', () => {
    const employees   = parseInt(document.getElementById('roi-employees').value) || 50;
    const currentCost = parseInt(document.getElementById('roi-current-cost').value) || 150000;
    const manualHours = parseInt(document.getElementById('roi-manual-hours').value) || 200;
    const hourlyRate  = parseInt(document.getElementById('roi-hourly-rate').value) || 800;
    const automation  = parseFloat(document.getElementById('roi-system').value) || 0.35;

    const hoursSaved     = Math.round(manualHours * automation);
    const labourSavings  = hoursSaved * hourlyRate;
    const processSavings = Math.round(currentCost * automation);
    const totalMonthly   = labourSavings + processSavings;
    const totalAnnual    = totalMonthly * 12;
    const systemCost     = employees <= 50 ? 150000 : employees <= 200 ? 350000 : 800000;
    const payback        = Math.ceil(systemCost / totalMonthly);

    const fmt = n => 'KES ' + n.toLocaleString();

    document.getElementById('roi-monthly-savings').textContent = fmt(totalMonthly);
    document.getElementById('roi-annual-savings').textContent  = fmt(totalAnnual);
    document.getElementById('roi-hours-saved').textContent     = hoursSaved + ' hrs';
    document.getElementById('roi-payback').textContent         = payback + ' mo';
    document.getElementById('roi-recommendation').textContent  =
      `Based on your inputs, a Krestworks system would save approximately ${fmt(totalMonthly)} per month (${fmt(totalAnnual)}/year), recovering the implementation cost in roughly ${payback} months. This is based on ${Math.round(automation * 100)}% automation of your current manual processes and labour costs.`;

    document.getElementById('roi-results').style.display = 'block';
    document.getElementById('roi-results').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  });

});
</script>

<!-- SDLC Styles (scoped to this page) -->
<style>
.sdlc-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.25rem;
}

@media (max-width: 1024px) { .sdlc-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 640px)  { .sdlc-grid { grid-template-columns: 1fr; } }

.sdlc-step {
  background: var(--kw-bg-card);
  border: 1px solid var(--kw-border);
  border-radius: var(--kw-radius-lg);
  padding: 1.5rem;
  position: relative;
  transition: var(--kw-transition);
}

.sdlc-step:hover {
  border-color: rgba(245,168,0,0.3);
  transform: translateY(-3px);
  box-shadow: var(--kw-shadow-md);
}

.sdlc-step-header {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  padding-left: 0.85rem;
  margin-bottom: 0.5rem;
}

.sdlc-step-num {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 800;
  flex-shrink: 0;
  font-family: var(--font-heading);
}

.sdlc-tools {
  display: flex;
  flex-wrap: wrap;
  gap: 0.3rem;
  margin-top: 0.5rem;
}

.sdlc-arrow {
  display: none; /* Hidden in grid layout */
}

/* Two-column layout for AI Hub and Community sections */
@media (max-width: 768px) {
  section .kw-container > div[style*="grid-template-columns:1fr 1fr"] {
    grid-template-columns: 1fr !important;
  }
}
</style>