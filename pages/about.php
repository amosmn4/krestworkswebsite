<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'About Us — ' . APP_NAME;
$page_description = 'Learn about Krestworks Solutions — our story, mission, vision, core values, and the team building Africa\'s most innovative enterprise software.';

?>

<!-- Hero -->
<section class="kw-page-hero" style="min-height:480px;display:flex;align-items:center;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">About Us</span>
    </div>
    <div style="max-width:680px;padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-building"></i> Who We Are</span>
      <h1>Empowering Digital<br><span style="color:var(--kw-primary);">Transformation</span></h1>
      <p style="color:rgba(255,255,255,0.65);font-size:1.05rem;line-height:1.8;">
        Krestworks Solutions is a technology company focused on building intelligent digital systems that help organisations operate more efficiently and make better decisions.
      </p>
    </div>
  </div>
</section>

<!-- Stats strip -->
<section style="background:var(--kw-bg-card);border-bottom:1px solid var(--kw-border);padding:2.5rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;" data-aos="fade-up">
      <?php foreach ([
        ['50+','Projects Delivered'],['12+','Industries Served'],['98%','Client Satisfaction'],['5+','Years Engineering'],
      ] as $s): ?>
      <div style="text-align:center;padding:1rem;">
        <div style="font-size:2.25rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);" data-counter data-target="<?= intval($s[0]) ?>" data-suffix="<?= preg_replace('/[0-9]/','', $s[0]) ?>"><?= $s[0] ?></div>
        <div style="font-size:0.82rem;color:var(--kw-text-muted);margin-top:0.25rem;"><?= $s[1] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Intro + Story -->
<section style="background:var(--kw-bg);padding:5rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;" data-aos="fade-up">
      <!-- Text -->
      <div>
        <span class="label" style="margin-bottom:1rem;display:inline-flex;"><i class="fa-solid fa-bullseye"></i> Our Purpose</span>
        <h2 style="margin-bottom:1.25rem;">Bridging the Gap Between<br>Innovation & Practicality</h2>
        <p style="margin-bottom:1rem;line-height:1.8;color:var(--kw-text-secondary);">
          In today's rapidly evolving digital environment, organisations require technology that is not only powerful but also practical and easy to use. At Krestworks, we bridge this gap by creating systems that combine innovation, reliability, and simplicity.
        </p>
        <p style="line-height:1.8;color:var(--kw-text-secondary);">
          Our goal is to help businesses, institutions, and service providers transition smoothly into the digital era — delivering technology that enhances operational efficiency and unlocks new opportunities for growth.
        </p>
        <div style="margin-top:2rem;display:flex;gap:1rem;flex-wrap:wrap;">
          <a href="<?= url('services') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-cogs"></i> Our Services</a>
          <a href="<?= url('contact') ?>" class="kw-btn kw-btn-ghost"><i class="fa-solid fa-envelope"></i> Get In Touch</a>
        </div>
      </div>
      <!-- Visual grid -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
        <?php foreach ([
          ['fa-lightbulb','#F5A800','Innovation','We continuously explore new technologies to deliver smarter digital solutions.'],
          ['fa-shield-halved','#3B82F6','Reliability','Systems designed for stability, security and long-term performance.'],
          ['fa-handshake','#22C55E','Partnership','We work closely with clients to understand and solve real challenges.'],
          ['fa-bolt','#A855F7','Efficiency','Every system we build is designed to reduce complexity and boost productivity.'],
        ] as $v): ?>
        <div class="kw-card" style="padding:1.25rem;border-top:3px solid <?= $v[1] ?>;">
          <i class="fa-solid <?= $v[0] ?>" style="font-size:1.25rem;color:<?= $v[1] ?>;margin-bottom:0.65rem;display:block;"></i>
          <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.3rem;"><?= $v[2] ?></div>
          <p style="font-size:0.75rem;color:var(--kw-text-muted);margin:0;line-height:1.5;"><?= $v[3] ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Our Story -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);padding:5rem 0;">
  <div class="kw-container">
    <div style="max-width:760px;margin:0 auto;" data-aos="fade-up">
      <div style="text-align:center;margin-bottom:3rem;">
        <span class="label"><i class="fa-solid fa-book-open"></i> Our Story</span>
        <h2 style="margin-top:0.75rem;">Why We Built Krestworks</h2>
      </div>
      <div style="position:relative;padding-left:2rem;border-left:3px solid var(--kw-primary);">
        <div style="font-size:0.95rem;line-height:1.9;color:var(--kw-text-secondary);">
          <p style="margin-bottom:1.25rem;">
            Krestworks was founded with a clear vision — to provide organisations with smart, scalable technology solutions that solve real operational challenges.
          </p>
          <p style="margin-bottom:1.25rem;">
            Many organisations struggle with fragmented systems, manual processes and limited access to actionable data. These challenges slow down productivity and hinder strategic growth. Krestworks was created to address these problems — building customised digital platforms that streamline operations and centralise information.
          </p>
          <p style="margin-bottom:1.25rem;">
            Our team consists of skilled developers, system architects, data analysts and technology consultants who work collaboratively to design systems tailored to each client's specific needs.
          </p>
          <p>
            We operate with a remote-first and agile work model, allowing our team to respond quickly to client needs while maintaining flexibility and efficiency. This approach enables us to serve organisations across Kenya, East Africa and beyond.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Mission & Vision -->
