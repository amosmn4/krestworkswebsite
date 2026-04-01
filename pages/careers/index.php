<?php
$page_title       = 'Careers — ' . APP_NAME;
$page_description = 'Join the Krestworks team. Explore open positions in software engineering, AI, design, sales, and support. Build enterprise systems that transform East Africa.';
require_once __DIR__ . '/../includes/header.php';

$pdo = db();

// Filters
$search   = trim($_GET['q'] ?? '');
$dept     = $_GET['dept'] ?? '';
$type     = $_GET['type'] ?? '';
$qual     = $_GET['qual'] ?? '';
$remote   = $_GET['remote'] ?? '';
$page_num = max(1, (int)($_GET['page'] ?? 1));
$per_page = 8;
$offset   = ($page_num - 1) * $per_page;

$where  = ['jp.is_active = 1'];
$params = [];

if ($search) {
    $where[]  = '(jp.title LIKE ? OR jp.department LIKE ? OR jp.description LIKE ?)';
    $params   = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}
if ($dept)   { $where[] = 'jp.department = ?';       $params[] = $dept; }
if ($type)   { $where[] = 'jp.employment_type = ?';  $params[] = $type; }
if ($qual)   { $where[] = 'jp.min_qualification = ?';$params[] = $qual; }
if ($remote !== '') { $where[] = 'jp.is_remote = ?'; $params[] = (int)$remote; }

$wsql = implode(' AND ', $where);

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM job_positions jp WHERE $wsql");
$countStmt->execute($params);
$total     = (int)$countStmt->fetchColumn();
$totalPages = max(1, ceil($total / $per_page));

