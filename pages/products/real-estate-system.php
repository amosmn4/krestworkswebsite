<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Real Estate Management System — ' . APP_NAME;
$page_description = 'Manage properties, tenants, leases, rent collections, maintenance, and financials in one connected real estate platform.';


$product = [
  'name'       => 'Real Estate Management System',
  'icon'       => 'fa-building',
  'color'      => '#A855F7',
  'tagline'    => 'Manage every property, tenant, and lease — from a single intelligent platform.',
  'deployment' => ['Cloud','On-Premise','Hybrid'],
  'industries' => ['Real Estate','Property Management','Hospitality'],
  'price_from' => 'KES 55,000',
  'delivery'   => '8 – 14 weeks',
  'user_types' => 'Admin, Property Manager, Tenant, Maintenance',
  'mobile'     => 'Responsive + Tenant Mobile App',
  'languages'  => 'English, Swahili',
  'chart_data' => [55,62,70,78,85,93],
  'stats'      => [['∞','Properties'],['Auto','Rent Collection'],['100%','Lease Visibility'],['M-PESA','Integrated']],
  'modules'    => [
    ['Property Management','Manage residential, commercial, and mixed-use properties with unit tracking.',['Property Registry','Unit Management','Floor Plans','Amenities','Property Docs']],
    ['Tenant Management','Complete tenant profiles, documents, communication history, and tenancy status.',['Tenant Profiles','Document Storage','Communication Log','Tenancy Status','Emergency Contacts']],
    ['Lease Management','Digital lease creation, e-signatures, renewals, and termination workflows.',['Lease Builder','E-signatures','Renewal Alerts','Termination Process','Lease Archive']],
    ['Rent Collection','Automated M-PESA and bank collection with receipts and arrears management.',['M-PESA Integration','Bank Collection','Auto-Receipts','Arrears Alerts','Rent History']],
    ['Maintenance Management','Tenant-submitted maintenance requests tracked from open to resolution.',['Request Submission','Contractor Assignment','Status Tracking','Cost Recording','Maintenance History']],
    ['Financial Management','Rental income, expenses, utility billing, and property P&L reports.',['Income Tracking','Expense Management','Utility Billing','P&L Reports','Tax Reports']],
    ['Tenant Portal','Self-service portal for tenants to pay rent, submit requests, and view documents.',['Rent Payment','Maintenance Requests','Lease Documents','Payment History','Notices']],
    ['Vacancy Management','Track vacant units, manage listings, and process new tenant applications.',['Vacancy Dashboard','Listing Management','Viewing Scheduling','Application Processing','Move-in Checklist']],
  ],
  'related'    => [['crm-system','fa-handshake','CRM System'],['procurement-system','fa-shopping-cart','Procurement System'],['decision-support-system','fa-chart-line','Decision Support']],
];

$features_detailed = [
  ['fa-building','Property Registry','Multi-property, multi-unit management with floor plans and amenity tracking.'],
  ['fa-person','Tenant Management','360° tenant profiles with documents, communication history, and tenancy status.'],
  ['fa-file-signature','Digital Leases','Build, sign, renew, and archive leases digitally — no paper required.'],
  ['fa-mobile-alt','M-PESA Collection','Automated STK Push rent collection with instant receipts and arrears alerts.'],
  ['fa-wrench','Maintenance Tracking','Tenant-submitted jobs tracked from open through contractor to signed-off.'],
  ['fa-file-invoice-dollar','Financial Reports','Income, expenses, utility billing, and per-property P&L dashboards.'],
  ['fa-user-circle','Tenant Portal','Tenant self-service for rent payments, maintenance requests, and documents.'],
  ['fa-brain','AI Occupancy Insights','AI-powered vacancy predictions, market rent benchmarking, and yield optimisation.'],
];

$faqs = [
  ['Can it collect rent via M-PESA?','Yes. We implement Safaricom Daraja STK Push for automated monthly rent collection with instant receipts sent to tenants by SMS and email.'],
  ['Does it support multiple property owners?','Yes. The system can be configured for property management companies managing properties on behalf of multiple owners, with per-owner financial reporting.'],
  ['Can tenants submit maintenance requests from their phone?','Yes. The tenant portal is mobile-responsive and allows request submission with photos. Tenants receive status updates by SMS/email.'],
  ['Does it handle utility billing?','Yes. You can configure shared utility allocation (electricity, water) per unit, generate utility invoices, and include them in rent statements.'],
  ['Can we manage commercial and residential properties together?','Yes. The system handles mixed-use portfolios with different lease types, billing structures, and report segmentation per property type.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Leaflet Maps','Chart.js']],
  ['Backend',['PHP','Laravel','M-PESA Daraja','Queue Workers']],
  ['Database',['MySQL','Redis','Full-text search']],
  ['Integrations',['M-PESA Daraja','SMS Gateway','Email','Bank APIs']],
  ['Infrastructure',['cPanel / AWS','Docker','Nginx','SSL']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';