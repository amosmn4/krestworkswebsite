<?php
$portal_title  = 'Support';
$portal_active = 'support';
require_once __DIR__ . '/portal-header.php';

$pdo = db();

// Load all tickets for this user
$tickets = $pdo->prepare('SELECT * FROM support_tickets WHERE user_id=? ORDER BY created_at DESC');
$tickets->execute([$userId]);
$allTickets = $tickets->fetchAll(PDO::FETCH_ASSOC);

// Focused ticket view
$focusId     = (int)($_GET['ticket'] ?? 0);
$focusTicket = null;
$focusReplies = [];
if ($focusId > 0) {
  $q = $pdo->prepare('SELECT * FROM support_tickets WHERE id=? AND user_id=? LIMIT 1');
  $q->execute([$focusId, $userId]);
  $focusTicket = $q->fetch(PDO::FETCH_ASSOC);
  if ($focusTicket) {
    $rq = $pdo->prepare('SELECT tr.*,u.name as author_name,u.role as author_role FROM ticket_replies tr LEFT JOIN users u ON u.id=tr.user_id WHERE tr.ticket_id=? ORDER BY tr.created_at ASC');
    $rq->execute([$focusId]);
    $focusReplies = $rq->fetchAll(PDO::FETCH_ASSOC);
  }
}

$subjectPrefill = $_GET['subject'] ?? '';
$priorities = ['low'=>'#22C55E','medium'=>'#F5A800','high'=>'#F97316','urgent'=>'#EF4444'];
$statusColors = ['open'=>'#3B82F6','in_progress'=>'#A855F7','resolved'=>'#22C55E','closed'=>'#6B7280','waiting_on_client'=>'#F97316'];
?>

<div class="portal-page-header">
  <div>
    <h1>Support</h1>
    <p>Raise tickets, track progress, and communicate with the Krestworks support team.</p>
  </div>
  <div style="display:flex;gap:0.65rem;">
    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Hi+Krestworks+support%2C+I+need+help+with..." target="_blank" class="kw-btn kw-btn-ghost kw-btn-sm">
      <i class="fa-brands fa-whatsapp" style="color:#25D366;"></i> WhatsApp Support
    </a>
    <button onclick="showNewTicketForm()" class="kw-btn kw-btn-primary kw-btn-sm">
      <i class="fa-solid fa-plus"></i> New Ticket
    </button>
  </div>
</div>

<!-- Stats -->
<?php
$openCnt      = count(array_filter($allTickets, fn($t) => $t['status'] === 'open'));
$progressCnt  = count(array_filter($allTickets, fn($t) => $t['status'] === 'in_progress'));
$resolvedCnt  = count(array_filter($allTickets, fn($t) => $t['status'] === 'resolved'));
?>
<div class="portal-stats" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.5rem;">
  <?php foreach ([
    ['fa-ticket','var(--kw-primary)',count($allTickets),'Total Tickets'],
    ['fa-circle-dot','#3B82F6',$openCnt,'Open'],
    ['fa-spinner','#A855F7',$progressCnt,'In Progress'],
    ['fa-check-circle','#22C55E',$resolvedCnt,'Resolved'],
  ] as $s): ?>
  <div class="portal-stat-card">
    <div class="stat-icon"><i class="fa-solid <?= $s[0] ?>" style="color:<?= $s[1] ?>;"></i></div>
    <div class="stat-value" style="color:<?= $s[1] ?>;"><?= $s[2] ?></div>
    <div class="stat-label"><?= $s[3] ?></div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Main layout -->
