<?php
require_once __DIR__ . '/../includes/header.php';
$page_title       = 'Knowledge Hub — ' . APP_NAME;
$page_description = 'Insights on AI in business, digital transformation, enterprise software, system architecture, and automation from the Krestworks team.';


// Fetch published posts from DB
$pdo        = db();
$category   = $_GET['cat'] ?? '';
$search     = trim($_GET['q'] ?? '');
$page_num   = max(1, intval($_GET['page'] ?? 1));
$per_page   = 9;
$offset     = ($page_num - 1) * $per_page;

$where  = ['bp.is_published = 1'];
$params = [];
if ($category) { $where[] = 'bp.category = ?'; $params[] = $category; }
if ($search)   { $where[] = '(bp.title LIKE ? OR bp.excerpt LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }
$whereSQL = implode(' AND ', $where);

$total = $pdo->prepare("SELECT COUNT(*) FROM blog_posts bp WHERE $whereSQL");
$total->execute($params);
$totalPosts = (int)$total->fetchColumn();
$totalPages  = max(1, ceil($totalPosts / $per_page));

$stmt = $pdo->prepare("SELECT bp.*, u.name AS author_name FROM blog_posts bp LEFT JOIN users u ON u.id = bp.author_id WHERE $whereSQL ORDER BY bp.published_at DESC LIMIT ? OFFSET ?");
$stmtParams = array_merge($params, [$per_page, $offset]);
$stmt->execute($stmtParams);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Seed posts for display if DB empty
if (empty($posts)) {
  $posts = [
    ['slug'=>'ai-in-enterprise-2025','title'=>'How AI Is Transforming Enterprise Software in 2025','excerpt'=>'From intelligent automation to predictive analytics — here\'s how African enterprises are embedding AI into daily operations and what results they\'re seeing.','category'=>'AI & Technology','author_name'=>'Krestworks Team','published_at'=>'2025-03-01','read_time'=>'8 min read'],
    ['slug'=>'digital-transformation-common-mistakes','title'=>'5 Common Digital Transformation Mistakes (and How to Avoid Them)','excerpt'=>'Most transformations don\'t fail because of technology. They fail because of these five avoidable mistakes in planning and execution.','category'=>'Digital Transformation','author_name'=>'Krestworks Team','published_at'=>'2025-02-20','read_time'=>'6 min read'],
    ['slug'=>'microservices-vs-monolith','title'=>'Microservices vs Monolith: Which Architecture Is Right for Your Business?','excerpt'=>'The microservices vs monolith debate isn\'t settled by technology — it\'s settled by your team size, stage, and operational maturity.','category'=>'Architecture','author_name'=>'Krestworks Team','published_at'=>'2025-02-10','read_time'=>'7 min read'],
    ['slug'=>'mpesa-api-integration-guide','title'=>'A Practical Guide to M-PESA Daraja API Integration','excerpt'=>'Step-by-step guide to integrating Safaricom\'s Daraja API — covering STK Push, C2B, B2C, callbacks, and handling edge cases in production.','category'=>'Development','author_name'=>'Krestworks Team','published_at'=>'2025-01-28','read_time'=>'10 min read'],
    ['slug'=>'why-off-the-shelf-software-fails','title'=>'Why Off-the-Shelf Software Keeps Failing African Businesses','excerpt'=>'Generic ERP systems designed for Western markets rarely fit the operational reality of businesses in Kenya and East Africa. Here\'s why, and what to do instead.','category'=>'Enterprise Software','author_name'=>'Krestworks Team','published_at'=>'2025-01-15','read_time'=>'5 min read'],
    ['slug'=>'building-secure-php-applications','title'=>'Building Secure PHP Applications: A Practical Checklist','excerpt'=>'CSRF, SQL injection, XSS, session hijacking — every PHP developer\'s guide to building production-grade secure web applications.','category'=>'Development','author_name'=>'Krestworks Team','published_at'=>'2025-01-05','read_time'=>'9 min read'],
    ['slug'=>'database-design-best-practices','title'=>'Database Design Best Practices for Enterprise Applications','excerpt'=>'Normalisation, indexing strategy, soft deletes, audit tables, and the schema decisions that separate maintainable systems from expensive rebuilds.','category'=>'Architecture','author_name'=>'Krestworks Team','published_at'=>'2024-12-20','read_time'=>'7 min read'],
    ['slug'=>'automation-roi-calculation','title'=>'How to Calculate the Real ROI of Business Process Automation','excerpt'=>'A practical framework for quantifying the return on automation investments — beyond just time savings, including risk reduction and scalability benefits.','category'=>'Automation','author_name'=>'Krestworks Team','published_at'=>'2024-12-10','read_time'=>'6 min read'],
    ['slug'=>'choosing-erp-in-africa','title'=>'Choosing an ERP System in 2025: A Guide for East African Businesses','excerpt'=>'Build vs buy, cloud vs on-premise, local vs international vendors — how to make the right ERP decision for your organisation\'s size, budget, and industry.','category'=>'Enterprise Software','author_name'=>'Krestworks Team','published_at'=>'2024-11-28','read_time'=>'8 min read'],
  ];
}

$categories = ['AI & Technology','Digital Transformation','Architecture','Development','Enterprise Software','Automation','Cloud & Infrastructure','Cybersecurity'];
$catColors  = ['AI & Technology'=>'#A855F7','Digital Transformation'=>'#F5A800','Architecture'=>'#3B82F6','Development'=>'#22C55E','Enterprise Software'=>'#F97316','Automation'=>'#EF4444','Cloud & Infrastructure'=>'#06B6D4','Cybersecurity'=>'#8B5CF6'];
?>

<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a><i class="fa-solid fa-chevron-right"></i>
      <span class="current">Knowledge Hub</span>
    </div>
    <div style="padding:2.5rem 0 3rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-newspaper"></i> Insights</span>
      <h1>Knowledge Hub</h1>
      <p style="color:rgba(255,255,255,0.65);max-width:520px;">Practical insights on AI, enterprise software, digital transformation, and system architecture from the Krestworks engineering team.</p>
    </div>
  </div>
</section>

<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">

    <!-- Search + filters -->
    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2.5rem;flex-wrap:wrap;" data-aos="fade-up">
      <form method="GET" action="" style="display:flex;flex:1;min-width:220px;max-width:360px;gap:0;">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search articles…" style="flex:1;padding:0.6rem 1rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-right:none;border-radius:var(--kw-radius-md) 0 0 var(--kw-radius-md);font-size:0.875rem;color:var(--kw-text-primary);outline:none;" onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
        <button type="submit" style="padding:0.6rem 1rem;background:var(--kw-primary);border:1px solid var(--kw-primary);border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;cursor:pointer;color:#0A0F1A;"><i class="fa-solid fa-search"></i></button>
      </form>
      <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
        <a href="<?= url('blog') ?>" style="padding:0.3rem 0.85rem;border-radius:999px;border:1px solid <?= !$category ? 'var(--kw-primary)' : 'var(--kw-border)' ?>;background:<?= !$category ? 'rgba(245,168,0,0.15)' : 'none' ?>;color:<?= !$category ? 'var(--kw-primary)' : 'var(--kw-text-muted)' ?>;font-size:0.75rem;font-weight:700;text-decoration:none;">All</a>
        <?php foreach ($categories as $cat): ?>
        <a href="<?= url('blog') ?>?cat=<?= urlencode($cat) ?>" style="padding:0.3rem 0.85rem;border-radius:999px;border:1px solid <?= $category===$cat ? ($catColors[$cat]??'var(--kw-primary)') : 'var(--kw-border)' ?>;background:<?= $category===$cat ? ($catColors[$cat]??'var(--kw-primary)').'15' : 'none' ?>;color:<?= $category===$cat ? ($catColors[$cat]??'var(--kw-primary)') : 'var(--kw-text-muted)' ?>;font-size:0.75rem;font-weight:700;text-decoration:none;white-space:nowrap;"><?= $cat ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if (empty($posts)): ?>
    <div style="text-align:center;padding:4rem 2rem;color:var(--kw-text-muted);">
      <i class="fa-solid fa-newspaper" style="font-size:3rem;margin-bottom:1rem;display:block;"></i>
      <h3>No articles found</h3>
      <p style="margin-bottom:1.5rem;">Try adjusting your search or category filter.</p>
      <a href="<?= url('blog') ?>" class="kw-btn kw-btn-ghost">Clear Filters</a>
    </div>
    <?php else: ?>

    <!-- Featured post (first article) -->
    <?php $featured = $posts[0]; $rest = array_slice($posts, 1); ?>
    <div class="kw-card" style="padding:0;overflow:hidden;margin-bottom:2.5rem;display:grid;grid-template-columns:1fr 1fr;" data-aos="fade-up">
      <div style="background:linear-gradient(135deg,<?= $catColors[$featured['category']]??'var(--kw-primary)' ?>25,<?= $catColors[$featured['category']]??'var(--kw-primary)' ?>08);min-height:280px;display:flex;align-items:center;justify-content:center;border-right:1px solid var(--kw-border);">
        <i class="fa-solid fa-newspaper" style="font-size:5rem;color:<?= $catColors[$featured['category']]??'var(--kw-primary)' ?>;opacity:0.35;"></i>
      </div>
      <div style="padding:2.5rem;display:flex;flex-direction:column;justify-content:center;">
        <div style="display:flex;align-items:center;gap:0.65rem;margin-bottom:1rem;flex-wrap:wrap;">
          <span style="background:<?= $catColors[$featured['category']]??'var(--kw-primary)' ?>15;color:<?= $catColors[$featured['category']]??'var(--kw-primary)' ?>;border-radius:999px;padding:0.2rem 0.7rem;font-size:0.68rem;font-weight:700;"><?= e($featured['category'] ?? 'General') ?></span>
          <span style="font-size:0.72rem;color:var(--kw-text-muted);"><i class="fa-regular fa-calendar" style="margin-right:0.3rem;"></i><?= date('M j, Y', strtotime($featured['published_at'])) ?></span>
          <?php if (!empty($featured['read_time'])): ?><span style="font-size:0.72rem;color:var(--kw-text-muted);">· <?= e($featured['read_time']) ?></span><?php endif; ?>
        </div>
        <h2 style="font-size:1.35rem;margin-bottom:0.85rem;line-height:1.3;"><?= e($featured['title']) ?></h2>
        <p style="font-size:0.875rem;color:var(--kw-text-muted);line-height:1.7;margin-bottom:1.5rem;"><?= e($featured['excerpt']) ?></p>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;">
          <span style="font-size:0.78rem;color:var(--kw-text-muted);"><i class="fa-solid fa-user-pen" style="color:var(--kw-primary);margin-right:0.3rem;"></i><?= e($featured['author_name'] ?? 'Krestworks Team') ?></span>
          <a href="<?= url('blog/' . $featured['slug']) ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-arrow-right"></i> Read Article</a>
        </div>
      </div>
    </div>

    <!-- Article grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.5rem;margin-bottom:2.5rem;">
      <?php foreach ($rest as $i => $post): $color = $catColors[$post['category']] ?? '#6B7280'; ?>
      <div class="kw-card" style="padding:0;overflow:hidden;transition:transform 0.25s;"
           onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''"
           data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 60 ?>">
        <div style="height:100px;background:linear-gradient(135deg,<?= $color ?>25,<?= $color ?>08);display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--kw-border);">
          <i class="fa-solid fa-file-alt" style="font-size:2.5rem;color:<?= $color ?>;opacity:0.35;"></i>
        </div>
        <div style="padding:1.5rem;">
          <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;flex-wrap:wrap;">
            <span style="background:<?= $color ?>15;color:<?= $color ?>;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.65rem;font-weight:700;"><?= e($post['category'] ?? 'General') ?></span>
            <span style="font-size:0.68rem;color:var(--kw-text-muted);"><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
            <?php if (!empty($post['read_time'])): ?><span style="font-size:0.68rem;color:var(--kw-text-muted);">· <?= e($post['read_time']) ?></span><?php endif; ?>
          </div>
          <h4 style="font-size:0.95rem;margin-bottom:0.5rem;line-height:1.4;"><?= e($post['title']) ?></h4>
          <p style="font-size:0.78rem;color:var(--kw-text-muted);line-height:1.6;margin-bottom:1.25rem;"><?= e(truncate($post['excerpt'] ?? '', 120)) ?></p>
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.72rem;color:var(--kw-text-muted);"><i class="fa-solid fa-user-pen" style="color:var(--kw-primary);font-size:0.65rem;margin-right:0.3rem;"></i><?= e($post['author_name'] ?? 'Krestworks Team') ?></span>
            <a href="<?= url('blog/' . $post['slug']) ?>" style="font-size:0.75rem;color:var(--kw-primary);font-weight:700;text-decoration:none;">Read <i class="fa-solid fa-arrow-right" style="font-size:0.65rem;"></i></a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div style="display:flex;justify-content:center;gap:0.5rem;flex-wrap:wrap;" data-aos="fade-up">
      <?php for ($p = 1; $p <= $totalPages; $p++): ?>
      <a href="<?= url('blog') ?>?page=<?= $p ?><?= $category ? '&cat=' . urlencode($category) : '' ?><?= $search ? '&q=' . urlencode($search) : '' ?>"
         style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:var(--kw-radius-md);border:1px solid <?= $p===$page_num ? 'var(--kw-primary)' : 'var(--kw-border)' ?>;background:<?= $p===$page_num ? 'var(--kw-primary)' : 'transparent' ?>;color:<?= $p===$page_num ? '#0A0F1A' : 'var(--kw-text-muted)' ?>;font-size:0.82rem;font-weight:700;text-decoration:none;"><?= $p ?></a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php endif; ?>

    <!-- Newsletter CTA -->
    <div class="kw-card" style="padding:2.5rem;margin-top:3rem;text-align:center;background:var(--kw-bg-hero);" data-aos="fade-up">
      <h3 style="color:#fff;margin-bottom:0.5rem;">Stay Ahead of the Curve</h3>
      <p style="color:rgba(255,255,255,0.6);margin-bottom:1.5rem;font-size:0.875rem;">Get practical insights on AI, software, and digital transformation — delivered to your inbox twice a month.</p>
      <form style="display:flex;gap:0.5rem;max-width:420px;margin:0 auto;" onsubmit="subscribeNewsletter(event,this)">
        <?= csrfField() ?>
        <input type="email" name="email" placeholder="Your email address" required style="flex:1;padding:0.65rem 1rem;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:var(--kw-radius-md);color:#fff;font-size:0.875rem;outline:none;" onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor=''">
        <button type="submit" class="kw-btn kw-btn-primary" style="white-space:nowrap;"><i class="fa-solid fa-paper-plane"></i> Subscribe</button>
      </form>
    </div>

  </div>
</section>

<script>
async function subscribeNewsletter(e, form) {
  e.preventDefault();
  const btn = form.querySelector('button[type=submit]');
  btn.disabled = true; btn.innerHTML = '…';
  try {
    const resp = await fetch('<?= url('api/newsletter') ?>', { method: 'POST', body: new FormData(form) });
    const data = await resp.json();
    window.Krest?.toast(data.message || 'Subscribed!', data.success ? 'success' : 'error');
    if (data.success) form.reset();
  } catch { window.Krest?.toast('Error. Please try again.', 'error'); }
  btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Subscribe';
}
</script>
<style>
@media(max-width:768px){
  .kw-card[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important;}
  .kw-card>div[style*="min-height:280px"]{min-height:140px!important;}
}
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>