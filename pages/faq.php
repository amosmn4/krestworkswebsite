<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'Frequently Asked Questions — ' . APP_NAME;
$page_description = 'Common questions about Krestworks products, services, pricing, implementation, and support — all answered clearly.';


$faqSections = [
  [
    'icon'  => 'fa-boxes-stacking',
    'color' => '#F5A800',
    'title' => 'Products & Systems',
    'faqs'  => [
      ['What enterprise systems does Krestworks offer?','We offer 9 core enterprise systems: HR Management, Procurement Management, eLearning Management, Real Estate Management, Supply Chain Management, Executive Decision Support, CRM, Hospital Management, and POS System. Each is available as cloud-hosted SaaS or on-premise deployment.'],
      ['Can a system be customised for our specific workflow?','Yes. Every Krestworks system is designed as a starting point, not a ceiling. We regularly extend and customise systems to match specific business logic, approval workflows, industry regulations, and reporting requirements.'],
      ['How long does it take to implement a system?','Implementation timelines vary by system and scope. A standard deployment takes 2–6 weeks. Customised deployments with complex integrations can take 8–16 weeks. We provide a detailed implementation timeline during the proposal stage.'],
      ['Do you provide training?','Yes. All implementations include role-based training for end users, managers, and administrators. We deliver training via live sessions, recorded walkthroughs, and written user manuals tailored to your configuration.'],
      ['Can the system handle our volume of data?','Our systems are built for enterprise scale. The architecture is optimised for large datasets, high concurrent user loads, and multi-year data growth. We size infrastructure specifically to your volume requirements.'],
    ],
  ],
  [
    'icon'  => 'fa-laptop-code',
    'color' => '#3B82F6',
    'title' => 'Custom Development',
    'faqs'  => [
      ['Do you build completely custom systems from scratch?','Yes. Custom software development is one of our core services. We design and build bespoke enterprise systems, web platforms, mobile apps, and APIs tailored to your exact requirements — no templates, no compromises.'],
      ['Who owns the source code after development?','You do — completely. All intellectual property transfers to you on final payment. We retain no ownership rights or licence fees on custom-built systems.'],
      ['What development methodology do you use?','We follow Agile/Scrum methodology with 2-week sprints. You see working software at the end of every sprint, not just a final reveal. Requirements can evolve through a formal change management process.'],
      ['What is your typical project timeline?','A focused module (e.g., a leave management system) typically takes 4–8 weeks. A full enterprise platform takes 4–6 months. Complex multi-system platforms can take 6–12 months. We always provide a detailed timeline in our proposal.'],
      ['Do you sign NDAs?','Yes. We are willing to sign mutual NDAs before any discovery session where sensitive business information will be shared.'],
    ],
  ],
  [
    'icon'  => 'fa-tags',
    'color' => '#22C55E',
    'title' => 'Pricing & Contracts',
    'faqs'  => [
      ['How is pricing structured?','We offer three pricing models: (1) Subscription — monthly/annual SaaS access to our standard systems, (2) Enterprise Licence — full system ownership with one-time or phased payment, (3) Custom Package — scoped custom development with T&M or fixed-price contracts.'],
      ['Is there a free trial?','We offer a 7-day sandbox environment for all standard products. This gives you hands-on access to the full system with pre-loaded test data. Contact us to request sandbox access.'],
      ['Do subscription prices include hosting?','Yes. Subscription pricing includes cloud hosting, SSL certificates, database management, uptime monitoring, and regular security updates. Enterprise licences are priced separately from infrastructure.'],
      ['Are there any hidden fees?','No. Our pricing is transparent. Custom development, additional modules, and training beyond the initial onboarding are quoted separately and always agreed before work begins.'],
      ['Can we start small and scale?','Yes. We encourage phased adoption. Many clients start with a core module (e.g., HR), validate the value, and expand to additional modules over 6–12 months.'],
    ],
  ],
  [
    'icon'  => 'fa-plug',
    'color' => '#A855F7',
    'title' => 'Integrations',
    'faqs'  => [
      ['Can the system integrate with our existing software?','Yes. We have integration experience with most major ERP platforms (SAP, Odoo, QuickBooks), payment gateways (M-PESA Daraja, Stripe, Flutterwave), communication platforms (Twilio, SendGrid), and HR systems. If it has an API, we can connect to it.'],
      ['Do you support M-PESA integration?','Yes. We implement the full Safaricom Daraja API — STK Push, C2B, B2C, Reversals, and Account Balance. We handle sandbox testing, production go-live, and all edge cases including timeouts and payment reconciliation.'],
      ['Can you integrate biometric devices?','Yes. We integrate biometric attendance terminals, fingerprint readers, and access control hardware from major vendors. Integration is typically via SDK, database polling, or TCP/IP protocol.'],
      ['What if the third-party system has no API?','We can use file-based integration (CSV/SFTP), screen scraping, or RPA bots as fallbacks when official APIs are not available. We always recommend the most stable, maintainable integration method available.'],
    ],
  ],
  [
    'icon'  => 'fa-headset',
    'color' => '#F97316',
    'title' => 'Support & Maintenance',
    'faqs'  => [
      ['What support is included after launch?','All deployments include a 30-day hypercare period with daily check-ins. After hypercare, support is governed by an SLA that specifies response times by issue severity (P1: 2h, P2: 8h, P3: 24h).'],
      ['How do we report issues?','Via our support portal (accessible from your client dashboard), email, WhatsApp, or phone. Critical issues (system down) can be escalated directly via phone 24/7.'],
      ['Do you provide system updates?','Yes. SaaS subscriptions include all platform updates and security patches automatically. Enterprise licence holders receive updates per the maintenance agreement terms.'],
      ['Can you maintain a system another company built?','Yes, with a system handover assessment. We review the codebase, documentation, and infrastructure before assuming maintenance responsibility and provide a risk assessment with our proposal.'],
    ],
  ],
  [
    'icon'  => 'fa-shield-halved',
    'color' => '#EF4444',
    'title' => 'Security & Compliance',
    'faqs'  => [
      ['How do you ensure data security?','All systems are built with OWASP best practices: prepared statements, CSRF protection, encrypted storage, HTTPS-only, role-based access control, audit logging, and regular security reviews. We conduct a security audit before every production go-live.'],
      ['Where is data hosted?','SaaS deployments are hosted on servers in data centres with ISO 27001 certification. We can accommodate data residency requirements including local Kenya-hosted environments.'],
      ['Is data backed up?','Yes. Automated daily backups with 30-day retention as standard. Backups are stored in a separate region from the production environment. We test restore procedures monthly.'],
      ['Do you comply with the Kenya Data Protection Act?','Yes. Our systems and practices are designed to comply with the Kenya Data Protection Act 2019, including data subject rights, retention policies, and breach notification requirements.'],
    ],
  ],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">FAQ</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-circle-question"></i> Help</span>
      <h1>Frequently Asked Questions</h1>
      <p style="color:rgba(255,255,255,0.65);max-width:520px;">Everything you need to know about Krestworks products, services, pricing, and support — answered clearly and honestly.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:240px 1fr;gap:3rem;align-items:flex-start;">

      <!-- Sidebar nav -->
      <nav style="position:sticky;top:calc(var(--kw-nav-height)+1.5rem);" data-aos="fade-right">
        <div class="kw-card" style="padding:1.25rem;">
          <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Jump To Section</div>
          <?php foreach ($faqSections as $i => $section): ?>
          <a href="#faq-<?= $i ?>" onclick="smoothScroll('#faq-<?= $i ?>')" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0.65rem;border-radius:var(--kw-radius-sm);font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;margin-bottom:0.15rem;transition:all 0.2s;" onmouseover="this.style.background='var(--kw-bg-alt)';this.style.color='var(--kw-primary)'" onmouseout="this.style.background='transparent';this.style.color='var(--kw-text-secondary)'">
            <i class="fa-solid <?= $section['icon'] ?>" style="color:<?= $section['color'] ?>;width:14px;font-size:0.78rem;flex-shrink:0;"></i>
            <?= $section['title'] ?>
          </a>
          <?php endforeach; ?>
          <div style="border-top:1px solid var(--kw-border);margin-top:1rem;padding-top:1rem;">
            <p style="font-size:0.75rem;color:var(--kw-text-muted);margin-bottom:0.75rem;">Can't find your answer?</p>
            <a href="<?= url('contact') ?>" class="kw-btn kw-btn-primary kw-btn-sm" style="width:100%;justify-content:center;"><i class="fa-solid fa-envelope"></i> Ask Us</a>
          </div>
        </div>
      </nav>

      <!-- FAQ content -->
      <div>
        <!-- Quick search -->
        <div style="margin-bottom:2.5rem;" data-aos="fade-up">
          <div style="position:relative;">
            <i class="fa-solid fa-search" style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:var(--kw-text-muted);font-size:0.875rem;pointer-events:none;"></i>
            <input type="text" id="faq-search" placeholder="Search all questions…" oninput="searchFAQ(this.value)"
              style="width:100%;padding:0.75rem 1rem 0.75rem 2.75rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-lg);font-size:0.9rem;color:var(--kw-text-primary);outline:none;box-sizing:border-box;"
              onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
          </div>
        </div>

        <?php foreach ($faqSections as $i => $section): ?>
        <div id="faq-<?= $i ?>" style="margin-bottom:3rem;" data-aos="fade-up">
          <div style="display:flex;align-items:center;gap:0.85rem;margin-bottom:1.25rem;">
            <div style="width:42px;height:42px;border-radius:var(--kw-radius-md);background:<?= $section['color'] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="fa-solid <?= $section['icon'] ?>" style="color:<?= $section['color'] ?>;font-size:0.95rem;"></i>
            </div>
            <h3 style="font-size:1.1rem;margin:0;"><?= $section['title'] ?></h3>
          </div>
          <div class="kw-accordion-group faq-section">
            <?php foreach ($section['faqs'] as $j => $faq): ?>
            <div class="kw-accordion-item faq-item" data-question="<?= strtolower(e($faq[0])) ?>" data-answer="<?= strtolower(e($faq[1])) ?>">
              <button class="kw-accordion-trigger">
                <?= e($faq[0]) ?>
                <i class="fa-solid fa-chevron-down"></i>
              </button>
              <div class="kw-accordion-body">
                <div class="kw-accordion-body-inner"><?= e($faq[1]) ?></div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>

        <!-- Still need help -->
        <div class="kw-card" style="padding:2rem;text-align:center;border-top:3px solid var(--kw-primary);" data-aos="fade-up">
          <i class="fa-solid fa-headset" style="font-size:2rem;color:var(--kw-primary);margin-bottom:1rem;display:block;"></i>
          <h3 style="margin-bottom:0.5rem;">Still Have Questions?</h3>
          <p style="color:var(--kw-text-muted);margin-bottom:1.5rem;font-size:0.875rem;">Our team is available Monday to Friday, 8am–6pm EAT. We also respond to WhatsApp messages over the weekend.</p>
          <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?= url('contact') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-envelope"></i> Contact Us</a>
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank" class="kw-btn" style="background:#25D366;color:#fff;border:none;"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
            <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-ghost"><i class="fa-solid fa-calendar-check"></i> Book Consultation</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
function smoothScroll(target) {
  event.preventDefault();
  const el = document.querySelector(target);
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
function searchFAQ(query) {
  const q = query.toLowerCase().trim();
  document.querySelectorAll('.faq-item').forEach(item => {
    const match = !q || item.dataset.question.includes(q) || item.dataset.answer.includes(q);
    item.style.display = match ? '' : 'none';
    if (match && q) {
      // Auto-open matched items
      const body = item.querySelector('.kw-accordion-body');
      if (body && body.style.maxHeight === '0px' || !body?.style.maxHeight) {
        item.querySelector('.kw-accordion-trigger')?.click();
      }
    }
  });
  // Show/hide section headings
  document.querySelectorAll('.faq-section').forEach(section => {
    const hasVisible = [...section.querySelectorAll('.faq-item')].some(i => i.style.display !== 'none');
    section.closest('[id^="faq-"]').style.display = hasVisible ? '' : 'none';
  });
}
</script>
<style>
@media(max-width:1024px){
  .kw-container > div[style*="240px 1fr"]{grid-template-columns:1fr!important;}
  nav[style*="sticky"]{position:static!important;}
}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>