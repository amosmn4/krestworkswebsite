<?php
// Shared AI Tool template
// Requires: $tool (array), $system_prompt (string), $tool_config (array)
if (!isset($tool)) die();
$is_premium = $tool['premium'] ?? false;
$user_logged_in = !empty($_SESSION['user_id']);
$can_use = !$is_premium || $user_logged_in;
?>

<section class="kw-page-hero" style="padding-bottom:0;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('ai-hub') ?>">AI Hub</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current"><?= e($tool['name']) ?></span>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;padding:2rem 0;" data-aos="fade-up">
      <div>
        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
          <div style="width:44px;height:44px;border-radius:var(--kw-radius-md);background:<?= $tool['color'] ?>20;color:<?= $tool['color'] ?>;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
            <i class="fa-solid <?= $tool['icon'] ?>"></i>
          </div>
          <div>
            <div style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:<?= $tool['color'] ?>;"><?= $is_premium ? '⭐ Premium Tool' : '✅ Free Tool' ?></div>
            <h1 style="font-size:1.6rem;color:#fff;margin:0;"><?= e($tool['name']) ?></h1>
          </div>
        </div>
        <p style="color:rgba(255,255,255,0.6);max-width:560px;"><?= e($tool['tagline']) ?></p>
      </div>
      <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
        <a href="<?= url('ai-hub') ?>" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-arrow-left"></i> All Tools</a>
        <?php if ($is_premium && !$user_logged_in): ?>
          <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-sm" style="background:var(--kw-primary);color:#0A0F1A;"><i class="fa-solid fa-crown"></i> Unlock Pro</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- Tool Interface -->
