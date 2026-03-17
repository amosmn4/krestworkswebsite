<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'CRM System — ' . APP_NAME;
$page_description = 'AI-powered customer relationship management — lead tracking, sales pipeline, contact management, email integration, and deal forecasting.';


$product = [
  'name'       => 'CRM System',
  'icon'       => 'fa-handshake',
  'color'      => '#06B6D4',
  'tagline'    => 'From first contact to closed deal — manage every customer relationship intelligently.',
  'deployment' => ['Cloud','SaaS','On-Premise'],
  'industries' => ['Corporate','Real Estate','Financial Services','Retail'],
  'price_from' => 'KES 40,000',
  'delivery'   => '6 – 10 weeks',
  'user_types' => 'Sales Rep, Manager, Admin, Support',
  'mobile'     => 'Sales Mobile App',
  'languages'  => 'English, Swahili',
  'chart_data' => [35,50,58,68,75,88],
  'stats'      => [['AI','Lead Scoring'],['360°','Customer View'],['Auto','Follow-ups'],['Pipeline','Forecasting']],
  'modules'    => [
    ['Lead Management','Capture leads from web forms, email, and manual entry. Score and route automatically.',['Lead Capture','AI Scoring','Lead Routing','Lead Source Tracking','Duplicate Detection']],
    ['Sales Pipeline','Visual kanban pipeline with stage-based automation and conversion tracking.',['Kanban Board','Stage Automation','Win/Loss Tracking','Pipeline Value','Conversion Rates']],
    ['Contact & Account Management','360° profiles for contacts and companies with full interaction history.',['Contact Profiles','Company Accounts','Interaction Timeline','Tags & Segments','Document Attachments']],
    ['Email Integration','Two-way email sync with templates, sequences, and open/click tracking.',['Email Sync','Templates','Sequences','Open Tracking','Click Tracking']],
    ['Activity Management','Log calls, meetings, tasks, and follow-up reminders linked to deals.',['Call Logging','Meeting Notes','Task Management','Follow-up Reminders','Activity Reports']],
    ['Deal Forecasting','AI-powered revenue forecasting with probability scoring and pipeline health.',['Forecast Dashboard','Probability Scoring','Revenue Projections','Pipeline Health','Quota Tracking']],
    ['Customer Segmentation','Segment customers by behaviour, value, industry, and lifecycle stage.',['Segment Builder','Behavioural Tags','Customer Value Scoring','Lifecycle Stages','Bulk Actions']],
    ['CRM Analytics','Dashboards for sales performance, team activity, pipeline velocity, and win rates.',['Sales Dashboard','Team Leaderboard','Pipeline Velocity','Win Rate Analysis','Custom Reports']],
  ],
  'related'    => [['real-estate-system','fa-building','Real Estate System'],['decision-support-system','fa-chart-line','Decision Support'],['hr-system','fa-users','HR System']],
];

$features_detailed = [
  ['fa-filter','Lead Management','AI-scored lead capture from web forms, email, and social with automatic routing.'],
  ['fa-chart-gantt','Sales Pipeline','Visual kanban board with stage automation, win/loss tracking, and conversion metrics.'],
  ['fa-address-card','Contact Management','360° contact and company profiles with full interaction history and document storage.'],
  ['fa-envelope','Email Integration','Two-way Gmail/Outlook sync with templates, automated sequences, and open tracking.'],
  ['fa-calendar-check','Activity Tracking','Log calls, meetings, and tasks with automated follow-up reminders.'],
  ['fa-chart-line','Deal Forecasting','AI-powered revenue forecasting with probability scoring and quota tracking.'],
  ['fa-users-gear','Customer Segmentation','Behavioural segmentation with lifecycle stages and bulk action campaigns.'],
  ['fa-brain','AI Sales Coach','AI-powered next-best-action recommendations, deal risk alerts, and coaching tips.'],
];

$faqs = [
  ['Does it integrate with Gmail and Outlook?','Yes. Full two-way email sync with both Gmail and Outlook. Emails sent and received are automatically logged against the relevant contact or deal.'],
  ['Can it capture leads from our website?','Yes. We provide an embeddable web form and a JavaScript snippet that captures visitor details and form submissions directly into the CRM pipeline.'],
  ['How does AI lead scoring work?','The AI analyses lead behaviour (email opens, page visits, form interactions), firmographic data, and historical conversion patterns to assign a probability score to each lead.'],
  ['Can it send automated follow-up emails?','Yes. You can set up email sequences — a series of timed, personalised emails that go out automatically when a lead reaches a specific pipeline stage.'],
  ['Does it work for field sales teams?','Yes. The mobile app allows field reps to log calls, update deals, check-in to client locations, and access the full pipeline without a laptop.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Chart.js','DnD Kit (kanban)']],
  ['Backend',['PHP','Laravel','Queue Workers','WebSockets']],
  ['Database',['MySQL','Redis','Full-text search']],
  ['Integrations',['Gmail API','Outlook API','M-PESA','Twilio SMS']],
  ['Infrastructure',['AWS / cPanel','Docker','Nginx','SSL']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';