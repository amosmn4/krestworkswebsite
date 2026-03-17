<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'Terms & Conditions — ' . APP_NAME;
$page_description = 'Terms and conditions governing the use of Krestworks Solutions website, products, and services. Last updated March 2026.';

$lastUpdated = 'March 1, 2026';
?>

<section class="kw-page-hero" style="min-height:280px;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Terms & Conditions</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-file-contract"></i> Legal</span>
      <h1>Terms & Conditions</h1>
      <p style="color:rgba(255,255,255,0.55);font-size:0.875rem;">Last updated: <?= $lastUpdated ?> &nbsp;·&nbsp; Governed by the laws of Kenya</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:220px 1fr;gap:3rem;align-items:flex-start;">

      <!-- TOC -->
      <nav style="position:sticky;top:calc(var(--kw-nav-height)+1rem);" data-aos="fade-right">
        <div class="kw-card" style="padding:1.25rem;">
          <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Contents</div>
          <?php foreach ([
            ['#acceptance','Acceptance of Terms'],['#services','Our Services'],['#accounts','User Accounts'],
            ['#ip','Intellectual Property'],['#prohibited','Prohibited Uses'],['#subscriptions','Subscriptions & Payment'],
            ['#sla','Service Levels'],['#liability','Limitation of Liability'],['#indemnity','Indemnification'],
            ['#termination','Termination'],['#governing','Governing Law'],['#contact-us','Contact'],
          ] as $item): ?>
          <a href="<?= $item[0] ?>" style="display:block;padding:0.4rem 0.5rem;border-radius:4px;font-size:0.78rem;color:var(--kw-text-muted);text-decoration:none;line-height:1.4;transition:all 0.15s;"
             onmouseover="this.style.background='var(--kw-bg-alt)';this.style.color='var(--kw-primary)'"
             onmouseout="this.style.background='';this.style.color='var(--kw-text-muted)'"><?= $item[1] ?></a>
          <?php endforeach; ?>
        </div>
      </nav>

      <!-- Terms content -->
      <div class="policy-content" data-aos="fade-up">

        <div style="padding:1.25rem;background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);border-radius:var(--kw-radius-lg);margin-bottom:2rem;">
          <strong style="color:#3B82F6;"><i class="fa-solid fa-info-circle"></i> Summary:</strong> These Terms govern your use of the Krestworks website and all related services. By using our site or engaging our services, you agree to these terms. Read them fully — the key sections are Intellectual Property (§4), Limitation of Liability (§8), and Governing Law (§11).
        </div>

        <div id="acceptance" style="margin-bottom:2.5rem;">
          <h2>1. Acceptance of Terms</h2>
          <p>By accessing the Krestworks Solutions website at <?= BASE_URL ?> ("the Website"), using any of our software products, or engaging our professional services, you agree to be bound by these Terms and Conditions ("Terms"), our <a href="<?= url('privacy') ?>">Privacy Policy</a>, and any additional terms applicable to specific products or services.</p>
          <p>If you are using our services on behalf of an organisation, you represent that you have authority to bind that organisation to these Terms. "You" and "your" refer to you or that organisation.</p>
          <p>If you do not agree to these Terms, do not use our Website or services.</p>
        </div>

        <div id="services" style="margin-bottom:2.5rem;">
          <h2>2. Our Services</h2>
          <p>Krestworks Solutions provides enterprise software products, custom software development, system integration, AI tools, cloud infrastructure, and technology consulting services.</p>
          <p>We reserve the right to:</p>
          <ul>
            <li>Modify, suspend, or discontinue any service with reasonable notice</li>
            <li>Update pricing for subscription services with 30 days' written notice</li>
            <li>Refuse service to any party at our sole discretion</li>
            <li>Modify these Terms with notice as described in Section 12</li>
          </ul>
          <p>Our website and tools are provided on an "as is" basis without warranties of any kind beyond those expressly stated.</p>
        </div>

        <div id="accounts" style="margin-bottom:2.5rem;">
          <h2>3. User Accounts</h2>
          <p>When you register for a Krestworks client portal or community account, you agree to:</p>
          <ul>
            <li>Provide accurate, current, and complete registration information</li>
            <li>Maintain the security of your password and notify us immediately of any unauthorised access</li>
            <li>Take responsibility for all activities that occur under your account</li>
            <li>Not share account credentials with third parties</li>
            <li>Not register multiple accounts for the same individual or organisation without written permission</li>
          </ul>
          <p>We reserve the right to suspend or terminate accounts that violate these Terms, engage in prohibited conduct, or remain inactive for more than 12 months.</p>
        </div>

        <div id="ip" style="margin-bottom:2.5rem;">
          <h2>4. Intellectual Property</h2>
          <h3>Krestworks Content</h3>
          <p>The Krestworks website, brand (including the KC logo), blog content, documentation, and all AI tools are the intellectual property of Krestworks Solutions. You may not reproduce, distribute, or create derivative works without express written permission.</p>
          <h3>Custom Development Work</h3>
          <p>For custom software development projects, intellectual property ownership is governed by the specific project contract. Unless otherwise agreed in writing, all custom-built code, databases, and systems transfer to the client upon full payment of the agreed project fee.</p>
          <h3>Standard Products (SaaS)</h3>
          <p>Subscription access to Krestworks standard products grants a non-exclusive, non-transferable licence to use the software during the subscription period. The underlying platform, code, and architecture remain the intellectual property of Krestworks Solutions.</p>
          <h3>Your Data</h3>
          <p>You retain full ownership of all data you upload or create within Krestworks systems. We do not claim any ownership rights over your business data.</p>
        </div>

        <div id="prohibited" style="margin-bottom:2.5rem;">
          <h2>5. Prohibited Uses</h2>
          <p>You agree not to use our website or services to:</p>
          <ul>
            <li>Violate any applicable Kenyan or international law or regulation</li>
            <li>Transmit spam, phishing, or malicious content</li>
            <li>Attempt to gain unauthorised access to our systems, other accounts, or third-party systems</li>
            <li>Reverse engineer, decompile, or disassemble any Krestworks software</li>
            <li>Reproduce or distribute our AI tools without authorisation</li>
            <li>Submit false or misleading information in consultation or demo requests</li>
            <li>Engage in any activity that overloads, damages, or impairs our infrastructure</li>
            <li>Upload malicious code, viruses, or exploits</li>
            <li>Harass, abuse, or threaten other users on our community platform</li>
          </ul>
          <p>Violations may result in immediate account termination, legal action, or both.</p>
        </div>

        <div id="subscriptions" style="margin-bottom:2.5rem;">
          <h2>6. Subscriptions & Payment</h2>
          <p>All subscription fees are stated in Kenyan Shillings (KES) unless otherwise agreed. Prices are inclusive of applicable taxes.</p>
          <ul>
            <li><strong>Billing Cycle:</strong> Monthly or annual subscriptions are charged at the start of each billing period</li>
            <li><strong>Payment Methods:</strong> M-PESA, bank transfer, or card (Stripe). Payment is due within 7 days of invoice</li>
            <li><strong>Late Payment:</strong> Accounts more than 14 days overdue may be suspended. Service resumes within 24 hours of payment clearance</li>
            <li><strong>Refunds:</strong> Monthly subscriptions are non-refundable after the first 7 days. Annual subscriptions may receive a prorated refund for unused months with 30 days' notice</li>
            <li><strong>Price Changes:</strong> We will provide 30 days' written notice of any subscription price increases</li>
          </ul>
        </div>

        <div id="sla" style="margin-bottom:2.5rem;">
          <h2>7. Service Levels</h2>
          <p>For SaaS products, Krestworks targets:</p>
          <ul>
            <li><strong>Uptime:</strong> 99.5% monthly availability (excluding scheduled maintenance windows)</li>
            <li><strong>Scheduled Maintenance:</strong> Communicated at least 48 hours in advance, typically scheduled outside business hours (EAT)</li>
            <li><strong>Support Response Times:</strong> P1 (system down) — 2 hours; P2 (major feature unavailable) — 8 hours; P3 (minor issue) — 24 business hours</li>
          </ul>
          <p>Service credits for SLA breaches are available to clients on Enterprise plans per the specific SLA addendum. Standard subscription clients are not entitled to service credits.</p>
        </div>

        <div id="liability" style="margin-bottom:2.5rem;">
          <h2>8. Limitation of Liability</h2>
          <p>To the maximum extent permitted by Kenyan law, Krestworks Solutions shall not be liable for:</p>
          <ul>
            <li>Indirect, incidental, special, or consequential damages</li>
            <li>Loss of profits, revenue, data, or business opportunity</li>
            <li>Damages arising from third-party services or integrations</li>
            <li>Any damages exceeding the total fees paid to Krestworks in the 12 months preceding the claim</li>
          </ul>
          <p>This limitation applies even if Krestworks has been advised of the possibility of such damages. Some jurisdictions do not allow limitation of liability for certain damages, so this limitation may not apply to you in full.</p>
        </div>

        <div id="indemnity" style="margin-bottom:2.5rem;">
          <h2>9. Indemnification</h2>
          <p>You agree to indemnify and hold harmless Krestworks Solutions, its directors, employees, and agents from any claims, damages, losses, or expenses (including legal fees) arising from:</p>
          <ul>
            <li>Your use of our services in violation of these Terms</li>
            <li>Your violation of any third-party rights</li>
            <li>Content you submit to our community or support platforms</li>
            <li>Your breach of any applicable law</li>
          </ul>
        </div>

        <div id="termination" style="margin-bottom:2.5rem;">
          <h2>10. Termination</h2>
          <p><strong>By You:</strong> You may cancel a subscription at any time by contacting us or through the client portal. Access continues until the end of the current billing period.</p>
          <p><strong>By Krestworks:</strong> We may suspend or terminate your account immediately for material breach of these Terms, non-payment, or activity that poses a security risk to our systems or other clients.</p>
          <p><strong>Data on Termination:</strong> Upon account termination, you have 30 days to export your data. After this period, we will delete your data per our data retention schedule, subject to legal obligations requiring us to retain certain records.</p>
        </div>

        <div id="governing" style="margin-bottom:2.5rem;">
          <h2>11. Governing Law & Disputes</h2>
          <p>These Terms are governed by and construed in accordance with the laws of the Republic of Kenya. Any disputes arising from these Terms or your use of Krestworks services shall first be subject to good-faith negotiation. If unresolved within 30 days, disputes shall be submitted to mediation in Nairobi before any litigation.</p>
          <p>If mediation fails, disputes shall be subject to the exclusive jurisdiction of the courts of Nairobi, Kenya.</p>
        </div>

        <div id="contact-us" style="margin-bottom:2rem;">
          <h2>12. Contact & Changes</h2>
          <p>We may update these Terms from time to time. We will notify you of material changes by email or by posting a notice on our website. Continued use after notification constitutes acceptance of the revised Terms.</p>
          <p>For questions about these Terms:</p>
          <div class="kw-card" style="padding:1.25rem;display:flex;flex-wrap:wrap;gap:1.25rem;">
            <div style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.875rem;">
              <i class="fa-solid fa-envelope" style="color:var(--kw-primary);margin-top:0.15rem;width:14px;"></i>
              <a href="mailto:<?= COMPANY_EMAIL ?>"><?= COMPANY_EMAIL ?></a>
            </div>
            <div style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.875rem;">
              <i class="fa-solid fa-map-marker-alt" style="color:var(--kw-primary);margin-top:0.15rem;width:14px;"></i>
              <?= COMPANY_ADDRESS ?>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<style>
.policy-content h2{font-size:1.2rem;font-weight:700;margin:0 0 0.85rem;padding-top:0.5rem;color:var(--kw-text-primary);}
.policy-content h3{font-size:0.95rem;font-weight:700;margin:1.25rem 0 0.6rem;color:var(--kw-text-primary);}
.policy-content p{font-size:0.9rem;line-height:1.8;color:var(--kw-text-secondary);margin-bottom:1rem;}
.policy-content ul{padding-left:1.4rem;margin-bottom:1rem;}
.policy-content li{font-size:0.9rem;line-height:1.7;color:var(--kw-text-secondary);margin-bottom:0.4rem;}
.policy-content a{color:var(--kw-primary);text-decoration:underline;}
.policy-content strong{color:var(--kw-text-primary);}
@media(max-width:1024px){.kw-container>div[style*="220px 1fr"]{grid-template-columns:1fr!important;}nav[style*="sticky"]{position:static!important;}}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>