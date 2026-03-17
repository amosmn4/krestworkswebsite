<?php
$portal_title  = 'Training Resources';
$portal_active = 'training';
require_once __DIR__ . '/portal-header.php';

$pdo = db();

$subs = $pdo->prepare("SELECT p.slug FROM subscriptions s JOIN products p ON p.id=s.product_id WHERE s.user_id=? AND s.status='active'");
$subs->execute([$userId]);
$activeSlugs = array_column($subs->fetchAll(PDO::FETCH_ASSOC), 'slug');

$courses = [
  [
    'id'       => 'hr-admin',
    'title'    => 'HR System — Administrator Training',
    'product'  => 'hr-system',
    'level'    => 'Beginner',
    'duration' => '3.5 hours',
    'modules'  => 8,
    'icon'     => 'fa-users',
    'color'    => '#F5A800',
    'desc'     => 'Complete guide to setting up and managing the HR system — employee records, departments, roles, and system configuration.',
    'topics'   => ['System Setup','Employee Management','Department & Role Config','Leave Management','Payroll Setup','Reports & Analytics','User Roles & Permissions','System Maintenance'],
  ],
  [
    'id'       => 'hr-payroll',
    'title'    => 'Payroll Processing Mastery',
    'product'  => 'hr-system',
    'level'    => 'Intermediate',
    'duration' => '2 hours',
    'modules'  => 5,
    'icon'     => 'fa-money-bill',
    'color'    => '#22C55E',
    'desc'     => 'Deep dive into payroll runs, deductions, tax handling, and compliance reporting.',
    'topics'   => ['Payroll Configuration','Running Payroll','Handling Deductions','Tax Compliance','Payslip Generation','Error Resolution'],
  ],
  [
    'id'       => 'procurement-admin',
    'title'    => 'Procurement System — Full Training',
    'product'  => 'procurement-system',
    'level'    => 'Beginner',
    'duration' => '4 hours',
    'modules'  => 10,
    'icon'     => 'fa-boxes-stacking',
    'color'    => '#3B82F6',
    'desc'     => 'End-to-end procurement training — supplier onboarding, purchase orders, approvals, invoices, and reporting.',
    'topics'   => ['Supplier Management','Creating Purchase Orders','Approval Workflows','Invoice Processing','Budget Tracking','Vendor Performance','System Configuration','Reports'],
  ],
  [
    'id'       => 'elearning-admin',
    'title'    => 'eLearning — Course Creation & Management',
    'product'  => 'elearning-system',
    'level'    => 'Beginner',
    'duration' => '3 hours',
    'modules'  => 7,
    'icon'     => 'fa-graduation-cap',
    'color'    => '#A855F7',
    'desc'     => 'Learn to create courses, manage students, set up assessments, and track progress analytics.',
    'topics'   => ['Platform Setup','Course Builder','Student Enrollment','Assessments & Quizzes','Grading & Certificates','Progress Analytics','Communication Tools'],
  ],
  [
    'id'       => 'crm-sales',
    'title'    => 'CRM — Sales Team Training',
    'product'  => 'crm-system',
    'level'    => 'Beginner',
    'duration' => '2.5 hours',
    'modules'  => 6,
    'icon'     => 'fa-handshake',
    'color'    => '#06B6D4',
    'desc'     => 'Train your sales team on lead management, deal tracking, communication logging, and sales reporting.',
    'topics'   => ['Lead Capture & Management','Pipeline Management','Contact Records','Communication Logging','Deal Closing Workflow','Sales Reports & Forecasting'],
  ],
  [
    'id'       => 'general-admin',
    'title'    => 'System Administration Fundamentals',
    'product'  => null,
    'level'    => 'Beginner',
    'duration' => '1.5 hours',
    'modules'  => 4,
    'icon'     => 'fa-gear',
    'color'    => '#6B7280',
    'desc'     => 'General training on user management, role configuration, security settings, and system maintenance applicable to all Krestworks systems.',
    'topics'   => ['User Management','Role & Permissions Setup','Security Settings','Backup & Maintenance','Audit Logs','System Health Monitoring'],
  ],
];