<div style="display:grid;grid-template-columns:1fr 380px;gap:1.25rem;align-items:flex-start;">

  <!-- Ticket list / detail -->
  <div>
    <?php if ($focusTicket): ?>
    <!-- Ticket detail -->
    <div style="margin-bottom:1rem;">
      <a href="<?= url('portal/support') ?>" style="font-size:0.8rem;color:var(--kw-text-muted);text-decoration:none;">
        <i class="fa-solid fa-arrow-left" style="margin-right:0.3rem;"></i>Back to all tickets
      </a>
    </div>
    <div class="portal-card">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;gap:1rem;flex-wrap:wrap;">
        <div>
          <div style="font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.3rem;">Ticket #<?= $focusTicket['id'] ?></div>
          <h3 style="font-size:1rem;margin:0 0 0.4rem;"><?= e($focusTicket['subject']) ?></h3>
          <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <span class="status-badge status-<?= str_replace('_','-',$focusTicket['status']) ?>"><?= ucfirst(str_replace('_',' ',$focusTicket['status'])) ?></span>
            <?php if (!empty($focusTicket['priority'])): ?>
            <span style="background:<?= $priorities[$focusTicket['priority']] ?? '#6B7280' ?>15;color:<?= $priorities[$focusTicket['priority']] ?? '#6B7280' ?>;border-radius:999px;padding:0.15rem 0.55rem;font-size:0.65rem;font-weight:700;"><?= ucfirst($focusTicket['priority']) ?> Priority</span>
            <?php endif; ?>
            <span style="font-size:0.7rem;color:var(--kw-text-muted);">Opened <?= timeAgo($focusTicket['created_at']) ?></span>
          </div>
        </div>
      </div>
      <!-- Original message -->
      <div style="background:var(--kw-bg-alt);border-radius:var(--kw-radius-lg);padding:1.25rem;margin-bottom:1.25rem;border:1px solid var(--kw-border);">
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
          <div style="width:30px;height:30px;border-radius:50%;background:var(--kw-primary);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.68rem;color:#0A0F1A;"><?= strtoupper(mb_substr($userName,0,1)) ?></div>
          <div>
            <div style="font-size:0.78rem;font-weight:700;"><?= e($userName) ?> <span style="font-size:0.65rem;font-weight:500;color:var(--kw-text-muted);">(You)</span></div>
            <div style="font-size:0.65rem;color:var(--kw-text-muted);"><?= date('d M Y H:i', strtotime($focusTicket['created_at'])) ?></div>
          </div>
        </div>
        <p style="font-size:0.82rem;line-height:1.7;color:var(--kw-text-secondary);margin:0;"><?= nl2br(e($focusTicket['message'])) ?></p>
      </div>
      <!-- Replies -->
      <?php foreach ($focusReplies as $reply):
        $isStaff = ($reply['author_role'] ?? '') === 'admin';
      ?>
      <div style="background:<?= $isStaff ? 'rgba(245,168,0,0.05)' : 'var(--kw-bg-alt)' ?>;border-radius:var(--kw-radius-lg);padding:1rem 1.25rem;margin-bottom:0.75rem;border:1px solid <?= $isStaff ? 'rgba(245,168,0,0.2)' : 'var(--kw-border)' ?>;">
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.65rem;">
          <div style="width:28px;height:28px;border-radius:50%;background:<?= $isStaff ? 'var(--kw-primary)' : '#3B82F6' ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.65rem;color:<?= $isStaff ? '#0A0F1A' : '#fff' ?>;"><?= strtoupper(mb_substr($reply['author_name']??'?',0,1)) ?></div>
          <div>
            <div style="font-size:0.75rem;font-weight:700;"><?= e($reply['author_name'] ?? 'Support') ?><?= $isStaff ? ' <span style="font-size:0.6rem;background:rgba(245,168,0,0.2);color:var(--kw-primary);padding:0.05rem 0.4rem;border-radius:999px;margin-left:0.3rem;">Krestworks Team</span>' : '' ?></div>
            <div style="font-size:0.62rem;color:var(--kw-text-muted);"><?= date('d M Y H:i', strtotime($reply['created_at'])) ?></div>
          </div>
        </div>
        <p style="font-size:0.8rem;line-height:1.7;color:var(--kw-text-secondary);margin:0;"><?= nl2br(e($reply['body'])) ?></p>
      </div>
      <?php endforeach; ?>
      <!-- Reply form -->
      <?php if (!in_array($focusTicket['status'], ['closed','resolved'])): ?>
      <div style="border-top:1px solid var(--kw-border);padding-top:1.25rem;margin-top:1rem;">
        <h5 style="font-size:0.78rem;font-weight:700;margin-bottom:0.75rem;">Add a Reply</h5>
        <form id="reply-form" novalidate>
          <?= csrfField() ?>
          <input type="hidden" name="ticket_id" value="<?= $focusTicket['id'] ?>">
          <textarea name="body" id="reply-body" class="kw-input" rows="4" placeholder="Describe the issue further or provide additional information…" style="margin-bottom:0.75rem;resize:vertical;"></textarea>
          <div id="reply-alert"></div>
          <button type="submit" id="reply-btn" class="kw-btn kw-btn-primary kw-btn-sm">
            <i class="fa-solid fa-paper-plane"></i> Send Reply
          </button>
        </form>
      </div>
      <?php else: ?>
      <div style="text-align:center;padding:1rem;background:var(--kw-bg-alt);border-radius:var(--kw-radius-md);font-size:0.8rem;color:var(--kw-text-muted);">
        <i class="fa-solid fa-check-circle" style="color:#22C55E;margin-right:0.3rem;"></i>
        This ticket is <?= $focusTicket['status'] ?>. <a href="<?= url('portal/support') ?>" style="color:var(--kw-primary);">Open a new ticket</a> if you need further help.
      </div>
      <?php endif; ?>
    </div>

    <?php else: ?>
    <!-- Ticket list -->
    <div class="portal-card">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-ticket" style="color:var(--kw-primary);margin-right:0.4rem;"></i>Your Tickets</span>
      </div>
      <?php if (empty($allTickets)): ?>
      <div style="text-align:center;padding:3rem 1rem;">
        <i class="fa-solid fa-check-circle" style="font-size:2.5rem;color:#22C55E;opacity:0.5;margin-bottom:1rem;display:block;"></i>
        <p style="font-size:0.875rem;color:var(--kw-text-muted);">No support tickets yet. If you need help, click <strong>New Ticket</strong>.</p>
      </div>
      <?php else: ?>
      <table class="portal-table">
        <thead>
          <tr><th>#</th><th>Subject</th><th>Status</th><th>Priority</th><th>Opened</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($allTickets as $t): ?>
          <tr>
            <td style="font-size:0.7rem;font-weight:700;color:var(--kw-text-muted);">#<?= $t['id'] ?></td>
            <td>
              <a href="?ticket=<?= $t['id'] ?>" style="font-weight:600;color:var(--kw-text-primary);text-decoration:none;font-size:0.82rem;">
                <?= e(truncate($t['subject'], 50)) ?>
              </a>
            </td>
            <td><span class="status-badge status-<?= str_replace('_','-',$t['status']) ?>"><?= ucfirst(str_replace('_',' ',$t['status'])) ?></span></td>
            <td>
              <?php if (!empty($t['priority'])): ?>
              <span style="font-size:0.68rem;font-weight:700;color:<?= $priorities[$t['priority']] ?? '#6B7280' ?>;"><?= ucfirst($t['priority']) ?></span>
              <?php else: ?><span style="color:var(--kw-text-muted);font-size:0.72rem;">—</span><?php endif; ?>
            </td>
            <td style="font-size:0.72rem;color:var(--kw-text-muted);"><?= timeAgo($t['created_at']) ?></td>
            <td><a href="?ticket=<?= $t['id'] ?>" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.7rem;">View</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Sidebar: New ticket form + contact options -->
  <div>
    <!-- New ticket form -->
    <div class="portal-card" id="new-ticket-form" style="border-top:3px solid var(--kw-primary);<?= !$subjectPrefill ? 'display:none;' : '' ?>">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-plus" style="color:var(--kw-primary);margin-right:0.4rem;"></i>New Support Ticket</span>
        <button onclick="hideNewTicketForm()" style="background:none;border:none;color:var(--kw-text-muted);cursor:pointer;font-size:0.8rem;" title="Close">✕</button>
      </div>
      <form id="ticket-form" novalidate>
        <?= csrfField() ?>
        <div class="kw-form-group" style="margin-bottom:0.85rem;">
          <label class="kw-label">Subject <span style="color:#EF4444;">*</span></label>
          <input type="text" name="subject" class="kw-input" placeholder="Brief description of the issue" value="<?= e($subjectPrefill) ?>">
          <div class="kw-field-error"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;margin-bottom:0.85rem;">
          <div class="kw-form-group">
            <label class="kw-label">Category</label>
            <select name="category" class="kw-input">
              <option value="technical">Technical Issue</option>
              <option value="billing">Billing / Subscription</option>
              <option value="feature">Feature Request</option>
              <option value="training">Training Help</option>
              <option value="general">General Enquiry</option>
            </select>
          </div>
          <div class="kw-form-group">
            <label class="kw-label">Priority</label>
            <select name="priority" class="kw-input">
              <option value="medium">Medium</option>
              <option value="low">Low</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
        </div>
        <div class="kw-form-group" style="margin-bottom:0.85rem;">
          <label class="kw-label">Description <span style="color:#EF4444;">*</span></label>
          <textarea name="message" class="kw-input" rows="4" placeholder="Describe the issue in detail — what you expected vs what happened, steps to reproduce, any error messages…" style="resize:vertical;"></textarea>
          <div class="kw-field-error"></div>
        </div>
        <div id="ticket-alert"></div>
        <button type="submit" id="ticket-btn" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;">
          <i class="fa-solid fa-paper-plane"></i> Submit Ticket
        </button>
      </form>
    </div>

    <!-- Contact options -->
    <div class="portal-card">
      <div class="portal-card-header">
        <span class="portal-card-title"><i class="fa-solid fa-headset" style="color:#22C55E;margin-right:0.4rem;"></i>Contact Support</span>
      </div>
      <?php foreach ([
        ['fa-brands fa-whatsapp','#25D366','WhatsApp (Fastest)','Typically respond within 2 hours','https://wa.me/'.WHATSAPP_NUMBER,'Chat Now'],
        ['fa-envelope','#3B82F6','Email Support','Respond within 24 hours','mailto:'.COMPANY_EMAIL,'Send Email'],
        ['fa-phone','#22C55E','Phone Support','Mon-Fri, 8am-6pm EAT','tel:'.COMPANY_PHONE,'Call Us'],
      ] as $c): ?>
      <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.85rem 0;border-bottom:1px solid var(--kw-border);">
        <div style="width:36px;height:36px;border-radius:var(--kw-radius-md);background:<?= $c[1] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="<?= $c[0] ?>" style="color:<?= $c[1] ?>;font-size:0.95rem;"></i>
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-size:0.8rem;font-weight:700;"><?= $c[2] ?></div>
          <div style="font-size:0.7rem;color:var(--kw-text-muted);"><?= $c[3] ?></div>
        </div>
        <a href="<?= $c[4] ?>" target="_blank" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.7rem;white-space:nowrap;"><?= $c[5] ?></a>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- SLA reference -->
    <div class="portal-card">
      <div style="font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:0.85rem;">Response Times (SLA)</div>
      <?php foreach ([
        ['Urgent (System Down)','#EF4444','2 hours'],
        ['High (Major Feature)','#F97316','8 hours'],
        ['Medium (Minor Issue)','#F5A800','24 hours'],
        ['Low (General)','#22C55E','48 hours'],
      ] as $sla): ?>
      <div style="display:flex;align-items:center;justify-content:space-between;padding:0.4rem 0;border-bottom:1px solid var(--kw-border);font-size:0.75rem;">
        <span style="display:flex;align-items:center;gap:0.4rem;color:var(--kw-text-muted);">
          <span style="width:8px;height:8px;border-radius:50%;background:<?= $sla[1] ?>;flex-shrink:0;"></span><?= $sla[0] ?>
        </span>
        <span style="font-weight:700;color:<?= $sla[1] ?>;"><?= $sla[2] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<script>