<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;align-items:flex-start;">

      <!-- Tool Panel -->
      <div>
        <?php if (!$can_use): ?>
        <!-- Locked state -->
        <div class="kw-card" style="padding:3rem;text-align:center;">
          <i class="fa-solid fa-lock" style="font-size:2.5rem;color:var(--kw-text-muted);margin-bottom:1rem;display:block;"></i>
          <h3 style="margin-bottom:0.5rem;">Premium Tool</h3>
          <p style="color:var(--kw-text-muted);margin-bottom:1.5rem;">This tool requires a Pro Plan subscription. Unlock all premium tools from <?php echo 'KES 4,900/month'; ?>.</p>
          <div style="display:flex;gap:0.75rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?= url('pricing') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-crown"></i> View Plans</a>
            <a href="<?= url('portal/login') ?>" class="kw-btn kw-btn-ghost">Log In</a>
          </div>
        </div>

        <?php else: ?>
        <!-- Active tool -->
        <div class="kw-card" style="padding:0;overflow:hidden;" id="ai-tool-panel">

          <!-- Tool Header -->
          <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--kw-border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;">
              <div style="width:8px;height:8px;border-radius:50%;background:#22C55E;animation:pulse 2s infinite;"></div>
              <span style="font-size:0.82rem;font-weight:600;"><?= e($tool['name']) ?> — Ready</span>
            </div>
            <div style="display:flex;gap:0.4rem;">
              <button id="clear-tool-btn" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.75rem;padding:0.3rem 0.7rem;">
                <i class="fa-solid fa-rotate-left"></i> Clear
              </button>
              <button id="copy-output-btn" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.75rem;padding:0.3rem 0.7rem;" disabled>
                <i class="fa-solid fa-copy"></i> Copy
              </button>
            </div>
          </div>

          <!-- Input Area -->
          <div style="padding:1.5rem;border-bottom:1px solid var(--kw-border);">
            <?php if (!empty($tool_config['input_label'])): ?>
              <label style="font-size:0.8rem;font-weight:700;color:var(--kw-text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;display:block;">
                <?= e($tool_config['input_label']) ?>
              </label>
            <?php endif; ?>

            <?php if (!empty($tool_config['options'])): ?>
            <!-- Option selector above textarea -->
            <div style="display:flex;gap:0.4rem;flex-wrap:wrap;margin-bottom:0.75rem;" id="tool-options">
              <?php foreach ($tool_config['options'] as $opt): ?>
              <button class="kw-btn kw-btn-ghost kw-btn-sm tool-option-btn" data-prompt="<?= htmlspecialchars($opt['prompt'], ENT_QUOTES) ?>"
                      style="font-size:0.75rem;padding:0.3rem 0.75rem;">
                <?= e($opt['label']) ?>
              </button>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <textarea id="tool-input"
                      rows="<?= $tool_config['input_rows'] ?? 8 ?>"
                      placeholder="<?= htmlspecialchars($tool_config['placeholder'] ?? 'Paste your content here...', ENT_QUOTES) ?>"
                      style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:var(--font-body);font-size:0.875rem;color:var(--kw-text-primary);outline:none;transition:border-color 0.2s;"
                      onfocus="this.style.borderColor='<?= $tool['color'] ?>'" onblur="this.style.borderColor=''"></textarea>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:0.75rem;flex-wrap:wrap;gap:0.5rem;">
              <span id="char-count" style="font-size:0.75rem;color:var(--kw-text-muted);">0 characters</span>
              <button id="run-tool-btn" class="kw-btn kw-btn-sm" style="background:<?= $tool['color'] ?>;color:#fff;border-color:<?= $tool['color'] ?>;">
                <i class="fa-solid fa-wand-magic-sparkles"></i> <?= e($tool_config['cta_text'] ?? 'Generate') ?>
              </button>
            </div>
          </div>

          <!-- Output Area -->
          <div style="padding:1.5rem;min-height:200px;">
            <div id="tool-output-placeholder" style="display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:180px;color:var(--kw-text-muted);">
              <i class="fa-solid fa-wand-magic-sparkles" style="font-size:2rem;margin-bottom:0.75rem;opacity:0.3;color:<?= $tool['color'] ?>;"></i>
              <p style="font-size:0.85rem;text-align:center;max-width:260px;">Your AI-generated output will appear here</p>
            </div>
            <div id="tool-loader" style="display:none;text-align:center;padding:2rem;">
              <div style="display:inline-flex;align-items:center;gap:0.75rem;background:<?= $tool['color'] ?>10;border:1px solid <?= $tool['color'] ?>25;border-radius:999px;padding:0.5rem 1.25rem;">
                <div class="kw-spinner" style="border-top-color:<?= $tool['color'] ?>;width:16px;height:16px;"></div>
                <span style="font-size:0.82rem;color:<?= $tool['color'] ?>;font-weight:600;">AI is working...</span>
              </div>
            </div>
            <div id="tool-output" style="display:none;font-size:0.875rem;line-height:1.75;color:var(--kw-text-primary);"></div>
          </div>

        </div>
        <?php endif; ?>
      </div>

      <!-- Sidebar -->
      <div>
        <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:calc(var(--kw-nav-height)+1rem);">

          <!-- Tips -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">
              <i class="fa-solid fa-lightbulb" style="color:var(--kw-primary);"></i> Tips for Best Results
            </h5>
            <?php foreach ($tool['tips'] as $tip): ?>
            <div style="display:flex;align-items:flex-start;gap:0.5rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.8rem;color:var(--kw-text-secondary);">
              <i class="fa-solid fa-circle-check" style="color:var(--kw-primary);margin-top:0.15rem;flex-shrink:0;font-size:0.7rem;"></i>
              <?= e($tip) ?>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Capabilities -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">
              <i class="fa-solid fa-list-check" style="color:var(--kw-primary);"></i> What This Tool Does
            </h5>
            <?php foreach ($tool['capabilities'] as $cap): ?>
            <div style="display:flex;align-items:center;gap:0.45rem;font-size:0.8rem;padding:0.3rem 0;color:var(--kw-text-secondary);">
              <i class="fa-solid fa-check" style="color:<?= $tool['color'] ?>;font-size:0.65rem;flex-shrink:0;"></i>
              <?= e($cap) ?>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Other Tools -->
          <div class="kw-card" style="padding:1.5rem;">
            <h5 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Other Free Tools</h5>
            <?php
            $other = [
              ['document-summarizer','fa-file-lines','Document Summarizer'],
              ['resume-analyzer','fa-id-card','Resume Analyzer'],
              ['code-assistant','fa-code','Code Assistant'],
              ['meeting-notes','fa-microphone','Meeting Notes'],
              ['email-writer','fa-envelope','Email Writer'],
            ];
            foreach ($other as $o):
              if ($o[0] === $tool['slug']) continue; ?>
            <a href="<?= url('ai-hub/' . $o[0]) ?>" style="display:flex;align-items:center;gap:0.6rem;padding:0.45rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;">
              <i class="fa-solid <?= $o[1] ?>" style="color:var(--kw-primary);width:14px;font-size:0.8rem;"></i><?= $o[2] ?>
              <i class="fa-solid fa-arrow-right" style="margin-left:auto;font-size:0.65rem;color:var(--kw-text-muted);"></i>
            </a>
            <?php endforeach; ?>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<style>
@media(max-width:768px){
  .kw-container > div[style*="grid-template-columns:2fr 1fr"]{ grid-template-columns:1fr!important; }
}
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
</style>

