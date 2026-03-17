<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Community — ' . APP_NAME;
$page_description = 'Join the Krestworks community — discuss technology, share insights, ask questions, and learn from developers and business leaders across Africa.';


// Pagination
$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 15;
$offset  = ($page - 1) * $perPage;

// Category filter
$validCats  = ['all','discussion','question','insight','tutorial','news'];
$catFilter  = in_array($_GET['cat'] ?? 'all', $validCats) ? ($_GET['cat'] ?? 'all') : 'all';

// Search
$search = trim(htmlspecialchars($_GET['q'] ?? '', ENT_QUOTES, 'UTF-8'));

// Fetch posts
try {
    $pdo = db();

    $where  = ['p.is_active = 1'];
    $params = [];

    if ($catFilter !== 'all') {
        $where[]  = 'p.category = ?';
        $params[] = $catFilter;
    }
    if ($search) {
        $where[]  = '(p.title LIKE ? OR p.body LIKE ?)';
        $params[] = "%{$search}%";
        $params[] = "%{$search}%";
    }

    $whereSQL = implode(' AND ', $where);

    // Count total
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM community_posts p WHERE {$whereSQL}");
    $countStmt->execute($params);
    $total    = (int)$countStmt->fetchColumn();
    $maxPages = max(1, ceil($total / $perPage));

    // Fetch page
    $stmt = $pdo->prepare("
        SELECT p.id, p.title, p.body, p.category, p.tags, p.views, p.likes,
               p.created_at, p.is_pinned,
               u.id   AS author_id,
               u.name AS author_name,
               (SELECT COUNT(*) FROM community_replies r WHERE r.post_id = p.id AND r.is_active = 1) AS reply_count
        FROM community_posts p
        JOIN users u ON u.id = p.user_id
        WHERE {$whereSQL}
        ORDER BY p.is_pinned DESC, p.created_at DESC
        LIMIT {$perPage} OFFSET {$offset}
    ");
    $stmt->execute($params);
    $posts = $stmt->fetchAll();

    // Trending posts (most views last 7 days)
    $trending = $pdo->query("
        SELECT p.id, p.title, p.category,
               (SELECT COUNT(*) FROM community_replies r WHERE r.post_id = p.id) AS reply_count
        FROM community_posts p
        WHERE p.is_active = 1 AND p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ORDER BY p.views DESC
        LIMIT 5
    ")->fetchAll();

    // Popular tags
    $allTagRows = $pdo->query("SELECT tags FROM community_posts WHERE tags IS NOT NULL AND is_active=1 LIMIT 200")->fetchAll(PDO::FETCH_COLUMN);
    $tagCounts  = [];
    foreach ($allTagRows as $row) {
        $tags = json_decode($row, true) ?: [];
        foreach ($tags as $tag) {
            $tagCounts[$tag] = ($tagCounts[$tag] ?? 0) + 1;
        }
    }
    arsort($tagCounts);
    $popularTags = array_slice($tagCounts, 0, 20, true);

    // Community stats
    $stats = $pdo->query("
        SELECT
            (SELECT COUNT(*) FROM community_posts WHERE is_active=1) AS total_posts,
            (SELECT COUNT(*) FROM community_replies WHERE is_active=1) AS total_replies,
            (SELECT COUNT(*) FROM users) AS total_members,
            (SELECT COUNT(*) FROM community_posts WHERE is_active=1 AND created_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)) AS posts_this_week
    ")->fetch();

} catch (Exception $e) {
    error_log('Community index error: ' . $e->getMessage());
    $posts = $trending = $popularTags = [];
    $total = $maxPages = 0;
    $stats = ['total_posts'=>0,'total_replies'=>0,'total_members'=>0,'posts_this_week'=>0];
}

$catColors = ['discussion'=>'#3B82F6','question'=>'#F5A800','insight'=>'#22C55E','tutorial'=>'#A855F7','news'=>'#EF4444'];
$catIcons  = ['discussion'=>'fa-comments','question'=>'fa-circle-question','insight'=>'fa-lightbulb','tutorial'=>'fa-graduation-cap','news'=>'fa-newspaper'];
?>

<!-- Hero -->
<section class="kw-page-hero" style="padding-bottom:0;">
  <div class="kw-container">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.5rem;padding-bottom:2rem;" data-aos="fade-up">
      <div>
        <span class="label"><i class="fa-solid fa-users"></i> Knowledge Village</span>
        <h1 style="margin-bottom:0.5rem;">Krestworks Community</h1>
        <p style="color:rgba(255,255,255,0.6);max-width:540px;">A space for developers, tech leaders, and business owners to share knowledge, ask questions, and grow together. Read freely — join to participate.</p>
      </div>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="<?= url('community/new-post') ?>" class="kw-btn kw-btn-primary kw-btn-lg">
          <i class="fa-solid fa-pen-to-square"></i> New Post
        </a>
      <?php else: ?>
        <div style="display:flex;gap:0.65rem;flex-wrap:wrap;">
          <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-user-plus"></i> Join Community</a>
          <a href="<?= url('portal/login') ?>" class="kw-btn kw-btn-ghost" style="border-color:rgba(255,255,255,0.2);color:rgba(255,255,255,0.7);">Log In</a>
        </div>
      <?php endif; ?>
    </div>

    <!-- Stats strip -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid rgba(255,255,255,0.08);padding:1.25rem 0;">
      <?php
      $communityStats = [
        [$stats['total_members'],'Members','fa-users'],
        [$stats['total_posts'],'Discussions','fa-comments'],
        [$stats['total_replies'],'Replies','fa-reply'],
        [$stats['posts_this_week'],'New This Week','fa-fire'],
      ];
      foreach ($communityStats as $cs): ?>
      <div style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);">
          <?= number_format((int)$cs[0]) ?>
        </div>
        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);"><?= $cs[1] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Main Content -->
