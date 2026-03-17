<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Enterprise Products — ' . APP_NAME;
$page_description = 'Nine production-grade enterprise systems: HR Management, Procurement, eLearning, Real Estate, Supply Chain, CRM, Hospital Management, Decision Support, and POS.';


$products = [
  ['hr-system','fa-users','HR Management System','#F5A800',
   'End-to-end human resource lifecycle management from recruitment to retirement.',
   ['Payroll Processing','Leave Management','Performance Tracking','Recruitment Module','HR Analytics','Time & Attendance','Training Management','Compliance Reports'],
   'Cloud / On-Premise / Hybrid','Healthcare, Corporate, Government, Education'],

  ['procurement-system','fa-shopping-cart','Procurement Management System','#3B82F6',
   'Automate procurement workflows from requisition to payment reconciliation.',
   ['Supplier Management','Purchase Workflows','Invoice Processing','Approval Automation','Spend Analytics','Budget Tracking','Contract Management','Financial Integration'],
   'Cloud / On-Premise / Hybrid','Manufacturing, Government, Healthcare, Corporate'],

  ['elearning-system','fa-graduation-cap','eLearning Management System','#22C55E',
   'Full-featured LMS for academic institutions and corporate training programs.',
   ['Course Management','Student Portal','Online Exams & Grading','Progress Analytics','Certification System','Live Classes','Content Library','Bulk Enrollment'],
   'Cloud / SaaS','Education, Corporate, NGOs, Government'],

  ['real-estate-system','fa-building','Real Estate Management System','#A855F7',
   'Manage properties, tenants, leases, and rent collections in one connected platform.',
   ['Property Management','Tenant Management','Lease Tracking','Rent Automation','Maintenance Requests','Financial Reports','Document Storage','Tenant Portal'],
   'Cloud / On-Premise','Real Estate, Property Management, Hospitality'],

  ['supply-chain-system','fa-truck','Supply Chain Management System','#F97316',
   'Full visibility and control across inventory, logistics, warehousing, and fulfilment.',
   ['Inventory Tracking','Logistics Management','Warehouse Control','Order Processing','Supplier Integration','Demand Forecasting','Multi-location Support','Real-time Analytics'],
   'Cloud / On-Premise / Hybrid','Manufacturing, Retail, Logistics, Agriculture'],

  ['decision-support-system','fa-chart-line','Executive Decision Support System','#EF4444',
   'AI-powered analytics and business intelligence for C-suite strategic decisions.',
   ['Business Analytics','Data Visualisation','AI Insights','KPI Dashboards','Scenario Modelling','Report Builder','Multi-source Integration','Alerts & Notifications'],
   'Cloud / On-Premise','Corporate, Finance, Government, Healthcare'],

  ['crm-system','fa-handshake','CRM System','#06B6D4',
   'Turn leads into loyal customers with AI-powered pipeline and relationship management.',
   ['Lead Management','Sales Pipeline','Contact Management','Email Integration','Activity Tracking','Deal Forecasting','Customer Segmentation','Reports & Analytics'],
   'Cloud / SaaS','Corporate, Retail, Real Estate, Financial Services'],

  ['hospital-system','fa-hospital','Hospital Management System','#8B5CF6',
   'Integrated healthcare operations from EMR to pharmacy, billing, and lab.',
   ['Patient Records (EMR)','Appointment Scheduling','Billing & Insurance','Pharmacy Management','Lab Integration','Doctor Portal','Nurse Station','Analytics Dashboard'],
   'Cloud / On-Premise','Hospitals, Clinics, Health Centres, Laboratories'],

  ['pos-system','fa-cash-register','Point of Sale System','#F59E0B',
   'Fast, reliable cloud POS with inventory sync, multi-branch, and integrated payments.',
   ['Sales Processing','Inventory Sync','Multi-branch Support','Payment Integration','Receipt Printing','Daily Reports','Customer Loyalty','Offline Mode'],
   'Cloud / On-Premise','Retail, Hospitality, Restaurants, Supermarkets'],
];
?>

<!-- Page Hero -->
<section class="kw-page-hero">
  <div class="kw-container">
    <div class="kw-breadcrumb">
      <a href="<?= url() ?>">Home</a>
      <i class="fa-solid fa-chevron-right"></i>
      <span class="current">Products</span>
    </div>
    <div data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-boxes-stacked"></i> Enterprise Systems</span>
      <h1>Software Products Built<br><span style="color:var(--kw-primary);">to Scale Your Business</span></h1>
      <p>Nine production-grade enterprise platforms — each engineered for a specific industry vertical, deployable on cloud, on-premise, or hybrid infrastructure.</p>
    </div>
  </div>
