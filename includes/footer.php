<?php require_once __DIR__ . '/helpers.php'; ?>

<!-- Floating Buttons -->
<div id="kw-floating-btns" aria-label="Quick contact buttons">
  <a id="kw-float-whatsapp" href="https://wa.me/<?= e(WHATSAPP_NUMBER) ?>"
     class="kw-float-btn kw-float-whatsapp"
     data-number="<?= e(WHATSAPP_NUMBER) ?>"
     target="_blank" rel="noopener noreferrer"
     aria-label="Chat on WhatsApp"
     title="Chat on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
  </a>

  <button id="kw-float-ai"
          class="kw-float-btn kw-float-ai"
          aria-label="Open Krest AI Assistant"
          title="Talk to Krest AI">
    <i class="fa-solid fa-robot"></i>
  </button>
</div>

<!-- Krest AI Chat Widget -->
<div id="kw-ai-chat" role="dialog" aria-modal="true" aria-label="Krest AI Assistant">
  <div class="ai-chat-header">
    <div class="ai-chat-avatar"><i class="fa-solid fa-robot"></i></div>
    <div class="ai-chat-info">
      <div class="ai-chat-name">Krest AI</div>
      <div class="ai-chat-status">Online — Powered by AI</div>
    </div>
    <button id="ai-chat-close" class="ai-chat-close" aria-label="Close chat">
      <i class="fa-solid fa-times"></i>
    </button>
  </div>
  <div id="ai-chat-messages" class="ai-chat-messages" aria-live="polite"></div>
  <div class="ai-chat-input-area">
    <textarea id="ai-chat-input"
              placeholder="Ask me anything about Krestworks..."
              rows="1"
              aria-label="Message input"
              maxlength="1000"></textarea>
    <button id="ai-chat-send" class="ai-chat-send" aria-label="Send message">
      <i class="fa-solid fa-paper-plane"></i>
    </button>
  </div>
</div>