<?php if ($can_use): ?>
<script>
(function(){
  const input   = document.getElementById('tool-input');
  const runBtn  = document.getElementById('run-tool-btn');
  const clearBtn= document.getElementById('clear-tool-btn');
  const copyBtn = document.getElementById('copy-output-btn');
  const output  = document.getElementById('tool-output');
  const loader  = document.getElementById('tool-loader');
  const placeholder = document.getElementById('tool-output-placeholder');
  const charCount = document.getElementById('char-count');
  const systemPrompt = <?= json_encode($system_prompt) ?>;

  // Char counter
  input?.addEventListener('input', () => {
    charCount.textContent = input.value.length.toLocaleString() + ' characters';
  });

  // Option buttons pre-fill
  document.querySelectorAll('.tool-option-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.tool-option-btn').forEach(b => {
        b.style.background=''; b.style.color=''; b.style.borderColor='';
      });
      btn.style.background='<?= $tool['color'] ?>20';
      btn.style.color='<?= $tool['color'] ?>';
      btn.style.borderColor='<?= $tool['color'] ?>50';
      // If the option sets input text:
      const prompt = btn.dataset.prompt;
      if (prompt && !input.value.trim()) input.value = prompt;
    });
  });

  // Run
  runBtn?.addEventListener('click', runTool);
  input?.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') runTool();
  });

  async function runTool() {
    const text = input?.value.trim();
    if (!text) { window.Krest?.toast('Please enter some content first.','warning'); return; }

    runBtn.disabled = true;
    runBtn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#fff;display:inline-block;margin-right:6px;"></div>Processing...';
    loader.style.display = 'block';
    placeholder.style.display = 'none';
    output.style.display = 'none';
    copyBtn.disabled = true;

    try {
      const resp = await fetch('<?= url('api/ai-assistant') ?>', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body: JSON.stringify({
          messages:[{role:'user',content:text}],
          system: systemPrompt,
          tool_slug: '<?= $tool['slug'] ?>'
        })
      });
      const data = await resp.json();

      loader.style.display = 'none';

      if (data.success && data.data?.reply) {
        output.innerHTML = formatAIOutput(data.data.reply);
        output.style.display = 'block';
        copyBtn.disabled = false;

        // Log usage
        fetch('<?= url('api/tool-usage') ?>', {
          method:'POST',
          headers:{'Content-Type':'application/x-www-form-urlencoded'},
          body:'tool_slug=<?= $tool['slug'] ?>'
        }).catch(()=>{});
      } else {
        window.Krest?.toast(data.message || 'Something went wrong. Please try again.','error');
        placeholder.style.display = 'flex';
      }
    } catch(err) {
      loader.style.display = 'none';
      placeholder.style.display = 'flex';
      window.Krest?.toast('Connection error. Please try again.','error');
    }

    runBtn.disabled = false;
    runBtn.innerHTML = '<i class="fa-solid fa-wand-magic-sparkles"></i> <?= htmlspecialchars($tool_config['cta_text'] ?? 'Generate', ENT_QUOTES) ?>';
  }

  // Clear
  clearBtn?.addEventListener('click', () => {
    input.value = '';
    output.style.display = 'none';
    placeholder.style.display = 'flex';
    copyBtn.disabled = true;
    charCount.textContent = '0 characters';
    document.querySelectorAll('.tool-option-btn').forEach(b=>{b.style.background='';b.style.color='';b.style.borderColor='';});
  });

  // Copy
  copyBtn?.addEventListener('click', () => {
    const text = output.innerText;
    navigator.clipboard.writeText(text).then(() => window.Krest?.toast('Copied to clipboard!','success'));
  });

  // Format AI output — convert markdown-lite to HTML
  function formatAIOutput(text) {
    return text
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.+?)\*/g, '<em>$1</em>')
      .replace(/^## (.+)$/gm, '<h4 style="margin:1rem 0 0.4rem;color:<?= $tool['color'] ?>;">$1</h4>')
      .replace(/^### (.+)$/gm, '<h5 style="margin:0.75rem 0 0.3rem;">$1</h5>')
      .replace(/^- (.+)$/gm, '<li style="margin-left:1.2rem;margin-bottom:0.2rem;">$1</li>')
      .replace(/(<li[\s\S]*?<\/li>)/g, '<ul style="margin:0.5rem 0;">$1</ul>')
      .replace(/\n\n/g, '</p><p style="margin-top:0.75rem;">')
      .replace(/^/, '<p>')
      .replace(/$/, '</p>');
  }
})();
</script>
<?php endif; ?>