<?php
require_once __DIR__ . '/../../includes/header.php';

$profileId = (int)($_GET['id'] ?? 0);
if (!$profileId) { redirect('community'); }

try {
    $pdo = db();

    $userStmt = $pdo->prepare("
        SELECT id, name, email, company, phone, role, created_at
        FROM users WHERE id = ? LIMIT 1
    ");
    $userStmt->execute([$profileId]);
    $profile = $userStmt->fetch();

    if (!$profile) { redirect('community'); }

    // Posts by user
    $postsStmt = $pdo->prepare("
        SELECT p.id, p.title, p.category, p.views, p.likes, p.created_at,
               (SELECT COUNT(*) FROM community_replies r WHERE r.post_id = p.id AND r.is_active=1) AS reply_count
        FROM community_posts p
        WHERE p.user_id = ? AND p.is_active = 1
        ORDER BY p.created_at DESC
        LIMIT 20
    ");
    $postsStmt->execute([$profileId]);
    $userPosts = $postsStmt->fetchAll();

    // Replies by user
    $repliesStmt = $pdo->prepare("
        SELECT r.body, r.created_at, r.likes, p.id AS post_id, p.title AS post_title
        FROM community_replies r
        JOIN community_posts p ON p.id = r.post_id
        WHERE r.user_id = ? AND r.is_active = 1
        ORDER BY r.created_at DESC
        LIMIT 10
    ");
    $repliesStmt->execute([$profileId]);
    $userReplies = $repliesStmt->fetchAll();

    // Stats
    $stats = $pdo->prepare("
        SELECT
            (SELECT COUNT(*) FROM community_posts WHERE user_id=? AND is_active=1) AS total_posts,
            (SELECT COUNT(*) FROM community_replies WHERE user_id=? AND is_active=1) AS total_replies,
            (SELECT COALESCE(SUM(likes),0) FROM community_posts WHERE user_id=? AND is_active=1) AS post_likes,
            (SELECT COALESCE(SUM(views),0) FROM community_posts WHERE user_id=? AND is_active=1) AS total_views
    ");
    $stats->execute([$profileId, $profileId, $profileId, $profileId]);
    $profileStats = $stats->fetch();

} catch(Exception $e) {
    error_log('Community profile error: ' . $e->getMessage());
    redirect('community');
}

$page_title       = e($profile['name']) . ' — Community — ' . APP_NAME;
$page_description = e($profile['name']) . "'s community profile on " . APP_NAME;
$initial          = strtoupper(mb_substr($profile['name'], 0, 1));

$catColors = ['discussion'=>'#3B82F6','question'=>'#F5A800','insight'=>'#22C55E','tutorial'=>'#A855F7','news'=>'#EF4444'];
?>

<section class="kw-page-hero" style="padding-bottom:0;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>" style="color:rgba(255,255,255,0.5);">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('community') ?>" style="color:rgba(255,255,255,0.5);">Community</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span style="color:rgba(255,255,255,0.8);"><?= e($profile['name']) ?></span>
    </div>

    <!-- Profile Header -->
    <div style="display:flex;align-items:flex-end;gap:2rem;padding:2rem 0;flex-wrap:wrap;" data-aos="fade-up">
      <div style="width:80px;height:80px;border-radius:50%;background:var(--kw-primary);color:#0A0F1A;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:2rem;flex-shrink:0;border:3px solid rgba(255,255,255,0.15);">
        <?= $initial ?>
      </div>
      <div>
        <h1 style="color:#fff;margin-bottom:0.2rem;"><?= e($profile['name']) ?></h1>
        <?php if ($profile['company']): ?>
        <div style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:0.4rem;">
          <i class="fa-solid fa-building" style="margin-right:0.35rem;"></i><?= e($profile['company']) ?>
        </div>
        <?php endif; ?>
        <div style="color:rgba(255,255,255,0.35);font-size:0.78rem;">
          <i class="fa-solid fa-calendar" style="margin-right:0.35rem;"></i>Member since <?= date('F Y', strtotime($profile['created_at'])) ?>
        </div>
      </div>
    </div>

    <!-- Stats bar -->
    <div style="display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid rgba(255,255,255,0.08);padding:1.25rem 0;">
      <?php foreach ([
        [$profileStats['total_posts'],'Posts','fa-pen-to-square'],
        [$profileStats['total_replies'],'Replies','fa-reply'],
        [$profileStats['post_likes'],'Likes Received','fa-heart'],
        [$profileStats['total_views'],'Post Views','fa-eye'],
      ] as $s): ?>
      <div style="text-align:center;">
        <div style="font-size:1.4rem;font-weight:800;color:var(--kw-primary);font-family:var(--font-heading);"><?= number_format($s[0]) ?></div>
        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);"><?= $s[1] ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:2.5rem 0 4rem;">
  <div class="kw-container" style="max-width:860px;">

    <!-- Tabs -->
    <div class="kw-tabs" data-tabs-container style="margin-bottom:1.75rem;">
      <button class="kw-tab-btn active" data-tab="posts">Posts (<?= $profileStats['total_posts'] ?>)</button>
      <button class="kw-tab-btn" data-tab="replies">Replies (<?= $profileStats['total_replies'] ?>)</button>
    </div>

    <!-- Posts Tab -->
    <div class="kw-tab-panel active" data-tab-panel="posts">
      <?php if (empty($userPosts)): ?>
      <div class="kw-card" style="padding:2.5rem;text-align:center;">
        <i class="fa-solid fa-pen-to-square" style="font-size:2rem;color:var(--kw-text-muted);margin-bottom:1rem;display:block;"></i>
        <h4>No posts yet</h4>
        <p style="color:var(--kw-text-muted);font-size:0.875rem;">This member hasn't posted yet.</p>
      </div>
      <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        <?php foreach ($userPosts as $p):
          $color = $catColors[$p['category']] ?? '#6B7280'; ?>
        <div class="kw-card" style="padding:1.1rem 1.4rem;">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:0;">
              <span style="background:<?= $color ?>15;color:<?= $color ?>;border:1px solid <?= $color ?>30;border-radius:999px;padding:0.12rem 0.5rem;font-size:0.63rem;font-weight:700;text-transform:uppercase;margin-bottom:0.4rem;display:inline-block;"><?= ucfirst($p['category']) ?></span>
              <a href="<?= url('community/post/'.$p['id']) ?>" style="display:block;font-size:0.9rem;font-weight:600;color:var(--kw-text-primary);text-decoration:none;margin-bottom:0.25rem;line-height:1.3;">
                <?= e($p['title']) ?>
              </a>
              <span style="font-size:0.72rem;color:var(--kw-text-muted);"><?= timeAgo($p['created_at']) ?></span>
            </div>
            <div style="display:flex;gap:1rem;font-size:0.75rem;color:var(--kw-text-muted);flex-shrink:0;">
              <span><i class="fa-solid fa-eye" style="margin-right:0.2rem;"></i><?= number_format($p['views']) ?></span>
              <span><i class="fa-solid fa-heart" style="color:#EF4444;margin-right:0.2rem;"></i><?= $p['likes'] ?></span>
              <span><i class="fa-solid fa-comment" style="color:var(--kw-primary);margin-right:0.2rem;"></i><?= $p['reply_count'] ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <!-- Replies Tab -->
    <div class="kw-tab-panel" data-tab-panel="replies">
      <?php if (empty($userReplies)): ?>
      <div class="kw-card" style="padding:2.5rem;text-align:center;">
        <i class="fa-solid fa-reply" style="font-size:2rem;color:var(--kw-text-muted);margin-bottom:1rem;display:block;"></i>
        <h4>No replies yet</h4>
        <p style="color:var(--kw-text-muted);font-size:0.875rem;">This member hasn't replied to any posts yet.</p>
      </div>
      <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:0.75rem;">
        <?php foreach ($userReplies as $r): ?>
        <div class="kw-card" style="padding:1.25rem 1.4rem;">
          <div style="margin-bottom:0.65rem;">
            <span style="font-size:0.72rem;color:var(--kw-text-muted);">Replied to:</span>
            <a href="<?= url('community/post/'.$r['post_id']) ?>" style="font-size:0.82rem;font-weight:600;color:var(--kw-primary);text-decoration:none;margin-left:0.35rem;">
              <?= e(truncate($r['post_title'], 70)) ?>
            </a>
          </div>
          <p style="font-size:0.85rem;color:var(--kw-text-secondary);line-height:1.6;margin-bottom:0.5rem;">
            <?= e(truncate(strip_tags($r['body']), 200)) ?>
          </p>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.72rem;color:var(--kw-text-muted);"><?= timeAgo($r['created_at']) ?></span>
            <span style="font-size:0.72rem;color:var(--kw-text-muted);">
              <i class="fa-solid fa-heart" style="color:#EF4444;margin-right:0.2rem;"></i><?= $r['likes'] ?> likes
            </span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div style="text-align:center;margin-top:2rem;">
      <a href="<?= url('community') ?>" class="kw-btn kw-btn-ghost">
        <i class="fa-solid fa-arrow-left"></i> Back to Community
      </a>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>