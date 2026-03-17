<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Business Automation Consulting — ' . APP_NAME;
$page_description = 'Identify, prioritise, and automate the manual processes that are slowing your business down. Our Business Automation consulting finds the highest-ROI automations and builds the roadmap to get there.';
$consult = [
  'slug' => 'business-automation', 'icon' => 'fa-gears', 'color' => '#F97316',
  'title' => 'Business Automation Consulting',
  'tagline' => 'Identify, prioritise, and automate the manual processes that are slowing your business down.',
  'description' => 'Most businesses are running on processes designed for a smaller, slower era. Staff spend hours on data entry, manual approvals, report generation, and reconciliation — work that systems should do automatically. Our Business Automation consulting identifies exactly where to automate for maximum impact and builds the roadmap to get there.',
  'challenges' => ['Excessive manual data entry across departments','Slow multi-step approval processes (POs, leave, invoices)','Report generation taking days instead of minutes','Errors from re-keying data between systems','Staff time consumed by routine, repetitive tasks','No audit trail on manual processes'],
  'outcomes' => ['Process audit across all departments','Automation opportunity register with ROI estimates','Automation priority matrix (quick wins vs strategic)','Tool and platform recommendations','Integration map (what connects to what)','Implementation roadmap','Estimated FTE savings per automation'],
  'process' => [
    ['Process Mapping Workshop','Walk through each department\'s workflows — document every manual step, approval gate, and hand-off point.'],
    ['Automation Opportunity Scoring','Score each process by automation feasibility, data availability, and estimated time savings.'],
    ['Quick Wins Identification','Identify 3–5 automations that can be implemented in under 4 weeks for immediate ROI.'],
    ['Tool & Platform Selection','Recommend the right automation tools — custom code, workflow engines, RPA, or low-code platforms.'],
    ['Roadmap Development','Sequence all automation initiatives into a 12-month delivery plan with effort and impact estimates.'],
    ['Implementation Kickoff (Optional)','Begin building the quick wins immediately, with longer initiatives entering the development pipeline.'],
  ],
  'whoIsItFor' => ['Operations Managers drowning in manual processes','Finance teams spending days on month-end','HR teams processing leave and payroll manually','Procurement teams with paper-based approval chains','Customer service teams handling repetitive queries','Any manager whose team\'s time is trapped in admin'],
  'faqs' => [
    ['Where do most businesses find the biggest automation wins?','Leave management, payroll processing, invoice approval, report generation, and customer onboarding — these are the highest-ROI automations in most organisations.'],
    ['Do we need to replace our existing systems?','Not necessarily. Many automation wins come from connecting and orchestrating existing systems rather than replacing them.'],
    ['What is RPA and when should we use it?','Robotic Process Automation mimics human actions on a screen — useful when a proper API integration isn\'t possible. It\'s a last resort, not a first choice.'],
    ['How do you measure the ROI of automation?','We calculate time saved × loaded hourly cost per role, plus error reduction value, compliance benefits, and processing speed improvements.'],
  ],
  'related' => [['digital-transformation','fa-arrow-trend-up','Digital Transformation'],['ai-implementation','fa-robot','AI Implementation'],['system-design','fa-drafting-compass','System Architecture']],
];
include __DIR__ . '/consultation-template.php';
require_once __DIR__ . '/../../includes/footer.php';