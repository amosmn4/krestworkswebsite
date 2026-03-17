<?php
$slug = $_GET['slug'] ?? '';
if (!$slug) { header('Location: ' . url('blog')); exit; }

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../config/config.php';

$pdo  = db();
$stmt = $pdo->prepare('SELECT bp.*, u.name AS author_name FROM blog_posts bp LEFT JOIN users u ON u.id = bp.author_id WHERE bp.slug = ? AND bp.is_published = 1');
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Fallback demo posts for when DB is empty
if (!$post) {
  $demoPosts = [
    'ai-in-enterprise-2025' => [
      'slug'         => 'ai-in-enterprise-2025',
      'title'        => 'How AI Is Transforming Enterprise Software in 2025',
      'category'     => 'AI & Technology',
      'author_name'  => 'Krestworks Team',
      'published_at' => '2025-03-01',
      'read_time'    => '8 min read',
      'excerpt'      => 'From intelligent automation to predictive analytics — here\'s how African enterprises are embedding AI into daily operations.',
      'content'      => '<h2>The Shift Is Already Happening</h2><p>Across East Africa, enterprise software is undergoing a fundamental shift. Systems that were once passive record-keepers are becoming active participants in business operations — surfacing insights, flagging anomalies, automating decisions, and accelerating workflows that once required significant human intervention.</p><p>This shift is not driven by theoretical ambition. It is being driven by measurable business outcomes: faster approvals, fewer errors, real-time visibility, and the ability to scale operations without proportional headcount growth.</p><h2>Where AI Is Making the Biggest Impact</h2><p>The highest-ROI AI applications in enterprise contexts right now are concentrated in three areas: <strong>intelligent document processing</strong>, <strong>predictive analytics and forecasting</strong>, and <strong>conversational AI for internal workflows</strong>.</p><p>Document processing has historically consumed enormous amounts of analyst time — extracting data from invoices, contracts, reports, and forms. AI can now handle this extraction with high accuracy, routing documents through approval workflows automatically and flagging exceptions for human review.</p><p>Predictive analytics are enabling finance teams to forecast cash flow with greater accuracy, supply chain managers to anticipate stockouts before they happen, and HR teams to identify flight risk before key talent exits.</p><h2>The Key Lesson from Early AI Deployments</h2><p>The most important lesson from organisations that have successfully deployed AI is this: <strong>AI succeeds where the data is clean and the process is well-defined</strong>. Deploying AI into a chaotic, poorly understood process doesn\'t improve it — it automates the chaos at scale.</p><p>Before asking "how can we use AI?", the better question is "how well do we understand and document this process?" If the answer is "not very well", the first investment should be process clarity — not AI.</p><h2>What\'s Next</h2><p>Over the next 24 months, we expect to see significant adoption of AI in three emerging areas: autonomous multi-step approval workflows, real-time anomaly detection in financial and operational data, and AI-assisted system configuration — where non-technical users describe what they need and the system adapts accordingly.</p>',
    ],
  ];
  $post = $demoPosts[$slug] ?? null;
}

if (!$post) { header('HTTP/1.0 404 Not Found'); header('Location: ' . url('blog')); exit; }
require_once __DIR__ . '/../includes/header.php';
$page_title       = e($post['title']) . ' — ' . APP_NAME;
$page_description = e(strip_tags($post['excerpt'] ?? ''));


// Related posts
$related = $pdo->prepare('SELECT slug, title, category, published_at FROM blog_posts WHERE is_published = 1 AND slug != ? AND category = ? ORDER BY published_at DESC LIMIT 3');
$related->execute([$slug, $post['category'] ?? '']);
$relatedPosts = $related->fetchAll(PDO::FETCH_ASSOC);

$catColors = ['AI & Technology'=>'#A855F7','Digital Transformation'=>'#F5A800','Architecture'=>'#3B82F6','Development'=>'#22C55E','Enterprise Software'=>'#F97316','Automation'=>'#EF4444','Cloud & Infrastructure'=>'#06B6D4','Cybersecurity'=>'#8B5CF6'];
$catColor  = $catColors[$post['category'] ?? ''] ?? '#6B7280';
?>

