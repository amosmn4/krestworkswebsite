<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Software Decision Simulator — Innovation Lab — ' . APP_NAME;

$scenarios = [
  ['Choose a Database','You are building a new SaaS platform expecting 50,000 daily active users with complex relational data and reporting needs.',
   [['MySQL/PostgreSQL','Mature RDBMS, excellent for relational data, strong ecosystem, easy for team'],
    ['MongoDB','Flexible document store, fast for reads, good for unstructured data'],
    ['Redis','In-memory, blazing fast, not designed for primary persistent storage'],
    ['Firebase','Managed, real-time, great for mobile, limited complex queries']],'MySQL/PostgreSQL'],
  ['Deployment Strategy','Your HR system upgrade is ready. It handles payroll for 2,000 employees. You need minimal downtime.',
   [['Blue-Green Deployment','Two identical environments, instant switch, easy rollback'],
    ['Big Bang Cutover','Switch all at once on a weekend, fast but high risk'],
    ['Rolling Update','Gradually replace instances, balanced risk, some complexity'],
    ['Feature Flags','Release incrementally by user group, controlled exposure']],'Blue-Green Deployment'],
  ['API Architecture','Building an API that 3 different client apps (web, mobile, desktop) will consume with different data needs.',
   [['REST API','Standard, well-understood, each endpoint returns fixed data'],
    ['GraphQL','Clients request exact data needed, reduces over/under-fetching'],
    ['gRPC','High performance, binary protocol, best for internal microservices'],
    ['SOAP','Strict contract, enterprise legacy, heavy overhead']],'GraphQL'],
  ['Authentication Method','You are building a B2B SaaS where corporate clients need SSO and you serve individual users too.',
   [['JWT + OAuth 2.0','Stateless, scalable, supports SSO via OAuth providers'],
    ['Session-based Auth','Server stores sessions, simple, stateful, harder to scale'],
    ['API Key Only','Simple for APIs, but lacks user-level auth for web apps'],
    ['Magic Link','Passwordless, great UX, but requires reliable email delivery']],'JWT + OAuth 2.0'],
  ['Caching Strategy','Your product listing page makes 15 DB queries and loads slowly at 4+ seconds under load.',
   [['Redis Cache','In-memory, sub-millisecond reads, TTL support, perfect for this'],
    ['Database Query Cache','Built-in MySQL cache, deprecated in MySQL 8, limited'],
    ['CDN Only','Good for static assets, not for dynamic query results'],
    ['No Caching — Optimize Queries','May help but will not achieve target at scale']],'Redis Cache'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">Decision Simulator</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-scale-balanced"></i> Decision Training</span>
      <h1>Software Decision Simulator</h1>
      <p>Practice real architectural and technical decisions that developers and CTOs face. Choose your approach, see consequences, and learn from AI analysis.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container" style="max-width:860px;">

    <!-- Score header -->
    <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:2rem;padding:1rem 1.5rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);">
      <div>
        <div style="font-size:0.72rem;color:var(--kw-text-muted);margin-bottom:0.2rem;">SCENARIO</div>
        <div id="scenario-counter" style="font-size:1.1rem;font-weight:800;font-family:var(--font-heading);">1 / <?= count($scenarios) ?></div>
      </div>
      <div style="width:1px;height:40px;background:var(--kw-border);"></div>
      <div>
        <div style="font-size:0.72rem;color:var(--kw-text-muted);margin-bottom:0.2rem;">SCORE</div>
        <div id="score-display" style="font-size:1.1rem;font-weight:800;font-family:var(--font-heading);color:var(--kw-primary);">0 pts</div>
      </div>
      <div style="width:1px;height:40px;background:var(--kw-border);"></div>
      <div style="flex:1;">
        <div style="font-size:0.72rem;color:var(--kw-text-muted);margin-bottom:0.35rem;">PROGRESS</div>
        <div style="height:6px;background:var(--kw-border);border-radius:999px;overflow:hidden;">
          <div id="sim-progress" style="height:100%;background:var(--kw-primary);border-radius:999px;transition:width 0.4s;width:20%;"></div>
        </div>
      </div>
      <button onclick="DS.reset()" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-redo"></i> Restart</button>
    </div>

    <!-- Scenario panel -->
    <div id="scenario-panel" class="kw-card" style="padding:2rem;"></div>

    <!-- Result panel (hidden) -->
    <div id="result-panel" style="display:none;"></div>

  </div>
</section>

<script>
const SCENARIOS = <?= json_encode(array_map(fn($s)=>['title'=>$s[0],'scenario'=>$s[1],'options'=>$s[2],'correct'=>$s[3]], $scenarios)) ?>;
let score=0, current=0, answered=false;

const DS = {
  render() {
    if (current >= SCENARIOS.length) { this.showFinal(); return; }
    answered = false;
    const s = SCENARIOS[current];
    document.getElementById('scenario-counter').textContent = `${current+1} / ${SCENARIOS.length}`;
    document.getElementById('sim-progress').style.width = ((current+1)/SCENARIOS.length*100)+'%';

    document.getElementById('scenario-panel').innerHTML = `
      <div style="margin-bottom:1.5rem;">
        <span style="background:var(--kw-primary)15;color:var(--kw-primary);border:1px solid var(--kw-primary)30;border-radius:999px;padding:0.2rem 0.75rem;font-size:0.72rem;font-weight:700;text-transform:uppercase;margin-bottom:0.65rem;display:inline-block;">Decision ${current+1}</span>
        <h2 style="margin-bottom:0.75rem;">${s.title}</h2>
        <div style="background:var(--kw-bg-alt);border-left:4px solid var(--kw-primary);border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;padding:1rem 1.25rem;font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.7;">
          <strong>Scenario:</strong> ${s.scenario}
        </div>
      </div>
      <h4 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:1rem;">Choose your approach:</h4>
      <div style="display:flex;flex-direction:column;gap:0.75rem;" id="options-list">
        ${s.options.map((opt,i)=>`
          <button class="option-btn" data-choice="${opt[0]}" data-correct="${s.correct}"
                  onclick="DS.choose(this)"
                  style="text-align:left;padding:1rem 1.25rem;background:var(--kw-bg-alt);border:1.5px solid var(--kw-border);border-radius:var(--kw-radius-md);cursor:pointer;transition:all 0.2s;width:100%;"
                  onmouseover="if(!this.disabled){this.style.borderColor='var(--kw-primary)';this.style.background='var(--kw-primary)08';}"
                  onmouseout="if(!this.disabled){this.style.borderColor='var(--kw-border)';this.style.background='';}">
            <div style="display:flex;align-items:center;gap:0.85rem;">
              <div style="width:28px;height:28px;border-radius:50%;border:2px solid var(--kw-border);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;flex-shrink:0;color:var(--kw-text-muted);">${String.fromCharCode(65+i)}</div>
              <div>
                <div style="font-size:0.875rem;font-weight:700;color:var(--kw-text-primary);">${opt[0]}</div>
                <div style="font-size:0.78rem;color:var(--kw-text-muted);margin-top:0.15rem;">${opt[1]}</div>
              </div>
            </div>
          </button>
        `).join('')}
      </div>
      <div id="choice-feedback" style="display:none;margin-top:1.5rem;"></div>
    `;
    document.getElementById('result-panel').style.display='none';
  },

  async choose(btn) {
    if (answered) return;
    answered = true;
    const choice  = btn.dataset.choice;
    const correct = btn.dataset.correct;
    const isRight = choice === correct;
    if (isRight) score += 20;
    document.getElementById('score-display').textContent = score + ' pts';

    // Style all buttons
    document.querySelectorAll('.option-btn').forEach(b => {
      b.disabled = true;
      b.style.cursor = 'default';
      if (b.dataset.choice === correct) {
        b.style.borderColor = '#22C55E'; b.style.background = '#22C55E15';
        b.querySelector('div>div:first-child').style.borderColor='#22C55E';
        b.querySelector('div>div:first-child').style.color='#22C55E';
      } else if (b === btn && !isRight) {
        b.style.borderColor='#EF4444'; b.style.background='#EF444415';
      }
    });

    const fb = document.getElementById('choice-feedback');
    fb.style.display = 'block';
    fb.innerHTML = `
      <div style="padding:1rem 1.25rem;background:${isRight?'#22C55E':'#EF4444'}10;border:1px solid ${isRight?'#22C55E':'#EF4444'}30;border-radius:var(--kw-radius-md);margin-bottom:0.85rem;">
        <div style="font-weight:700;color:${isRight?'#22C55E':'#EF4444'};margin-bottom:0.35rem;">
          ${isRight?'✅ Excellent choice! +20 points':'❌ Not the optimal choice'}
        </div>
        <div style="font-size:0.85rem;color:var(--kw-text-secondary);">The best answer is: <strong>${correct}</strong></div>
      </div>
      <div id="ai-explanation" style="padding:1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.82rem;color:var(--kw-text-muted);">
        <i class="fa-solid fa-spinner fa-spin" style="color:var(--kw-primary);"></i> Getting AI analysis...
      </div>
      <div style="margin-top:1rem;display:flex;gap:0.65rem;">
        <button onclick="DS.next()" class="kw-btn kw-btn-primary">${current<SCENARIOS.length-1?'Next Scenario →':'See Final Score →'}</button>
      </div>
    `;

    // AI explanation
    try {
      const s = SCENARIOS[current];
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({messages:[{role:'user',content:`Technical decision: "${s.title}". Scenario: ${s.scenario}. Chosen: ${choice}. Best answer: ${correct}. Explain in 3-4 sentences why ${correct} is optimal for this specific scenario, and when ${choice} would be appropriate instead.`}],system:'You are a senior software architect. Be concise and practical.'})
      });
      const data = await resp.json();
      document.getElementById('ai-explanation').innerHTML = (data.data?.reply||'Analysis unavailable.').replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong style="color:var(--kw-text-primary);">$1</strong>');
    } catch(e) { document.getElementById('ai-explanation').textContent = 'AI analysis unavailable.'; }
  },

  next() { current++; this.render(); },
  reset() { score=0; current=0; answered=false; document.getElementById('score-display').textContent='0 pts'; this.render(); },
  showFinal() {
    const pct = score;
    const grade = pct>=80?['🏆 Expert Architect','#22C55E']:pct>=60?['👍 Solid Engineer','#F5A800']:pct>=40?['📚 Keep Learning','#3B82F6']:['🔄 Try Again','#EF4444'];
    document.getElementById('scenario-panel').style.display='none';
    document.getElementById('result-panel').style.display='block';
    document.getElementById('result-panel').innerHTML=`
      <div class="kw-card" style="padding:3rem;text-align:center;">
        <div style="font-size:3rem;margin-bottom:0.75rem;">${grade[0].split(' ')[0]}</div>
        <h2 style="color:${grade[1]};margin-bottom:0.5rem;">${grade[0].substring(2)}</h2>
        <div style="font-size:2.5rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);margin-bottom:0.5rem;">${score}/100</div>
        <p style="color:var(--kw-text-muted);margin-bottom:2rem;">You scored ${score} points across ${SCENARIOS.length} architectural decisions.</p>
        <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
          <button onclick="DS.reset()" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-redo"></i> Retake Simulation</button>
          <a href="<?= url('innovation-lab') ?>" class="kw-btn kw-btn-ghost kw-btn-lg">Back to Lab</a>
          <a href="<?= url('community') ?>" class="kw-btn kw-btn-ghost kw-btn-lg"><i class="fa-solid fa-users"></i> Discuss in Community</a>
        </div>
      </div>
    `;
  }
};

DS.render();
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>