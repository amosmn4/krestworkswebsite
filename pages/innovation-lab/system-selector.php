<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'AI System Selector — Innovation Lab — ' . APP_NAME;
$page_description = 'Answer 8 questions about your business and get an AI recommendation of the right Krestworks enterprise system.';

?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">AI System Selector</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-robot"></i> AI Tool</span>
      <h1>AI System Selector</h1>
      <p>Answer 8 quick questions about your business — our AI recommends the right enterprise system with a detailed rationale.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="max-width:760px;margin:0 auto;">

      <!-- Progress -->
      <div id="selector-progress" style="margin-bottom:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
          <span style="font-size:0.78rem;font-weight:600;color:var(--kw-text-muted);">Question <span id="q-num">1</span> of 8</span>
          <span id="progress-pct" style="font-size:0.78rem;color:var(--kw-primary);font-weight:700;">0% complete</span>
        </div>
        <div style="height:4px;background:var(--kw-bg-alt);border-radius:999px;overflow:hidden;">
          <div id="progress-bar" style="height:100%;background:var(--kw-primary);border-radius:999px;transition:width 0.4s ease;width:0%;"></div>
        </div>
      </div>

      <!-- Questions Panel -->
      <div id="selector-questions">
        <?php
        $questions = [
          ['What best describes your industry?',
           ['Healthcare / Hospital','Education / Training','Real Estate / Property','Manufacturing / Production',
            'Retail / Commerce','Corporate / Services','Government / NGO','Logistics / Supply Chain']],
          ['What is the size of your organisation?',
           ['1–10 employees (startup)','11–50 employees (small)','51–200 employees (medium)','200+ employees (large/enterprise)']],
          ['What is your biggest operational challenge right now?',
           ['Managing staff and payroll','Tracking procurement and suppliers','Training and knowledge sharing',
            'Managing properties and tenants','Tracking inventory and logistics','Analysing data and making decisions',
            'Managing customer relationships','Managing sales and retail']],
          ['How is your data currently managed?',
           ['Spreadsheets (Excel/Google Sheets)','Disconnected software tools','Partial system — needs upgrade','Manual / paper-based']],
          ['What is your deployment preference?',
           ['Cloud (we prefer managed hosting)','On-premise (data stays with us)','Hybrid (both)','Not sure — need guidance']],
          ['When do you need to be live?',
           ['Within 4 weeks (urgent)','1–3 months','3–6 months','6+ months (planning phase)']],
          ['What is your budget range for this system?',
           ['Under KES 50,000','KES 50,000 – 150,000','KES 150,000 – 500,000','KES 500,000+ (enterprise)']],
          ['What matters most to you?',
           ['Low total cost of ownership','Fast implementation speed','High degree of customisation','Specific compliance or regulatory features','AI and automation capabilities','Mobile accessibility']],
        ];
        foreach ($questions as $qi => $q): ?>
        <div class="selector-question kw-card" style="padding:2rem;display:<?= $qi===0?'block':'none' ?>;" data-qi="<?= $qi ?>">
          <h3 style="margin-bottom:1.5rem;font-size:1.1rem;"><?= ($qi+1) . '. ' . e($q[0]) ?></h3>
          <div style="display:flex;flex-direction:column;gap:0.65rem;" class="selector-options">
            <?php foreach ($q[1] as $ai => $answer): ?>
            <button class="selector-option-btn kw-btn kw-btn-ghost" data-qi="<?= $qi ?>" data-ai="<?= $ai ?>" data-text="<?= htmlspecialchars($answer, ENT_QUOTES) ?>"
                    style="justify-content:flex-start;text-align:left;padding:0.85rem 1.25rem;font-size:0.875rem;">
              <span style="width:26px;height:26px;border-radius:50%;background:var(--kw-bg-alt);border:1.5px solid var(--kw-border);display:inline-flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;margin-right:0.75rem;flex-shrink:0;"><?= chr(65+$ai) ?></span>
              <?= e($answer) ?>
            </button>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Result Panel -->
      <div id="selector-result" style="display:none;" class="kw-card" style="padding:2.5rem;">
        <div id="selector-loader" style="text-align:center;padding:3rem;">
          <div class="kw-spinner" style="width:40px;height:40px;border-width:3px;border-top-color:var(--kw-primary);margin:0 auto 1rem;"></div>
          <p style="color:var(--kw-text-muted);">Our AI is analysing your answers and matching the best system...</p>
        </div>
        <div id="selector-output" style="display:none;padding:2.5rem;"></div>
      </div>

      <!-- Restart button -->
      <div id="selector-restart" style="display:none;text-align:center;margin-top:1.5rem;">
        <button onclick="restartSelector()" class="kw-btn kw-btn-ghost">
          <i class="fa-solid fa-rotate-left"></i> Start Over
        </button>
        <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary" style="margin-left:0.75rem;">
          <i class="fa-solid fa-calendar-check"></i> Book a Consultation
        </a>
      </div>

    </div>
  </div>
</section>

<script>
const answers     = {};
let currentQ      = 0;
const totalQ      = 8;

document.querySelectorAll('.selector-option-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const qi = parseInt(btn.dataset.qi);
    const text = btn.dataset.text;

    // Highlight selected
    document.querySelectorAll(`.selector-option-btn[data-qi="${qi}"]`).forEach(b => {
      b.style.background = '';
      b.style.borderColor = '';
      b.style.color = '';
      b.querySelector('span').style.background = '';
      b.querySelector('span').style.borderColor = '';
    });
    btn.style.background = 'var(--kw-primary)20';
    btn.style.borderColor = 'var(--kw-primary)';
    btn.style.color = 'var(--kw-primary)';
    btn.querySelector('span').style.background = 'var(--kw-primary)';
    btn.querySelector('span').style.borderColor = 'var(--kw-primary)';
    btn.querySelector('span').style.color = '#0A0F1A';

    answers[qi] = text;

    // Advance after brief pause
    setTimeout(() => {
      if (qi < totalQ - 1) {
        showQuestion(qi + 1);
      } else {
        showResult();
      }
    }, 300);
  });
});

