<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'SDLC Simulator — Innovation Lab — ' . APP_NAME;

$phases = [
  ['fa-lightbulb','#F5A800','Planning','Define project scope, objectives, feasibility, resources, timeline, and budget.',
   ['Project Charter','Feasibility Study','Risk Assessment','Resource Plan','Timeline & Milestones'],
   ['What problem are we solving?','Who are the stakeholders?','Is it technically feasible?','What is the budget range?','What is the deadline?']],
  ['fa-magnifying-glass','#3B82F6','Requirements Analysis','Gather and document functional and non-functional requirements from stakeholders.',
   ['Business Requirements','Functional Specs','Non-Functional Specs','Use Case Diagrams','Data Flow Diagrams'],
   ['Who are the end users?','What must the system do?','What are performance requirements?','What integrations are needed?','What are the security requirements?']],
  ['fa-pencil-ruler','#A855F7','System Design','Architect the system — database design, UI wireframes, API design, tech stack selection.',
   ['System Architecture','Database Schema','API Design','UI/UX Wireframes','Tech Stack Decision'],
   ['Monolith or microservices?','What database engine to use?','REST or GraphQL API?','What frontend framework?','How to handle authentication?']],
  ['fa-code','#22C55E','Development','Write code, build features, conduct code reviews, manage version control.',
   ['Frontend Development','Backend Development','Database Implementation','API Development','Code Review Process'],
   ['What coding standards to follow?','How to structure the codebase?','What testing approach?','How to handle CI/CD?','How to manage branches?']],
  ['fa-vials','#F97316','Testing','Unit testing, integration testing, UAT, performance testing, security testing.',
   ['Unit Testing','Integration Testing','User Acceptance Testing','Performance Testing','Security Testing'],
   ['What test coverage target?','Who performs UAT?','What load can the system handle?','How to test for SQL injection?','What testing tools to use?']],
  ['fa-rocket','#EF4444','Deployment','Deploy to production, configure servers, monitor rollout, train users.',
   ['Deployment Plan','Server Configuration','Data Migration','User Training','Rollback Plan'],
   ['Phased or full rollout?','Cloud or on-premise?','How to migrate existing data?','Who trains the users?','What is the rollback procedure?']],
  ['fa-wrench','#6B7280','Maintenance','Monitor, patch, update, optimize, and add features post-launch.',
   ['Bug Fixes','Performance Monitoring','Security Updates','Feature Enhancements','Documentation Updates'],
   ['How to handle bug reports?','What monitoring tools to use?','How to manage security patches?','How to prioritize new features?','Who owns the documentation?']],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">SDLC Simulator</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-diagram-project"></i> Learning Tool</span>
      <h1>SDLC Interactive Simulator</h1>
      <p>Walk through all 7 phases of the Software Development Life Cycle interactively. Make decisions, see consequences, and get AI guidance on each phase.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container">

    <!-- Phase Timeline -->
    <div style="display:flex;align-items:center;overflow-x:auto;gap:0;margin-bottom:2.5rem;padding-bottom:0.5rem;" data-aos="fade-up">
      <?php foreach ($phases as $i => $ph): ?>
      <div style="display:flex;align-items:center;flex-shrink:0;">
        <div class="phase-step" data-phase="<?= $i ?>" onclick="SDLC.goTo(<?= $i ?>)"
             style="display:flex;flex-direction:column;align-items:center;cursor:pointer;padding:0 0.5rem;">
          <div class="phase-dot" style="width:44px;height:44px;border-radius:50%;border:2.5px solid <?= $ph[1] ?>40;background:var(--kw-bg-card);display:flex;align-items:center;justify-content:center;transition:all 0.3s;margin-bottom:0.4rem;">
            <i class="fa-solid <?= $ph[0] ?>" style="font-size:0.9rem;color:<?= $ph[1] ?>80;"></i>
          </div>
          <span style="font-size:0.62rem;font-weight:600;color:var(--kw-text-muted);white-space:nowrap;text-align:center;"><?= $ph[2] ?></span>
        </div>
        <?php if ($i < count($phases)-1): ?>
        <div style="width:32px;height:2px;background:var(--kw-border);flex-shrink:0;"></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Phase Content -->
    <div style="display:grid;grid-template-columns:1fr 340px;gap:2rem;align-items:flex-start;">

      <!-- Main phase panel -->
      <div id="phase-panel" class="kw-card" style="padding:2rem;min-height:400px;"></div>

      <!-- Sidebar -->
      <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:calc(var(--kw-nav-height)+1rem);">

        <!-- Progress -->
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Simulation Progress</h5>
          <div id="progress-bar-wrap" style="height:8px;background:var(--kw-border);border-radius:999px;margin-bottom:0.6rem;overflow:hidden;">
            <div id="progress-bar" style="height:100%;background:var(--kw-primary);border-radius:999px;transition:width 0.4s;width:0%;"></div>
          </div>
          <div style="font-size:0.78rem;color:var(--kw-text-muted);" id="progress-label">Phase 1 of 7</div>
          <div id="phase-checklist" style="margin-top:0.85rem;display:flex;flex-direction:column;gap:0.25rem;"></div>
        </div>

        <!-- AI Mentor -->
        <div class="kw-card" style="padding:1.25rem;border-top:3px solid #A855F7;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:#A855F7;margin-bottom:0.75rem;">
            <i class="fa-solid fa-wand-magic-sparkles"></i> AI Mentor
          </h5>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:0.75rem;">Ask anything about the current phase.</p>
          <input id="ai-mentor-input" placeholder="Ask the AI mentor..."
                 style="width:100%;padding:0.5rem 0.65rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:6px;font-size:0.8rem;color:var(--kw-text-primary);margin-bottom:0.5rem;outline:none;"
                 onfocus="this.style.borderColor='#A855F7'" onblur="this.style.borderColor=''"
                 onkeydown="if(event.key==='Enter')SDLC.askMentor()">
          <button onclick="SDLC.askMentor()" class="kw-btn kw-btn-sm" style="width:100%;justify-content:center;background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;font-size:0.75rem;">
            Ask AI Mentor
          </button>
          <div id="mentor-response" style="display:none;margin-top:0.75rem;padding:0.75rem;background:var(--kw-bg-alt);border:1px solid #A855F730;border-radius:6px;font-size:0.78rem;color:var(--kw-text-secondary);line-height:1.6;max-height:200px;overflow-y:auto;"></div>
        </div>

        <!-- Navigation -->
        <div style="display:flex;gap:0.5rem;">
          <button id="prev-btn" onclick="SDLC.prev()" class="kw-btn kw-btn-ghost" style="flex:1;justify-content:center;font-size:0.82rem;" disabled>
            <i class="fa-solid fa-arrow-left"></i> Prev
          </button>
          <button id="next-btn" onclick="SDLC.next()" class="kw-btn kw-btn-primary" style="flex:1;justify-content:center;font-size:0.82rem;">
            Next <i class="fa-solid fa-arrow-right"></i>
          </button>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
const PHASES = <?= json_encode(array_map(fn($p)=>['icon'=>$p[0],'color'=>$p[1],'name'=>$p[2],'description'=>$p[3],'deliverables'=>$p[4],'questions'=>$p[5]], $phases)) ?>;
const completed = new Set();

const SDLC = {
  current: 0,
  goTo(i) {
    this.current = i;
    this.render();
  },
  render() {
    const p = PHASES[this.current];
    const panel = document.getElementById('phase-panel');
    panel.innerHTML = `
      <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding-bottom:1.25rem;border-bottom:1px solid var(--kw-border);">
        <div style="width:56px;height:56px;border-radius:14px;background:${p.color}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="fa-solid ${p.icon}" style="font-size:1.4rem;color:${p.color};"></i>
        </div>
        <div>
          <div style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:${p.color};margin-bottom:0.2rem;">Phase ${this.current+1} of 7</div>
          <h2 style="margin:0;">${p.name}</h2>
        </div>
      </div>

      <p style="font-size:0.9rem;color:var(--kw-text-secondary);line-height:1.7;margin-bottom:1.75rem;">${p.description}</p>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.75rem;">
        <div>
          <h4 style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.75rem;"><i class="fa-solid fa-file-alt" style="color:${p.color};"></i> Key Deliverables</h4>
          ${p.deliverables.map(d=>`
            <div style="display:flex;gap:0.5rem;align-items:center;padding:0.45rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;">
              <i class="fa-solid fa-check-circle" style="color:${p.color};font-size:0.75rem;flex-shrink:0;"></i>
              ${d}
            </div>
          `).join('')}
        </div>
        <div>
          <h4 style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.75rem;"><i class="fa-solid fa-circle-question" style="color:${p.color};"></i> Key Questions</h4>
          ${p.questions.map(q=>`
            <div style="display:flex;gap:0.5rem;align-items:flex-start;padding:0.45rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;color:var(--kw-text-secondary);">
              <i class="fa-solid fa-arrow-right" style="color:${p.color};font-size:0.6rem;margin-top:0.35rem;flex-shrink:0;"></i>
              ${q}
            </div>
          `).join('')}
        </div>
      </div>

      <div class="kw-card" style="padding:1.25rem;background:${p.color}08;border-color:${p.color}30;">
        <h4 style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:${p.color};margin-bottom:0.85rem;">
          <i class="fa-solid fa-graduation-cap"></i> Interactive Exercise
        </h4>
        <p style="font-size:0.85rem;color:var(--kw-text-secondary);margin-bottom:0.85rem;">
          For the ${p.name} phase of a <strong>Hospital Management System</strong>, list the 3 most critical tasks your team should complete before moving to the next phase.
        </p>
        <textarea id="exercise-answer" rows="4" placeholder="Write your answer here..."
                  style="width:100%;resize:vertical;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:0.75rem;font-size:0.82rem;color:var(--kw-text-primary);outline:none;margin-bottom:0.75rem;"
                  onfocus="this.style.borderColor='${p.color}'" onblur="this.style.borderColor=''"></textarea>
        <button onclick="SDLC.reviewExercise()" class="kw-btn kw-btn-sm" style="background:${p.color};color:#fff;border:none;">
          <i class="fa-solid fa-robot"></i> Get AI Feedback
        </button>
        <div id="exercise-feedback" style="display:none;margin-top:0.85rem;padding:0.85rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.82rem;color:var(--kw-text-secondary);line-height:1.65;"></div>
      </div>
    `;

    // Update timeline dots
    document.querySelectorAll('.phase-step').forEach((el,i) => {
      const dot = el.querySelector('.phase-dot');
      const ph  = PHASES[i];
      if (i===this.current) {
        dot.style.background = ph.color;
        dot.style.borderColor = ph.color;
        dot.querySelector('i').style.color = '#fff';
        dot.style.boxShadow = `0 0 0 4px ${ph.color}30`;
      } else if (completed.has(i)) {
        dot.style.background = ph.color+'20';
        dot.style.borderColor = ph.color;
        dot.querySelector('i').style.color = ph.color;
        dot.style.boxShadow = '';
      } else {
        dot.style.background = 'var(--kw-bg-card)';
        dot.style.borderColor = ph.color+'40';
        dot.querySelector('i').style.color = ph.color+'80';
        dot.style.boxShadow = '';
      }
    });

    // Progress
    const pct = ((this.current+1)/7*100).toFixed(0);
    document.getElementById('progress-bar').style.width = pct+'%';
    document.getElementById('progress-label').textContent = `Phase ${this.current+1} of 7 — ${PHASES[this.current].name}`;

    // Checklist
    document.getElementById('phase-checklist').innerHTML = PHASES.map((ph,i)=>`
      <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;color:${i===this.current?ph.color:(completed.has(i)?'#22C55E':'var(--kw-text-muted)')};font-weight:${i===this.current?700:400};">
        <i class="fa-solid ${completed.has(i)?'fa-check-circle':(i===this.current?'fa-circle-dot':'fa-circle')}" style="font-size:0.6rem;"></i>
        ${ph.name}
      </div>
    `).join('');

    document.getElementById('prev-btn').disabled = this.current === 0;
    document.getElementById('next-btn').textContent = this.current===6 ? '✓ Complete' : 'Next ›';
    document.getElementById('mentor-response').style.display = 'none';
  },
  next() {
    completed.add(this.current);
    if (this.current < 6) { this.current++; this.render(); }
    else { window.Krest?.toast('🎉 SDLC Simulation Complete! You\'ve walked through all 7 phases.','success'); }
  },
  prev() {
    if (this.current > 0) { this.current--; this.render(); }
  },
  async reviewExercise() {
    const answer = document.getElementById('exercise-answer').value.trim();
    const fb = document.getElementById('exercise-feedback');
    if (!answer) { window.Krest?.toast('Write your answer first','warning'); return; }
    fb.style.display = 'block';
    fb.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color:var(--kw-primary);"></i> Reviewing...';
    const p = PHASES[this.current];
    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({messages:[{role:'user',content:`SDLC phase: ${p.name}. System: Hospital Management. Student answer: "${answer}". Give constructive feedback, rate completeness 1-10, mention what was missed.`}],system:'You are an experienced software project manager teaching SDLC. Be encouraging and educational.'})
      });
      const data = await resp.json();
      fb.innerHTML = (data.data?.reply||'Feedback unavailable.').replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
    } catch(e) { fb.textContent = 'Could not get feedback.'; }
  },
  async askMentor() {
    const q = document.getElementById('ai-mentor-input').value.trim();
    if (!q) return;
    const resp_div = document.getElementById('mentor-response');
    resp_div.style.display = 'block';
    resp_div.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color:#A855F7;"></i>';
    const p = PHASES[this.current];
    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({messages:[{role:'user',content:`In the context of SDLC ${p.name} phase: ${q}`}],system:'You are an SDLC expert mentor. Be concise and practical, 2-4 sentences max.'})
      });
      const data = await resp.json();
      resp_div.innerHTML = (data.data?.reply||'No response.').replace(/\n/g,'<br>');
      document.getElementById('ai-mentor-input').value = '';
    } catch(e) { resp_div.textContent = 'Mentor unavailable.'; }
  }
};

SDLC.render();
</script>
<style>
@media(max-width:1024px){ .kw-container>div[style*="1fr 340px"]{grid-template-columns:1fr!important;} div[style*="position:sticky"]{position:static!important;} }
@media(max-width:768px){ #phase-panel .kw-card>div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;} }
</style>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>