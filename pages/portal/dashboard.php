<?php
$portal_title  = 'Dashboard';
$portal_active = 'dashboard';
require_once __DIR__ . '/portal-header.php';

$pdo = db();

// User record
$userRow = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
$userRow->execute([$userId]);
$userRow = $userRow->fetch(PDO::FETCH_ASSOC);

// Active subscriptions
$subs = $pdo->prepare('SELECT s.*,p.name as product_name,p.slug as product_slug FROM subscriptions s LEFT JOIN products p ON p.id=s.product_id WHERE s.user_id=? AND s.status="active" ORDER BY s.created_at DESC');
$subs->execute([$userId]);
$subscriptions = $subs->fetchAll(PDO::FETCH_ASSOC);

// Recent tickets
$tix = $pdo->prepare('SELECT * FROM support_tickets WHERE user_id=? ORDER BY created_at DESC LIMIT 5');
$tix->execute([$userId]);
$recentTickets = $tix->fetchAll(PDO::FETCH_ASSOC);

// Open ticket count
$openQ = $pdo->prepare("SELECT COUNT(*) FROM support_tickets WHERE user_id=? AND status NOT IN('closed','resolved')");
$openQ->execute([$userId]);
$openCount = (int)$openQ->fetchColumn();

// AI usage this month
$aiQ = $pdo->prepare("SELECT COUNT(*) FROM ai_tool_usage WHERE user_id=? AND used_at>=DATE_FORMAT(NOW(),'%Y-%m-01')");
$aiQ->execute([$userId]);
$aiUses = (int)$aiQ->fetchColumn();

$hour = (int)date('H');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
$firstName = explode(' ', $userName)[0];
?>

<div class="portal-page-header">
  <div>
    <h1><?= $greeting ?>, <?= e($firstName) ?> 👋</h1>
    <p>Welcome to your portal &mdash; <?= date('l, j F Y') ?></p>
  </div>
  <div style="display:flex;gap:0.65rem;flex-wrap:wrap;">
    <a href="<?= url('demo') ?>" target="_blank" class="kw-btn kw-btn-ghost kw-btn-sm"><i class="fa-solid fa-play-circle"></i> Book Demo</a>
    <a href="<?= url('portal/support') ?>" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-headset"></i> Get Support</a>
  </div>
</div>

<!-- Stats -->
<div class="portal-stats">
  <?php foreach ([
    ['fa-layer-group','var(--kw-primary)',count($subscriptions),'Active Systems',url('portal/subscriptions')],
    ['fa-headset','#3B82F6',$openCount,'Open Tickets',url('portal/support')],
    ['fa-robot','#A855F7',$aiUses,'AI Uses (month)',url('ai-hub')],
    ['fa-download','#22C55E','∞','Downloads Available',url('portal/downloads')],
  ] as $s): ?>
  <a href="<?= $s[4] ?>" style="text-decoration:none;" class="portal-stat-card" onmouseover="this.style.borderColor='<?= $s[1] ?>50'" onmouseout="this.style.borderColor=''">
    <div class="stat-icon"><i class="fa-solid <?= $s[0] ?>" style="color:<?= $s[1] ?>;"></i></div>
    <div class="stat-value" style="color:<?= $s[1] ?>;"><?= $s[2] ?></div>
    <div class="stat-label"><?= $s[3] ?></div>
  </a>
  <?php endforeach; ?>
</div>

