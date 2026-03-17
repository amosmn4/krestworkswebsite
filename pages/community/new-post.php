<?php
require_once __DIR__ . '/../../includes/header.php';
requireLogin();

$page_title       = 'New Post — Community — ' . APP_NAME;
$page_description = 'Start a new discussion, ask a question, or share an insight with the Krestworks community.';
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('community') ?>">Community</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">New Post</span>
    </div>
    <div data-aos="fade-up" style="padding-bottom:2rem;">
      <span class="label"><i class="fa-solid fa-pen-to-square"></i> Create Post</span>
      <h1>Share With the Community</h1>
      <p>Ask a question, start a discussion, share an insight, or post a tutorial. Be specific and valuable.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2.5rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2.5rem;align-items:flex-start;">

      <!-- Form -->
      <div class="kw-card" style="padding:2rem;">
        <form id="new-post-form" novalidate>
          <?= csrfField() ?>

          <!-- Category -->
          <div style="margin-bottom:1.5rem;">
            <label style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.65rem;display:block;">
              Post Type <span style="color:#EF4444;">*</span>
            </label>
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0.5rem;" id="category-selector">
              <?php
              $cats = [
                ['discussion','fa-comments','#3B82F6','Discussion'],
                ['question','fa-circle-question','#F5A800','Question'],
                ['insight','fa-lightbulb','#22C55E','Insight'],
                ['tutorial','fa-graduation-cap','#A855F7','Tutorial'],
                ['news','fa-newspaper','#EF4444','News'],
              ];
              foreach ($cats as $ci => $cat): ?>
              <label style="cursor:pointer;">
                <input type="radio" name="category" value="<?= $cat[0] ?>" <?= $ci===0?'checked':'' ?> style="display:none;" class="cat-radio">
                <div class="cat-option" data-color="<?= $cat[2] ?>" style="text-align:center;padding:0.75rem 0.5rem;border:2px solid var(--kw-border);border-radius:var(--kw-radius-md);transition:all 0.2s;<?= $ci===0?"border-color:{$cat[2]};background:{$cat[2]}15;" : '' ?>">
                  <i class="fa-solid <?= $cat[1] ?>" style="font-size:1rem;color:<?= $cat[2] ?>;display:block;margin-bottom:0.3rem;"></i>
                  <span style="font-size:0.7rem;font-weight:600;"><?= $cat[3] ?></span>
                </div>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Title -->
          <div style="margin-bottom:1.25rem;">
            <label for="post-title" style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.5rem;display:flex;justify-content:space-between;">
              <span>Title <span style="color:#EF4444;">*</span></span>
              <span id="title-count" style="font-weight:400;">0/255</span>
            </label>
            <input type="text" id="post-title" name="title" maxlength="255"
                   placeholder="Write a clear, specific title..."
                   style="width:100%;padding:0.75rem 1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-family:var(--font-body);font-size:0.95rem;color:var(--kw-text-primary);outline:none;transition:border-color 0.2s;"
                   onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
            <div id="title-error" style="display:none;font-size:0.78rem;color:#EF4444;margin-top:0.3rem;"></div>
          </div>

          <!-- Body -->
          <div style="margin-bottom:1.25rem;">
            <label for="post-body" style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.5rem;display:flex;justify-content:space-between;">
              <span>Content <span style="color:#EF4444;">*</span></span>
              <span id="body-count" style="font-weight:400;">0/10,000</span>
            </label>
            <textarea id="post-body" name="body" rows="12" maxlength="10000"
                      placeholder="Share your question, discussion topic, insight, or tutorial content. Be detailed and specific — the more context you give, the better responses you'll get."
                      style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:var(--font-body);font-size:0.875rem;color:var(--kw-text-primary);outline:none;transition:border-color 0.2s;line-height:1.7;"
                      onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''"></textarea>
            <div id="body-error" style="display:none;font-size:0.78rem;color:#EF4444;margin-top:0.3rem;"></div>
          </div>

          <!-- Tags -->
          <div style="margin-bottom:1.75rem;">
            <label for="post-tags" style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.5rem;display:block;">
              Tags <span style="font-weight:400;color:var(--kw-text-muted);">(optional, comma-separated, max 5)</span>
            </label>
            <input type="text" id="post-tags" name="tags"
                   placeholder="e.g. php, laravel, database, ai, kenya"
                   style="width:100%;padding:0.65rem 1rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-family:var(--font-body);font-size:0.875rem;color:var(--kw-text-primary);outline:none;transition:border-color 0.2s;"
                   onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
            <div id="tags-preview" style="display:flex;flex-wrap:wrap;gap:0.35rem;margin-top:0.5rem;"></div>
          </div>

          <!-- Submit -->
          <div style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <button type="submit" id="post-submit-btn" class="kw-btn kw-btn-primary kw-btn-lg">
              <i class="fa-solid fa-paper-plane"></i> Publish Post
            </button>
            <a href="<?= url('community') ?>" class="kw-btn kw-btn-ghost kw-btn-lg">
              <i class="fa-solid fa-times"></i> Cancel
            </a>
          </div>

          <div id="post-form-error" style="display:none;margin-top:1rem;padding:0.75rem 1rem;background:#ef444415;border:1px solid #ef444430;border-radius:var(--kw-radius-md);font-size:0.85rem;color:#EF4444;"></div>
        </form>
      </div>

      <!-- Tips Sidebar -->
      <div style="display:flex;flex-direction:column;gap:1.25rem;position:sticky;top:calc(var(--kw-nav-height)+1rem);">

        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="margin-bottom:1rem;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">
            <i class="fa-solid fa-lightbulb" style="color:var(--kw-primary);"></i> Writing Tips
          </h5>
          <?php foreach ([
            ['fa-bullseye','Be Specific','Vague questions get vague answers. Include context, what you\'ve tried, and what you expect.'],
            ['fa-code','Share Code','For technical questions, paste the relevant code snippet.'],
            ['fa-tag','Use Tags','Tags help the right people find your post.'],
            ['fa-spell-check','Proofread','A well-written post gets better responses.'],
            ['fa-reply','Engage Back','Reply to answers and mark helpful ones.'],
          ] as $tip): ?>
          <div style="display:flex;gap:0.65rem;padding:0.6rem 0;border-bottom:1px solid var(--kw-border);">
            <i class="fa-solid <?= $tip[0] ?>" style="color:var(--kw-primary);margin-top:0.15rem;font-size:0.8rem;flex-shrink:0;"></i>
            <div>
              <div style="font-size:0.8rem;font-weight:700;margin-bottom:0.15rem;"><?= $tip[1] ?></div>
              <div style="font-size:0.75rem;color:var(--kw-text-muted);"><?= $tip[2] ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="margin-bottom:0.85rem;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);">
            <i class="fa-solid fa-shield-halved" style="color:var(--kw-primary);"></i> Community Rules
          </h5>
          <?php foreach (['Be respectful to all members','No spam or self-promotion','Stay on topic','No hate speech or harassment','Credit external sources'] as $rule): ?>
          <div style="display:flex;gap:0.4rem;font-size:0.78rem;color:var(--kw-text-secondary);padding:0.25rem 0;">
            <i class="fa-solid fa-check" style="color:#22C55E;margin-top:0.15rem;font-size:0.65rem;flex-shrink:0;"></i><?= $rule ?>
          </div>
          <?php endforeach; ?>
        </div>

      </div>
    </div>
  </div>