</section>

<!-- Filter & Stats -->
<section style="background:var(--kw-bg-alt);border-bottom:1px solid var(--kw-border);padding:1.5rem 0;position:sticky;top:var(--kw-nav-height);z-index:100;backdrop-filter:blur(12px);">
  <div class="kw-container">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
      <div style="display:flex;gap:0.5rem;flex-wrap:wrap;" id="product-filters">
        <?php
        $filters = ['All','Cloud','On-Premise','Healthcare','Education','Corporate','Manufacturing','Retail'];
        foreach ($filters as $i => $f): ?>
        <button class="kw-btn kw-btn-sm product-filter-btn <?= $i === 0 ? 'active' : '' ?>"
                data-filter="<?= $f ?>"
                style="<?= $i === 0 ? 'background:var(--kw-primary);color:#0A0F1A;' : '' ?>font-size:0.78rem;">
          <?= $f ?>
        </button>
        <?php endforeach; ?>
      </div>
      <div class="kw-search" style="max-width:260px;flex:1;">
        <i class="fa-solid fa-search"></i>
        <input type="text" id="product-search" placeholder="Search products..." style="width:100%;">
      </div>
    </div>
  </div>
</section>

<!-- Products Grid -->
<section class="kw-section" style="background:var(--kw-bg);">
  <div class="kw-container">

    <!-- Comparison CTA -->
    <div class="kw-highlight-box" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:3rem;" data-aos="fade-up">
      <div style="display:flex;align-items:center;gap:0.75rem;">
        <i class="fa-solid fa-scale-balanced" style="color:var(--kw-primary);font-size:1.3rem;"></i>
        <div>
          <div style="font-weight:700;font-size:0.95rem;">Not sure which system fits your business?</div>
          <div style="font-size:0.82rem;color:var(--kw-text-muted);">Use our AI-powered system selector or book a free consultation.</div>
        </div>
      </div>
      <div style="display:flex;gap:0.6rem;flex-wrap:wrap;">
        <a href="<?= url('innovation-lab/system-selector') ?>" class="kw-btn kw-btn-primary kw-btn-sm">
          <i class="fa-solid fa-robot"></i> System Selector
        </a>
        <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-ghost kw-btn-sm">
          <i class="fa-solid fa-calendar-check"></i> Free Consultation
        </a>
      </div>
    </div>

    <!-- Grid -->
    <div id="products-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;">
      <?php foreach ($products as $i => $p): ?>
      <div class="product-card kw-product-item"
           data-tags="<?= strtolower($p[6] . ' ' . $p[7]) ?>"
           data-name="<?= strtolower($p[2]) ?>"
           data-aos="fade-up"
           data-aos-delay="<?= ($i % 3) * 80 ?>">

        <!-- Top accent line -->
        <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,<?= $p[3] ?>,transparent);border-radius:var(--kw-radius-lg) var(--kw-radius-lg) 0 0;"></div>

        <div class="product-card-icon" style="background:<?= $p[3] ?>15;color:<?= $p[3] ?>;">
          <i class="fa-solid <?= $p[1] ?>"></i>
        </div>

        <h3 style="margin-bottom:0.3rem;"><?= $p[2] ?></h3>
        <p class="tagline"><?= $p[4] ?></p>

        <!-- Deployment badges -->
        <div style="display:flex;flex-wrap:wrap;gap:0.3rem;margin-bottom:1rem;">
          <?php foreach (explode(' / ', $p[6]) as $dep): ?>
            <span style="font-size:0.68rem;padding:0.18rem 0.55rem;border-radius:999px;background:<?= $p[3] ?>12;color:<?= $p[3] ?>;border:1px solid <?= $p[3] ?>30;font-weight:600;">
              <?= trim($dep) ?>
            </span>
          <?php endforeach; ?>
        </div>

        <ul class="product-card-features">
          <?php foreach (array_slice($p[5], 0, 5) as $feat): ?>
            <li><?= $feat ?></li>
          <?php endforeach; ?>
          <?php if (count($p[5]) > 5): ?>
            <li style="color:var(--kw-primary);font-weight:600;">+<?= count($p[5]) - 5 ?> more features</li>
          <?php endif; ?>
        </ul>

        <!-- Industries -->
        <div style="margin-bottom:1.25rem;padding-top:0.75rem;border-top:1px solid var(--kw-border);">
          <span style="font-size:0.72rem;color:var(--kw-text-muted);font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">Industries: </span>
          <span style="font-size:0.75rem;color:var(--kw-text-secondary);"><?= $p[7] ?></span>
        </div>

        <div class="product-card-actions">
          <a href="<?= url('products/' . $p[0]) ?>" class="kw-btn kw-btn-primary kw-btn-sm" style="flex:1;justify-content:center;">
            <i class="fa-solid fa-arrow-right"></i> View Details
          </a>
          <a href="<?= url('demo') ?>" class="kw-btn kw-btn-ghost kw-btn-sm" title="Request Demo">
            <i class="fa-solid fa-play"></i>
          </a>
          <button class="kw-btn kw-btn-ghost kw-btn-sm compare-btn" data-product="<?= $p[0] ?>" data-name="<?= $p[2] ?>" data-color="<?= $p[3] ?>" data-icon="<?= $p[1] ?>" title="Add to compare">
            <i class="fa-solid fa-scale-balanced"></i>
          </button>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- No results message -->
    <div id="no-results" style="display:none;text-align:center;padding:4rem 0;">
      <i class="fa-solid fa-search" style="font-size:2.5rem;color:var(--kw-text-muted);margin-bottom:1rem;display:block;"></i>
      <h4>No products match your search</h4>
      <p style="color:var(--kw-text-muted);">Try a different keyword or filter.</p>
      <button onclick="resetFilters()" class="kw-btn kw-btn-ghost" style="margin-top:1rem;">Clear Filters</button>
    </div>

  </div>