<section style="background:var(--kw-bg);padding:5rem 0;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem;margin-bottom:3rem;" data-aos="fade-up">
      <!-- Mission -->
      <div class="kw-card" style="padding:2.5rem;border-top:4px solid var(--kw-primary);position:relative;overflow:hidden;">
        <div style="position:absolute;top:-1rem;right:-1rem;font-size:5rem;font-weight:900;color:var(--kw-primary);opacity:0.04;font-family:var(--font-heading);line-height:1;">M</div>
        <div style="width:52px;height:52px;border-radius:var(--kw-radius-md);background:rgba(245,168,0,0.12);display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
          <i class="fa-solid fa-bullseye" style="font-size:1.25rem;color:var(--kw-primary);"></i>
        </div>
        <h3 style="margin-bottom:0.75rem;font-size:1.1rem;text-transform:uppercase;letter-spacing:0.04em;">Our Mission</h3>
        <p style="line-height:1.8;color:var(--kw-text-secondary);font-size:0.95rem;">
          To empower organisations by developing practical, reliable and user-friendly technology solutions that simplify operations and support data-driven decision making.
        </p>
      </div>
      <!-- Vision -->
      <div class="kw-card" style="padding:2.5rem;border-top:4px solid #3B82F6;position:relative;overflow:hidden;">
        <div style="position:absolute;top:-1rem;right:-1rem;font-size:5rem;font-weight:900;color:#3B82F6;opacity:0.04;font-family:var(--font-heading);line-height:1;">V</div>
        <div style="width:52px;height:52px;border-radius:var(--kw-radius-md);background:rgba(59,130,246,0.12);display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
          <i class="fa-solid fa-eye" style="font-size:1.25rem;color:#3B82F6;"></i>
        </div>
        <h3 style="margin-bottom:0.75rem;font-size:1.1rem;text-transform:uppercase;letter-spacing:0.04em;">Our Vision</h3>
        <p style="line-height:1.8;color:var(--kw-text-secondary);font-size:0.95rem;">
          To become a trusted technology partner across Africa by delivering innovative systems that transform how organisations operate, collaborate and grow in an increasingly digital world.
        </p>
      </div>
    </div>
  </div>
</section>

