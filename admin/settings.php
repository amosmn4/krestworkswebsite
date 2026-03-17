<?php
$admin_title='Settings'; $admin_active='settings';
require_once __DIR__ . '/admin-header.php';
?>
<div style="margin-bottom:1.25rem;"><h1 style="font-size:1.1rem;margin:0;">Settings</h1><p style="font-size:0.72rem;color:var(--kw-text-muted);">Configuration overview and system status</p></div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

  <!-- Config overview -->
  <div class="adm-card" style="padding:1.25rem;">
    <h4 style="font-size:0.875rem;margin-bottom:1rem;"><i class="fa-solid fa-sliders" style="color:var(--kw-primary);margin-right:0.4rem;"></i>Configuration</h4>
    <?php foreach([
      ['APP_NAME',APP_NAME],['APP_VERSION',APP_VERSION],['APP_ENV',APP_ENV],['BASE_URL',BASE_URL],
      ['MAIL_FROM',MAIL_FROM],['MAIL_HOST',MAIL_HOST],['WHATSAPP','...'.substr(WHATSAPP_NUMBER,-4)],
      ['AI_MODEL',AI_MODEL],['DB_HOST',DB_HOST],['DB_NAME',DB_NAME],
    ] as $row): ?>
    <div style="display:flex;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);font-size:0.72rem;">
      <span style="color:var(--kw-text-muted);font-weight:700;"><?= $row[0] ?></span>
      <span style="font-family:monospace;font-size:0.68rem;"><?= e($row[1]) ?></span>
    </div>
    <?php endforeach; ?>
    <p style="font-size:0.68rem;color:var(--kw-text-muted);margin-top:0.75rem;">Edit values in <code>config/config.php</code></p>
  </div>

  <!-- System health -->
  <div style="display:flex;flex-direction:column;gap:1.25rem;">
    <div class="adm-card" style="padding:1.25rem;">
      <h4 style="font-size:0.875rem;margin-bottom:1rem;"><i class="fa-solid fa-heart-pulse" style="color:#22C55E;margin-right:0.4rem;"></i>System Health</h4>
      <?php
      $checks = [
        ['Database', function() use($pdo){ try{ $pdo->query('SELECT 1'); return [true,'Connected']; }catch(Exception $e){ return [false,'Error: '.$e->getMessage()]; }}],
        ['AI API Key', function(){ return [!empty(AI_API_KEY), !empty(AI_API_KEY)?'Configured':'Not set (dev mode)']; }],
        ['Mail Config', function(){ return [!empty(MAIL_PASS), !empty(MAIL_PASS)?'SMTP configured':'Dev mode (PHP mail)']; }],
        ['WhatsApp', function(){ return [!empty(WHATSAPP_NUMBER), !empty(WHATSAPP_NUMBER)?WHATSAPP_NUMBER:'Not configured']; }],
        ['Upload Path', function(){ return [is_writable(UPLOAD_PATH), is_writable(UPLOAD_PATH)?'Writable':'Not writable']; }],
        ['PHP Version', function(){ return [version_compare(PHP_VERSION,'8.0','>='), PHP_VERSION]; }],
        ['Session', function(){ return [session_status()===PHP_SESSION_ACTIVE,'Active']; }],
      ];
      foreach($checks as $chk):
        [$ok, $msg] = ($chk[1])();
      ?>
      <div style="display:flex;justify-content:space-between;align-items:center;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);font-size:0.72rem;">
        <span style="color:var(--kw-text-muted);"><?= $chk[0] ?></span>
        <span style="display:flex;align-items:center;gap:0.35rem;font-weight:600;color:<?=$ok?'#22C55E':'#EF4444'?>;">
          <i class="fa-solid fa-<?=$ok?'check-circle':'times-circle'?>" style="font-size:0.65rem;"></i><?= e($msg) ?>
        </span>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="adm-card" style="padding:1.25rem;">
      <h4 style="font-size:0.875rem;margin-bottom:1rem;"><i class="fa-solid fa-tools" style="color:#3B82F6;margin-right:0.4rem;"></i>Quick Actions</h4>
      <?php foreach([
        [url('admin'),'fa-gauge-high','#F5A800','Refresh Dashboard'],
        [url('admin/analytics'),'fa-chart-bar','#A855F7','View Analytics'],
        [url('admin/inquiries').'?status=new','fa-inbox','#3B82F6','New Inquiries'],
        [url('admin/content'),'fa-newspaper','#22C55E','Manage Blog Posts'],
        [url(),'fa-globe','#06B6D4','View Website'],
      ] as $action): ?>
      <a href="<?=$action[0]?>" style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.78rem;color:var(--kw-text-secondary);text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='<?=$action[2]?>'" onmouseout="this.style.color=''">
        <i class="fa-solid <?=$action[1]?>" style="color:<?=$action[2]?>;width:14px;font-size:0.75rem;"></i> <?=$action[3]?>
        <i class="fa-solid fa-arrow-right" style="margin-left:auto;font-size:0.6rem;opacity:0.35;"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<style>@media(max-width:768px){div[style*="1fr 1fr"]{grid-template-columns:1fr!important;}}</style>
<?php require_once __DIR__ . '/admin-footer.php'; ?>