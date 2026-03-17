// ============================================================
// KRESTWORKS — THEME MANAGER (Light / Dark toggle)
// ============================================================

const KrestTheme = (() => {
  const STORAGE_KEY = 'kw_theme';
  const DARK_CLASS  = 'dark-theme';
  const root        = document.documentElement;

  function getStored()   { return localStorage.getItem(STORAGE_KEY); }
  function getPreferred(){ return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'; }
  function getCurrent()  { return document.body.classList.contains(DARK_CLASS) ? 'dark' : 'light'; }

  function applyTheme(theme) {
    if (theme === 'dark') {
      document.body.classList.add(DARK_CLASS);
    } else {
      document.body.classList.remove(DARK_CLASS);
    }
    updateToggleIcon(theme);
    localStorage.setItem(STORAGE_KEY, theme);
    document.dispatchEvent(new CustomEvent('kw:themeChange', { detail: { theme } }));
  }

  function updateToggleIcon(theme) {
    const btn = document.getElementById('theme-toggle');
    if (!btn) return;
    btn.innerHTML = theme === 'dark'
      ? '<i class="fa-solid fa-sun"></i>'
      : '<i class="fa-solid fa-moon"></i>';
    btn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode');
  }

  function toggle() {
    applyTheme(getCurrent() === 'dark' ? 'light' : 'dark');
  }

  function init() {
    const stored = getStored();
    applyTheme(stored || getPreferred());

    const btn = document.getElementById('theme-toggle');
    if (btn) btn.addEventListener('click', toggle);
  }

  return { init, toggle, getCurrent, applyTheme };
})();

document.addEventListener('DOMContentLoaded', KrestTheme.init);