<!-- Core Values -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);padding:5rem 0;">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up" style="margin-bottom:3rem;">
      <span class="label"><i class="fa-solid fa-star"></i> What We Stand For</span>
      <h2>Core Values</h2>
      <p>The principles that guide every system we build and every relationship we form.</p>
      <div class="kw-divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.25rem;" data-aos="fade-up" data-aos-delay="100">
      <?php foreach ([
        ['fa-lightbulb','#F5A800','Innovation','We continuously explore new technologies and ideas to deliver smarter, more effective digital solutions.'],
        ['fa-shield-halved','#3B82F6','Reliability','Our systems are designed with stability, security and long-term performance in mind. We build for the long run.'],
        ['fa-handshake','#22C55E','Client Partnership','We work closely with our clients to understand their needs and deliver solutions that create real, measurable impact.'],
        ['fa-bolt','#A855F7','Efficiency','Every system we build is designed to reduce complexity and improve operational productivity across every department.'],
        ['fa-magnifying-glass','#F97316','Transparency','Clear communication at every stage — no surprises on scope, timeline, or cost. We say what we mean.'],
        ['fa-globe-africa','#EF4444','African Context','We understand the unique challenges and opportunities of operating in Africa — and we design for them.'],
      ] as $i => $v): ?>
      <div class="kw-card" style="padding:1.75rem;" data-aos="zoom-in" data-aos-delay="<?= $i * 50 ?>">
        <div style="width:48px;height:48px;border-radius:var(--kw-radius-md);background:<?= $v[1] ?>15;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;border:1px solid <?= $v[1] ?>30;">
          <i class="fa-solid <?= $v[0] ?>" style="font-size:1.1rem;color:<?= $v[1] ?>;"></i>
        </div>
        <h4 style="margin-bottom:0.5rem;font-size:1rem;"><?= $v[2] ?></h4>
        <p style="font-size:0.82rem;color:var(--kw-text-muted);line-height:1.6;margin:0;"><?= $v[3] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Our Approach (5 steps) -->
<section style="background:var(--kw-bg);padding:5rem 0;">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up" style="margin-bottom:3rem;">
      <span class="label"><i class="fa-solid fa-diagram-project"></i> How We Work</span>
      <h2>Our Approach</h2>
      <p>A structured, collaborative process that ensures every project delivers lasting value.</p>
      <div class="kw-divider"></div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0;position:relative;" data-aos="fade-up" data-aos-delay="100">
      <!-- Connector line -->
      <div style="position:absolute;top:38px;left:10%;right:10%;height:2px;background:linear-gradient(to right,var(--kw-primary),#3B82F6,#22C55E,#A855F7,#F97316);z-index:0;opacity:0.3;"></div>

      <?php foreach ([
        ['fa-comments','#F5A800','01','Consultation & Discovery','We begin by understanding your needs, operational challenges, and business objectives through structured workshops.'],
        ['fa-pen-ruler','#3B82F6','02','System Design','Our team designs a tailored solution with a focus on usability, scalability, and performance before any code is written.'],
        ['fa-code','#22C55E','03','Development & Testing','We build and rigorously test systems to ensure reliability, security, and seamless functionality across all environments.'],
        ['fa-rocket','#A855F7','04','Deployment & Training','We support clients during implementation and provide comprehensive training to ensure smooth system adoption.'],
        ['fa-headset','#F97316','05','Continuous Support','Our work does not end at deployment. We provide ongoing technical support, upgrades, and improvements.'],
      ] as $step): ?>
      <div style="text-align:center;padding:0 0.75rem;position:relative;z-index:1;">
        <div style="width:76px;height:76px;border-radius:50%;background:var(--kw-bg-card);border:2px solid <?= $step[1] ?>;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
          <i class="fa-solid <?= $step[0] ?>" style="font-size:1.5rem;color:<?= $step[1] ?>;"></i>
        </div>
        <div style="font-size:0.65rem;font-weight:800;color:<?= $step[1] ?>;letter-spacing:0.1em;margin-bottom:0.35rem;"><?= $step[2] ?></div>
        <h4 style="font-size:0.85rem;margin-bottom:0.5rem;line-height:1.3;"><?= $step[3] ?></h4>
        <p style="font-size:0.75rem;color:var(--kw-text-muted);line-height:1.5;margin:0;"><?= $step[4] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Technology we use -->
<section style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);padding:4rem 0;">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up" style="margin-bottom:2.5rem;">
      <span class="label"><i class="fa-solid fa-microchip"></i> Our Stack</span>
      <h2>Technologies We Work With</h2>
      <div class="kw-divider"></div>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:0.65rem;justify-content:center;" data-aos="fade-up" data-aos-delay="100">
      <?php foreach ([
        'PHP','Laravel','Node.js','Express','React','React Native','Flutter','Python','Django','Flask',
        'TypeScript','HTML5','CSS3','Bootstrap','Tailwind CSS','MySQL','PostgreSQL','Redis','MongoDB',
        'Docker','AWS','DigitalOcean','Git','REST APIs','GraphQL','Anthropic Claude','OpenAI','TensorFlow',
        'scikit-learn','Nginx','Linux','Stripe','M-PESA Daraja','Firebase',
      ] as $tech): ?>
      <span class="tech-pill" style="font-size:0.8rem;"><?= $tech ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Team section placeholder -->