</section>

<!-- Deployment Options -->
<section class="kw-section" style="background:var(--kw-bg-alt);border-top:1px solid var(--kw-border);">
  <div class="kw-container">
    <div class="kw-section-title" data-aos="fade-up">
      <span class="label"><i class="fa-solid fa-server"></i> Deployment</span>
      <h2>Deploy Your Way</h2>
      <p>Every Krestworks system supports multiple deployment models — choose what fits your infrastructure, compliance needs, and budget.</p>
      <div class="kw-divider"></div>
    </div>
    <div class="kw-grid-3" data-aos="fade-up" data-aos-delay="100">
      <?php
      $deployments = [
        ['fa-cloud','Cloud Hosted','#3B82F6',
         'Fully managed on our cloud infrastructure. No servers to manage.',
         ['Fastest time to go-live','Automatic updates','Managed backups','99.9% uptime SLA','Pay as you scale']],
        ['fa-server','On-Premise','#22C55E',
         'Installed on your own servers inside your network perimeter.',
         ['Full data sovereignty','No recurring hosting fees','Custom integrations','Works offline','Your infrastructure']],
        ['fa-layer-group','Hybrid','#F5A800',
         'Core data on-premise, cloud features for scale and collaboration.',
         ['Best of both worlds','Data stays local','Cloud AI features','Disaster recovery','Flexible scaling']],
      ];
      foreach ($deployments as $dep): ?>
      <div class="kw-card" style="padding:2rem;" data-aos="zoom-in">
        <div style="width:56px;height:56px;border-radius:var(--kw-radius-md);background:<?= $dep[2] ?>15;color:<?= $dep[2] ?>;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:1.25rem;">
          <i class="fa-solid <?= $dep[0] ?>"></i>
        </div>
        <h3 style="margin-bottom:0.5rem;"><?= $dep[1] ?></h3>
        <p style="font-size:0.875rem;margin-bottom:1.25rem;"><?= $dep[3] ?></p>
        <ul style="display:flex;flex-direction:column;gap:0.4rem;">
          <?php foreach ($dep[4] as $feat): ?>
          <li style="display:flex;align-items:center;gap:0.5rem;font-size:0.82rem;color:var(--kw-text-secondary);">
            <i class="fa-solid fa-check" style="color:<?= $dep[2] ?>;font-size:0.7rem;flex-shrink:0;"></i><?= $feat ?>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Compare Drawer -->
