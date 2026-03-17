// ============================================================
// KRESTWORKS — ANIMATIONS & SCROLL EFFECTS
// ============================================================

const KrestAnimations = (() => {

  // ---- Counter animation ----
  function animateCounter(el) {
    const target = parseInt(el.dataset.target || el.textContent, 10);
    const suffix = el.dataset.suffix || '';
    const duration = 1800;
    const start = performance.now();

    function update(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
      el.textContent = Math.round(eased * target) + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }

    requestAnimationFrame(update);
  }

  // ---- Intersection Observer for counters ----
  function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.dataset.counted) {
          entry.target.dataset.counted = 'true';
          animateCounter(entry.target);
        }
      });
    }, { threshold: 0.5 });

    counters.forEach(el => observer.observe(el));
  }

  // ---- Sticky nav ----
  function initStickyNav() {
    const nav = document.getElementById('kw-nav');
    if (!nav) return;

    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
  }

  // ---- Smooth section reveals (fallback if AOS not loaded) ----
  function initReveal() {
    if (window.AOS) return; // AOS handles it
    const els = document.querySelectorAll('[data-aos]');
    if (!els.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    els.forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
      observer.observe(el);
    });
  }

  // ---- Hero canvas particle network ----
  function initHeroCanvas() {
    const canvas = document.getElementById('hero-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let w, h, particles = [], animFrame;

    const config = {
      count: 70,
      maxDist: 130,
      speed: 0.4,
      dotColor: 'rgba(245,168,0,',
      lineColor: 'rgba(245,168,0,',
    };

    function resize() {
      w = canvas.width  = canvas.offsetWidth;
      h = canvas.height = canvas.offsetHeight;
    }

    function Particle() {
      this.x  = Math.random() * w;
      this.y  = Math.random() * h;
      this.vx = (Math.random() - 0.5) * config.speed;
      this.vy = (Math.random() - 0.5) * config.speed;
      this.r  = Math.random() * 1.5 + 1;
    }

    function initParticles() {
      particles = Array.from({ length: config.count }, () => new Particle());
    }

    function draw() {
      ctx.clearRect(0, 0, w, h);

      // Update positions
      particles.forEach(p => {
        p.x += p.vx;
        p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;

        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = config.dotColor + '0.6)';
        ctx.fill();
      });

      // Draw connections
      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const dx = particles[i].x - particles[j].x;
          const dy = particles[i].y - particles[j].y;
          const dist = Math.sqrt(dx * dx + dy * dy);

          if (dist < config.maxDist) {
            const alpha = (1 - dist / config.maxDist) * 0.25;
            ctx.beginPath();
            ctx.moveTo(particles[i].x, particles[i].y);
            ctx.lineTo(particles[j].x, particles[j].y);
            ctx.strokeStyle = config.lineColor + alpha + ')';
            ctx.lineWidth = 0.8;
            ctx.stroke();
          }
        }
      }

      animFrame = requestAnimationFrame(draw);
    }

    const ro = new ResizeObserver(() => { resize(); initParticles(); });
    ro.observe(canvas.parentElement);

    resize();
    initParticles();
    draw();

    // Pause when tab not visible
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) cancelAnimationFrame(animFrame);
      else draw();
    });
  }

  // ---- GSAP hero text (if GSAP available) ----
  function initGSAPHero() {
    if (typeof gsap === 'undefined') return;

    gsap.from('.hero-badge', { opacity: 0, y: 20, duration: 0.6, delay: 0.2 });
    gsap.from('.hero-title', { opacity: 0, y: 30, duration: 0.7, delay: 0.4 });
    gsap.from('.hero-subtitle', { opacity: 0, y: 20, duration: 0.6, delay: 0.6 });
    gsap.from('.hero-actions', { opacity: 0, y: 20, duration: 0.5, delay: 0.8 });
    gsap.from('.hero-stats', { opacity: 0, y: 20, duration: 0.5, delay: 1.0 });
  }

  function init() {
    initStickyNav();
    initCounters();
    initReveal();
    initHeroCanvas();

    // Init GSAP after DOM ready
    window.addEventListener('load', initGSAPHero);

    // Init AOS if available
    if (window.AOS) {
      AOS.init({ duration: 600, once: true, offset: 60, easing: 'ease-out-cubic' });
    }
  }

  return { init, animateCounter, initHeroCanvas };
})();

document.addEventListener('DOMContentLoaded', KrestAnimations.init);