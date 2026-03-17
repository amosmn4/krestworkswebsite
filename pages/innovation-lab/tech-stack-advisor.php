<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Tech Stack Advisor — Innovation Lab — ' . APP_NAME;

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">Tech Stack Advisor</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-layer-group"></i> AI Tool</span>
      <h1>Tech Stack Advisor</h1>
      <p>Describe your project and constraints. Get a tailored technology stack recommendation with pros, cons, and implementation guidance.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container" style="max-width:900px;">

    <!-- Questionnaire -->
    <div class="kw-card" style="padding:2rem;margin-bottom:1.5rem;" id="questionnaire">
      <h3 style="margin-bottom:1.5rem;font-size:1rem;">Tell us about your project</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

        <div>
          <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--kw-text-muted);margin-bottom:0.4rem;display:block;">Project Type</label>
          <select id="q-type" style="width:100%;padding:0.6rem 0.85rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;">
            <option value="">-- Select --</option>
            <option>Web Application (SaaS)</option><option>Mobile Application</option><option>Enterprise System</option>
            <option>E-Commerce Platform</option><option>API / Backend Service</option><option>Data Analytics Platform</option>
            <option>Real-time Application</option><option>Content Management System</option>
          </select>
        </div>
        <div>
          <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--kw-text-muted);margin-bottom:0.4rem;display:block;">Expected Users</label>
          <select id="q-users" style="width:100%;padding:0.6rem 0.85rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;">
            <option value="">-- Select --</option>
            <option>Less than 100</option><option>100–1,000</option><option>1,000–10,000</option>
            <option>10,000–100,000</option><option>100,000+</option>
          </select>
        </div>
        <div>
          <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--kw-text-muted);margin-bottom:0.4rem;display:block;">Team Size</label>
          <select id="q-team" style="width:100%;padding:0.6rem 0.85rem;background:var(--kw-bg-alt);border:1px solid var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);">
            <option value="">-- Select --</option>
            <option>Solo Developer</option><option>2–5 developers</option><option>6–15 developers</option><option>15+ developers</option>
          </select>
        </div>
        <div>
          <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--kw-text-muted);margin-bottom:0.4rem;display:block;">Budget Level</label>
          <select id="q-budget" style="width:100%;padding:0.6rem 0.85rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;">
            <option value="">-- Select --</option>
            <option>Bootstrap / Minimal</option><option>Startup Budget</option><option>Mid-range</option><option>Enterprise Budget</option>
          </select>
        </div>

        <div style="grid-column:span 2;">
          <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--kw-text-muted);margin-bottom:0.4rem;display:block;">Key Requirements</label>
          <div style="display:flex;flex-wrap:wrap;gap:0.5rem;" id="req-checkboxes">
            <?php foreach (['Real-time updates','Offline support','AI/ML integration','High availability (99.9%+)','Multi-tenancy','Mobile-first','Heavy file storage','Complex reporting','Payment processing','Third-party integrations','Microservices ready','Open-source preferred'] as $r): ?>
            <label style="display:flex;align-items:center;gap:0.35rem;padding:0.35rem 0.75rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:999px;cursor:pointer;font-size:0.78rem;transition:all 0.15s;">
              <input type="checkbox" value="<?= $r ?>" style="width:12px;height:12px;"> <?= $r ?>
            </label>
            <?php endforeach; ?>
          </div>
        </div>

        <div style="grid-column:span 2;">
          <label style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:var(--kw-text-muted);margin-bottom:0.4rem;display:block;">Describe Your Project (optional)</label>
          <textarea id="q-desc" rows="3" placeholder="e.g. A hospital appointment booking system with patient records, doctor scheduling, and SMS notifications..."
                    style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:0.75rem 1rem;font-size:0.875rem;color:var(--kw-text-primary);outline:none;"
                    onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''"></textarea>
        </div>
      </div>

      <button onclick="TSA.getRecommendation()" id="get-stack-btn" class="kw-btn kw-btn-primary kw-btn-lg">
        <i class="fa-solid fa-layer-group"></i> Get Stack Recommendation
      </button>
    </div>

    <!-- Result -->
    <div id="stack-result" style="display:none;"></div>

    <!-- Popular Stacks Reference -->
    <div class="kw-card" style="padding:1.75rem;margin-top:1.5rem;">
      <h4 style="margin-bottom:1.25rem;font-size:0.9rem;"><i class="fa-solid fa-star" style="color:var(--kw-primary);"></i> Popular Stack Combinations</h4>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:0.85rem;">
        <?php foreach ([
          ['LAMP','Linux + Apache + MySQL + PHP','#F5A800','Traditional, proven, cPanel-friendly'],
          ['LEMP','Linux + Nginx + MySQL + PHP','#3B82F6','High-performance PHP stack'],
          ['MERN','MongoDB + Express + React + Node','#22C55E','Full JS, great for real-time apps'],
          ['Django Stack','Python + Django + PostgreSQL','#A855F7','Rapid development, built-in admin'],
          ['Laravel + Vue','PHP + Laravel + Vue.js + MySQL','#EF4444','Full-stack PHP SPA'],
          ['JAMstack','JS + APIs + Markup (Next.js)','#F97316','Static-first, CDN-distributed'],
        ] as $st): ?>
        <div style="padding:1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);border-top:3px solid <?= $st[2] ?>;">
          <div style="font-weight:800;font-size:0.9rem;color:<?= $st[2] ?>;margin-bottom:0.2rem;font-family:var(--font-heading);"><?= $st[0] ?></div>
          <div style="font-size:0.72rem;font-weight:600;color:var(--kw-text-secondary);margin-bottom:0.4rem;"><?= $st[1] ?></div>
          <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= $st[3] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<script>
