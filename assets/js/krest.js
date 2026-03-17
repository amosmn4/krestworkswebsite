// ============================================================
// KRESTWORKS — CORE GLOBAL JAVASCRIPT
// ============================================================

const Krest = (() => {

  // ---- NAVIGATION ----
  function initNav() {
    const toggle    = document.getElementById('nav-toggle');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileClose = document.getElementById('mobile-nav-close');

    if (toggle && mobileNav) {
      toggle.addEventListener('click', () => {
        mobileNav.classList.add('open');
        document.body.classList.add('no-scroll');
        toggle.setAttribute('aria-expanded', 'true');
      });
    }

    const closeMenu = () => {
      mobileNav?.classList.remove('open');
      document.body.classList.remove('no-scroll');
      toggle?.setAttribute('aria-expanded', 'false');
    };

    mobileClose?.addEventListener('click', closeMenu);

    // Close on outside click
    mobileNav?.addEventListener('click', (e) => {
      if (e.target === mobileNav) closeMenu();
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeMenu();
    });

    // Mobile accordion submenus
    document.querySelectorAll('.mobile-nav-group-trigger').forEach(trigger => {
      trigger.addEventListener('click', () => {
        const group = trigger.closest('.mobile-nav-group');
        group?.classList.toggle('open');
      });
    });
  }

  // ---- TABS ----
  function initTabs() {
    document.querySelectorAll('.kw-tabs').forEach(tabGroup => {
      tabGroup.querySelectorAll('.kw-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          const target = btn.dataset.tab;
          const container = btn.closest('[data-tabs-container]') || document;

          // Deactivate all
          tabGroup.querySelectorAll('.kw-tab-btn').forEach(b => b.classList.remove('active'));
          container.querySelectorAll('.kw-tab-panel').forEach(p => p.classList.remove('active'));

          // Activate selected
          btn.classList.add('active');
          container.querySelector(`[data-tab-panel="${target}"]`)?.classList.add('active');
        });
      });

      // Init first tab
      tabGroup.querySelector('.kw-tab-btn')?.click();
    });
  }

  // ---- ACCORDIONS ----
  function initAccordions() {
    document.querySelectorAll('.kw-accordion-trigger').forEach(trigger => {
      trigger.addEventListener('click', () => {
        const item = trigger.closest('.kw-accordion-item');
        const isOpen = item.classList.contains('open');

        // Optional: close others in same group
        const group = item.closest('.kw-accordion-group');
        if (group) {
          group.querySelectorAll('.kw-accordion-item.open').forEach(openItem => {
            if (openItem !== item) openItem.classList.remove('open');
          });
        }

        item.classList.toggle('open', !isOpen);
      });
    });
  }

  // ---- MODALS ----
  function openModal(id) {
    const overlay = document.getElementById(id);
    if (!overlay) return;
    overlay.classList.add('open');
    document.body.classList.add('no-scroll');
    overlay.querySelector('[autofocus]')?.focus();
  }

  function closeModal(id) {
    const overlay = document.getElementById(id);
    if (!overlay) return;
    overlay.classList.remove('open');
    document.body.classList.remove('no-scroll');
  }

  function initModals() {
    // Trigger buttons
    document.querySelectorAll('[data-modal-open]').forEach(btn => {
      btn.addEventListener('click', () => openModal(btn.dataset.modalOpen));
    });

    // Close buttons
    document.querySelectorAll('[data-modal-close], .kw-modal-close').forEach(btn => {
      btn.addEventListener('click', () => {
        const overlay = btn.closest('.kw-modal-overlay');
        if (overlay) closeModal(overlay.id);
      });
    });

    // Close on overlay click
    document.querySelectorAll('.kw-modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModal(overlay.id);
      });
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.kw-modal-overlay.open').forEach(overlay => {
          closeModal(overlay.id);
        });
      }
    });
  }

  // ---- TOAST NOTIFICATIONS ----
  function toast(message, type = 'info', duration = 4000) {
    const container = document.getElementById('kw-toast-container');
    if (!container) return;

    const icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
    const toast = document.createElement('div');
    toast.className = `kw-toast ${type}`;
    toast.innerHTML = `<i class="fa-solid ${icons[type] || icons.info}"></i><span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(12px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, duration);
  }

  // ---- FORM HELPERS ----
  function getFormData(form) {
    const data = {};
    new FormData(form).forEach((val, key) => { data[key] = val.trim(); });
    return data;
  }

  function setFieldError(field, message) {
    field.classList.add('error');
    const existing = field.parentElement.querySelector('.kw-field-error');
    if (existing) existing.remove();
    if (message) {
      const err = document.createElement('div');
      err.className = 'kw-field-error';
      err.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${message}`;
      field.parentElement.appendChild(err);
    }
  }

  function clearFieldErrors(form) {
    form.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
    form.querySelectorAll('.kw-field-error').forEach(el => el.remove());
  }

  function setButtonLoading(btn, loading = true) {
    if (loading) {
      btn.dataset.origText = btn.innerHTML;
      btn.innerHTML = '<span class="kw-spinner" style="width:18px;height:18px;border-width:2px;vertical-align:middle;"></span> Processing...';
      btn.disabled = true;
    } else {
      if (btn.dataset.origText) btn.innerHTML = btn.dataset.origText;
      btn.disabled = false;
    }
  }

  async function submitForm(form, url, onSuccess, onError) {
    const submitBtn = form.querySelector('[type="submit"]');
    clearFieldErrors(form);
    if (submitBtn) setButtonLoading(submitBtn, true);

    try {
      const res = await fetch(url, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
      });

      const json = await res.json();

      if (json.success) {
        onSuccess?.(json);
      } else {
        if (json.fields) {
          Object.entries(json.fields).forEach(([name, msg]) => {
            const field = form.querySelector(`[name="${name}"]`);
            if (field) setFieldError(field, msg);
          });
        }
        onError?.(json);
        toast(json.message || 'Something went wrong.', 'error');
      }
    } catch (err) {
      console.error('Form submit error:', err);
      toast('Network error. Please try again.', 'error');
      onError?.(err);
    } finally {
      if (submitBtn) setButtonLoading(submitBtn, false);
    }
  }

  // ---- FLOATING WHATSAPP ----
  function initWhatsApp() {
    const btn = document.getElementById('kw-float-whatsapp');
    if (!btn) return;
    const number = btn.dataset.number || '254700000000';
    const message = encodeURIComponent('Hello Krestworks! I\'d like to learn more about your services.');
    btn.href = `https://wa.me/${number}?text=${message}`;
  }

  // ---- SEARCH ----
  function initSearch() {
    const searchInput = document.getElementById('site-search');
    if (!searchInput) return;

    let debounceTimer;
    searchInput.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        const q = searchInput.value.trim();
        if (q.length > 2) performSearch(q);
      }, 300);
    });
  }

  function performSearch(query) {
    // Implement global search — override per page as needed
    console.log('Search query:', query);
  }

  // ---- LAZY LOAD IMAGES ----
  function initLazyLoad() {
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) {
              img.src = img.dataset.src;
              img.removeAttribute('data-src');
            }
            observer.unobserve(img);
          }
        });
      });
      document.querySelectorAll('img[data-src]').forEach(img => observer.observe(img));
    }
  }

  // ---- SMOOTH SCROLL for anchor links ----
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', (e) => {
        const target = document.querySelector(anchor.getAttribute('href'));
        if (!target) return;
        e.preventDefault();
        const offset = 80;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      });
    });
  }

  // ---- INIT ----
  function init() {
    initNav();
    initTabs();
    initAccordions();
    initModals();
    initWhatsApp();
    initSearch();
    initLazyLoad();
    initSmoothScroll();
  }

  return { init, toast, openModal, closeModal, submitForm, setFieldError, clearFieldErrors, setButtonLoading, getFormData };
})();

document.addEventListener('DOMContentLoaded', Krest.init);

// Make globally accessible
window.Krest = Krest;