<section style="background:var(--kw-bg);padding:2.5rem 0 4rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:2.5rem;align-items:flex-start;">

      <!-- Feed -->
      <div>

        <!-- Search + Filter bar -->
        <div style="display:flex;gap:0.75rem;margin-bottom:1.5rem;flex-wrap:wrap;">
          <div style="flex:1;min-width:220px;position:relative;">
            <i class="fa-solid fa-search" style="position:absolute;left:0.9rem;top:50%;transform:translateY(-50%);color:var(--kw-text-muted);font-size:0.8rem;"></i>
            <form method="GET" action="<?= url('community') ?>">
              <?php if ($catFilter !== 'all'): ?><input type="hidden" name="cat" value="<?= e($catFilter) ?>"><?php endif; ?>
              <input type="text" name="q" value="<?= e($search) ?>"
                     placeholder="Search discussions..."
                     style="width:100%;padding:0.65rem 1rem 0.65rem 2.25rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;"
                     onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
            </form>
          </div>
          <div style="display:flex;gap:0.4rem;flex-wrap:wrap;">
            <?php foreach (['all'=>['All','#6B7280']] + array_combine(array_keys($catColors),array_map(fn($c,$i)=>[$c,$i],array_map('ucfirst',array_keys($catColors)),array_values($catColors))) as $cat => $info): ?>
            <a href="<?= url('community') ?>?cat=<?= $cat ?><?= $search ? '&q='.urlencode($search) : '' ?>"
               class="kw-btn kw-btn-sm"
               style="font-size:0.75rem;padding:0.3rem 0.75rem;<?= $catFilter===$cat ? "background:{$info[1]};color:#fff;border-color:{$info[1]};" : '' ?>">
              <?= $cat !== 'all' ? '<i class="fa-solid '.($catIcons[$cat] ?? 'fa-tag').' " style="font-size:0.65rem;"></i> ' : '' ?><?= $info[0] ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Search result note -->
        <?php if ($search): ?>
        <div style="margin-bottom:1rem;font-size:0.85rem;color:var(--kw-text-muted);">
          Showing <?= $total ?> result<?= $total !== 1 ? 's' : '' ?> for "<strong style="color:var(--kw-text-primary);"><?= e($search) ?></strong>"
          — <a href="<?= url('community') ?>" style="color:var(--kw-primary);">clear search</a>
        </div>
        <?php endif; ?>

        <!-- Posts -->
        <?php if (empty($posts)): ?>
        <div class="kw-card" style="padding:3rem;text-align:center;">
          <i class="fa-solid fa-comments" style="font-size:2.5rem;color:var(--kw-text-muted);margin-bottom:1rem;display:block;"></i>
          <h4>No posts yet<?= $catFilter !== 'all' ? ' in this category' : '' ?><?= $search ? ' matching your search' : '' ?></h4>
          <p style="color:var(--kw-text-muted);font-size:0.875rem;margin:0.5rem 0 1.5rem;">Be the first to start a discussion!</p>
          <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="<?= url('community/new-post') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-pen-to-square"></i> Create First Post</a>
          <?php else: ?>
            <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-user-plus"></i> Join to Post</a>
          <?php endif; ?>
        </div>
        <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:0.85rem;" id="posts-feed">
          <?php foreach ($posts as $post):
            $tags    = json_decode($post['tags'] ?? '[]', true) ?: [];
            $excerpt = mb_substr(strip_tags($post['body']), 0, 140);
            $color   = $catColors[$post['category']] ?? '#6B7280';
            $icon    = $catIcons[$post['category']] ?? 'fa-tag';
            $initial = strtoupper(mb_substr($post['author_name'], 0, 1));
          ?>
          <div class="kw-card community-post-card" style="padding:1.25rem 1.5rem;<?= $post['is_pinned'] ? 'border-left:3px solid var(--kw-primary);' : '' ?>">
            <?php if ($post['is_pinned']): ?>
            <div style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--kw-primary);margin-bottom:0.5rem;">
              <i class="fa-solid fa-thumbtack"></i> Pinned
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:1rem;align-items:flex-start;">
              <!-- Avatar -->
              <div style="width:38px;height:38px;border-radius:50%;background:<?= $color ?>20;color:<?= $color ?>;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;flex-shrink:0;border:2px solid <?= $color ?>30;">
                <?= $initial ?>
              </div>

              <!-- Content -->
              <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;margin-bottom:0.35rem;">
                  <span style="background:<?= $color ?>15;color:<?= $color ?>;border:1px solid <?= $color ?>30;border-radius:999px;padding:0.15rem 0.55rem;font-size:0.65rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;">
                    <i class="fa-solid <?= $icon ?>" style="font-size:0.55rem;"></i> <?= ucfirst($post['category']) ?>
                  </span>
                  <span style="font-size:0.72rem;color:var(--kw-text-muted);">
                    by <a href="<?= url('community/profile/' . $post['author_id']) ?>" style="color:var(--kw-text-secondary);font-weight:600;"><?= e($post['author_name']) ?></a>
                    · <?= timeAgo($post['created_at']) ?>
                  </span>
                </div>

                <a href="<?= url('community/post/' . $post['id']) ?>" style="text-decoration:none;">
                  <h3 style="font-size:0.975rem;margin-bottom:0.3rem;color:var(--kw-text-primary);line-height:1.4;transition:color 0.2s;"
                      onmouseover="this.style.color='var(--kw-primary)'" onmouseout="this.style.color='var(--kw-text-primary)'">
                    <?= e($post['title']) ?>
                  </h3>
                </a>

                <p style="font-size:0.8rem;color:var(--kw-text-muted);margin-bottom:0.65rem;line-height:1.5;">
                  <?= e($excerpt) ?><?= mb_strlen(strip_tags($post['body'])) > 140 ? '...' : '' ?>
                </p>

                <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                  <!-- Tags -->
                  <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                  <a href="<?= url('community') ?>?q=<?= urlencode($tag) ?>"
                     style="background:var(--kw-bg-alt);color:var(--kw-text-muted);border-radius:999px;padding:0.15rem 0.6rem;font-size:0.68rem;text-decoration:none;border:1px solid var(--kw-border);">
                    #<?= e($tag) ?>
                  </a>
                  <?php endforeach; ?>

                  <div style="margin-left:auto;display:flex;gap:0.85rem;font-size:0.75rem;color:var(--kw-text-muted);">
                    <span><i class="fa-solid fa-eye" style="margin-right:0.25rem;"></i><?= number_format($post['views']) ?></span>
                    <span><i class="fa-solid fa-heart" style="margin-right:0.25rem;color:#EF4444;"></i><?= number_format($post['likes']) ?></span>
                    <span><i class="fa-solid fa-comment" style="margin-right:0.25rem;color:var(--kw-primary);"></i><?= number_format($post['reply_count']) ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($maxPages > 1): ?>
        <div style="display:flex;gap:0.4rem;justify-content:center;margin-top:2rem;flex-wrap:wrap;">
          <?php for ($p = 1; $p <= $maxPages; $p++): ?>
          <a href="<?= url('community') ?>?page=<?= $p ?><?= $catFilter !== 'all' ? '&cat='.$catFilter : '' ?><?= $search ? '&q='.urlencode($search) : '' ?>"
             class="kw-btn kw-btn-sm" style="<?= $p === $page ? 'background:var(--kw-primary);color:#0A0F1A;border-color:var(--kw-primary);' : '' ?>min-width:36px;justify-content:center;">
            <?= $p ?>
          </a>
          <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>

      </div>

      <!-- Sidebar -->
      <div style="position:sticky;top:calc(var(--kw-nav-height)+1rem);display:flex;flex-direction:column;gap:1.25rem;">

        <!-- Join CTA for guests -->
        <?php if (empty($_SESSION['user_id'])): ?>
        <div class="kw-card" style="padding:1.5rem;text-align:center;border-top:3px solid var(--kw-primary);">
          <i class="fa-solid fa-users" style="font-size:1.75rem;color:var(--kw-primary);margin-bottom:0.75rem;display:block;"></i>
          <h4 style="margin-bottom:0.4rem;">Join the Community</h4>
          <p style="font-size:0.8rem;color:var(--kw-text-muted);margin-bottom:1.25rem;">Create a free account to post, reply, and like discussions.</p>
          <a href="<?= url('portal/register') ?>" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;margin-bottom:0.5rem;">
            <i class="fa-solid fa-user-plus"></i> Create Free Account
          </a>
          <a href="<?= url('portal/login') ?>" class="kw-btn kw-btn-ghost" style="width:100%;justify-content:center;font-size:0.8rem;">
            Already a member? Log in
          </a>
        </div>
        <?php else: ?>
        <div class="kw-card" style="padding:1.25rem;border-top:3px solid var(--kw-primary);">
          <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
            <div style="width:40px;height:40px;border-radius:50%;background:var(--kw-primary);color:#0A0F1A;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;">
              <?= strtoupper(mb_substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
            </div>
            <div>
              <div style="font-weight:700;font-size:0.875rem;"><?= e($_SESSION['user_name'] ?? '') ?></div>
              <div style="font-size:0.72rem;color:var(--kw-text-muted);">Community Member</div>
            </div>
          </div>
          <a href="<?= url('community/new-post') ?>" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;">
            <i class="fa-solid fa-pen-to-square"></i> New Post
          </a>
        </div>
        <?php endif; ?>

        <!-- Trending -->
        <?php if (!empty($trending)): ?>
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">
            <i class="fa-solid fa-fire" style="color:#F97316;"></i> Trending This Week
          </h5>
          <?php foreach ($trending as $i => $t): ?>
          <div style="display:flex;gap:0.65rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);<?= $i === count($trending)-1 ? 'border:none;' : '' ?>">
            <span style="font-size:1rem;font-weight:800;color:var(--kw-text-muted);line-height:1.2;min-width:18px;"><?= $i+1 ?></span>
            <div>
              <a href="<?= url('community/post/'.$t['id']) ?>" style="font-size:0.8rem;font-weight:600;color:var(--kw-text-secondary);text-decoration:none;line-height:1.4;display:block;margin-bottom:0.2rem;">
                <?= e(truncate($t['title'], 60)) ?>
              </a>
              <span style="font-size:0.68rem;color:var(--kw-text-muted);">
                <i class="fa-solid fa-comment" style="margin-right:0.2rem;color:var(--kw-primary);"></i><?= $t['reply_count'] ?> replies
              </span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Popular Tags -->
        <?php if (!empty($popularTags)): ?>
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">
            <i class="fa-solid fa-tags" style="color:var(--kw-primary);"></i> Popular Topics
          </h5>
          <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
            <?php foreach ($popularTags as $tag => $count): ?>
            <a href="<?= url('community') ?>?q=<?= urlencode($tag) ?>"
               style="background:var(--kw-bg-alt);color:var(--kw-text-secondary);border:1px solid var(--kw-border);border-radius:999px;padding:0.2rem 0.65rem;font-size:0.72rem;text-decoration:none;transition:all 0.2s;"
               onmouseover="this.style.borderColor='var(--kw-primary)';this.style.color='var(--kw-primary)'"
               onmouseout="this.style.borderColor='';this.style.color=''">
              #<?= e($tag) ?> <span style="opacity:0.5;">(<?= $count ?>)</span>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Community Guidelines -->
        <div class="kw-card" style="padding:1.25rem;">
          <h5 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:0.85rem;">
            <i class="fa-solid fa-shield-halved" style="color:var(--kw-primary);"></i> Community Guidelines
          </h5>
          <?php foreach (['Be respectful and constructive','Share real knowledge and experience','Ask specific, well-framed questions','No spam or promotional content','Credit sources when sharing content'] as $rule): ?>
          <div style="display:flex;gap:0.45rem;font-size:0.78rem;color:var(--kw-text-secondary);padding:0.3rem 0;">
            <i class="fa-solid fa-check" style="color:var(--kw-primary);margin-top:0.15rem;font-size:0.65rem;flex-shrink:0;"></i><?= $rule ?>
          </div>
          <?php endforeach; ?>
        </div>

      </div>

    </div>
  </div>
</section>

<style>
.community-post-card { transition: border-color 0.2s, box-shadow 0.2s; }
.community-post-card:hover { box-shadow: var(--kw-shadow-md); border-color: var(--kw-border-hover); }
@media(max-width:1024px) {
  .kw-container > div[style*="grid-template-columns:1fr 300px"] { grid-template-columns:1fr!important; }
  .kw-container > div > div[style*="sticky"] { position:static!important; }
}
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>