<section class="kw-page-hero" style="min-height:320px;">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <a href="<?= url('blog') ?>">Knowledge Hub</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current"><?= e(truncate($post['title'], 40)) ?></span>
    </div>
    <div style="max-width:720px;padding:2.5rem 0 3rem;" data-aos="fade-up">
      <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap;">
        <span style="background:<?= $catColor ?>15;color:<?= $catColor ?>;border-radius:999px;padding:0.25rem 0.75rem;font-size:0.72rem;font-weight:700;"><?= e($post['category'] ?? 'General') ?></span>
        <span style="font-size:0.78rem;color:rgba(255,255,255,0.5);"><i class="fa-regular fa-calendar" style="margin-right:0.3rem;"></i><?= date('F j, Y', strtotime($post['published_at'])) ?></span>
        <?php if (!empty($post['read_time'])): ?><span style="font-size:0.78rem;color:rgba(255,255,255,0.5);">· <?= e($post['read_time']) ?></span><?php endif; ?>
      </div>
      <h1 style="font-size:clamp(1.5rem,3vw,2.25rem);line-height:1.2;margin-bottom:1rem;"><?= e($post['title']) ?></h1>
      <p style="color:rgba(255,255,255,0.6);font-size:0.95rem;line-height:1.7;"><?= e($post['excerpt'] ?? '') ?></p>
      <div style="margin-top:1.25rem;display:flex;align-items:center;gap:0.75rem;">
        <div style="width:36px;height:36px;border-radius:50%;background:<?= $catColor ?>;display:flex;align-items:center;justify-content:center;">
          <i class="fa-solid fa-user" style="color:#0A0F1A;font-size:0.85rem;"></i>
        </div>
        <div>
          <div style="font-size:0.82rem;font-weight:700;color:#fff;"><?= e($post['author_name'] ?? 'Krestworks Team') ?></div>
          <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);">Krestworks Solutions</div>
        </div>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:4rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:3rem;align-items:flex-start;">

      <!-- Article body -->
      <article>
        <div class="blog-content" style="font-size:0.95rem;line-height:1.9;color:var(--kw-text-secondary);" data-aos="fade-up">
          <?= $post['content'] ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($post['tags'])): ?>
        <div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--kw-border);">
          <span style="font-size:0.75rem;font-weight:700;color:var(--kw-text-muted);margin-right:0.5rem;">TAGS:</span>
          <?php foreach (explode(',', $post['tags']) as $tag): ?>
          <a href="<?= url('blog') ?>?q=<?= urlencode(trim($tag)) ?>" style="display:inline-block;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:999px;padding:0.2rem 0.65rem;font-size:0.72rem;color:var(--kw-text-muted);text-decoration:none;margin:0.15rem;"><?= e(trim($tag)) ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Share -->
        <div style="margin-top:2rem;padding:1.5rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-xl);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
          <div>
            <div style="font-size:0.875rem;font-weight:700;margin-bottom:0.15rem;">Found this useful?</div>
            <div style="font-size:0.78rem;color:var(--kw-text-muted);">Share it with your team or network.</div>
          </div>
          <div style="display:flex;gap:0.5rem;">
            <?php
            $shareUrl   = urlencode(url('blog/' . $post['slug']));
            $shareTitle = urlencode($post['title']);
            $shareLinks = [
              ['fa-brands fa-linkedin','#0A66C2','https://www.linkedin.com/sharing/share-offsite/?url='.$shareUrl],
              ['fa-brands fa-twitter','#1DA1F2','https://twitter.com/intent/tweet?url='.$shareUrl.'&text='.$shareTitle],
              ['fa-brands fa-whatsapp','#25D366','https://wa.me/?text='.$shareTitle.'%20'.$shareUrl],
            ];
            foreach ($shareLinks as $share): ?>
            <a href="<?= $share[2] ?>" target="_blank" style="width:36px;height:36px;border-radius:50%;background:<?= $share[1] ?>20;color:<?= $share[1] ?>;display:flex;align-items:center;justify-content:center;font-size:0.85rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='<?= $share[1] ?>';this.style.color='#fff'" onmouseout="this.style.background='<?= $share[1] ?>20';this.style.color='<?= $share[1] ?>'">
              <i class="<?= $share[0] ?>"></i>
            </a>
            <?php endforeach; ?>
            <button onclick="navigator.clipboard.writeText(window.location.href);window.Krest?.toast('Link copied','success')" style="width:36px;height:36px;border-radius:50%;background:var(--kw-bg-alt);border:1px solid var(--kw-border);color:var(--kw-text-muted);cursor:pointer;font-size:0.8rem;transition:all 0.2s;" title="Copy link">
              <i class="fa-solid fa-link"></i>
            </button>
          </div>
        </div>

        <!-- Related posts -->
        <?php if (!empty($relatedPosts)): ?>
        <div style="margin-top:3rem;">
          <h3 style="margin-bottom:1.25rem;">Related Articles</h3>
          <div style="display:flex;flex-direction:column;gap:0;">
            <?php foreach ($relatedPosts as $rel): ?>
            <a href="<?= url('blog/' . $rel['slug']) ?>" style="display:flex;align-items:flex-start;gap:0.85rem;padding:1rem 0;border-bottom:1px solid var(--kw-border);text-decoration:none;">
              <div style="width:42px;height:42px;border-radius:var(--kw-radius-md);background:<?= $catColors[$rel['category']] ?? '#6B7280' ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-file-alt" style="color:<?= $catColors[$rel['category']] ?? '#6B7280' ?>;font-size:0.9rem;"></i>
              </div>
              <div>
                <div style="font-size:0.875rem;font-weight:700;color:var(--kw-text-primary);margin-bottom:0.2rem;line-height:1.3;"><?= e($rel['title']) ?></div>
                <div style="font-size:0.72rem;color:var(--kw-text-muted);"><?= date('M j, Y', strtotime($rel['published_at'])) ?></div>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </article>

      <!-- Sidebar -->
      <aside style="position:sticky;top:calc(var(--kw-nav-height)+1rem);">

        <!-- About author -->
        <div class="kw-card" style="padding:1.5rem;margin-bottom:1.25rem;text-align:center;">
          <div style="width:56px;height:56px;border-radius:50%;background:<?= $catColor ?>;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
            <i class="fa-solid fa-user" style="color:#0A0F1A;font-size:1.1rem;"></i>
          </div>
          <div style="font-weight:700;margin-bottom:0.2rem;"><?= e($post['author_name'] ?? 'Krestworks Team') ?></div>
          <div style="font-size:0.75rem;color:var(--kw-primary);font-weight:600;margin-bottom:0.75rem;">Krestworks Solutions</div>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin:0;">Enterprise software engineers and consultants helping African organisations build better digital systems.</p>
        </div>

        <!-- CTA -->
        <div class="kw-card" style="padding:1.5rem;margin-bottom:1.25rem;border-top:3px solid var(--kw-primary);">
          <h5 style="margin-bottom:0.4rem;">Need a System Like This?</h5>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);margin-bottom:1rem;">We build enterprise systems tailored to your exact workflow. Let's talk.</p>
          <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-sm" style="width:100%;justify-content:center;margin-bottom:0.5rem;"><i class="fa-solid fa-calendar-check"></i> Free Consultation</a>
          <a href="<?= url('demo') ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="width:100%;justify-content:center;"><i class="fa-solid fa-play-circle"></i> View Demo</a>
        </div>

        <!-- Categories -->
        <div class="kw-card" style="padding:1.5rem;">
          <h5 style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--kw-text-muted);margin-bottom:1rem;">Browse Topics</h5>
          <?php foreach (array_keys($catColors) as $cat): ?>
          <a href="<?= url('blog') ?>?cat=<?= urlencode($cat) ?>" style="display:flex;align-items:center;gap:0.5rem;padding:0.45rem 0;border-bottom:1px solid var(--kw-border);font-size:0.82rem;color:var(--kw-text-secondary);text-decoration:none;">
            <span style="width:8px;height:8px;border-radius:50%;background:<?= $catColors[$cat] ?>;flex-shrink:0;"></span>
            <?= $cat ?>
          </a>
          <?php endforeach; ?>
        </div>
      </aside>

    </div>
  </div>