$stmt = $pdo->prepare("
    SELECT jp.*,
           (SELECT COUNT(*) FROM job_applications ja WHERE ja.job_position_id = jp.id AND ja.status != 'draft') AS applicant_count
    FROM job_positions jp
    WHERE $wsql
    ORDER BY jp.is_featured DESC, jp.created_at DESC
    LIMIT ? OFFSET ?
");
$stmt->execute(array_merge($params, [$per_page, $offset]));
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Distinct departments for filter
$depts = $pdo->query("SELECT DISTINCT department FROM job_positions WHERE is_active=1 AND department IS NOT NULL ORDER BY department")->fetchAll(PDO::FETCH_COLUMN);

$qualLabels = ['kcse'=>'KCSE','certificate'=>'Certificate','diploma'=>'Diploma','degree'=>'Bachelor\'s Degree','masters'=>'Master\'s','phd'=>'PhD'];
$typeLabels = ['full-time'=>'Full Time','part-time'=>'Part Time','contract'=>'Contract','internship'=>'Internship','volunteer'=>'Volunteer'];
$typeColors = ['full-time'=>'#22C55E','part-time'=>'#3B82F6','contract'=>'#F5A800','internship'=>'#A855F7','volunteer'=>'#F97316'];

// Days left helper
function daysLeft(?string $deadline): string {
    if (!$deadline) return 'Open';
    $diff = (int)ceil((strtotime($deadline) - time()) / 86400);
    if ($diff < 0)  return 'Closed';
    if ($diff === 0) return 'Closes today';
    if ($diff === 1) return '1 day left';
    return "$diff days left";
}
?>

<!-- Hero -->
<section class="kw-page-hero" style="min-height:360px;">
  <div class="kw-container">
    <div style="max-width:640px;padding:3rem 0 2.5rem;" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-briefcase"></i> Careers</span>
      <h1>Build What Matters.<br><span style="color:var(--kw-primary);">Work at Krestworks.</span></h1>
      <p style="color:rgba(255,255,255,0.65);font-size:1rem;line-height:1.8;margin-bottom:2rem;">
        We build enterprise systems that transform how organisations operate across East Africa. Join a team of engineers, designers, and consultants solving real problems at scale.
      </p>
      <!-- Quick search -->
      <form method="GET" action="" style="display:flex;max-width:480px;gap:0;">
        <input type="text" name="q" value="<?= e($search) ?>" placeholder="Search job titles, departments…"
          style="flex:1;padding:0.75rem 1.25rem;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-right:none;border-radius:var(--kw-radius-md) 0 0 var(--kw-radius-md);font-size:0.9rem;color:#fff;outline:none;backdrop-filter:blur(8px);"
          onfocus="this.style.borderColor='var(--kw-primary)'" onblur="this.style.borderColor='rgba(255,255,255,0.2)'"
          placeholder="e.g. Software Engineer, Design…">
        <button type="submit" style="padding:0.75rem 1.25rem;background:var(--kw-primary);border:none;border-radius:0 var(--kw-radius-md) var(--kw-radius-md) 0;cursor:pointer;color:#0A0F1A;font-weight:700;white-space:nowrap;font-size:0.875rem;">
          <i class="fa-solid fa-search"></i> Search
        </button>
      </form>
    </div>
  </div>
  <!-- Stats bar -->
  <div style="background:rgba(0,0,0,0.2);border-top:1px solid rgba(255,255,255,0.08);padding:0.85rem 0;">
    <div class="kw-container" style="display:flex;gap:2rem;flex-wrap:wrap;">
      <?php foreach ([
        ['fa-briefcase', number_format($total) . ' Open Roles'],
        ['fa-globe-africa', 'Kenya & Remote'],
        ['fa-users', 'Growing Team'],
        ['fa-heart', 'Impact-Driven Work'],
      ] as $d): ?>
      <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;color:rgba(255,255,255,0.6);">
        <i class="fa-solid <?= $d[0] ?>" style="color:var(--kw-primary);"></i> <?= $d[1] ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Main content -->
<section style="background:var(--kw-bg);padding:3rem 0 5rem;">
  <div class="kw-container">
    <div style="display:grid;grid-template-columns:260px 1fr;gap:2rem;align-items:flex-start;">

      <!-- Filters sidebar -->
      <aside style="position:sticky;top:calc(var(--kw-nav-height)+1rem);" data-aos="fade-right">
        <div class="kw-card" style="padding:1.25rem;">
          <form method="GET" action="" id="filter-form">
            <?php if ($search): ?><input type="hidden" name="q" value="<?= e($search) ?>"><?php endif; ?>

            <div style="font-size:0.72rem;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;color:var(--kw-text-muted);margin-bottom:0.85rem;display:flex;align-items:center;justify-content:space-between;">
              Filters
              <?php if ($dept || $type || $qual || $remote !== ''): ?>
              <a href="?<?= $search ? 'q='.urlencode($search) : '' ?>" style="font-size:0.65rem;color:var(--kw-primary);font-weight:700;text-transform:none;letter-spacing:0;">✕ Clear</a>
              <?php endif; ?>
            </div>

            <!-- Department -->
            <div style="margin-bottom:1.1rem;">
              <label class="kw-label" style="font-size:0.68rem;">Department</label>
              <select name="dept" class="kw-input" style="font-size:0.82rem;" onchange="this.form.submit()">
                <option value="">All Departments</option>
                <?php foreach ($depts as $d): ?>
                <option value="<?= e($d) ?>" <?= $dept===$d?'selected':'' ?>><?= e($d) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Employment type -->
            <div style="margin-bottom:1.1rem;">
              <label class="kw-label" style="font-size:0.68rem;">Employment Type</label>
              <?php foreach ($typeLabels as $k => $label): ?>
              <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;margin-bottom:0.3rem;cursor:pointer;">
                <input type="radio" name="type" value="<?= $k ?>" <?= $type===$k?'checked':'' ?> style="accent-color:var(--kw-primary);" onchange="this.form.submit()">
                <span style="color:<?= $typeColors[$k] ?? 'var(--kw-text-muted)' ?>;">●</span> <?= $label ?>
              </label>
              <?php endforeach; ?>
              <?php if ($type): ?>
              <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;cursor:pointer;color:var(--kw-text-muted);">
                <input type="radio" name="type" value="" <?= !$type?'checked':'' ?> onchange="this.form.submit()"> All Types
              </label>
              <?php endif; ?>
            </div>

            <!-- Min qualification -->
            <div style="margin-bottom:1.1rem;">
              <label class="kw-label" style="font-size:0.68rem;">Minimum Qualification</label>
              <select name="qual" class="kw-input" style="font-size:0.82rem;" onchange="this.form.submit()">
                <option value="">Any Level</option>
                <?php foreach ($qualLabels as $k => $label): ?>
                <option value="<?= $k ?>" <?= $qual===$k?'selected':'' ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Remote -->
            <div style="margin-bottom:0.5rem;">
              <label class="kw-label" style="font-size:0.68rem;">Work Mode</label>
              <?php foreach ([['','Any'],['1','Remote OK'],['0','On-site Only']] as $r): ?>
              <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.78rem;margin-bottom:0.3rem;cursor:pointer;">
                <input type="radio" name="remote" value="<?= $r[0] ?>" <?= $remote===$r[0]?'checked':'' ?> style="accent-color:var(--kw-primary);" onchange="this.form.submit()">
                <?= $r[1] ?>
              </label>
              <?php endforeach; ?>
            </div>

          </form>
        </div>

        <!-- Culture card -->
        <div class="kw-card" style="padding:1.25rem;margin-top:1rem;background:linear-gradient(135deg,rgba(245,168,0,0.08),rgba(245,168,0,0.03));border-color:rgba(245,168,0,0.2);">
          <h4 style="font-size:0.82rem;margin-bottom:0.75rem;color:var(--kw-primary);">Life at Krestworks</h4>
          <?php foreach ([
            ['fa-laptop-house','Remote-first culture'],
            ['fa-graduation-cap','Learning & growth budget'],
            ['fa-heart-pulse','Health coverage'],
            ['fa-people-group','Diverse & inclusive team'],
            ['fa-chart-line','Equity & performance bonus'],
          ] as $perk): ?>
          <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.72rem;color:var(--kw-text-muted);margin-bottom:0.35rem;">
            <i class="fa-solid <?= $perk[0] ?>" style="color:var(--kw-primary);width:12px;font-size:0.65rem;"></i> <?= $perk[1] ?>
          </div>
          <?php endforeach; ?>
        </div>
      </aside>

      <!-- Job listings -->
      <div>
        <!-- Results header -->
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;flex-wrap:wrap;gap:0.75rem;">
          <div>
            <h2 style="font-size:1.05rem;margin:0 0 0.2rem;">
              <?php if ($search): ?>Results for "<?= e($search) ?>"<?php else: ?>Open Positions<?php endif; ?>
            </h2>
            <p style="font-size:0.75rem;color:var(--kw-text-muted);">
              <?= number_format($total) ?> <?= $total === 1 ? 'position' : 'positions' ?> found
              <?php if ($dept): ?> · <?= e($dept) ?><?php endif; ?>
              <?php if ($type): ?> · <?= $typeLabels[$type] ?? $type ?><?php endif; ?>
            </p>
          </div>
          <div style="display:flex;gap:0.5rem;align-items:center;font-size:0.72rem;color:var(--kw-text-muted);">
            <span>Sort:</span>
            <a href="?<?= http_build_query(array_merge($_GET, ['sort'=>'newest'])) ?>" style="color:var(--kw-primary);text-decoration:none;font-weight:700;">Newest</a>
          </div>
        </div>

        <?php if (empty($jobs)): ?>
        <div style="text-align:center;padding:4rem 2rem;background:var(--kw-bg-card);border:1px solid var(--kw-border);border-radius:var(--kw-radius-xl);">
          <i class="fa-solid fa-magnifying-glass" style="font-size:2.5rem;color:var(--kw-text-muted);opacity:0.3;margin-bottom:1rem;display:block;"></i>
          <h3 style="margin-bottom:0.5rem;">No positions found</h3>
          <p style="color:var(--kw-text-muted);font-size:0.875rem;margin-bottom:1.5rem;">Try adjusting your filters or search terms.</p>
          <a href="<?= url('careers') ?>" class="kw-btn kw-btn-ghost">Clear All Filters</a>
        </div>
        <?php else: ?>

        <div style="display:flex;flex-direction:column;gap:1rem;">
          <?php foreach ($jobs as $job):
            $days       = daysLeft($job['deadline']);
            $deadlineCls = str_contains($days, 'day') && (int)$days <= 5 ? '#EF4444' : 'var(--kw-text-muted)';
            $typeColor   = $typeColors[$job['employment_type']] ?? '#6B7280';
          ?>
          <div class="kw-card" style="padding:1.5rem;transition:all 0.2s;cursor:pointer;<?= $job['is_featured'] ? 'border-color:rgba(245,168,0,0.3);' : '' ?>"
               onclick="window.location.href='<?= url('careers/' . $job['slug']) ?>'"
               onmouseover="this.style.transform='translateY(-2px)';this.style.borderColor='var(--kw-primary)40'"
               onmouseout="this.style.transform='';this.style.borderColor='<?= $job['is_featured'] ? 'rgba(245,168,0,0.3)' : '' ?>'">

            <?php if ($job['is_featured']): ?>
            <div style="position:absolute;top:0;left:1.5rem;background:var(--kw-primary);color:#0A0F1A;font-size:0.6rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;padding:0.15rem 0.6rem;border-radius:0 0 4px 4px;">Featured</div>
            <?php endif; ?>

            <div style="display:grid;grid-template-columns:1fr auto;gap:1rem;align-items:flex-start;">
              <div>
                <!-- Title + Department -->
                <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.5rem;flex-wrap:wrap;">
                  <span style="background:<?= $typeColor ?>15;color:<?= $typeColor ?>;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.65rem;font-weight:700;"><?= $typeLabels[$job['employment_type']] ?? $job['employment_type'] ?></span>
                  <?php if ($job['is_remote']): ?><span style="background:rgba(59,130,246,0.12);color:#3B82F6;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.65rem;font-weight:700;"><i class="fa-solid fa-wifi" style="font-size:0.55rem;margin-right:0.2rem;"></i>Remote OK</span><?php endif; ?>
                  <span style="font-size:0.7rem;color:var(--kw-text-muted);"><?= e($job['department']) ?></span>
                </div>
                <h3 style="font-size:1.05rem;margin:0 0 0.4rem;line-height:1.3;"><?= e($job['title']) ?></h3>
                <p style="font-size:0.8rem;color:var(--kw-text-muted);margin:0 0 0.85rem;line-height:1.6;"><?= e(truncate(strip_tags($job['description'] ?? ''), 140)) ?></p>

                <!-- Meta chips -->
                <div style="display:flex;align-items:center;gap:0.65rem;flex-wrap:wrap;font-size:0.72rem;color:var(--kw-text-muted);">
                  <span><i class="fa-solid fa-location-dot" style="color:var(--kw-primary);margin-right:0.25rem;"></i><?= e($job['location'] ?? 'Kenya') ?></span>
                  <span><i class="fa-solid fa-graduation-cap" style="color:var(--kw-primary);margin-right:0.25rem;"></i><?= $qualLabels[$job['min_qualification']] ?? $job['min_qualification'] ?></span>
                  <?php if ($job['experience_years'] > 0): ?>
                  <span><i class="fa-solid fa-briefcase" style="color:var(--kw-primary);margin-right:0.25rem;"></i><?= $job['experience_years'] ?>+ yrs exp</span>
                  <?php endif; ?>
                  <?php if ($job['show_salary'] && $job['salary_min']): ?>
                  <span><i class="fa-solid fa-money-bill-wave" style="color:#22C55E;margin-right:0.25rem;"></i><?= $job['salary_currency'] ?> <?= number_format($job['salary_min']) ?><?= $job['salary_max'] ? '–'.number_format($job['salary_max']) : '+' ?>/mo</span>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Right side -->
              <div style="text-align:right;flex-shrink:0;">
                <div style="font-size:0.72rem;color:<?= $deadlineCls ?>;margin-bottom:0.5rem;font-weight:600;">
                  <i class="fa-solid fa-clock" style="margin-right:0.25rem;"></i><?= $days ?>
                </div>
                <?php if ($job['applicant_count'] > 0): ?>
                <div style="font-size:0.65rem;color:var(--kw-text-muted);margin-bottom:0.75rem;"><?= $job['applicant_count'] ?> applicant<?= $job['applicant_count'] !== 1 ? 's' : '' ?></div>
                <?php endif; ?>
                <a href="<?= url('careers/' . $job['slug']) ?>" class="kw-btn kw-btn-primary kw-btn-sm" onclick="event.stopPropagation();">
                  View & Apply
                </a>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div style="display:flex;justify-content:center;gap:0.5rem;margin-top:2rem;flex-wrap:wrap;">
          <?php for ($p = 1; $p <= $totalPages; $p++): ?>
          <a href="?<?= http_build_query(array_merge($_GET, ['page' => $p])) ?>"
             style="width:36px;height:36px;display:flex;align-items:center;justify-content:center;border-radius:var(--kw-radius-md);border:1px solid <?= $p===$page_num?'var(--kw-primary)':'var(--kw-border)' ?>;background:<?= $p===$page_num?'var(--kw-primary)':'transparent' ?>;color:<?= $p===$page_num?'#0A0F1A':'var(--kw-text-muted)' ?>;font-size:0.82rem;font-weight:700;text-decoration:none;">
            <?= $p ?>
          </a>
          <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>

        <!-- Open application CTA -->
        <div class="kw-card" style="padding:1.75rem;margin-top:2rem;text-align:center;border-top:3px solid var(--kw-primary);" data-aos="fade-up">
          <h3 style="margin-bottom:0.35rem;">Don't see the right role?</h3>
          <p style="color:var(--kw-text-muted);font-size:0.875rem;margin-bottom:1.25rem;">We're always looking for talented engineers, designers, and consultants. Send us your CV for future openings.</p>
          <a href="mailto:careers@krestworks.com?subject=Open Application — <?= APP_NAME ?>" class="kw-btn kw-btn-primary"><i class="fa-solid fa-paper-plane"></i> Send Open Application</a>
        </div>

      </div>
    </div>
  </div>
</section>

<style>
@media(max-width:1024px){ .kw-container>div[style*="260px 1fr"]{grid-template-columns:1fr!important;} aside[style*="sticky"]{position:static!important;} }
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>