</section>

<script>
// Category selector
document.querySelectorAll('.cat-radio').forEach(radio => {
  radio.addEventListener('change', () => {
    document.querySelectorAll('.cat-option').forEach(opt => {
      opt.style.borderColor = '';
      opt.style.background = '';
    });
    const opt = radio.nextElementSibling;
    const color = opt.dataset.color;
    opt.style.borderColor = color;
    opt.style.background = color + '15';
  });
});

// Char counters
document.getElementById('post-title')?.addEventListener('input', function() {
  document.getElementById('title-count').textContent = this.value.length + '/255';
});
document.getElementById('post-body')?.addEventListener('input', function() {
  document.getElementById('body-count').textContent = this.value.length.toLocaleString() + '/10,000';
});

// Tag preview
document.getElementById('post-tags')?.addEventListener('input', function() {
  const preview = document.getElementById('tags-preview');
  const tags = this.value.split(',').map(t => t.trim()).filter(Boolean).slice(0, 5);
  preview.innerHTML = tags.map(t =>
    `<span style="background:var(--kw-bg-alt);color:var(--kw-text-secondary);border:1px solid var(--kw-border);border-radius:999px;padding:0.18rem 0.6rem;font-size:0.72rem;">#${t}</span>`
  ).join('');
});

// Form submit
document.getElementById('new-post-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const errDiv = document.getElementById('post-form-error');
  const btn    = document.getElementById('post-submit-btn');

  // Client-side validation
  let valid = true;
  const title = document.getElementById('post-title').value.trim();
  const body  = document.getElementById('post-body').value.trim();

  const titleErr = document.getElementById('title-error');
  const bodyErr  = document.getElementById('body-error');
  titleErr.style.display = 'none';
  bodyErr.style.display  = 'none';
  errDiv.style.display   = 'none';

  if (title.length < 5) {
    titleErr.textContent = 'Title must be at least 5 characters.';
    titleErr.style.display = 'block';
    valid = false;
  }
  if (body.length < 20) {
    bodyErr.textContent = 'Content must be at least 20 characters.';
    bodyErr.style.display = 'block';
    valid = false;
  }
  if (!valid) return;

  btn.disabled = true;
  btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Publishing...';

  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const formData = new FormData(this);

  try {
    const resp = await fetch('<?= url('api/community-post') ?>', {
      method: 'POST',
      headers: { 'X-CSRF-Token': csrfMeta?.content || '' },
      body: formData
    });
    const data = await resp.json();

    if (data.success) {
      window.Krest?.toast('Post published!', 'success');
      setTimeout(() => { window.location.href = data.data?.redirect || '<?= url('community') ?>'; }, 600);
    } else {
      errDiv.textContent = data.message || 'Failed to publish post.';
      errDiv.style.display = 'block';
      if (data.fields) {
        if (data.fields.title) { titleErr.textContent = data.fields.title; titleErr.style.display='block'; }
        if (data.fields.body)  { bodyErr.textContent  = data.fields.body;  bodyErr.style.display='block';  }
      }
    }
  } catch(err) {
    errDiv.textContent = 'Connection error. Please try again.';
    errDiv.style.display = 'block';
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Publish Post';
});
</script>
<style>
@media(max-width:768px){
  .kw-container > div[style*="grid-template-columns:2fr 1fr"]{ grid-template-columns:1fr!important; }
  div[style*="position:sticky"]{ position:static!important; }
  #category-selector{ grid-template-columns:repeat(3,1fr)!important; }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>