<!-- Main grid -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

  <!-- Left column -->
  <div>
    <!-- Active subscriptions -->
    <div class="portal-card">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-layer-group" style="color:var(--kw-primary);margin-right:0.4rem;"></i>Active Subscriptions</span>
        <a href="<?= url('portal/subscriptions') ?>" style="font-size:0.73rem;color:var(--kw-primary);text-decoration:none;">View all →</a>
      </div>
      <?php if (empty($subscriptions)): ?>
      <div style="text-align:center;padding:2rem 1rem;">
        <i class="fa-solid fa-box-open" style="font-size:2rem;color:var(--kw-text-muted);margin-bottom:0.75rem;display:block;opacity:0.35;"></i>
        <p style="font-size:0.82rem;color:var(--kw-text-muted);margin-bottom:1rem;">No active subscriptions yet.</p>
        <a href="<?= url('products') ?>" target="_blank" class="kw-btn kw-btn-primary kw-btn-sm"><i class="fa-solid fa-arrow-right"></i> Browse Products</a>
      </div>
      <?php else: ?>
        <?php foreach ($subscriptions as $sub):
          $daysLeft = $sub['expires_at'] ? ceil((strtotime($sub['expires_at']) - time()) / 86400) : null;
        ?>
        <div style="display:flex;align-items:center;gap:0.75rem;padding:0.85rem 0;border-bottom:1px solid var(--kw-border);">
          <div style="width:38px;height:38px;border-radius:var(--kw-radius-md);background:rgba(245,168,0,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fa-solid fa-cube" style="color:var(--kw-primary);font-size:0.9rem;"></i>
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:0.82rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($sub['product_name'] ?? 'System') ?></div>
            <div style="font-size:0.7rem;color:var(--kw-text-muted);">
              <?php if ($daysLeft !== null): ?>
                <?= $daysLeft > 0 ? "Expires in $daysLeft days" : 'Expired' ?>
              <?php else: ?> Ongoing <?php endif; ?>
            </div>
          </div>
          <span class="status-badge <?= ($daysLeft === null || $daysLeft > 0) ? 'status-active' : 'status-expired' ?>">
            <i class="fa-solid fa-circle" style="font-size:0.4rem;"></i>
            <?= ($daysLeft === null || $daysLeft > 0) ? 'Active' : 'Expired' ?>
          </span>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Quick Actions -->
    <div class="portal-card">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-bolt" style="color:#F5A800;margin-right:0.4rem;"></i>Quick Actions</span>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.6rem;">
        <?php foreach ([
          [url('portal/support'),'fa-headset','#3B82F6','New Ticket'],
          [url('portal/downloads'),'fa-download','#22C55E','Downloads'],
          [url('portal/training'),'fa-graduation-cap','#A855F7','Training'],
          [url('demo'),'fa-play-circle','#F97316','Book Demo'],
          [url('ai-hub'),'fa-robot','#EF4444','AI Tools'],
          [url('consultation'),'fa-calendar-check','#06B6D4','Consult'],
        ] as $qa): ?>
        <a href="<?= $qa[0] ?>" style="display:flex;align-items:center;gap:0.5rem;padding:0.65rem 0.8rem;background:var(--kw-bg-alt);border:1px solid var(--kw-border);border-radius:var(--kw-radius-md);font-size:0.78rem;font-weight:600;color:var(--kw-text-secondary);text-decoration:none;transition:all 0.2s;"
           onmouseover="this.style.borderColor='<?= $qa[2] ?>';this.style.color='<?= $qa[2] ?>'" onmouseout="this.style.borderColor='';this.style.color=''">
          <i class="fa-solid <?= $qa[1] ?>" style="color:<?= $qa[2] ?>;font-size:0.82rem;width:14px;"></i><?= $qa[3] ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Right column -->
  <div>
    <!-- Support tickets -->
    <div class="portal-card">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-headset" style="color:#3B82F6;margin-right:0.4rem;"></i>Recent Support Tickets</span>
        <a href="<?= url('portal/support') ?>" style="font-size:0.73rem;color:var(--kw-primary);text-decoration:none;">View all →</a>
      </div>
      <?php if (empty($recentTickets)): ?>
      <div style="text-align:center;padding:2rem 1rem;">
        <i class="fa-solid fa-check-circle" style="font-size:2rem;color:#22C55E;margin-bottom:0.65rem;display:block;opacity:0.55;"></i>
        <p style="font-size:0.82rem;color:var(--kw-text-muted);">All clear! No support tickets.</p>
      </div>
      <?php else: ?>
        <?php foreach ($recentTickets as $t): ?>
        <a href="<?= url('portal/support') ?>?ticket=<?= $t['id'] ?>" style="display:flex;align-items:center;gap:0.65rem;padding:0.75rem 0;border-bottom:1px solid var(--kw-border);text-decoration:none;">
          <div style="width:8px;height:8px;border-radius:50%;flex-shrink:0;background:<?= $t['status']==='open'?'#3B82F6':($t['status']==='in_progress'?'#A855F7':'#22C55E') ?>;"></div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:0.82rem;font-weight:600;color:var(--kw-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($t['subject']) ?></div>
            <div style="font-size:0.7rem;color:var(--kw-text-muted);"><?= timeAgo($t['created_at']) ?></div>
          </div>
          <span class="status-badge status-<?= str_replace('_','-',$t['status']) ?>" style="flex-shrink:0;font-size:0.62rem;"><?= ucfirst(str_replace('_',' ',$t['status'])) ?></span>
        </a>
        <?php endforeach; ?>
      <?php endif; ?>
      <div style="margin-top:1rem;">
        <a href="<?= url('portal/support') ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="width:100%;justify-content:center;">
          <i class="fa-solid fa-plus"></i> New Support Ticket
        </a>
      </div>
    </div>

    <!-- Resources -->
    <div class="portal-card">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-circle-info" style="color:#06B6D4;margin-right:0.4rem;"></i>Help & Resources</span>
      </div>
      <?php foreach ([
        [url('faq'),'fa-circle-question','FAQ','Quick answers to common questions'],
        [url('blog'),'fa-newspaper','Knowledge Hub','Guides and industry insights'],
        [url('portal/training'),'fa-graduation-cap','Training','Video courses and manuals'],
        ['mailto:'.COMPANY_EMAIL,'fa-envelope','Email Support',COMPANY_EMAIL],
        ['https://wa.me/'.WHATSAPP_NUMBER,'fa-brands fa-whatsapp','WhatsApp',WHATSAPP_DISPLAY],
      ] as $res): ?>
      <a href="<?= $res[0] ?>" style="display:flex;align-items:center;gap:0.65rem;padding:0.65rem 0;border-bottom:1px solid var(--kw-border);text-decoration:none;font-size:0.8rem;color:var(--kw-text-secondary);transition:color 0.15s;"
         onmouseover="this.style.color='var(--kw-primary)'" onmouseout="this.style.color=''">
        <i class="<?= strpos($res[1],'fa-brands')!==false ? $res[1] : 'fa-solid '.$res[1] ?>" style="color:var(--kw-primary);width:16px;font-size:0.78rem;flex-shrink:0;"></i>
        <span style="font-weight:600;"><?= $res[2] ?></span>
        <span style="color:var(--kw-text-muted);font-size:0.72rem;"> — <?= $res[3] ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>

</div>
<style>@media(max-width:768px){div[style*="grid-template-columns:1fr 1fr:last-of-type"]{grid-template-columns:1fr!important;} .portal-stats{grid-template-columns:1fr 1fr!important;}}</style>
<?php require_once __DIR__ . '/portal-footer.php'; ?>