<div id="compare-drawer" style="position:fixed;bottom:0;left:0;right:0;background:var(--kw-bg-card);border-top:2px solid var(--kw-primary);padding:1rem 1.5rem;z-index:500;transform:translateY(100%);transition:transform 0.3s ease;box-shadow:0 -8px 32px rgba(0,0,0,0.15);">
  <div class="kw-container">
    <div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
      <span style="font-size:0.85rem;font-weight:700;color:var(--kw-text-muted);white-space:nowrap;">Compare:</span>
      <div id="compare-items" style="display:flex;gap:0.75rem;flex:1;flex-wrap:wrap;"></div>
      <div style="display:flex;gap:0.6rem;flex-shrink:0;">
        <button id="compare-now-btn" class="kw-btn kw-btn-primary kw-btn-sm" disabled>
          <i class="fa-solid fa-scale-balanced"></i> Compare Now
        </button>
        <button onclick="clearCompare()" class="kw-btn kw-btn-ghost kw-btn-sm">
          <i class="fa-solid fa-times"></i> Clear
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Compare Modal -->
<div id="compare-modal" class="kw-modal-overlay">
  <div class="kw-modal" style="max-width:900px;">
    <div class="kw-modal-header">
      <h3><i class="fa-solid fa-scale-balanced" style="color:var(--kw-primary);"></i> Product Comparison</h3>
      <button class="kw-modal-close"><i class="fa-solid fa-times"></i></button>
    </div>
    <div class="kw-modal-body" id="compare-modal-body" style="overflow-x:auto;"></div>
  </div>
</div>

<!-- CTA -->
<section style="background:var(--kw-bg-hero);padding:5rem 0;">
  <div class="kw-container" style="text-align:center;" data-aos="fade-up">
    <h2 style="color:#fff;margin-bottom:1rem;">Need a Custom System Not Listed Here?</h2>
    <p style="color:rgba(255,255,255,0.6);max-width:520px;margin:0 auto 2rem;">We build fully custom enterprise platforms tailored to your exact requirements. Tell us what you need.</p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= url('consultation') ?>" class="kw-btn kw-btn-primary kw-btn-lg"><i class="fa-solid fa-comments"></i> Discuss Your System</a>
      <a href="<?= url('services/custom-software') ?>" class="kw-btn kw-btn-lg" style="background:rgba(255,255,255,0.08);color:#fff;border:1px solid rgba(255,255,255,0.2);"><i class="fa-solid fa-laptop-code"></i> Custom Development</a>
    </div>
  </div>
</section>

<script>
// ---- Filter & Search ----
const filterBtns   = document.querySelectorAll('.product-filter-btn');
const searchInput  = document.getElementById('product-search');
const items        = document.querySelectorAll('.kw-product-item');
const noResults    = document.getElementById('no-results');
let activeFilter   = 'all';
let searchQuery    = '';

function applyFilters() {
  let visible = 0;
  items.forEach(item => {
    const tags = item.dataset.tags || '';
    const name = item.dataset.name || '';
    const matchFilter = activeFilter === 'all' || tags.includes(activeFilter.toLowerCase());
    const matchSearch = !searchQuery || name.includes(searchQuery) || tags.includes(searchQuery);
    const show = matchFilter && matchSearch;
    item.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  noResults.style.display = visible === 0 ? 'block' : 'none';
}

filterBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    filterBtns.forEach(b => {
      b.classList.remove('active');
      b.style.background = ''; b.style.color = '';
    });
    btn.classList.add('active');
    btn.style.background = 'var(--kw-primary)';
    btn.style.color = '#0A0F1A';
    activeFilter = btn.dataset.filter;
    applyFilters();
  });
});

let searchTimer;
searchInput?.addEventListener('input', () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    searchQuery = searchInput.value.trim().toLowerCase();
    applyFilters();
  }, 200);
});

function resetFilters() {
  activeFilter  = 'all';
  searchQuery   = '';
  if (searchInput) searchInput.value = '';
  filterBtns.forEach((b, i) => {
    b.classList.toggle('active', i === 0);
    b.style.background = i === 0 ? 'var(--kw-primary)' : '';
    b.style.color      = i === 0 ? '#0A0F1A' : '';
  });
  applyFilters();
}

// ---- Compare ----
let compareList = [];
const drawer    = document.getElementById('compare-drawer');
const compareItemsEl = document.getElementById('compare-items');
const compareBtn = document.getElementById('compare-now-btn');

document.querySelectorAll('.compare-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const id    = btn.dataset.product;
    const name  = btn.dataset.name;
    const color = btn.dataset.color;
    const icon  = btn.dataset.icon;

    if (compareList.find(p => p.id === id)) {
      compareList = compareList.filter(p => p.id !== id);
      btn.style.background = '';
      btn.style.color = '';
    } else {
      if (compareList.length >= 3) {
        window.Krest?.toast('You can compare up to 3 products at a time.', 'info');
        return;
      }
      compareList.push({ id, name, color, icon });
      btn.style.background = color + '20';
      btn.style.color = color;
    }
    updateCompareDrawer();
  });
});

