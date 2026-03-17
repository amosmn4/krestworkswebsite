<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Custom Software Development — ' . APP_NAME;
$page_description = 'Bespoke enterprise software development — ERP, workflow automation, digital transformation, and AI-integrated systems built to your exact specifications.';

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('services') ?>">Services</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Custom Software Development</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-laptop-code"></i> Service</span>
      <h1>Custom Software<br><span style="color:var(--kw-primary);">Development</span></h1>
      <p>We engineer bespoke enterprise systems tailored to your exact operational workflows — not generic tools that need workarounds.</p>
    </div>
  </div>
</section>

<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;" data-aos="fade-up">

      <!-- Main Content -->
      <div>
        <!-- Tabs -->
        <div class="kw-tabs" data-tabs-container>
          <button class="kw-tab-btn active" data-tab="overview">Overview</button>
          <button class="kw-tab-btn" data-tab="process">Process</button>
          <button class="kw-tab-btn" data-tab="deliverables">Deliverables</button>
          <button class="kw-tab-btn" data-tab="faq">FAQ</button>
        </div>

        <!-- Overview -->
        <div class="kw-tab-panel active" data-tab-panel="overview">
          <h3 style="margin-bottom:1rem;">What We Build</h3>
          <p>Custom software development at Krestworks means your system is architected from the ground up around your business logic — not the other way around. We don't retrofit generic platforms; we design for how your teams actually work.</p>
          <p>Every system we deliver is production-grade: secured, documented, tested, and built to scale from day one.</p>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin:1.5rem 0;">
            <?php
            $capabilities = [
              ['fa-building','Enterprise Systems','Full-scale ERP, HR, procurement, and operational platforms.'],
              ['fa-shuffle','Business Automation','Workflow engines, approval systems, task automation, scheduled jobs.'],
              ['fa-brain','AI Integration','Embed AI models, chatbots, predictive analytics into your systems.'],
              ['fa-plug','API Development','RESTful and GraphQL APIs — internal, partner-facing, or public.'],
              ['fa-arrows-rotate','Legacy Modernization','Rebuild ageing systems without losing business logic or data.'],
              ['fa-chart-line','Analytics Platforms','Dashboards, reporting engines, and data visualization tools.'],
            ];
            foreach ($capabilities as $cap): ?>
            <div class="kw-card" style="padding:1.25rem;">
              <div style="display:flex;align-items:flex-start;gap:0.75rem;">
                <i class="fa-solid <?= $cap[0] ?>" style="color:var(--kw-primary);font-size:1rem;margin-top:0.15rem;flex-shrink:0;"></i>
                <div>
                  <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.2rem;"><?= $cap[1] ?></div>
                  <div style="font-size:0.8rem;color:var(--kw-text-muted);"><?= $cap[2] ?></div>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>

          <h4 style="margin:2rem 0 1rem;">Technologies We Use</h4>
          <div class="tech-pills-wrap">
            <?php foreach (['PHP','Laravel','Node.js','Express','React','Python','Django','Flask','TypeScript','MySQL','PostgreSQL','Redis','Docker','Git','REST APIs','GraphQL'] as $t): ?>
              <span class="tech-pill"><?= $t ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Process -->
        <div class="kw-tab-panel" data-tab-panel="process">
          <h3 style="margin-bottom:1.5rem;">Our Development Process</h3>
          <div class="kw-steps">
            <?php
            $steps = [
              ['Requirements Workshop','2–3 sessions to map your business processes, pain points, and success criteria. We document everything before quoting.'],
              ['Solution Architecture','System design document, database schema, API contracts, and UI wireframes — submitted for your approval.'],
              ['Sprint-based Development','2-week sprints with a live demo at the end of each. You test real features, not mock-ups.'],
              ['Quality Assurance','Unit tests, integration tests, load testing, security scans — all run before any release.'],
              ['User Acceptance Testing','Your team tests the full system in a staging environment with real data before go-live.'],
              ['Production Deployment','Zero-downtime deployment, DNS/SSL configuration, monitoring setup, and performance baseline.'],
              ['Post-Launch Support','30-day hypercare period with daily check-ins, followed by standard SLA-based support.'],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="kw-step">
              <div class="kw-step-num"><?= $i+1 ?></div>
              <div class="kw-step-body">
                <h4><?= $step[0] ?></h4>
                <p><?= $step[1] ?></p>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Deliverables -->
        <div class="kw-tab-panel" data-tab-panel="deliverables">
          <h3 style="margin-bottom:1.25rem;">What You Receive</h3>
          <div style="display:flex;flex-direction:column;gap:0.75rem;">
            <?php
            $deliverables = [
              ['fa-file-code','Full Source Code','Complete, well-commented source code — yours to own.'],
              ['fa-database','Database Schema & Migrations','Documented schema with all migrations and seed data.'],
              ['fa-book','Technical Documentation','API docs, system architecture, and developer guide.'],
              ['fa-graduation-cap','User Training Manual','Role-based user guides and video walkthroughs.'],
              ['fa-server','Deployed Production System','Live, monitored, SSL-secured production environment.'],
              ['fa-shield-halved','Security Report','Post-deployment security audit report and remediation log.'],
              ['fa-headset','Support & SLA','Defined support agreement covering bugs, uptime, and enhancements.'],
            ];
            foreach ($deliverables as $d): ?>
            <div style="display:flex;align-items:flex-start;gap:1rem;padding:1rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);">
              <div style="width:38px;height:38px;border-radius:var(--kw-radius-sm);background:rgba(245,168,0,0.1);display:flex;align-items:center;justify-content:center;color:var(--kw-primary);flex-shrink:0;">
                <i class="fa-solid <?= $d[0] ?>"></i>
              </div>
              <div>
                <div style="font-weight:700;font-size:0.9rem;margin-bottom:0.2rem;"><?= $d[1] ?></div>
                <div style="font-size:0.82rem;color:var(--kw-text-muted);"><?= $d[2] ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- FAQ -->
        <div class="kw-tab-panel" data-tab-panel="faq">
          <h3 style="margin-bottom:1.5rem;">Frequently Asked Questions</h3>
          <div class="kw-accordion-group">
            <?php
            $faqs = [
              ['How long does a custom software project take?','Timelines depend on scope. A focused module (e.g., leave management) can take 4–6 weeks. A full ERP covering multiple departments typically takes 4–6 months. We always provide a detailed timeline before signing.'],
              ['Do I own the source code?','Yes — completely. All intellectual property transfers to you upon final payment. We retain no rights to your system.'],
              ['Can you work with our existing systems?','Absolutely. We design integrations as a core part of every project — not an afterthought. We assess your current stack during discovery.'],
              ['What if requirements change during development?','We use Agile methodology. Changes are handled through a formal change request process — scoped, priced, and approved before implementation.'],
              ['Do you provide hosting?','Yes. We offer cloud hosting, cPanel hosting, on-premise deployment, and hybrid setups. Hosting is quoted separately from development.'],
              ['How do you handle data security?','All systems are built with OWASP best practices: prepared statements, CSRF protection, encrypted storage, role-based access, and audit logging. We also conduct a security audit before go-live.'],
            ];
            foreach ($faqs as $faq): ?>
            <div class="kw-accordion-item">
              <button class="kw-accordion-trigger"><?= $faq[0] ?><i class="fa-solid fa-chevron-down"></i></button>
              <div class="kw-accordion-body"><div class="kw-accordion-body-inner"><?= $faq[1] ?></div></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Sidebar -->
      <div>
        <div style="position:sticky;top:calc(var(--kw-nav-height) + 1.5rem);display:flex;flex-direction:column;gap:1.25rem;">

          <!-- Quick CTA -->
          <div class="kw-card kw-card-gold-top" style="padding:1.5rem;">
            <h4 style="margin-bottom:0.5rem;">Start Your Project</h4>
            <p style="font-size:0.85rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Tell us what you need. We'll respond with a scoping proposal within 48 hours.</p>
            <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;margin-bottom:0.6rem;"><i class="fa-solid fa-calendar-check"></i> Book Consultation</a>
            <a href="<?= url('demo') ?>" class="kw-btn kw-btn-ghost" style="width:100%;justify-content:center;"><i class="fa-solid fa-play-circle"></i> Request Demo</a>
          </div>

          <!-- Key Info -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="margin-bottom:1rem;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Service Details</h5>
            <?php
            $info = [['fa-clock','Timeline','4 weeks – 6 months'],['fa-tags','Pricing','Custom quote'],['fa-layer-group','Methodology','Agile / Scrum'],['fa-file-contract','Contract','Fixed or T&M'],['fa-headset','Support','SLA-based']];
            foreach ($info as $inf): ?>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.6rem 0;border-bottom:1px solid var(--kw-border);font-size:0.85rem;">
              <span style="color:var(--kw-text-muted);display:flex;align-items:center;gap:0.4rem;"><i class="fa-solid <?= $inf[0] ?>" style="color:var(--kw-primary);width:14px;"></i><?= $inf[1] ?></span>
              <span style="font-weight:600;"><?= $inf[2] ?></span>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Related Services -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="margin-bottom:1rem;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">Related Services</h5>
            <?php
            $related = [['ai-solutions','fa-robot','AI Solutions'],['system-integration','fa-plug','System Integration'],['cloud-infrastructure','fa-cloud','Cloud Infrastructure']];
            foreach ($related as $r): ?>
            <a href="<?= url('services/'.$r[0]) ?>" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.85rem;color:var(--kw-text-secondary);text-decoration:none;transition:color 0.2s;">
              <i class="fa-solid <?= $r[1] ?>" style="color:var(--kw-primary);width:16px;"></i><?= $r[2] ?>
              <i class="fa-solid fa-arrow-right" style="margin-left:auto;font-size:0.7rem;color:var(--kw-text-muted);"></i>
            </a>
            <?php endforeach; ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>