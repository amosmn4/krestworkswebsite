<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Supply Chain Management System — ' . APP_NAME;
$page_description = 'End-to-end supply chain visibility — inventory tracking, logistics, warehouse management, order processing, and demand forecasting.';


$product = [
  'name'       => 'Supply Chain Management System',
  'icon'       => 'fa-truck',
  'color'      => '#F97316',
  'tagline'    => 'Full supply chain visibility from supplier to customer — in real time.',
  'deployment' => ['Cloud','On-Premise','Hybrid'],
  'industries' => ['Manufacturing','Retail','Logistics','Agriculture'],
  'price_from' => 'KES 70,000',
  'delivery'   => '10 – 18 weeks',
  'user_types' => 'Admin, Warehouse, Logistics, Finance, Supplier',
  'mobile'     => 'Responsive + Warehouse Mobile App',
  'languages'  => 'English, Swahili',
  'chart_data' => [40,52,60,72,80,88],
  'stats'      => [['Real-time','Tracking'],['Auto','Reorder'],['Multi-','Warehouse'],['AI','Forecasting']],
  'modules'    => [
    ['Inventory Management','Real-time stock tracking with bin locations, batch numbers, and expiry management.',['Stock Levels','Bin Locations','Batch Tracking','Expiry Alerts','Stock Adjustments']],
    ['Warehouse Management','Goods receipt, put-away, picking, packing, and dispatch workflows.',['GRN Processing','Put-away','Pick Lists','Pack & Dispatch','Cycle Counting']],
    ['Order Management','Sales orders, purchase orders, fulfilment tracking, and backorder management.',['Order Entry','Order Fulfilment','Backorder Management','Order Status','Returns Management']],
    ['Supplier Integration','Supplier portals, PO transmission, ASN receipt, and supplier performance.',['Supplier Portal','PO Transmission','ASN Receipt','Performance Scoring','Supplier Payments']],
    ['Logistics & Fleet','Route planning, delivery tracking, driver management, and proof of delivery.',['Route Planning','GPS Tracking','Driver Management','POD Collection','Delivery Reports']],
    ['Demand Forecasting','AI-powered demand forecasting with seasonality and trend analysis.',['Forecast Engine','Seasonality','Reorder Points','Safety Stock','Forecast Accuracy']],
    ['Multi-location Support','Manage stock across multiple warehouses, branches, and distribution centres.',['Multi-warehouse','Stock Transfers','Consolidated View','Per-location Reports','Inter-branch Transfers']],
    ['Supply Chain Analytics','End-to-end dashboards for inventory health, supplier performance, and logistics KPIs.',['Inventory KPIs','Supplier Analytics','Logistics Metrics','Cost Analysis','Custom Reports']],
  ],
  'related'    => [['procurement-system','fa-shopping-cart','Procurement System'],['pos-system','fa-cash-register','POS System'],['decision-support-system','fa-chart-line','Decision Support']],
];

$features_detailed = [
  ['fa-boxes-stacked','Inventory Tracking','Real-time stock levels with bin locations, batch/serial numbers, and expiry dates.'],
  ['fa-warehouse','Warehouse Management','GRN, put-away, pick, pack, and dispatch workflows with mobile scanning support.'],
  ['fa-list-check','Order Management','Sales and purchase order fulfilment with backorder management and status tracking.'],
  ['fa-truck-ramp-box','Logistics Tracking','Real-time delivery tracking, route planning, and digital proof of delivery.'],
  ['fa-rotate','Supplier Integration','Supplier portal for PO transmission, ASN receipt, and performance scoring.'],
  ['fa-chart-line','Demand Forecasting','AI-powered demand forecasting with auto-reorder and safety stock recommendations.'],
  ['fa-map-location-dot','Multi-location','Unified view across all warehouses, branches, and distribution centres.'],
  ['fa-brain','AI Supply Intelligence','Anomaly detection, supplier risk scoring, and supply disruption early warnings.'],
];

$faqs = [
  ['Does it support barcode and QR scanning?','Yes. The warehouse module supports USB barcode scanners, mobile camera scanning, and dedicated handheld scanners for GRN, picking, and dispatch.'],
  ['Can it manage perishable goods with expiry dates?','Yes. Batch tracking includes expiry date management with FEFO (First Expired, First Out) picking rules and automated expiry alerts.'],
  ['How does demand forecasting work?','We use historical sales data, seasonality patterns, and growth trends to generate SKU-level demand forecasts. The system auto-suggests reorder points and safety stock levels.'],
  ['Can it track deliveries in real time?','Yes. We integrate with GPS tracking systems and provide a delivery dashboard with live status updates. Drivers can update status from a mobile app.'],
  ['Does it integrate with our accounting system?','Yes. We integrate with QuickBooks, Sage, and custom ERP financial modules for automatic journal entry posting on goods receipt and dispatch.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Leaflet (maps)','Chart.js']],
  ['Backend',['PHP','Laravel','WebSockets','Queue Workers']],
  ['Database',['MySQL','Redis','Elasticsearch']],
  ['Integrations',['GPS APIs','Barcode SDKs','QuickBooks','M-PESA']],
  ['Infrastructure',['AWS / cPanel','Docker','Nginx','Auto-scaling']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';