function updateCompareDrawer() {
  drawer.style.transform = compareList.length > 0 ? 'translateY(0)' : 'translateY(100%)';
  compareBtn.disabled = compareList.length < 2;
  compareItemsEl.innerHTML = compareList.map(p => `
    <div style="display:flex;align-items:center;gap:0.4rem;background:${p.color}12;border:1px solid ${p.color}30;border-radius:999px;padding:0.3rem 0.75rem 0.3rem 0.5rem;font-size:0.78rem;font-weight:600;color:${p.color};">
      <i class="fa-solid ${p.icon}" style="font-size:0.8rem;"></i>${p.name}
      <span onclick="removeCompare('${p.id}')" style="cursor:pointer;opacity:0.6;margin-left:0.25rem;font-size:0.75rem;">✕</span>
    </div>
  `).join('');
}

function removeCompare(id) {
  compareList = compareList.filter(p => p.id !== id);
  // Reset button state
  document.querySelectorAll('.compare-btn').forEach(btn => {
    if (btn.dataset.product === id) { btn.style.background = ''; btn.style.color = ''; }
  });
  updateCompareDrawer();
}

function clearCompare() {
  compareList = [];
  document.querySelectorAll('.compare-btn').forEach(btn => { btn.style.background=''; btn.style.color=''; });
  updateCompareDrawer();
}

compareBtn?.addEventListener('click', () => {
  if (compareList.length < 2) return;
  buildCompareTable();
  window.Krest?.openModal('compare-modal');
});

function buildCompareTable() {
  const featureMap = {
    'hr-system':             { price:'From KES 50,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'24/7' },
    'procurement-system':    { price:'From KES 60,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'24/7' },
    'elearning-system':      { price:'From KES 45,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'Business hours' },
    'real-estate-system':    { price:'From KES 55,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'Business hours' },
    'supply-chain-system':   { price:'From KES 70,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'24/7' },
    'decision-support-system':{ price:'From KES 80,000',users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'24/7' },
    'crm-system':            { price:'From KES 40,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'Business hours' },
    'hospital-system':       { price:'From KES 90,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'24/7' },
    'pos-system':            { price:'From KES 35,000', users:'Unlimited',  cloud:'✅', onprem:'✅', mobile:'✅', ai:'✅', api:'✅', support:'Business hours' },
  };

  const rows = [
    ['Starting Price','price'],['User Licenses','users'],
    ['Cloud Deployment','cloud'],['On-Premise','onprem'],
    ['Mobile App','mobile'],['AI Features','ai'],
    ['API Access','api'],['Support','support'],
  ];

  let html = '<table style="width:100%;border-collapse:collapse;font-size:0.85rem;">';
  html += '<thead><tr><th style="text-align:left;padding:0.75rem 1rem;border-bottom:2px solid var(--kw-border);color:var(--kw-text-muted);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;">Feature</th>';
  compareList.forEach(p => {
    html += `<th style="text-align:center;padding:0.75rem 1rem;border-bottom:2px solid ${p.color};color:${p.color};font-size:0.8rem;">${p.name}</th>`;
  });
  html += '</tr></thead><tbody>';

  rows.forEach(([label, key]) => {
    html += `<tr><td style="padding:0.7rem 1rem;border-bottom:1px solid var(--kw-border);font-weight:600;color:var(--kw-text-secondary);">${label}</td>`;
    compareList.forEach(p => {
      const val = featureMap[p.id]?.[key] ?? '—';
      html += `<td style="text-align:center;padding:0.7rem 1rem;border-bottom:1px solid var(--kw-border);">${val}</td>`;
    });
    html += '</tr>';
  });

  html += '</tbody></table>';
  html += '<div style="display:flex;gap:0.6rem;justify-content:center;margin-top:1.5rem;flex-wrap:wrap;">';
  compareList.forEach(p => {
    html += `<a href="<?= url('products/') ?>${p.id}" class="kw-btn kw-btn-sm" style="background:${p.color}15;color:${p.color};border:1px solid ${p.color}30;">View ${p.name} →</a>`;
  });
  html += '</div>';

  document.getElementById('compare-modal-body').innerHTML = html;
}

@media (max-width: 768px) {
  #products-grid { grid-template-columns: 1fr !important; }
}
</script>
<style>
@media (max-width:1024px){ #products-grid{ grid-template-columns:repeat(2,1fr)!important; } }
@media (max-width:640px) { #products-grid{ grid-template-columns:1fr!important; } }
</style>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>