function showQuestion(qi) {
  document.querySelectorAll('.selector-question').forEach(q => q.style.display = 'none');
  document.querySelector(`.selector-question[data-qi="${qi}"]`).style.display = 'block';
  currentQ = qi;
  const pct = Math.round((qi / totalQ) * 100);
  document.getElementById('q-num').textContent = qi + 1;
  document.getElementById('progress-pct').textContent = pct + '% complete';
  document.getElementById('progress-bar').style.width = pct + '%';
}

async function showResult() {
  document.getElementById('selector-questions').style.display = 'none';
  document.getElementById('selector-progress').style.display = 'none';
  const resultPanel = document.getElementById('selector-result');
  resultPanel.style.display = 'block';
  document.getElementById('selector-restart').style.display = 'block';

  // Update progress to 100%
  document.getElementById('progress-bar').style.width = '100%';
  document.getElementById('progress-pct').textContent = '100% complete';

  const answerSummary = Object.entries(answers).map(([qi, ans]) => `Q${parseInt(qi)+1}: ${ans}`).join('\n');

  const systemPrompt = `You are a senior enterprise software consultant at Krestworks Solutions. Based on a client's answers to 8 qualification questions, recommend the most suitable Krestworks enterprise system.

Available systems:
- HR Management System (hr-system)
- Procurement Management System (procurement-system)
- eLearning Management System (elearning-system)
- Real Estate Management System (real-estate-system)
- Supply Chain Management System (supply-chain-system)
- Executive Decision Support System (decision-support-system)
- CRM System (crm-system)
- Hospital Management System (hospital-system)
- Point of Sale System (pos-system)

Format your recommendation as:

## 🎯 Recommended System: [System Name]

## Why This System Fits
(3-4 specific reasons based on their answers)

## Key Features That Match Their Needs
(Bullet list of specific features that address their stated challenges)

## Deployment Recommendation
(Based on their preference and size)

## Implementation Timeline
(Based on urgency and budget)

## Secondary System to Consider
[Second most relevant system and why — only if genuinely relevant]

## Suggested Next Steps
1. [Specific action]
2. [Specific action]
3. [Specific action]

Base everything on the actual answers provided. Be specific and helpful.`;

  try {
    const resp = await fetch('<?= url('api/ai-assistant') ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        messages: [{ role: 'user', content: 'Client answers:\n' + answerSummary }],
        system: systemPrompt,
        tool_slug: 'system-selector'
      })
    });
    const data = await resp.json();

    document.getElementById('selector-loader').style.display = 'none';
    const outputEl = document.getElementById('selector-output');
    outputEl.style.display = 'block';

    if (data.success && data.data?.reply) {
      outputEl.innerHTML = `
        <div style="margin-bottom:1.5rem;padding:1rem;background:rgba(245,168,0,0.08);border:1px solid rgba(245,168,0,0.2);border-radius:var(--kw-radius-md);font-size:0.82rem;color:var(--kw-text-muted);">
          <i class="fa-solid fa-robot" style="color:var(--kw-primary);margin-right:0.4rem;"></i>
          Analysis based on your 8 answers — powered by Krest AI
        </div>
        <div style="font-size:0.9rem;line-height:1.75;">${formatOutput(data.data.reply)}</div>
      `;
    } else {
      outputEl.innerHTML = '<p style="color:var(--kw-text-muted);">Unable to generate recommendation. Please try again or <a href="<?= url('consultation') ?>">book a consultation</a>.</p>';
    }
  } catch(e) {
    document.getElementById('selector-loader').style.display = 'none';
    document.getElementById('selector-output').style.display = 'block';
    document.getElementById('selector-output').innerHTML = '<p>Connection error. <a href="<?= url('consultation') ?>">Book a free consultation</a> instead.</p>';
  }
}

function formatOutput(text) {
  return text
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/^## (.+)$/gm, '<h4 style="margin:1.25rem 0 0.5rem;color:var(--kw-primary);">$1</h4>')
    .replace(/^- (.+)$/gm, '<li style="margin-left:1.2rem;margin-bottom:0.25rem;">$1</li>')
    .replace(/^(\d+)\. (.+)$/gm, '<li style="margin-left:1.2rem;margin-bottom:0.25rem;"><strong>$1.</strong> $2</li>')
    .replace(/\n\n/g, '</p><p style="margin-top:0.75rem;">')
    .replace(/^/, '<p>').replace(/$/, '</p>');
}

function restartSelector() {
  Object.keys(answers).forEach(k => delete answers[k]);
  currentQ = 0;
  document.getElementById('selector-result').style.display = 'none';
  document.getElementById('selector-restart').style.display = 'none';
  document.getElementById('selector-questions').style.display = 'block';
  document.getElementById('selector-progress').style.display = 'block';
  document.querySelectorAll('.selector-question').forEach((q, i) => q.style.display = i===0?'block':'none');
  document.querySelectorAll('.selector-option-btn').forEach(b => {
    b.style.background=''; b.style.borderColor=''; b.style.color='';
    if(b.querySelector('span')) { b.querySelector('span').style.background=''; b.querySelector('span').style.borderColor=''; b.querySelector('span').style.color=''; }
  });
  document.getElementById('q-num').textContent = '1';
  document.getElementById('progress-pct').textContent = '0% complete';
  document.getElementById('progress-bar').style.width = '0%';
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>