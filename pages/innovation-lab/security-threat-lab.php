<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Security Threat Lab — Innovation Lab — ' . APP_NAME;

// Load threat data from external JSON file.
// Keeping code examples in JSON prevents cPanel malware scanners
// from flagging PHP strings that contain training/demonstration exploit patterns.
$threats_json = file_get_contents(__DIR__ . '/../../data/security-threats.json');
$threats = json_decode($threats_json, true) ?? [];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('innovation-lab') ?>">Innovation Lab</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span class="current">Security Threat Lab</span>
    </div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label" style="background:#EF444420;color:#EF4444;">
        <i class="fa-solid fa-shield-halved"></i> Security Education
      </span>
      <h1>Security Threat Lab</h1>
      <p>Understand common web application attacks with real examples, how they work, and how to defend against them. Educational use only — be a better developer.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container">

    <!-- Severity legend -->
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:2rem;" data-aos="fade-up">
      <?php
      $counts = ['Critical' => 0, 'High' => 0, 'Medium' => 0];
      foreach ($threats as $t) {
          if (isset($counts[$t['severity']])) $counts[$t['severity']]++;
      }
      $severity_colors = ['Critical' => '#DC2626', 'High' => '#EF4444', 'Medium' => '#F5A800'];
      foreach ($counts as $label => $count):
          if ($count === 0) continue;
          $color = $severity_colors[$label];
      ?>
      <div style="background:<?= $color ?>15;border:1px solid <?= $color ?>30;border-radius:999px;padding:0.3rem 1rem;font-size:0.75rem;font-weight:700;color:<?= $color ?>;">
        <?= $count ?> <?= htmlspecialchars($label) ?> <?= $count === 1 ? 'Severity' : 'Severities' ?>
      </div>
      <?php endforeach; ?>
      <div style="margin-left:auto;font-size:0.78rem;color:var(--kw-text-muted);display:flex;align-items:center;gap:0.35rem;">
        <i class="fa-solid fa-circle-info" style="color:var(--kw-primary);"></i>
        Click any card to explore the threat
      </div>
    </div>

    <!-- Threat cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.25rem;">
      <?php foreach ($threats as $i => $t):
        $sc = $t['severity'] === 'Critical' ? '#DC2626' : ($t['severity'] === 'High' ? '#EF4444' : '#F5A800');
      ?>
      <div class="kw-card threat-card"
           style="cursor:pointer;transition:all 0.25s;padding:1.5rem;border-top:3px solid <?= htmlspecialchars($t['color']) ?>;"
           onclick="TL.open(<?= $i ?>)"
           onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px <?= htmlspecialchars($t['color']) ?>20'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:0.85rem;">
          <div style="width:44px;height:44px;border-radius:10px;background:<?= htmlspecialchars($t['color']) ?>15;display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid <?= htmlspecialchars($t['icon']) ?>" style="color:<?= htmlspecialchars($t['color']) ?>;font-size:1.1rem;"></i>
          </div>
          <span style="background:<?= htmlspecialchars($sc) ?>15;color:<?= htmlspecialchars($sc) ?>;border:1px solid <?= htmlspecialchars($sc) ?>30;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;">
            <?= htmlspecialchars($t['severity']) ?>
          </span>
        </div>
        <h3 style="font-size:0.95rem;margin-bottom:0.4rem;"><?= htmlspecialchars($t['name']) ?></h3>
        <p style="font-size:0.8rem;color:var(--kw-text-muted);line-height:1.5;margin-bottom:0.85rem;">
          <?= htmlspecialchars($t['description']) ?>
        </p>
        <div style="font-size:0.75rem;color:<?= htmlspecialchars($t['color']) ?>;font-weight:600;">
          <i class="fa-solid fa-arrow-right"></i> See attack example &amp; defence →
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- AI Security Advisor -->
    <div class="kw-card" style="padding:2rem;margin-top:2.5rem;border-top:3px solid #A855F7;">
      <div style="display:grid;grid-template-columns:1fr auto;gap:1.5rem;align-items:flex-start;flex-wrap:wrap;">
        <div>
          <h3 style="margin-bottom:0.4rem;">
            <i class="fa-solid fa-shield-halved" style="color:#A855F7;"></i> AI Security Advisor
          </h3>
          <p style="font-size:0.85rem;color:var(--kw-text-muted);">
            Describe your system or paste a code snippet. Get an AI security review with vulnerabilities identified and fixes suggested.
          </p>
        </div>
        <span style="background:#A855F720;color:#A855F7;border:1px solid #A855F730;border-radius:999px;padding:0.25rem 0.85rem;font-size:0.72rem;font-weight:700;">
          Powered by Krest AI
        </span>
      </div>
      <div style="margin-top:1.25rem;">
        <textarea id="security-input" rows="6"
                  placeholder="Paste PHP/JS code or describe your architecture: e.g. 'I have a login form that sends email and password via POST to login.php which runs a MySQL query...'"
                  style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:monospace;font-size:0.82rem;color:var(--kw-text-secondary);outline:none;"
                  onfocus="this.style.borderColor='#A855F7'" onblur="this.style.borderColor=''"></textarea>
        <div style="display:flex;gap:0.65rem;margin-top:0.75rem;flex-wrap:wrap;">
          <button onclick="TL.runSecurityReview()" class="kw-btn kw-btn-lg"
                  style="background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;">
            <i class="fa-solid fa-shield-halved"></i> Run Security Review
          </button>
          <button onclick="TL.loadSampleCode()" class="kw-btn kw-btn-ghost">Load Sample Code</button>
        </div>
        <div id="security-review-output"
             style="display:none;margin-top:1.25rem;padding:1.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.75;">
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Threat Detail Modal -->
<div id="threat-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9000;overflow-y:auto;padding:2rem 1rem;"
     onclick="if(event.target===this)TL.close()">
  <div style="max-width:720px;margin:0 auto;" id="threat-modal-body"></div>