const TSA = {
  async getRecommendation() {
    const type   = document.getElementById('q-type').value;
    const users  = document.getElementById('q-users').value;
    const team   = document.getElementById('q-team').value;
    const budget = document.getElementById('q-budget').value;
    const desc   = document.getElementById('q-desc').value.trim();
    const reqs   = [...document.querySelectorAll('#req-checkboxes input:checked')].map(c=>c.value);

    if (!type) { window.Krest?.toast('Please select a project type','warning'); return; }

    const btn = document.getElementById('get-stack-btn');
    btn.disabled = true;
    btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Analysing...';

    const prompt = `Tech stack recommendation request:
- Project type: ${type}
- Expected users: ${users||'Not specified'}
- Team size: ${team||'Not specified'}
- Budget: ${budget||'Not specified'}
- Key requirements: ${reqs.join(', ')||'None specified'}
- Description: ${desc||'Not provided'}

Provide a complete, specific technology stack recommendation with:
1. Recommended Stack (with specific technologies)
2. Why this stack fits their needs
3. Frontend, Backend, Database, Infrastructure breakdown
4. 3 pros and 2 potential challenges
5. Krestworks can help implement this (mention our services briefly)`;

    const result = document.getElementById('stack-result');
    result.style.display = 'block';
    result.innerHTML = '<div class="kw-card" style="padding:2rem;text-align:center;"><i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem;color:var(--kw-primary);"></i><p style="margin-top:1rem;color:var(--kw-text-muted);">Analysing your requirements and crafting a recommendation...</p></div>';
    result.scrollIntoView({behavior:'smooth',block:'start'});

    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({messages:[{role:'user',content:prompt}],system:'You are a senior software architect at Krestworks Solutions. Give practical, specific stack recommendations. Format your response clearly with headers and bullet points.'})
      });
      const data = await resp.json();
      const reply = (data.data?.reply||'Recommendation unavailable.').replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>').replace(/#{1,3} (.*?)(<br>|$)/g,'<h4 style="color:var(--kw-primary);margin:1rem 0 0.4rem;">$1</h4>');
      result.innerHTML = `
        <div class="kw-card" style="padding:2rem;border-top:3px solid var(--kw-primary);">
          <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--kw-border);">
            <div style="width:42px;height:42px;border-radius:10px;background:var(--kw-primary)20;display:flex;align-items:center;justify-content:center;">
              <i class="fa-solid fa-layer-group" style="color:var(--kw-primary);"></i>
            </div>
            <div><h3 style="margin:0;">Your Recommended Stack</h3><div style="font-size:0.78rem;color:var(--kw-text-muted);">Based on your project requirements</div></div>
          </div>
          <div style="font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.8;">${reply}</div>
          <div style="margin-top:1.75rem;padding-top:1.25rem;border-top:1px solid var(--kw-border);display:flex;gap:0.65rem;flex-wrap:wrap;">
            <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-calendar"></i> Book Architecture Consultation</a>
            <a href="<?= url('services/custom-software') ?>" class="kw-btn kw-btn-ghost">See Our Development Services</a>
          </div>
        </div>
      `;
    } catch(e) { result.innerHTML = '<div class="kw-card" style="padding:2rem;text-align:center;color:#EF4444;">Failed to get recommendation. Please try again.</div>'; }

    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-layer-group"></i> Get Stack Recommendation';
  }
};

// Style checkbox labels on click
document.querySelectorAll('#req-checkboxes label').forEach(lbl => {
  lbl.addEventListener('change', () => {
    lbl.style.borderColor = lbl.querySelector('input').checked ? 'var(--kw-primary)' : '';
    lbl.style.background  = lbl.querySelector('input').checked ? 'var(--kw-primary)10' : '';
  });
});
</script>
<style>@media(max-width:768px){#questionnaire>div{grid-template-columns:1fr!important;}}</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>