$selectedCourse = $_GET['course'] ?? null;
$activeCourse = $selectedCourse ? array_values(array_filter($courses, fn($c) => $c['id'] === $selectedCourse)) : null;
$activeCourse = $activeCourse ? $activeCourse[0] : null;
$hasAccess = !$activeCourse || $activeCourse['product'] === null || in_array($activeCourse['product'], $activeSlugs);
?>

<div class="portal-page-header">
  <div>
    <h1>Training Resources</h1>
    <p>Video courses, manuals, and guided tutorials for your Krestworks systems.</p>
  </div>
</div>

<?php if ($activeCourse && $hasAccess): ?>
<!-- Course detail view -->
<div style="margin-bottom:1rem;">
  <a href="<?= url('portal/training') ?>" style="font-size:0.8rem;color:var(--kw-text-muted);text-decoration:none;">
    <i class="fa-solid fa-arrow-left" style="margin-right:0.3rem;"></i>Back to Training Library
  </a>
</div>
<div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem;align-items:flex-start;">
  <div>
    <div class="portal-card">
      <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem;">
        <div style="width:52px;height:52px;border-radius:var(--kw-radius-md);background:<?= $activeCourse['color'] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="fa-solid <?= $activeCourse['icon'] ?>" style="font-size:1.25rem;color:<?= $activeCourse['color'] ?>;"></i>
        </div>
        <div>
          <h2 style="font-size:1.15rem;margin:0 0 0.2rem;"><?= e($activeCourse['title']) ?></h2>
          <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
            <span style="background:<?= $activeCourse['color'] ?>15;color:<?= $activeCourse['color'] ?>;border-radius:999px;padding:0.1rem 0.55rem;font-size:0.68rem;font-weight:700;"><?= $activeCourse['level'] ?></span>
            <span style="font-size:0.72rem;color:var(--kw-text-muted);"><i class="fa-solid fa-clock" style="margin-right:0.2rem;"></i><?= $activeCourse['duration'] ?></span>
            <span style="font-size:0.72rem;color:var(--kw-text-muted);"><i class="fa-solid fa-layer-group" style="margin-right:0.2rem;"></i><?= $activeCourse['modules'] ?> modules</span>
          </div>
        </div>
      </div>
      <p style="font-size:0.875rem;color:var(--kw-text-secondary);line-height:1.7;margin-bottom:1.5rem;"><?= e($activeCourse['desc']) ?></p>

      <!-- Module list -->
      <h4 style="font-size:0.82rem;text-transform:uppercase;letter-spacing:0.06em;color:var(--kw-text-muted);margin-bottom:1rem;">Course Modules</h4>
      <?php foreach ($activeCourse['topics'] as $i => $topic): ?>
      <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 0;border-bottom:1px solid var(--kw-border);" onclick="startModule(<?= $i+1 ?>)" style="cursor:pointer;">
        <div style="width:28px;height:28px;border-radius:50%;background:<?= $activeCourse['color'] ?>15;border:1px solid <?= $activeCourse['color'] ?>30;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.7rem;font-weight:800;color:<?= $activeCourse['color'] ?>;"><?= $i+1 ?></div>
        <span style="font-size:0.82rem;flex:1;"><?= e($topic) ?></span>
        <button onclick="event.stopPropagation();startModule(<?= $i+1 ?>)" class="kw-btn kw-btn-ghost kw-btn-sm" style="font-size:0.68rem;">
          <i class="fa-solid fa-play"></i> Start
        </button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <div>
    <div class="portal-card" style="border-top:3px solid <?= $activeCourse['color'] ?>;">
      <h4 style="margin-bottom:1rem;">Course Info</h4>
      <?php foreach ([
        ['fa-clock','Duration',$activeCourse['duration']],
        ['fa-signal','Level',$activeCourse['level']],
        ['fa-layer-group','Modules',$activeCourse['modules'] . ' modules'],
        ['fa-certificate','Certificate','On completion'],
        ['fa-language','Language','English'],
      ] as $info): ?>
      <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--kw-border);font-size:0.8rem;">
        <span style="display:flex;align-items:center;gap:0.4rem;color:var(--kw-text-muted);"><i class="fa-solid <?= $info[0] ?>" style="color:<?= $activeCourse['color'] ?>;width:14px;font-size:0.75rem;"></i><?= $info[1] ?></span>
        <span style="font-weight:600;"><?= $info[2] ?></span>
      </div>
      <?php endforeach; ?>
      <div style="margin-top:1.25rem;">
        <button onclick="startModule(1)" class="kw-btn kw-btn-primary" style="width:100%;justify-content:center;">
          <i class="fa-solid fa-play"></i> Start Course
        </button>
      </div>
    </div>
  </div>
