<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Services — ' . APP_NAME;
$page_description = 'Full-spectrum software services: Custom Software Development, Web Development, Mobile Apps, AI Solutions, System Integration, Cloud Infrastructure, Consulting, and Cybersecurity.';


?>

<!-- Page Hero -->
<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span class="current">Services</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-cogs"></i> What We Do</span>
      <h1>Services That Drive<br>Real Business Outcomes</h1>
      <p>From strategy to deployment — we build, integrate, and maintain digital systems that transform how your business operates.</p>
    </div>
  </div>
</section>

<!-- Services Grid -->
<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">

    <!-- Stats -->
    <div class="kw-stats-grid" style="margin-bottom:4rem;" data-aos="fade-up">
      <?php $stats = [['50+','Projects Delivered'],['8','Core Service Areas'],['98%','Client Satisfaction'],['24/7','Support Available']];
      foreach ($stats as $s): ?>
      <div class="kw-stat-box">
        <span class="kw-stat-number" data-counter data-target="<?= intval($s[0]) ?>" data-suffix="<?= preg_replace('/[0-9]/','',$s[0]) ?>"><?= $s[0] ?></span>
        <span class="kw-stat-label"><?= $s[1] ?></span>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Service Cards -->
    <?php
    $services = [
      ['custom-software','fa-laptop-code','Custom Software Development','#F5A800',
        'We engineer bespoke enterprise systems tailored to your exact operational workflows — not generic off-the-shelf tools that need workarounds.',
        ['Enterprise Systems','Business Automation','Digital Transformation','AI Integration','ERP Development','Workflow Engines','API-first Architecture','Legacy Modernization'],
        ['PHP','Laravel','Node.js','React','Python','MySQL']
      ],
      ['web-development','fa-globe','Web Development','#3B82F6',
        'From high-performance business websites to full SaaS platforms — we build web experiences that convert visitors, retain users, and scale without friction.',
        ['Business Websites','SaaS Platforms','E-Commerce','Web Portals','Progressive Web Apps','CMS Development','Landing Pages','Performance Optimization'],
        ['React','Next.js','HTML5','CSS3','Tailwind','PHP']
      ],
      ['mobile-development','fa-mobile-alt','Mobile App Development','#22C55E',
        'Native and cross-platform mobile applications that deliver seamless experiences on iOS and Android — built for real-world business use cases.',
        ['iOS Applications','Android Apps','Cross-platform Apps','Mobile ERP','Field Service Apps','Offline-first Design','Push Notifications','Mobile Payments'],
        ['React Native','Flutter','Kotlin','Swift','Firebase','REST APIs']
      ],
      ['system-integration','fa-plug','System Integration','#F97316',
        'Connect your existing tools, platforms, and hardware into one seamless ecosystem. Eliminate data silos and automate cross-system workflows.',
        ['ERP Integration','API Development','Payment Gateways','Biometric/Hardware','Data Migration','ETL Pipelines','Third-party Connectors','Middleware Development'],
        ['REST','GraphQL','WebSockets','MPESA','Stripe','SAP']
      ],
      ['ai-solutions','fa-robot','AI Solutions','#A855F7',
        'Embed intelligence into your business operations — from intelligent automation and NLP to predictive analytics and AI-powered decision support.',
        ['AI Copilots','Intelligent Chatbots','Predictive Analytics','Computer Vision','NLP & Text Analysis','Recommendation Engines','ML Model Training','AI API Integration'],
        ['Python','TensorFlow','Anthropic','OpenAI','LangChain','FastAPI']
      ],
      ['consulting','fa-lightbulb','Technology Consulting','#EF4444',
        'Strategic technology guidance from architects who have built and scaled enterprise systems. We help you make the right decisions before writing a single line of code.',
        ['System Architecture','Digital Transformation','AI Roadmapping','Software Modernization','Technology Audits','CTO-as-a-Service','Business Automation Strategy','Vendor Selection'],
        ['Architecture','Agile','TOGAF','ITIL','DevOps','Cloud Strategy']
      ],
      ['cloud-infrastructure','fa-cloud','Cloud Infrastructure','#06B6D4',
        'Reliable, scalable, monitored cloud environments. We provision, configure, and manage your infrastructure so your systems run at peak performance.',
        ['Cloud Hosting','Auto-scaling','CI/CD Pipelines','Container Orchestration','Uptime Monitoring','Backup Management','CDN Setup','Cost Optimization'],
        ['AWS','DigitalOcean','cPanel','Docker','Nginx','Linux']
      ],
      ['cybersecurity','fa-shield-halved','Cybersecurity','#8B5CF6',
        'Proactive security architecture, vulnerability assessment, and compliance. We protect your systems, data, and users from modern threats.',
        ['Security Audits','Penetration Testing','OWASP Compliance','Data Encryption','Access Management','Incident Response','Security Training','GDPR/Data Compliance'],
        ['OWASP','SSL/TLS','WAF','2FA/MFA','SIEM','ISO 27001']
      ],
    ];
    ?>

    <?php foreach ($services as $i => $svc): ?>
    <div class="service-detail-row" data-aos="fade-up" style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;padding:3.5rem 0;border-bottom:1px solid var(--kw-border);<?= $i % 2 === 1 ? 'direction:rtl;' : '' ?>">

      <!-- Text side -->
      <div style="<?= $i % 2 === 1 ? 'direction:ltr;' : '' ?>">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
          <div style="width:54px;height:54px;border-radius:var(--kw-radius-md);background:<?= $svc[3] ?>15;color:<?= $svc[3] ?>;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
            <i class="fa-solid <?= $svc[1] ?>"></i>
          </div>
          <div>
            <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:<?= $svc[3] ?>;margin-bottom:0.2rem;">Service <?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></div>
            <h3 style="font-size:1.4rem;margin:0;"><?= $svc[2] ?></h3>
          </div>
        </div>
        <p style="margin-bottom:1.5rem;font-size:0.95rem;"><?= $svc[4] ?></p>
        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:1.5rem;">
          <?php foreach ($svc[5] as $feat): ?>
            <span style="background:<?= $svc[3] ?>0F;color:<?= $svc[3] ?>;border:1px solid <?= $svc[3] ?>30;border-radius:999px;padding:0.25rem 0.75rem;font-size:0.75rem;font-weight:600;"><?= $feat ?></span>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;margin-bottom:2rem;">
          <?php foreach ($svc[6] as $tech): ?>
            <span class="tech-pill" style="font-size:0.75rem;"><?= $tech ?></span>
          <?php endforeach; ?>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
          <a href="<?= url('services/' . $svc[0]) ?>" class="kw-btn kw-btn-primary kw-btn-sm">
            <i class="fa-solid fa-arrow-right"></i> Learn More
          </a>
          <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">
            <i class="fa-solid fa-calendar-check"></i> Book Consultation
          </a>
        </div>
      </div>

      <!-- Visual side -->
      <div style="<?= $i % 2 === 1 ? 'direction:ltr;' : '' ?>">
        <div style="background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-xl);padding:2rem;position:relative;overflow:hidden;">
          <div style="position:absolute;top:-20px;right:-20px;width:120px;height:120px;border-radius:50%;background:<?= $svc[3] ?>08;pointer-events:none;"></div>
          <div style="position:absolute;bottom:-30px;left:-15px;width:80px;height:80px;border-radius:50%;background:<?= $svc[3] ?>05;pointer-events:none;"></div>

          <!-- Service number watermark -->
          <div style="position:absolute;top:1rem;right:1.5rem;font-size:4rem;font-weight:800;color:<?= $svc[3] ?>08;font-family:var(--font-heading);line-height:1;">
            <?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?>
          </div>

          <!-- Feature checklist -->
          <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.1em;color:var(--kw-text-muted);margin-bottom:1rem;">What's included</h5>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
            <?php foreach (array_slice($svc[5], 0, 8) as $feat): ?>
            <div style="display:flex;align-items:center;gap:0.45rem;font-size:0.8rem;color:var(--kw-text-secondary);">
              <i class="fa-solid fa-check" style="color:<?= $svc[3] ?>;font-size:0.7rem;flex-shrink:0;"></i>
              <?= $feat ?>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- CTA strip -->
          <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--kw-border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
            <span style="font-size:0.78rem;color:var(--kw-text-muted);">Starting from</span>
            <span style="font-size:1rem;font-weight:700;color:<?= $svc[3] ?>;">Custom Quote</span>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

  </div>
