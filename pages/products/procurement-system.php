<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Procurement Management System — ' . APP_NAME;
$page_description = 'End-to-end procurement automation — purchase requisitions, supplier management, invoice processing, approvals, and spend analytics.';


$product = [
  'name'       => 'Procurement Management System',
  'icon'       => 'fa-shopping-cart',
  'color'      => '#3B82F6',
  'tagline'    => 'Automate procurement from requisition to payment — with full audit trail.',
  'deployment' => ['Cloud','On-Premise','Hybrid'],
  'industries' => ['Manufacturing','Government','Healthcare','Corporate'],
  'price_from' => 'KES 60,000',
  'delivery'   => '8 – 14 weeks',
  'user_types' => 'Procurement Officer, Approver, Finance, Supplier',
  'mobile'     => 'Responsive + Supplier Portal',
  'languages'  => 'English, Swahili',
  'chart_data' => [45,58,62,74,80,92],
  'stats'      => [['100%','Audit Trail'],['40%','Cost Reduction'],['∞','Suppliers'],['Auto','Approvals']],
  'modules'    => [
    ['Purchase Requisition','Digital PR creation with budget validation, cost centre allocation, and approval routing.',['Budget Check','Cost Centre','Approval Chain','PR Tracking','Amendments']],
    ['Supplier Management','Supplier onboarding, evaluation, blacklisting, and performance scoring.',['Supplier Registry','Onboarding Portal','Performance Scores','Blacklist Management','Supplier Documents']],
    ['Request for Quotation','Issue RFQs to multiple suppliers and compare bids side by side.',['RFQ Generation','Supplier Invitations','Bid Comparison','Award Automation','RFQ History']],
    ['Purchase Orders','Generate, approve, and track purchase orders with delivery milestones.',['PO Generation','Multi-level Approval','Delivery Tracking','PO Amendments','PO vs Invoice Match']],
    ['Invoice Processing','Three-way matching (PO, GRN, Invoice) with automated discrepancy alerts.',['3-Way Matching','Invoice Upload','Discrepancy Alerts','Payment Authorisation','Invoice Archive']],
    ['Budget Management','Real-time budget tracking against committed and actual spend.',['Budget Entry','Commitment Tracking','Variance Alerts','Department Budgets','Budget Reports']],
    ['Contract Management','Store, version-control, and get alerted on contract milestones and renewals.',['Contract Storage','Milestone Alerts','Renewal Reminders','Contract Versions','Vendor Contracts']],
    ['Spend Analytics','Dashboards for spend by category, supplier, department, and period.',['Spend Dashboard','Category Analysis','Supplier Spend','Savings Tracking','Procurement KPIs']],
  ],
  'related'    => [['hr-system','fa-users','HR System'],['supply-chain-system','fa-truck','Supply Chain'],['decision-support-system','fa-chart-line','Decision Support']],
];

$features_detailed = [
  ['fa-file-invoice','Purchase Requisitions','Digital PRs with budget validation and configurable multi-level approval workflows.'],
  ['fa-handshake','Supplier Management','360° supplier profiles with onboarding portal, scorecards, and blacklist management.'],
  ['fa-envelope-open-text','RFQ Management','Issue RFQs, collect bids electronically, and compare with automated scoring.'],
  ['fa-file-contract','Purchase Orders','Auto-generate POs from approved PRs with delivery tracking and amendment history.'],
  ['fa-receipt','Invoice Processing','Three-way matching with automated discrepancy flagging and payment authorisation.'],
  ['fa-coins','Budget Tracking','Real-time budget vs committed vs actual spend with variance alerts.'],
  ['fa-file-pen','Contract Management','Contract storage with milestone tracking, renewal alerts, and version history.'],
  ['fa-brain','AI Spend Intelligence','AI-powered spend categorisation, duplicate detection, and savings recommendations.'],
];

$faqs = [
  ['Can it integrate with our accounting software?','Yes. We integrate with QuickBooks, Sage, and custom ERP systems via API or CSV export for journal entry posting.'],
  ['How does the approval workflow work?','Approval chains are fully configurable — you define levels, thresholds, and escalation rules. Approvers receive email and SMS notifications.'],
  ['Is there a supplier self-service portal?','Yes. Suppliers get a dedicated portal to submit invoices, track payment status, and respond to RFQs without needing a system login.'],
  ['Can we set budget limits per department?','Yes. Budgets are set per cost centre and period. The system prevents PR creation if it exceeds the available budget (configurable hard/soft blocks).'],
  ['How does three-way matching work?','The system automatically compares the PO quantity/price, GRN (goods received note), and supplier invoice. Any variance triggers a discrepancy workflow.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Chart.js','DataTables']],
  ['Backend',['PHP','Laravel','RESTful API','Queue Workers']],
  ['Database',['MySQL','Redis','Full-text search indexes']],
  ['Integrations',['QuickBooks API','Sage API','M-PESA','Email/SMS']],
  ['Infrastructure',['AWS / cPanel','Docker','Cron Jobs','Nginx']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';