<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Executive Decision Support System — ' . APP_NAME;
$page_description = 'AI-powered business intelligence and executive analytics — KPI dashboards, scenario modelling, multi-source data integration, and strategic insights.';


$product = [
  'name'       => 'Executive Decision Support System',
  'icon'       => 'fa-chart-line',
  'color'      => '#EF4444',
  'tagline'    => 'AI-powered intelligence that turns your business data into strategic decisions.',
  'deployment' => ['Cloud','On-Premise','Hybrid'],
  'industries' => ['Corporate','Finance','Government','Healthcare'],
  'price_from' => 'KES 80,000',
  'delivery'   => '8 – 16 weeks',
  'user_types' => 'Executive, Analyst, Department Head, Viewer',
  'mobile'     => 'Executive Mobile Dashboard',
  'languages'  => 'English',
  'chart_data' => [70,75,80,82,88,95],
  'stats'      => [['360°','Data View'],['AI','Insights'],['Real-time','KPIs'],['Multi-','Source']],
  'modules'    => [
    ['KPI Dashboard Builder','Drag-and-drop dashboard builder with 20+ chart types and real-time data.',['Chart Builder','Drill-down','Date Filters','Saved Views','Dashboard Sharing']],
    ['Multi-source Data Integration','Connect ERP, CRM, HR, accounting, and external APIs into one view.',['API Connectors','CSV Import','Database Direct','Scheduled Sync','Data Mapping']],
    ['AI Insights Engine','AI-generated narratives explaining what changed, why, and what to do.',['Trend Narratives','Anomaly Detection','Root Cause AI','Predictive Alerts','Executive Summaries']],
    ['Scenario Modelling','What-if analysis with adjustable variables and side-by-side scenario comparison.',['Variable Sliders','Scenario Comparison','Outcome Simulation','Sensitivity Analysis','Save Scenarios']],
    ['Report Builder','Self-service report builder with scheduling and PDF/Excel export.',['Drag-drop Builder','Scheduled Reports','PDF Export','Excel Export','Report Sharing']],
    ['Alerts & Notifications','Set KPI thresholds and receive alerts by email, SMS, or in-app notification.',['Threshold Alerts','SMS Alerts','Email Digest','Escalation Rules','Alert History']],
    ['Board & Investor Reports','Executive-ready report templates for board packs and investor updates.',['Report Templates','Board Pack','Investor Summary','Benchmarking','YoY Comparisons']],
    ['Access Control','Role-based data access — executives see everything, managers see their domain.',['Row-level Security','Dashboard Roles','Data Masking','Audit Logs','SSO Support']],
  ],
  'related'    => [['hr-system','fa-users','HR System'],['procurement-system','fa-shopping-cart','Procurement System'],['crm-system','fa-handshake','CRM System']],
];

$features_detailed = [
  ['fa-gauge','KPI Dashboards','Custom drag-and-drop dashboards with 20+ chart types and real-time refresh.'],
  ['fa-database','Multi-source Integration','Connect ERP, CRM, HR, accounting, and 3rd-party data into a unified view.'],
  ['fa-brain','AI Insights','AI-generated narratives: what changed, why it changed, and recommended actions.'],
  ['fa-sliders','Scenario Modelling','Interactive what-if analysis with adjustable variables and side-by-side comparison.'],
  ['fa-file-export','Report Builder','Self-service report builder with automated scheduling and PDF/Excel export.'],
  ['fa-bell','Smart Alerts','KPI threshold alerts delivered by email, SMS, or WhatsApp the moment they trigger.'],
  ['fa-presentation-screen','Board Reports','Executive-ready board pack templates with benchmarking and YoY comparisons.'],
  ['fa-user-shield','Row-level Security','Data access controlled by role — executives see all, managers see their domain.'],
];

$faqs = [
  ['What data sources can it connect to?','Any system with an API, database, or CSV export. We have pre-built connectors for QuickBooks, Sage, Salesforce, Shopify, and all Krestworks systems.'],
  ['How does the AI insights feature work?','The AI analyses your KPI trends, compares them to historical baselines, detects anomalies, and generates plain-English narratives explaining what happened and suggesting actions.'],
  ['Can non-technical executives use it?','Yes. The executive-facing dashboards require zero technical knowledge. The drag-and-drop builder is for analysts; executives just consume and interact with the dashboards.'],
  ['Is real-time data supported?','Yes. Dashboards can refresh on configurable intervals (1 minute to daily). For true real-time, we implement WebSocket-based live updates.'],
  ['Can it replace our existing BI tool?','It depends on your current tool. We offer a free assessment to compare capabilities. Many clients migrate from Tableau or Power BI to reduce licensing costs.'],
];

$tech_stack = [
  ['Frontend',['React','D3.js','Chart.js','Recharts','Tailwind']],
  ['Backend',['PHP','Laravel','Python (AI)','WebSockets']],
  ['Database',['MySQL','ClickHouse (analytics)','Redis']],
  ['Integrations',['QuickBooks','Salesforce','REST APIs','Webhooks']],
  ['Infrastructure',['AWS / cPanel','Docker','Auto-scaling','CDN']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';