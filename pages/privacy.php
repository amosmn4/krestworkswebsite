<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'Privacy Policy — ' . APP_NAME;
$page_description = 'How Krestworks Solutions collects, uses, stores and protects your personal data. Last updated March 2026.';

$lastUpdated = 'March 1, 2026';
?>

<section class="kw-page-hero" style="min-height:280px;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Privacy Policy</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-shield-halved"></i> Legal</span>
      <h1>Privacy Policy</h1>
      <p style="color:rgba(255,255,255,0.55);font-size:0.875rem;">Last updated: <?= $lastUpdated ?> &nbsp;·&nbsp; Effective immediately</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:220px 1fr;gap:3rem;align-items:flex-start;">

      <!-- TOC Sidebar -->
      <nav style="position:sticky;top:calc(var(--kw-nav-height)+1rem);" data-aos="fade-right">
        <div class="kw-card" style="padding:1.25rem;">
          <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Contents</div>
          <?php foreach ([
            ['#overview','Overview'],['#collection','Information We Collect'],['#usage','How We Use Data'],
            ['#sharing','Data Sharing'],['#storage','Data Storage & Security'],['#rights','Your Rights'],
            ['#cookies','Cookies'],['#third-party','Third-Party Services'],['#children','Children\'s Privacy'],
            ['#changes','Policy Changes'],['#contact-us','Contact Us'],
          ] as $item): ?>
          <a href="<?= $item[0] ?>" style="display:block;padding:0.4rem 0.5rem;border-radius:4px;font-size:0.78rem;color:var(--kw-text-muted);text-decoration:none;line-height:1.4;transition:all 0.15s;"
             onmouseover="this.style.background='var(--kw-bg-alt)';this.style.color='var(--kw-primary)'"
             onmouseout="this.style.background='';this.style.color='var(--kw-text-muted)'"><?= $item[1] ?></a>
          <?php endforeach; ?>
        </div>
      </nav>

      <!-- Policy content -->
      <div class="policy-content" data-aos="fade-up">

        <div id="overview" style="margin-bottom:2.5rem;">
          <h2>Overview</h2>
          <p>Krestworks Solutions ("Krestworks", "we", "us", or "our") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website at <a href="<?= BASE_URL ?>"><?= BASE_URL ?></a>, use our products, or engage our services.</p>
          <p>Please read this policy carefully. If you disagree with any part, please discontinue use of our services. By using our website or services, you consent to the practices described here.</p>
          <div style="padding:1rem 1.25rem;background:rgba(245,168,0,0.08);border-left:3px solid var(--kw-primary);border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;">
            <strong>Scope:</strong> This policy applies to all Krestworks digital properties, products (SaaS systems), and professional services. It also applies to data collected through our contact forms, demo requests, newsletter, community platform, and client portal.
          </div>
        </div>

        <div id="collection" style="margin-bottom:2.5rem;">
          <h2>Information We Collect</h2>
          <h3>Information You Provide Directly</h3>
          <ul>
            <li><strong>Contact Information:</strong> Name, email address, phone number, company name — collected when you fill in contact, demo, or consultation forms.</li>
            <li><strong>Account Data:</strong> Email address and password (hashed) when you register for our client portal or community platform.</li>
            <li><strong>Payment Information:</strong> Handled directly by our payment processors (M-PESA, Stripe). We do not store payment card numbers on our servers.</li>
            <li><strong>Communications:</strong> Messages, enquiries, and support tickets submitted through our website or email.</li>
            <li><strong>Community Content:</strong> Posts, replies, and interactions on our community platform — associated with your registered account.</li>
          </ul>
          <h3>Information Collected Automatically</h3>
          <ul>
            <li><strong>Usage Data:</strong> Pages visited, time spent, clicks, referral source, browser type, and device information — collected via server logs and analytics.</li>
            <li><strong>IP Address:</strong> Used for rate limiting, security monitoring, and approximate geographic location (country/city level).</li>
            <li><strong>Cookies:</strong> See the <a href="#cookies">Cookies section</a> below for details.</li>
            <li><strong>AI Tool Interactions:</strong> Input prompts and output results from our AI Hub tools, associated with your account or IP address. These may be used to improve tool quality.</li>
          </ul>
        </div>

        <div id="usage" style="margin-bottom:2.5rem;">
          <h2>How We Use Your Data</h2>
          <p>We use the information we collect to:</p>
          <ul>
            <li>Respond to your enquiries, demo requests, and consultation bookings</li>
            <li>Provide, maintain, and improve our software products and services</li>
            <li>Process subscription payments and manage licence agreements</li>
            <li>Send transactional communications (booking confirmations, support updates, system notifications)</li>
            <li>Send marketing communications — only where you have given consent, and with easy opt-out</li>
            <li>Analyse usage patterns to improve website experience and AI tool quality</li>
            <li>Detect and prevent fraud, abuse, and security threats</li>
            <li>Comply with legal obligations under Kenyan law</li>
          </ul>
          <p>We do not use your personal data to make automated decisions that significantly affect you without human oversight.</p>
        </div>

        <div id="sharing" style="margin-bottom:2.5rem;">
          <h2>Data Sharing</h2>
          <p>We do not sell your personal data. We share it only in the following limited circumstances:</p>
          <ul>
            <li><strong>Service Providers:</strong> Trusted third parties who assist in operating our services — email delivery (SendGrid/Mailgun), payment processing (Stripe, M-PESA), cloud hosting, and analytics. These are bound by data processing agreements.</li>
            <li><strong>AI API Providers:</strong> When you use AI Hub tools, your input is processed by Anthropic's Claude API. Anthropic's own privacy policy governs that processing.</li>
            <li><strong>Legal Requirements:</strong> If required by Kenyan law, court order, or regulatory authority.</li>
            <li><strong>Business Transfers:</strong> In the event of a merger or acquisition, your data would transfer to the successor entity with equivalent protections.</li>
          </ul>
        </div>

        <div id="storage" style="margin-bottom:2.5rem;">
          <h2>Data Storage & Security</h2>
          <p>Your data is stored on servers hosted in data centres with industry-standard physical and logical security controls. We implement the following technical measures:</p>
          <ul>
            <li>HTTPS encryption for all data in transit</li>
            <li>Bcrypt password hashing — passwords are never stored in plain text</li>
            <li>Database access restricted to authenticated application connections only</li>
            <li>Regular security audits and vulnerability assessments</li>
            <li>Daily automated backups with 30-day retention</li>
            <li>Role-based access control limiting staff data access to what is necessary</li>
          </ul>
          <p>Despite these measures, no system is 100% secure. In the event of a data breach affecting your personal data, we will notify you in accordance with the Kenya Data Protection Act 2019 requirements.</p>
        </div>

        <div id="rights" style="margin-bottom:2.5rem;">
          <h2>Your Rights</h2>
          <p>Under the Kenya Data Protection Act 2019, you have the right to:</p>
          <ul>
            <li><strong>Access:</strong> Request a copy of the personal data we hold about you</li>
            <li><strong>Correction:</strong> Request correction of inaccurate or incomplete data</li>
            <li><strong>Deletion:</strong> Request deletion of your data (subject to legal retention obligations)</li>
            <li><strong>Objection:</strong> Object to processing of your data for marketing purposes</li>
            <li><strong>Portability:</strong> Request your data in a structured, machine-readable format</li>
            <li><strong>Withdrawal of Consent:</strong> Withdraw consent for processing at any time</li>
          </ul>
          <p>To exercise any of these rights, email us at <a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a> with the subject "Data Rights Request". We will respond within 30 days.</p>
        </div>

        <div id="cookies" style="margin-bottom:2.5rem;">
          <h2>Cookies</h2>
          <p>We use cookies and similar tracking technologies to improve your experience on our website.</p>
          <div style="display:flex;flex-direction:column;gap:0;margin-bottom:1rem;">
            <?php foreach ([
              ['Essential Cookies','Required for core functionality — session management, CSRF protection, login state. Cannot be disabled.','Always active'],
              ['Preference Cookies','Remember your theme choice (light/dark) and display preferences.','Stored in localStorage'],
              ['Analytics Cookies','Aggregate, anonymised usage data — pages visited, session duration. No personally identifiable information is transmitted.','Opt-out available'],
            ] as $cookie): ?>
            <div style="display:flex;gap:1rem;align-items:flex-start;padding:0.9rem 0;border-bottom:1px solid var(--kw-border);">
              <div style="flex:1;">
                <div style="font-size:0.875rem;font-weight:700;"><?= $cookie[0] ?></div>
                <div style="font-size:0.8rem;color:var(--kw-text-muted);margin-top:0.2rem;"><?= $cookie[1] ?></div>
              </div>
              <span style="font-size:0.72rem;font-weight:700;color:var(--kw-primary);white-space:nowrap;"><?= $cookie[2] ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <p>You can control cookies through your browser settings. Note that disabling essential cookies will affect website functionality.</p>
        </div>

        <div id="third-party" style="margin-bottom:2.5rem;">
          <h2>Third-Party Services</h2>
          <p>Our website and products integrate with the following third-party services, each governed by their own privacy policies:</p>
          <ul>
            <li><strong>Anthropic Claude API</strong> — powers our Krest AI Assistant and AI Hub tools</li>
            <li><strong>Safaricom M-PESA</strong> — payment processing for Kenyan customers</li>
            <li><strong>Stripe</strong> — card payment processing for international customers</li>
            <li><strong>SendGrid / Mailgun</strong> — transactional email delivery</li>
            <li><strong>Google Maps</strong> — location embed on our Contact page</li>
          </ul>
          <p>We encourage you to review the privacy policies of these services when they are relevant to your interaction with Krestworks.</p>
        </div>

        <div id="children" style="margin-bottom:2.5rem;">
          <h2>Children's Privacy</h2>
          <p>Our services are designed for business use by adults (18+). We do not knowingly collect personal data from children under 18. If we become aware that we have collected data from a minor without parental consent, we will delete it promptly. Contact us at <a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a> if you believe we have collected a child's data.</p>
          <p><em>Note: Our eLearning Management System is designed for institutions to manage student data. Any student data processed through that system is governed by the institution's own data policies, and the institution is the data controller.</em></p>
        </div>

        <div id="changes" style="margin-bottom:2.5rem;">
          <h2>Policy Changes</h2>
          <p>We may update this Privacy Policy from time to time to reflect changes in our practices, products, or legal requirements. When we make material changes, we will:</p>
          <ul>
            <li>Update the "Last Updated" date at the top of this page</li>
            <li>Notify registered users by email if the change is significant</li>
            <li>Display a notice on our website for 30 days after a significant update</li>
          </ul>
          <p>Continued use of our services after an update constitutes acceptance of the revised policy.</p>
        </div>

        <div id="contact-us" style="margin-bottom:2rem;">
          <h2>Contact Us</h2>
          <p>For privacy-related enquiries, data rights requests, or complaints:</p>
          <div class="kw-card" style="padding:1.5rem;display:flex;flex-wrap:wrap;gap:1.5rem;">
            <?php foreach ([
              ['fa-envelope','Email', COMPANY_EMAIL, 'mailto:'.COMPANY_EMAIL],
              ['fa-phone','Phone', COMPANY_PHONE, 'tel:'.COMPANY_PHONE],
              ['fa-map-marker-alt','Address', COMPANY_ADDRESS, null],
            ] as $c): ?>
            <div style="display:flex;align-items:flex-start;gap:0.65rem;">
              <i class="fa-solid <?= $c[0] ?>" style="color:var(--kw-primary);margin-top:0.15rem;width:16px;flex-shrink:0;"></i>
              <div>
                <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);"><?= $c[1] ?></div>
                <?php if ($c[3]): ?>
                <a href="<?= $c[3] ?>" style="font-size:0.875rem;color:var(--kw-primary);"><?= $c[2] ?></a>
                <?php else: ?>
                <div style="font-size:0.875rem;"><?= $c[2] ?></div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <p style="margin-top:1rem;font-size:0.82rem;color:var(--kw-text-muted);">We aim to respond to all privacy enquiries within 5 business days.</p>
        </div>

      </div>

    </div>
  </div>
</section>

<style>
.policy-content h2{font-size:1.25rem;font-weight:700;margin:0 0 0.85rem;padding-top:0.5rem;color:var(--kw-text-primary);}
.policy-content h3{font-size:1rem;font-weight:700;margin:1.5rem 0 0.65rem;color:var(--kw-text-primary);}
.policy-content p{font-size:0.9rem;line-height:1.8;color:var(--kw-text-secondary);margin-bottom:1rem;}
.policy-content ul,.policy-content ol{padding-left:1.4rem;margin-bottom:1rem;}
.policy-content li{font-size:0.9rem;line-height:1.7;color:var(--kw-text-secondary);margin-bottom:0.4rem;}
.policy-content a{color:var(--kw-primary);text-decoration:underline;}
.policy-content strong{color:var(--kw-text-primary);}
@media(max-width:1024px){.kw-container>div[style*="220px 1fr"]{grid-template-columns:1fr!important;}nav[style*="sticky"]{position:static!important;}}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>