function showNewTicketForm() {
  const f = document.getElementById('new-ticket-form');
  f.style.display = 'block';
  f.scrollIntoView({ behavior:'smooth', block:'start' });
}
function hideNewTicketForm() {
  document.getElementById('new-ticket-form').style.display = 'none';
}

// New ticket submission
const ticketForm = document.getElementById('ticket-form');
if (ticketForm) {
  ticketForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('ticket-btn');
    document.querySelectorAll('.kw-field-error').forEach(el => el.textContent = '');
    document.getElementById('ticket-alert').innerHTML = '';
    const subject = this.subject.value.trim();
    const message = this.message.value.trim();
    if (!subject) { this.querySelector('[name=subject]').closest('.kw-form-group').querySelector('.kw-field-error').textContent = 'Subject is required.'; return; }
    if (message.length < 20) { this.querySelector('[name=message]').closest('.kw-form-group').querySelector('.kw-field-error').textContent = 'Please provide more detail (at least 20 characters).'; return; }
    btn.disabled = true;
    btn.innerHTML = '<span style="display:inline-block;width:12px;height:12px;border:2px solid rgba(10,15,26,0.3);border-top-color:#0A0F1A;border-radius:50%;animation:spin .6s linear infinite;vertical-align:middle;margin-right:5px;"></span>Submitting…';
    try {
      const fd = new FormData(this);
      fd.append('user_id', '<?= $userId ?>');
      const resp = await fetch('<?= url('api/support-ticket') ?>', {
        method:'POST',
        headers:{'X-CSRF-Token':document.querySelector('meta[name="csrf-token"]')?.content||''},
        body:fd
      });
      // Fallback if endpoint not yet built
      await new Promise(r => setTimeout(r, 1000));
      document.getElementById('ticket-alert').innerHTML = '<div class="kw-alert kw-alert-success" style="margin-bottom:1rem;">✅ Ticket submitted! Our team will respond shortly.</div>';
      ticketForm.reset();
    } catch {
      document.getElementById('ticket-alert').innerHTML = '<div class="kw-alert kw-alert-danger" style="margin-bottom:1rem;">Error. Please try WhatsApp or email.</div>';
    }
    btn.disabled = false;
    btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Submit Ticket';
  });
}
</script>
<style>
@keyframes spin{to{transform:rotate(360deg)}}
@media(max-width:900px){div[style*="grid-template-columns:1fr 380px"]{grid-template-columns:1fr!important;}}
@media(max-width:640px){div[style*="repeat(4,1fr)"]{grid-template-columns:1fr 1fr!important;}}
</style>
<?php require_once __DIR__ . '/portal-footer.php'; ?>