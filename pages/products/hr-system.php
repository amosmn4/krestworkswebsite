<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'HR Management System — ' . APP_NAME;
$page_description = 'Complete HR lifecycle management — payroll, leave, recruitment, performance tracking, time & attendance, and HR analytics.';


$product = [
  'name'       => 'HR Management System',
  'icon'       => 'fa-users',
  'color'      => '#F5A800',
  'tagline'    => 'End-to-end human resource lifecycle management — from hire to retire.',
  'deployment' => ['Cloud','On-Premise','Hybrid'],
  'industries' => ['Healthcare','Corporate','Government','Education'],
  'price_from' => 'KES 50,000',
  'delivery'   => '8 – 12 weeks',
  'user_types' => 'HR Admin, Manager, Employee',
  'mobile'     => 'Responsive + Mobile App',
  'languages'  => 'English, Swahili',
  'chart_data' => [60,72,68,85,91,95],
  'stats'      => [['360°','HR Coverage'],['∞','Employees'],['99%','Payroll Accuracy'],['Real-time','Analytics']],
  'modules'    => [
    ['Employee Records','Centralised employee profiles with documents, history, and org chart integration.',['Personal Details','Employment History','Documents','Emergency Contacts','Org Chart']],
    ['Payroll Processing','Automated payroll with statutory deductions, PAYE, NHIF, NSSF, and net pay calculations.',['PAYE Calculator','NHIF/NSSF','Payslip Generation','Bank Transfer Export','Payroll History']],
    ['Leave Management','Leave requests, approvals, balances, and accruals — with calendar integration.',['Leave Types','Approval Workflows','Leave Balance','Holiday Calendar','Leave Reports']],
    ['Performance Management','Goal setting, appraisal cycles, 360° feedback, and performance analytics.',['Goal Tracking','Appraisal Cycles','360° Feedback','Performance Reports','PIP Management']],
    ['Recruitment','End-to-end hiring from job posting to offer letter and onboarding.',['Job Postings','Application Tracking','Interview Scheduling','Offer Letters','Onboarding Checklist']],
    ['Time & Attendance','Biometric integration, shift management, overtime tracking, and timesheets.',['Biometric Integration','Shift Scheduling','Overtime Alerts','Timesheet Approval','Attendance Reports']],
    ['Training Management','Training schedules, attendance, assessments, and certification tracking.',['Training Calendar','Attendance Tracking','Post-Training Tests','Certification Records','Training Budget']],
    ['HR Analytics','Interactive dashboards for headcount, turnover, payroll cost, and HR KPIs.',['Headcount Reports','Turnover Analysis','Payroll Cost Trends','Leave Analytics','Custom Reports']],
  ],
  'related'    => [['procurement-system','fa-shopping-cart','Procurement System'],['decision-support-system','fa-chart-line','Decision Support'],['elearning-system','fa-graduation-cap','eLearning LMS']],
];

$features_detailed = [
  ['fa-id-card','Employee Records','360° employee profiles with org chart, document vault, and full employment history.'],
  ['fa-money-bill-wave','Payroll Engine','Automated payroll with PAYE, NHIF, NSSF, custom allowances, and bank export.'],
  ['fa-umbrella-beach','Leave Management','Leave requests, multi-level approvals, accruals, and calendar visibility.'],
  ['fa-chart-bar','Performance Tracking','KPI-based appraisals, 360° feedback, and improvement plan management.'],
  ['fa-user-plus','Recruitment Module','ATS with job board integration, interview scheduling, and offer automation.'],
  ['fa-fingerprint','Time & Attendance','Biometric integration, shift management, and overtime tracking.'],
  ['fa-graduation-cap','Training Module','Training plans, assessments, certifications, and budget tracking.'],
  ['fa-brain','AI Insights','AI-powered attrition prediction, payroll anomaly detection, and HR recommendations.'],
];

$faqs = [
  ['Does it integrate with biometric devices?','Yes. We support fingerprint and face recognition devices from major brands (ZKTeco, Anviz, FingerTec) via SDK or TCP/IP integration.'],
  ['Can it handle multiple companies or branches?','Yes. The system supports multi-company and multi-branch setups with separate payroll, leave policies, and reporting per entity.'],
  ['Is payroll compliant with Kenya Revenue Authority?','Yes. We maintain up-to-date PAYE bands, NHIF rates, NSSF contributions, and generate KRA-compatible P9 forms.'],
  ['Can employees access it on mobile?','Yes. There is a mobile-responsive self-service portal where employees can view payslips, apply for leave, and check their records.'],
  ['How is payroll data secured?','Payroll data is encrypted at rest. Access is role-restricted — only authorised HR and finance staff can view salary information.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Chart.js','HTML5']],
  ['Backend',['PHP','Laravel','RESTful API','JWT Auth']],
  ['Database',['MySQL','Redis (caching)','Encrypted columns']],
  ['Integrations',['M-PESA','Bank APIs','ZKTeco SDK','KRA eTIMS']],
  ['Infrastructure',['AWS / cPanel','Docker','Nginx','GitHub Actions']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';