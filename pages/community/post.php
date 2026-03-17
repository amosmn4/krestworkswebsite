<?php
require_once __DIR__ . '/../../includes/header.php';

$postId = (int)($_GET['id'] ?? 0);
if (!$postId) { redirect('community'); }

try {
    $pdo = db();

    // Fetch post + author
    $postStmt = $pdo->prepare("
        SELECT p.*, u.id AS author_id, u.name AS author_name, u.company AS author_company,
               u.created_at AS author_since,
               (SELECT COUNT(*) FROM community_posts pp WHERE pp.user_id = u.id AND pp.is_active=1) AS author_posts
        FROM community_posts p
        JOIN users u ON u.id = p.user_id
        WHERE p.id = ? AND p.is_active = 1
        LIMIT 1
    ");
    $postStmt->execute([$postId]);
    $post = $postStmt->fetch();

    if (!$post) { redirect('community'); }

    // Update view count (non-blocking)
    try { $pdo->prepare("UPDATE community_posts SET views = views + 1 WHERE id = ?")->execute([$postId]); } catch(Exception $e){}

    // Fetch replies
    $replyStmt = $pdo->prepare("
        SELECT r.*, u.id AS author_id, u.name AS author_name, u.company AS author_company
        FROM community_replies r
        JOIN users u ON u.id = r.user_id
        WHERE r.post_id = ? AND r.is_active = 1
        ORDER BY r.created_at ASC
    ");
    $replyStmt->execute([$postId]);
    $replies = $replyStmt->fetchAll();

    // Fetch related posts (same category, excluding current)
    $related = $pdo->prepare("
        SELECT id, title, created_at FROM community_posts
        WHERE category = ? AND id != ? AND is_active = 1
        ORDER BY created_at DESC LIMIT 4
    ");
    $related->execute([$post['category'], $postId]);
    $relatedPosts = $related->fetchAll();

} catch(Exception $e) {
    error_log('Community post error: ' . $e->getMessage());
    redirect('community');
}

$page_title       = e($post['title']) . ' — Community — ' . APP_NAME;
$page_description = mb_substr(strip_tags($post['body']), 0, 160);

$tags      = json_decode($post['tags'] ?? '[]', true) ?: [];
$catColors = ['discussion'=>'#3B82F6','question'=>'#F5A800','insight'=>'#22C55E','tutorial'=>'#A855F7','news'=>'#EF4444'];
$catIcons  = ['discussion'=>'fa-comments','question'=>'fa-circle-question','insight'=>'fa-lightbulb','tutorial'=>'fa-graduation-cap','news'=>'fa-newspaper'];
$color     = $catColors[$post['category']] ?? '#6B7280';
$icon      = $catIcons[$post['category']] ?? 'fa-tag';
$myId      = $_SESSION['user_id'] ?? null;
?>

<section class="kw-page-hero" style="padding-bottom:0;">
  <div class="kw-container">
    <div class="kw-breadcrumb" style="color:rgba(255,255,255,0.5);">
      <a href="<?= url() ?>" style="color:rgba(255,255,255,0.5);">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('community') ?>" style="color:rgba(255,255,255,0.5);">Community</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span style="color:rgba(255,255,255,0.8);"><?= e(truncate($post['title'], 50)) ?></span>
    </div>
    <div style="padding:1.5rem 0 2rem;" data-aos="fade-up">
      <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:0.85rem;flex-wrap:wrap;">
        <span style="background:<?= $color ?>20;color:<?= $color ?>;border:1px solid <?= $color ?>40;border-radius:999px;padding:0.2rem 0.7rem;font-size:0.7rem;font-weight:700;text-transform:uppercase;">
          <i class="fa-solid <?= $icon ?>"></i> <?= ucfirst($post['category']) ?>
        </span>
        <span style="font-size:0.75rem;color:rgba(255,255,255,0.4);">
          <i class="fa-solid fa-eye" style="margin-right:0.2rem;"></i><?= number_format($post['views']) ?> views
          · <?= count($replies) ?> replies
          · <?= timeAgo($post['created_at']) ?>
        </span>
      </div>
      <h1 style="font-size:clamp(1.4rem,3vw,2rem);color:#fff;line-height:1.3;"><?= e($post['title']) ?></h1>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2rem 0 4rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 280px;gap:2.5rem;align-items:flex-start;">

      <!-- Post + Replies -->
      <div>

        <!-- Original Post -->
        <div class="kw-card" style="padding:2rem;margin-bottom:1.5rem;" id="post-body">
          <!-- Author row -->
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--kw-border);">
            <div style="display:flex;align-items:center;gap:0.75rem;">
              <div style="width:44px;height:44px;border-radius:50%;background:<?= $color ?>20;color:<?= $color ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;flex-shrink:0;">
                <?= strtoupper(mb_substr($post['author_name'],0,1)) ?>
              </div>
              <div>
                <a href="<?= url('community/profile/'.$post['author_id']) ?>" style="font-weight:700;font-size:0.9rem;color:var(--kw-text-primary);text-decoration:none;">
                  <?= e($post['author_name']) ?>
                </a>
                <div style="font-size:0.72rem;color:var(--kw-text-muted);">
                  <?= $post['author_posts'] ?> posts · Member since <?= date('M Y', strtotime($post['author_since'])) ?>
                </div>
              </div>
            </div>
            <div style="display:flex;gap:0.5rem;align-items:center;">
              <button class="like-btn kw-btn kw-btn-ghost kw-btn-sm" data-type="post" data-id="<?= $postId ?>" style="font-size:0.75rem;">
                <i class="fa-solid fa-heart" style="color:#EF4444;"></i>
                <span class="like-count"><?= $post['likes'] ?></span>
              </button>
              <?php if ($myId == $post['author_id'] || ($_SESSION['user_role'] ?? '') === 'admin'): ?>
              <a href="<?= url('community/edit-post/'.$postId) ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.72rem;">
                <i class="fa-solid fa-pen"></i> Edit
              </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Post body -->
          <div class="community-body" style="font-size:0.9rem;line-height:1.8;color:var(--kw-text-secondary);">
            <?= nl2br(e($post['body'])) ?>
          </div>

          <!-- Tags -->
          <?php if (!empty($tags)): ?>
          <div style="display:flex;gap:0.4rem;flex-wrap:wrap;margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--kw-border);">
            <?php foreach ($tags as $tag): ?>
            <a href="<?= url('community') ?>?q=<?= urlencode($tag) ?>"
               style="background:var(--kw-bg-alt);color:var(--kw-text-muted);border:1px solid var(--kw-border);border-radius:999px;padding:0.2rem 0.65rem;font-size:0.72rem;text-decoration:none;">
              #<?= e($tag) ?>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Replies Header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
          <h3 style="font-size:1rem;"><?= count($replies) ?> <?= count($replies) === 1 ? 'Reply' : 'Replies' ?></h3>
        </div>

        <!-- Replies List -->
        <div id="replies-container" style="display:flex;flex-direction:column;gap:0.85rem;margin-bottom:2rem;">
          <?php foreach ($replies as $reply):
            $rInitial = strtoupper(mb_substr($reply['author_name'],0,1));
          ?>
          <div class="kw-card reply-card" id="reply-<?= $reply['id'] ?>" style="padding:1.5rem;">
            <div style="display:flex;align-items:flex-start;gap:0.75rem;">
              <div style="width:36px;height:36px;border-radius:50%;background:var(--kw-bg-alt);color:var(--kw-text-secondary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;flex-shrink:0;border:1.5px solid var(--kw-border);">
                <?= $rInitial ?>
              </div>
              <div style="flex:1;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.4rem;flex-wrap:wrap;gap:0.3rem;">
                  <div>
                    <a href="<?= url('community/profile/'.$reply['author_id']) ?>" style="font-weight:700;font-size:0.82rem;color:var(--kw-text-primary);text-decoration:none;"><?= e($reply['author_name']) ?></a>
                    <span style="font-size:0.72rem;color:var(--kw-text-muted);margin-left:0.4rem;">· <?= timeAgo($reply['created_at']) ?></span>
                    <?php if ($reply['author_id'] == $post['author_id']): ?>
                    <span style="background:var(--kw-primary);color:#0A0F1A;border-radius:999px;padding:0.1rem 0.45rem;font-size:0.6rem;font-weight:700;margin-left:0.4rem;">OP</span>
                    <?php endif; ?>
                  </div>
                  <button class="like-btn kw-btn kw-btn-ghost kw-btn-sm" data-type="reply" data-id="<?= $reply['id'] ?>" style="font-size:0.72rem;padding:0.2rem 0.6rem;">
                    <i class="fa-solid fa-heart" style="color:#EF4444;font-size:0.65rem;"></i>
                    <span class="like-count"><?= $reply['likes'] ?></span>
                  </button>
                </div>
                <div style="font-size:0.875rem;line-height:1.7;color:var(--kw-text-secondary);">
                  <?= nl2br(e($reply['body'])) ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Reply Form -->
        <div class="kw-card" style="padding:1.5rem;" id="reply-form-section">
          <h4 style="margin-bottom:1rem;font-size:0.95rem;">Leave a Reply</h4>

          <?php if ($myId): ?>
          <form id="reply-form" novalidate>
            <?= csrfField() ?>
            <input type="hidden" name="post_id" value="<?= $postId ?>">
            <textarea name="body" id="reply-body" rows="5" placeholder="Share your thoughts, answer, or insight..."
                      style="width:100%;resize:vertical;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1rem;font-family:var(--font-body);font-size:0.875rem;color:var(--kw-text-primary);outline:none;transition:border-color 0.2s;margin-bottom:0.75rem;"
                      onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''"></textarea>
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.5rem;">
              <span style="font-size:0.72rem;color:var(--kw-text-muted);">
                <i class="fa-solid fa-circle-info"></i> Be specific and constructive. Markdown not supported.
              </span>
              <button type="submit" class="kw-btn kw-btn-primary" id="reply-submit-btn">
                <i class="fa-solid fa-reply"></i> Post Reply
              </button>
            </div>
            <div id="reply-form-error" style="display:none;margin-top:0.75rem;padding:0.65rem 1rem;background:#ef444415;border:1px solid #ef444430;border-radius:var(--kw-radius-md);font-size:0.82rem;color:#EF4444;"></div>
          </form>

          <?php else: ?>
          <div style="text-align:center;padding:1.5rem;background:var(--kw-bg-alt);border-radius:var(--kw-radius-md);">
            <p style="color:var(--kw-text-muted);margin-bottom:1rem;font-size:0.875rem;">Join the discussion — create a free account or log in to reply.</p>
            <div style="display:flex;gap:0.65rem;justify-content:center;flex-wrap:wrap;">
              <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-user-plus"></i> Create Account</a>
              <a href="<?= url('portal/login') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">Log In</a>
            </div>
          </div>
          <?php endif; ?>
        </div>

      </div>

      <!-- Sidebar -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);display:flex;flex-direction:column;gap:1.25rem;">

        <!-- Post Meta -->
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Post Info</h5>
          <?php foreach ([
            ['fa-calendar','Posted', date('d M Y', strtotime($post['created_at']))],
            ['fa-tag','Category', ucfirst($post['category'])],
            ['fa-eye','Views', number_format($post['views'])],
            ['fa-heart','Likes', $post['likes']],
            ['fa-comment','Replies', count($replies)],
          ] as $meta): ?>
          <div style="display:flex;justify-content:space-between;font-size:0.8rem;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);">
            <span style="color:var(--kw-text-muted);display:flex;align-items:center;gap:0.35rem;">
              <i class="fa-solid <?= $meta[0] ?>" style="color:var(--kw-primary);width:12px;font-size:0.7rem;"></i><?= $meta[1] ?>
            </span>
            <span style="font-weight:600;"><?= $meta[2] ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- About Author -->
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">About the Author</h5>
          <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
            <div style="width:42px;height:42px;border-radius:50%;background:<?= $color ?>20;color:<?= $color ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;flex-shrink:0;">
              <?= strtoupper(mb_substr($post['author_name'],0,1)) ?>
            </div>
            <div>
              <div style="font-weight:700;font-size:0.875rem;"><?= e($post['author_name']) ?></div>
              <?php if ($post['author_company']): ?><div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= e($post['author_company']) ?></div><?php endif; ?>
            </div>
          </div>
          <div style="font-size:0.78rem;color:var(--kw-text-muted);">
            <?= $post['author_posts'] ?> posts · Member since <?= date('M Y', strtotime($post['author_since'])) ?>
          </div>
          <a href="<?= url('community/profile/'.$post['author_id']) ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="margin-top:0.85rem;width:100%;justify-content:center;font-size:0.78rem;">
            View Profile
          </a>
        </div>

        <!-- Related Posts -->
        <?php if (!empty($relatedPosts)): ?>
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">
            Related <?= ucfirst($post['category']) ?>s
          </h5>
          <?php foreach ($relatedPosts as $rp): ?>
          <div style="padding:0.45rem 0;border-bottom:1px solid var(--kw-border);">
            <a href="<?= url('community/post/'.$rp['id']) ?>" style="font-size:0.8rem;color:var(--kw-text-secondary);text-decoration:none;line-height:1.4;display:block;">
              <?= e(truncate($rp['title'], 65)) ?>
            </a>
            <span style="font-size:0.68rem;color:var(--kw-text-muted);"><?= timeAgo($rp['created_at']) ?></span>
          </div>
          <?php endforeach; ?>
          <a href="<?= url('community').'?cat='.$post['category'] ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="margin-top:0.75rem;width:100%;justify-content:center;font-size:0.75rem;">
            All <?= ucfirst($post['category']) ?>s
          </a>
        </div>
        <?php endif; ?>

        <!-- Back link -->
        <a href="<?= url('community') ?>" class="kw-btn kw-btn-ghost" style="justify-content:center;">
          <i class="fa-solid fa-arrow-left"></i> Back to Community
        </a>

      </div>
    </div>
  </div>
</section>

<script>
// ---- Reply form ----
document.getElementById('reply-form')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn     = document.getElementById('reply-submit-btn');
  const errDiv  = document.getElementById('reply-form-error');
  const body    = document.getElementById('reply-body').value.trim();
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');

  if (!body || body.length < 5) {
    errDiv.textContent = 'Reply must be at least 5 characters.';
    errDiv.style.display = 'block';
    return;
  }

  btn.disabled = true;
  btn.innerHTML = '<div class="kw-spinner" style="width:14px;height:14px;border-top-color:#0A0F1A;display:inline-block;margin-right:6px;"></div>Posting...';
  errDiv.style.display = 'none';

  const formData = new FormData(this);

  try {
    const resp = await fetch('<?= url('api/community-reply') ?>', {
      method: 'POST',
      headers: { 'X-CSRF-Token': csrfMeta?.content || '' },
      body: formData
    });
    const data = await resp.json();

    if (data.success) {
      document.getElementById('reply-body').value = '';
      appendReply(data.data.reply);
      window.Krest?.toast('Reply posted!', 'success');
      document.getElementById('replies-container').lastElementChild?.scrollIntoView({ behavior:'smooth', block:'center' });
    } else {
      errDiv.textContent = data.message || 'Failed to post reply.';
      errDiv.style.display = 'block';
    }
  } catch(err) {
    errDiv.textContent = 'Connection error. Please try again.';
    errDiv.style.display = 'block';
  }

  btn.disabled = false;
  btn.innerHTML = '<i class="fa-solid fa-reply"></i> Post Reply';
});

