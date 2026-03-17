<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Digital Transformation Consulting — ' . APP_NAME;
$page_description = 'Our Digital Transformation consulting helps you build a practical, phased roadmap that your team can actually execute. Get a clear plan to modernise your operations, aligned to your budget, team, and goals.';
$consult = [
  'slug' => 'digital-transformation', 'icon' => 'fa-arrow-trend-up', 'color' => '#F5A800',
  'title' => 'Digital Transformation Consulting',
  'tagline' => 'A clear, phased roadmap to modernise your operations — aligned to your budget, team, and goals.',
  'description' => 'Digital transformation is not about buying software. It\'s about redesigning how your organisation operates — eliminating manual processes, creating data visibility, and enabling faster decision-making. Our Digital Transformation consulting helps you build a practical, phased roadmap that your team can actually execute.',
  'challenges' => ['Manual data entry consuming staff time','Disconnected systems causing information silos','No real-time visibility into operations','Slow approval and reporting cycles','Inability to scale without adding headcount','Losing competitive advantage to more agile competitors'],
  'outcomes' => ['Digital transformation roadmap (12–24 months)','Current state assessment and gap analysis','Technology stack recommendation','ROI projection per initiative','Change management guidance','Vendor selection criteria','Implementation partner shortlist'],
  'process' => [
    ['Discovery Workshop (Week 1)','Map current processes, identify pain points, and understand strategic goals across all departments.'],
    ['Current State Assessment (Week 2)','Evaluate existing systems, data quality, team digital literacy, and technical debt.'],
    ['Gap & Opportunity Analysis','Identify the highest-ROI transformation opportunities and sequence them by impact vs effort.'],
    ['Roadmap Development','Create a phased 12–24 month transformation roadmap with milestones, owners, and budget estimates.'],
    ['Presentation & Alignment','Present findings and roadmap to leadership for buy-in. Facilitate decision-making on priorities.'],
    ['Implementation Support (Optional)','Ongoing advisory during execution — keeping the transformation on track and adapting to change.'],
  ],
  'whoIsItFor' => ['CEOs & Managing Directors','Chief Operations Officers','IT Managers & Heads of Technology','Finance Directors managing efficiency budgets','HR Leaders automating people processes','Founders scaling startup operations'],
  'faqs' => [
    ['How long does a transformation project take?','A full transformation spans 12–36 months depending on organisational size. Our consulting engagement itself (assessment to roadmap) typically takes 3–6 weeks.'],
    ['Our industry is traditional — can this work for us?','Yes. We have deep experience in healthcare, manufacturing, real estate, education, and government. Transformation looks different in each sector and we account for that.'],
    ['Do you help with change management?','Yes. Technology is rarely the hardest part — people and process adoption is. We include change management guidance and training frameworks in all transformation roadmaps.'],
    ['What if we already started a transformation and it stalled?','We frequently engage as rescue consultants. We assess what went wrong, salvage what works, and design a revised plan to get momentum back.'],
  ],
  'related' => [['system-design','fa-drafting-compass','System Architecture'],['ai-implementation','fa-robot','AI Implementation'],['business-automation','fa-gears','Business Automation']],
];
include __DIR__ . '/consultation-template.php';
require_once __DIR__ . '/../../includes/footer.php';