</section>

<style>
.blog-content h2{font-size:1.35rem;font-weight:700;margin:2rem 0 0.85rem;color:var(--kw-text-primary);}
.blog-content h3{font-size:1.1rem;font-weight:700;margin:1.75rem 0 0.7rem;color:var(--kw-text-primary);}
.blog-content p{margin-bottom:1.25rem;}
.blog-content ul,.blog-content ol{padding-left:1.5rem;margin-bottom:1.25rem;}
.blog-content li{margin-bottom:0.5rem;}
.blog-content strong{color:var(--kw-text-primary);font-weight:700;}
.blog-content a{color:var(--kw-primary);text-decoration:underline;}
.blog-content code{background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:4px;padding:0.1rem 0.4rem;font-size:0.85em;font-family:monospace;}
.blog-content pre{background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);padding:1.25rem;overflow-x:auto;margin-bottom:1.25rem;}
.blog-content pre code{background:none;border:none;padding:0;}
.blog-content blockquote{border-left:3px solid var(--kw-primary);padding:1rem 1.25rem;background:rgba(245,168,0,0.06);border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;margin-bottom:1.25rem;font-style:italic;}
@media(max-width:1024px){.kw-container>div[style*="1fr 300px"]{grid-template-columns:1fr!important;}aside[style*="sticky"]{position:static!important;}}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>