function appendReply(r) {
  const container = document.getElementById('replies-container');
  const div = document.createElement('div');
  div.className = 'kw-card reply-card';
  div.id = 'reply-' + r.id;
  div.style.cssText = 'padding:1.5rem;animation:fadeInUp 0.3s ease;';
  div.innerHTML = `
    <div style="display:flex;align-items:flex-start;gap:0.75rem;">
      <div style="width:36px;height:36px;border-radius:50%;background:var(--kw-bg-alt);color:var(--kw-text-secondary);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;flex-shrink:0;border:1.5px solid var(--kw-border);">
        ${r.initial}
      </div>
      <div style="flex:1;">
        <div style="margin-bottom:0.4rem;">
          <strong style="font-size:0.82rem;">${r.author_name}</strong>
          <span style="font-size:0.72rem;color:var(--kw-text-muted);margin-left:0.4rem;">· just now</span>
        </div>
        <div style="font-size:0.875rem;line-height:1.7;color:var(--kw-text-secondary);">${escHtml(r.body).replace(/\n/g,'<br>')}</div>
      </div>
    </div>
  `;
  container.appendChild(div);

  // Update reply count in header
  const hdr = document.querySelector('h3');
  if (hdr && hdr.textContent.includes('Repl')) {
    const curr = parseInt(hdr.textContent) || 0;
    hdr.textContent = (curr + 1) + ' ' + (curr + 1 === 1 ? 'Reply' : 'Replies');
  }
}