<!-- FOOTER -->
<footer id="kw-footer" aria-label="Site footer">
  <div class="kw-container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-brand">
        <a href="<?= url() ?>" class="nav-logo">
          <img src="<?= asset('img/logo.png') ?>" alt="Krestworks Logo" width="38" height="38">
          <span class="nav-logo-text" style="color:#fff;">Krest<span>works</span></span>
        </a>
        <p>Building AI-powered enterprise software that transforms how businesses operate, grow, and compete in the digital era.</p>
        <div class="footer-socials">
          <a href="<?= e(SOCIAL_LINKEDIN) ?>"  class="footer-social-link" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="<?= e(SOCIAL_TWITTER) ?>"   class="footer-social-link" target="_blank" rel="noopener" aria-label="Twitter/X"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="<?= e(SOCIAL_GITHUB) ?>"    class="footer-social-link" target="_blank" rel="noopener" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
          <a href="<?= e(SOCIAL_YOUTUBE) ?>"   class="footer-social-link" target="_blank" rel="noopener" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          <a href="<?= e(SOCIAL_INSTAGRAM) ?>" class="footer-social-link" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="https://wa.me/<?= e(WHATSAPP_NUMBER) ?>" class="footer-social-link" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
        </div>
      </div>

      <!-- Services -->
      <div class="footer-col">
        <h4>Services</h4>
        <ul>
          <li><a href="<?= url('services/custom-software') ?>">Custom Software</a></li>
          <li><a href="<?= url('services/web-development') ?>">Web Development</a></li>
          <li><a href="<?= url('services/mobile-development') ?>">Mobile Apps</a></li>
          <li><a href="<?= url('services/ai-solutions') ?>">AI Solutions</a></li>
          <li><a href="<?= url('services/system-integration') ?>">System Integration</a></li>
          <li><a href="<?= url('services/cloud-infrastructure') ?>">Cloud Infrastructure</a></li>
          <li><a href="<?= url('services/cybersecurity') ?>">Cybersecurity</a></li>
          <li><a href="<?= url('services/consulting') ?>">Consulting</a></li>
        </ul>
      </div>

      <!-- Products -->
      <div class="footer-col">
        <h4>Products</h4>
        <ul>
          <li><a href="<?= url('products/hr-system') ?>">HR Management</a></li>
          <li><a href="<?= url('products/procurement-system') ?>">Procurement</a></li>
          <li><a href="<?= url('products/elearning-system') ?>">eLearning LMS</a></li>
          <li><a href="<?= url('products/real-estate-system') ?>">Real Estate</a></li>
          <li><a href="<?= url('products/supply-chain-system') ?>">Supply Chain</a></li>
          <li><a href="<?= url('products/crm-system') ?>">CRM</a></li>
          <li><a href="<?= url('products/hospital-system') ?>">Hospital System</a></li>
          <li><a href="<?= url('products') ?>">All Products →</a></li>
        </ul>
      </div>

      <!-- Platform -->
      <div class="footer-col">
        <h4>Platform</h4>
        <ul>
          <li><a href="<?= url('ai-hub') ?>">AI Hub</a></li>
          <li><a href="<?= url('innovation-lab') ?>">Innovation Lab</a></li>
          <li><a href="<?= url('community') ?>">Community</a></li>
          <li><a href="<?= url('demo') ?>">Demo Center</a></li>
          <li><a href="<?= url('pricing') ?>">Pricing</a></li>
          <li><a href="<?= url('portal') ?>">Client Portal</a></li>
          <li><a href="<?= url('consultation') ?>">Consultation</a></li>
          <li><a href="<?= url('blog') ?>">Blog & Insights</a></li>
        </ul>
      </div>

      <!-- Connect -->
      <div class="footer-col">
        <h4>Connect</h4>
        <ul>
          <li>
            <a href="mailto:<?= e(COMPANY_EMAIL) ?>">
              <i class="fa-solid fa-envelope" style="color:var(--kw-primary);width:14px;"></i>
              <?= e(COMPANY_EMAIL) ?>
            </a>
          </li>
          <li>
            <a href="tel:<?= e(COMPANY_PHONE) ?>">
              <i class="fa-solid fa-phone" style="color:var(--kw-primary);width:14px;"></i>
              <?= e(COMPANY_PHONE) ?>
            </a>
          </li>
          <li>
            <a href="https://wa.me/<?= e(WHATSAPP_NUMBER) ?>" target="_blank" rel="noopener">
              <i class="fa-brands fa-whatsapp" style="color:#25D366;width:14px;"></i>
              WhatsApp Us
            </a>
          </li>
          <li>
            <a href="<?= url('contact') ?>">
              <i class="fa-solid fa-location-dot" style="color:var(--kw-primary);width:14px;"></i>
              <?= e(COMPANY_ADDRESS) ?>
            </a>
          </li>
        </ul>

        <!-- Newsletter -->
        <div style="margin-top:1.5rem;">
          <p style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-bottom:0.6rem;">Stay updated with our latest insights</p>
          <form id="footer-newsletter" style="display:flex;gap:0.4rem;">
            <input type="email" name="email" placeholder="Your email"
                   style="flex:1;padding:0.55rem 0.85rem;border-radius:var(--kw-radius-pill);border:1px solid rgba(255,255,255,0.12);background:rgba(255,255,255,0.06);color:#fff;font-size:0.8rem;outline:none;"
                   aria-label="Newsletter email">
            <button type="submit" class="kw-btn kw-btn-primary kw-btn-sm" style="padding:0.55rem 1rem;">
              <i class="fa-solid fa-arrow-right"></i>
            </button>
          </form>
        </div>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <p class="footer-bottom-text">
        &copy; <?= date('Y') ?> <span><?= e(APP_NAME) ?></span>. All rights reserved. Built with ❤️ in Kenya.
      </p>
      <nav class="footer-legal" aria-label="Legal links">
        <a href="<?= url('privacy') ?>">Privacy Policy</a>
        <a href="<?= url('terms') ?>">Terms of Service</a>
        <a href="<?= url('faq') ?>">FAQ</a>
        <a href="https://system.krestworks.com" target="_blank" rel="noopener noreferrer">HRMS</a>
      </nav>
    </div>
  </div>
</footer>

<!-- CDN Libraries (deferred) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" crossorigin="anonymous" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" crossorigin="anonymous" defer></script>

<!-- Krestworks Scripts -->
<script src="<?= asset('js/krest-theme.js') ?>" defer></script>
<script src="<?= asset('js/animations.js') ?>" defer></script>
<script src="<?= asset('js/krest.js') ?>" defer></script>
<script src="<?= asset('js/krest-ai-widget.js') ?>" defer></script>

<?php if (!empty($extra_js)): ?>
  <?php foreach ($extra_js as $js): ?>
    <script src="<?= e($js) ?>" defer></script>
  <?php endforeach; ?>
<?php endif; ?>

<!-- Footer newsletter inline handler -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('footer-newsletter');
  if (!form) return;
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const email = form.querySelector('[name="email"]').value.trim();
    if (!email) return;
    try {
      const res = await fetch('/api/newsletter', {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
      });
      const data = await res.json();
      if (data.success) {
        window.Krest?.toast('Subscribed! Welcome to Krestworks insights.', 'success');
        form.reset();
      } else {
        window.Krest?.toast(data.message || 'Something went wrong.', 'error');
      }
    } catch {
      window.Krest?.toast('Network error. Please try again.', 'error');
    }
  });
});
</script>

</body>
</html>