<section style="background:var(--kw-bg);padding:5rem 0;">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up" style="margin-bottom:3rem;">
      <span class="label"><i class="fa-solid fa-users"></i> The Team</span>
      <h2>Built by Engineers,<br>Driven by Purpose</h2>
      <p>Our team combines deep technical expertise with real-world business understanding.</p>
      <div class="kw-divider"></div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;" data-aos="fade-up" data-aos-delay="100">
      <?php foreach ([
        ['fa-laptop-code','Software Engineers','Full-stack developers building enterprise systems in PHP, Laravel, Node.js, and React.'],
        ['fa-drafting-compass','System Architects','Experienced architects designing scalable, secure, maintainable platforms.'],
        ['fa-database','Data Analysts','Analysts turning raw data into actionable business intelligence and insights.'],
        ['fa-robot','AI Engineers','Specialists integrating machine learning and AI into real business workflows.'],
        ['fa-cloud','DevOps Engineers','Infrastructure experts managing cloud deployments, CI/CD, and uptime.'],
        ['fa-lightbulb','Technology Consultants','Strategic advisors helping organisations make the right technology decisions.'],
      ] as $role): ?>
      <div class="kw-card" style="padding:1.5rem;text-align:center;" data-aos="zoom-in" data-aos-delay="0">
        <div style="width:56px;height:56px;border-radius:50%;background:rgba(245,168,0,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;border:2px solid rgba(245,168,0,0.3);">
          <i class="fa-solid <?= $role[0] ?>" style="font-size:1.2rem;color:var(--kw-primary);"></i>
        </div>
        <h4 style="font-size:0.9rem;margin-bottom:0.4rem;"><?= $role[1] ?></h4>
        <p style="font-size:0.75rem;color:var(--kw-text-muted);margin:0;line-height:1.5;"><?= $role[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem;padding:1.5rem;background:var(--kw-bg-alt);border-radius:var(--kw-radius-xl);border:1px solid var(--kw-border);" data-aos="fade-up">
      <p style="color:var(--kw-text-muted);font-size:0.875rem;margin-bottom:0.75rem;">
        <i class="fa-solid fa-map-marker-alt" style="color:var(--kw-primary);"></i>
        Based in <strong>Nairobi, Kenya</strong> — serving clients across East Africa and beyond.
      </p>
      <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
        <span style="font-size:0.8rem;color:var(--kw-text-muted);"><i class="fa-solid fa-globe" style="color:var(--kw-primary);"></i> <?= COMPANY_WEBSITE ?? 'www.krestworks.com' ?></span>
        <span style="font-size:0.8rem;color:var(--kw-text-muted);"><i class="fa-solid fa-envelope" style="color:var(--kw-primary);"></i> <?= COMPANY_EMAIL ?></span>
        <span style="font-size:0.8rem;color:var(--kw-text-muted);"><i class="fa-solid fa-phone" style="color:var(--kw-primary);"></i> <?= COMPANY_PHONE ?></span>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section style="background:var(--kw-bg-hero);padding:5rem 0;">
  <div class="kw-container" style="text-align:center;" data-aos="fade-up">
    <h2 style="color:#fff;margin-bottom:0.75rem;">Ready to Work With Us?</h2>
    <p style="color:rgba(255,255,255,0.6);max-width:480px;margin:0 auto 2rem;">
      Let's start with a free consultation. Tell us about your business challenges and we'll map the right technology path forward.
    </p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-calendar-check"></i> Book Free Consultation</a>
      <a href="<?= url('contact') ?>" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.1);border-color:rgba(255,255,255,0.2);color:#fff;"><i class="fa-solid fa-envelope"></i> Contact Us</a>
    </div>
  </div>
</section>

<style>
@media(max-width:1024px){
  section > .kw-container > div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}
  div[style*="repeat(5,1fr)"]{grid-template-columns:repeat(3,1fr)!important;}
  div[style*="top:38px"]{display:none;}
}
@media(max-width:640px){
  div[style*="repeat(5,1fr)"]{grid-template-columns:1fr 1fr!important;}
  div[style*="repeat(4,1fr)"]{grid-template-columns:1fr 1fr!important;}
}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>