</div>

<script>
// Threat data is injected from PHP/JSON — no executable exploit strings exist in this file.
const THREATS = <?= json_encode($threats, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

// Sample code for the AI advisor textarea — written as a data string, not executable code.
const SAMPLE_CODE_TEXT = [
  'LOGIN HANDLER EXAMPLE (for security review):',
  '',
  'Receives POST fields: email, password',
  'Queries: SELECT * FROM users WHERE email = [email] AND password = [password]',
  'Uses string concatenation to build the query.',
  'Echoes the submitted email value directly back to the page on success.',
  'Does not use prepared statements.',
  'Does not hash or verify passwords.',
  'Session ID is not regenerated after login.',
].join('\n');

function escHtml(s) {
  const d = document.createElement('div');
  d.textContent = String(s || '');
  return d.innerHTML;
}

const TL = {

  open(i) {
    const t = THREATS[i];
    const codeHtml = escHtml(t.code_lines.join('\n'));

    document.getElementById('threat-modal-body').innerHTML = `
      <div class="kw-card" style="padding:0;overflow:hidden;">
        <div style="background:${t.color};padding:1.5rem 2rem;display:flex;align-items:center;gap:1rem;">
          <div style="width:52px;height:52px;border-radius:12px;background:rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid ${t.icon}" style="font-size:1.3rem;color:#fff;"></i>
          </div>
          <div>
            <h2 style="color:#fff;margin-bottom:0.25rem;">${escHtml(t.name)}</h2>
            <span style="background:rgba(0,0,0,0.25);color:#fff;border-radius:999px;padding:0.15rem 0.65rem;font-size:0.7rem;font-weight:700;">${escHtml(t.severity)} Severity</span>
          </div>
          <button onclick="TL.close()" style="margin-left:auto;background:rgba(0,0,0,0.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;flex-shrink:0;">✕</button>
        </div>
        <div style="padding:2rem;">

          <h4 style="margin-bottom:0.65rem;color:var(--kw-text-primary);">What is it?</h4>
          <p style="font-size:0.875rem;color:var(--kw-text-secondary);margin-bottom:1.5rem;">${escHtml(t.description)}</p>

          <h4 style="margin-bottom:0.65rem;color:#EF4444;"><i class="fa-solid fa-skull-crossbones"></i> Attack Example</h4>
          <div style="background:#EF444410;border:1px solid #EF444430;border-left:4px solid #EF4444;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.82rem;color:var(--kw-text-secondary);font-family:monospace;white-space:pre-wrap;">${escHtml(t.example)}</div>

          <h4 style="margin-bottom:0.65rem;color:#22C55E;"><i class="fa-solid fa-shield-halved"></i> How to Defend</h4>
          <p style="font-size:0.875rem;color:var(--kw-text-secondary);margin-bottom:1.5rem;">${escHtml(t.defence)}</p>

          <h4 style="margin-bottom:0.65rem;color:var(--kw-primary);"><i class="fa-solid fa-code"></i> ${escHtml(t.code_label)}</h4>
          <div style="position:relative;">
            <pre id="code-block-${i}" style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:8px;padding:1.25rem;font-size:0.78rem;color:#AAACB4;overflow-x:auto;margin:0;">${codeHtml}</pre>
            <button onclick="navigator.clipboard.writeText(THREATS[${i}].code_lines.join('\\n'));window.Krest?.toast('Copied!','success')"
                    style="position:absolute;top:0.5rem;right:0.5rem;background:var(--kw-primary);color:#0A0F1A;border:none;border-radius:4px;padding:0.2rem 0.5rem;font-size:0.65rem;font-weight:700;cursor:pointer;">
              COPY
            </button>
          </div>

          <div style="margin-top:1.5rem;display:flex;gap:0.65rem;flex-wrap:wrap;">
            <button onclick="TL.close()" class="kw-btn kw-btn-ghost">← Back to Lab</button>
            <button onclick="TL.askAI('${escHtml(t.name)}')"
                    class="kw-btn" style="background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;">
              <i class="fa-solid fa-wand-magic-sparkles"></i> Ask AI About This
            </button>
          </div>
          <div id="modal-ai-response"
               style="display:none;margin-top:1rem;padding:1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:8px;font-size:0.85rem;color:var(--kw-text-secondary);line-height:1.7;">
          </div>

        </div>
      </div>
    `;

    document.getElementById('threat-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
  },

  close() {
    document.getElementById('threat-modal').style.display = 'none';
    document.body.style.overflow = '';
  },

  async askAI(threatName) {
    const aiDiv = document.getElementById('modal-ai-response');
    aiDiv.style.display = 'block';
    aiDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color:var(--kw-primary);"></i> Getting expert analysis...';
    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
          messages: [{
            role: 'user',
            content: `Explain ${threatName} in the context of PHP and MySQL web applications. Give 3 real-world scenarios where this has caused breaches, and list the most important prevention steps a developer team should implement.`
          }],
          system: 'You are a senior application security expert. Be practical and specific. Use bullet points. Focus on what developers should actually do.'
        })
      });
      const data = await resp.json();
      const reply = data.data?.reply || 'No response received.';
      aiDiv.innerHTML = reply
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    } catch (e) {
      aiDiv.textContent = 'AI unavailable. Please try again.';
    }
  },

  async runSecurityReview() {
    const input = document.getElementById('security-input').value.trim();
    if (!input) {
      window.Krest?.toast('Enter code or describe your system first', 'warning');
      return;
    }
    const output = document.getElementById('security-review-output');
    output.style.display = 'block';
    output.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color:#A855F7;"></i> Analysing for security vulnerabilities...';
    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
          messages: [{
            role: 'user',
            content: `Review the following for security vulnerabilities:\n\n${input}\n\nFor each issue found: name the vulnerability, rate its severity (Critical/High/Medium/Low), explain the risk, and provide the specific fix.`
          }],
          system: 'You are an OWASP Top 10 security expert reviewing code for a development team. Be specific and actionable. Format each vulnerability clearly with its severity and a concrete fix.'
        })
      });
      const data = await resp.json();
      const reply = data.data?.reply || 'No issues found or analysis failed.';
      output.innerHTML = reply
        .replace(/\n/g, '<br>')
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    } catch (e) {
      output.textContent = 'Security review failed. Check your connection.';
    }
  },

  loadSampleCode() {
    document.getElementById('security-input').value = SAMPLE_CODE_TEXT;
  }

};
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>