function escHtml(s) {
  const d = document.createElement('div');
  d.textContent = s;
  return d.innerHTML;
}

// ---- Like buttons ----
document.addEventListener('click', async function(e) {
  const btn = e.target.closest('.like-btn');
  if (!btn) return;

  <?php if (!$myId): ?>
  window.Krest?.toast('Log in to like posts and replies.', 'warning');
  return;
  <?php endif; ?>

  const type = btn.dataset.type;
  const id   = btn.dataset.id;

  try {
    const fd = new FormData();
    fd.append('type', type);
    fd.append('id', id);
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');

    const resp = await fetch('<?= url('api/community-like') ?>', {
      method: 'POST',
      headers: { 'X-CSRF-Token': csrfMeta?.content || '' },
      body: fd
    });
    const data = await resp.json();
    if (data.success) {
      const countEl = btn.querySelector('.like-count');
      if (countEl) countEl.textContent = data.data.likes;
      btn.style.background = data.data.liked ? '#ef444415' : '';
    } else {
      window.Krest?.toast(data.message || 'Could not like.', 'warning');
    }
  } catch(err) {}
});

@keyframes fadeInUp {
  from { opacity:0; transform:translateY(12px); }
  to   { opacity:1; transform:translateY(0); }
}
</script>
<style>
@keyframes fadeInUp { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
.community-body p { margin-bottom: 0.85rem; }
@media(max-width:1024px){
  .kw-container > div[style*="grid-template-columns:1fr 280px"] { grid-template-columns:1fr!important; }
  div[style*="position:sticky"] { position:static!important; }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>