</section>

<!-- Process Section -->
<section class="kw-section" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-diagram-project"></i> Our Process</span>
      <h2>How We Deliver</h2>
      <p>A structured, transparent delivery process that keeps you in control at every phase.</p>
      <div class="kw-divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;position:relative;" data-aos="fade-up" data-aos-delay="100">
      <?php
      $process = [
        ['fa-comments','Discovery','We understand your business, goals, and constraints before proposing any solution.','#F5A800'],
        ['fa-pen-ruler','Design','Wireframes, architecture diagrams, and database schemas — agreed before development starts.','#3B82F6'],
        ['fa-code','Build','Agile sprints with regular demos. You see progress, not just a final reveal.','#22C55E'],
        ['fa-vial','Test','Unit tests, integration tests, security audits, and UAT with your team.','#A855F7'],
        ['fa-rocket','Deploy','Production deployment, DNS, SSL, monitoring — full go-live support.','#F97316'],
        ['fa-headset','Support','Post-launch maintenance, updates, performance tuning, and helpdesk.','#EF4444'],
      ];
      foreach ($process as $i => $step): ?>
      <div class="kw-card" style="text-align:center;padding:2rem 1.5rem;" data-aos="zoom-in" data-aos-delay="<?= $i * 60 ?>">
        <div style="width:54px;height:54px;border-radius:50%;background:<?= $step[3] ?>15;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.25rem;color:<?= $step[3] ?>;border:2px solid <?= $step[3] ?>30;">
          <i class="fa-solid <?= $step[0] ?>"></i>
        </div>
        <div style="font-size:0.7rem;font-weight:800;color:<?= $step[3] ?>;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.4rem;">Step <?= $i+1 ?></div>
        <h4 style="font-size:1rem;margin-bottom:0.5rem;"><?= $step[1] ?></h4>
        <p style="font-size:0.82rem;color:var(--kw-text-muted);margin:0;"><?= $step[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:var(--kw-bg-hero);padding:5rem 0;" data-aos="fade-up">
  <div class="kw-container" style="text-align:center;">
    <h2 style="color:#fff;margin-bottom:1rem;">Not Sure Which Service You Need?</h2>
    <p style="color:rgba(255,255,255,0.6);max-width:500px;margin:0 auto 2rem;">Tell us about your business challenge. Our consultants will map the right solution — no sales pressure.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-calendar-check"></i> Free Consultation</a>
      <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="kw-btn kw-btn-lg" style="background:#25D366;color:#fff;border:none;" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp Us</a>
    </div>
  </div>
</section>

<style>
@media (max-width: 768px) {
  .service-detail-row { grid-template-columns: 1fr !important; direction: ltr !important; }
  .service-detail-row > div { direction: ltr !important; }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>