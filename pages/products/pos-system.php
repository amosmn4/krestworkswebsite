<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Point of Sale System — ' . APP_NAME;
$page_description = 'Fast, reliable cloud POS for retail and hospitality — inventory sync, multi-branch, M-PESA integration, offline mode, and customer loyalty.';


$product = [
  'name'       => 'Point of Sale System',
  'icon'       => 'fa-cash-register',
  'color'      => '#F59E0B',
  'tagline'    => 'Sell fast, track everything, grow smarter — cloud POS built for African retail.',
  'deployment' => ['Cloud','On-Premise'],
  'industries' => ['Retail','Hospitality','Restaurants','Supermarkets'],
  'price_from' => 'KES 35,000',
  'delivery'   => '4 – 8 weeks',
  'user_types' => 'Cashier, Manager, Admin, Stock Controller',
  'mobile'     => 'Tablet POS + Mobile Reports',
  'languages'  => 'English, Swahili',
  'chart_data' => [55,65,72,78,84,92],
  'stats'      => [['M-PESA','Integrated'],['Offline','Capable'],['Multi-','Branch'],['Real-time','Stock Sync']],
  'modules'    => [
    ['Sales Processing','Fast checkout with barcode scanning, cash, card, M-PESA, and split payment.',['Barcode Scanning','Payment Methods','Split Payment','Discount Handling','Hold & Resume']],
    ['Inventory Management','Real-time stock sync across branches with reorder alerts and stock-takes.',['Stock Sync','Reorder Alerts','Stock Adjustments','Batch Tracking','Stock-take']],
    ['Multi-branch Management','Centralised control with per-branch performance and stock visibility.',['Branch Dashboard','Stock Transfers','Consolidated Reports','Per-branch PnL','Branch Staff Management']],
    ['Customer Loyalty','Points, tiers, and redemption integrated directly into the checkout flow.',['Points Earning','Tier Management','Points Redemption','Customer Profiles','Birthday Rewards']],
    ['Kitchen Display System','Order routing to kitchen displays for restaurants and cafés.',['Order Routing','KDS Display','Order Timing','Course Management','Kitchen Reports']],
    ['Supplier & Purchasing','Purchase orders, goods receipt, and supplier invoice management.',['Purchase Orders','GRN','Supplier Management','Invoice Matching','Purchase Reports']],
    ['End-of-Day Reporting','Z-reports, cash reconciliation, sales summaries, and cashier performance.',['Z-reports','Cash Reconciliation','Sales Summary','Cashier Reports','Audit Trails']],
    ['POS Analytics','Sales trends, top products, peak hours, and basket analysis dashboards.',['Sales Trends','Top Products','Peak Hours','Basket Analysis','Customer Analytics']],
  ],
  'related'    => [['supply-chain-system','fa-truck','Supply Chain'],['crm-system','fa-handshake','CRM System'],['decision-support-system','fa-chart-line','Decision Support']],
];

$features_detailed = [
  ['fa-barcode','Fast Checkout','Barcode scanning, touchscreen interface, and sub-3-second transaction processing.'],
  ['fa-mobile-alt','M-PESA Integration','STK Push, Till Number, and Paybill — all integrated directly at checkout.'],
  ['fa-wifi-slash','Offline Mode','Full POS functionality without internet — syncs automatically when connection restores.'],
  ['fa-boxes-stacked','Inventory Sync','Real-time stock deduction across all branches with low-stock alerts.'],
  ['fa-trophy','Customer Loyalty','Points earning, tier rewards, and redemption at checkout — fully automated.'],
  ['fa-utensils','Kitchen Display','Order routing to KDS screens for restaurants with timing and course management.'],
  ['fa-chart-bar','Branch Analytics','Per-branch sales, cashier performance, and consolidated multi-branch dashboards.'],
  ['fa-brain','AI Sales Insights','AI-powered basket analysis, upsell suggestions, and slow-moving stock alerts.'],
];

$faqs = [
  ['Does it work on tablet?','Yes. The POS interface is optimised for 10-inch tablets (Android and iPad) as well as desktop screens. You can mix hardware types across branches.'],
  ['What happens if the internet goes down?','The POS switches to offline mode automatically. All sales, payments, and stock movements are stored locally and sync to the cloud when connection restores.'],
  ['Does it integrate with M-PESA?','Yes — STK Push (customer pays from their phone), Till Number, and Paybill. Payments are confirmed in real-time at the cashier screen.'],
  ['Can I manage multiple shops from one account?','Yes. The admin panel gives you a consolidated view of all branches — stock, sales, and staff — from a single dashboard.'],
  ['Does it print receipts?','Yes. We support 80mm thermal receipt printers (Bluetooth and USB) as well as email and SMS receipts. Kitchen dockets are supported for restaurants.'],
];

$tech_stack = [
  ['Frontend',['React (POS UI)','Tailwind CSS','Offline Service Worker','Chart.js']],
  ['Backend',['PHP','Laravel','WebSockets','Queue Workers']],
  ['Database',['MySQL','IndexedDB (offline)','Redis']],
  ['Integrations',['M-PESA Daraja','Stripe','Thermal Printer SDK','Barcode API']],
  ['Infrastructure',['cPanel / AWS','Docker','CDN','SSL']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';