</div>

<?php else: ?>
<!-- Course library -->

<!-- Level filter -->
<div style="display:flex;gap:0.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
  <?php foreach (['All Courses','Beginner','Intermediate','Advanced'] as $lvl): ?>
  <button onclick="filterCourses('<?= $lvl ?>')" class="course-filter" data-level="<?= $lvl ?>" style="padding:0.3rem 0.85rem;border-radius:999px;border:1px solid var(--kw-border);background:none;color:var(--kw-text-muted);font-size:0.75rem;font-weight:600;cursor:pointer;transition:all 0.2s;"><?= $lvl ?></button>
  <?php endforeach; ?>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
  <?php foreach ($courses as $course):
    $accessible = $course['product'] === null || in_array($course['product'], $activeSlugs);
  ?>
  <div class="portal-card course-card" data-level="<?= $course['level'] ?>" style="border-top:3px solid <?= $accessible ? $course['color'] : 'var(--kw-border)' ?>;<?= !$accessible ? 'opacity:0.7;' : '' ?>">
    <div style="display:flex;align-items:flex-start;gap:0.75rem;margin-bottom:0.85rem;">
      <div style="width:44px;height:44px;border-radius:var(--kw-radius-md);background:<?= $course['color'] ?>15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fa-solid <?= $course['icon'] ?>" style="font-size:1.1rem;color:<?= $course['color'] ?>;"></i>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:0.875rem;font-weight:700;line-height:1.3;margin-bottom:0.3rem;"><?= e($course['title']) ?></div>
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
          <span style="background:<?= $course['color'] ?>12;color:<?= $course['color'] ?>;border-radius:999px;padding:0.1rem 0.5rem;font-size:0.62rem;font-weight:700;"><?= $course['level'] ?></span>
          <span style="font-size:0.68rem;color:var(--kw-text-muted);"><i class="fa-solid fa-clock" style="margin-right:0.2rem;"></i><?= $course['duration'] ?></span>
        </div>
      </div>
    </div>
    <p style="font-size:0.78rem;color:var(--kw-text-muted);line-height:1.5;margin-bottom:1rem;"><?= e(mb_substr($course['desc'], 0, 100)) ?>…</p>
    <div style="display:flex;gap:0.5rem;">
      <?php if ($accessible): ?>
      <a href="?course=<?= $course['id'] ?>" class="kw-btn kw-btn-primary kw-btn-sm" style="flex:1;justify-content:center;"><i class="fa-solid fa-play"></i> Start Training</a>
      <?php else: ?>
      <span style="flex:1;text-align:center;font-size:0.72rem;color:var(--kw-text-muted);padding:0.5rem;">
        <i class="fa-solid fa-lock" style="margin-right:0.3rem;"></i>Requires subscription
      </span>
      <?php endif; ?>
      <span style="font-size:0.7rem;color:var(--kw-text-muted);align-self:center;"><?= $course['modules'] ?> modules</span>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<script>
function filterCourses(level) {
  document.querySelectorAll('.course-filter').forEach(btn => {
    const active = btn.dataset.level === level;
    btn.style.borderColor = active ? 'var(--kw-primary)' : '';
    btn.style.background = active ? 'rgba(245,168,0,0.1)' : 'none';
    btn.style.color = active ? 'var(--kw-primary)' : '';
  });
  document.querySelectorAll('.course-card').forEach(card => {
    card.style.display = (level === 'All Courses' || card.dataset.level === level) ? '' : 'none';
  });
}
filterCourses('All Courses');
function startModule(num) {
  window.Krest?.toast('Module ' + num + ' opening… (video player integration pending)', 'info');
}
</script>
<?php require_once __DIR__ . '/portal-footer.php'; ?>