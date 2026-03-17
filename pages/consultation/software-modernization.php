<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Software Modernisation Consulting — ' . APP_NAME;
$page_description = 'Migrate legacy systems to modern, maintainable platforms — without disrupting ongoing operations. Our Software Modernisation consulting designs the safest, most cost-effective path from your current state to a modern, scalable platform.';
$consult = [
  'slug' => 'software-modernization', 'icon' => 'fa-recycle', 'color' => '#22C55E',
  'title' => 'Software Modernisation Consulting',
  'tagline' => 'Migrate legacy systems to modern, maintainable platforms — without disrupting ongoing operations.',
  'description' => 'Legacy systems are not just a technical problem — they are a business risk. They are slow to change, expensive to maintain, a security liability, and a barrier to growth. Our Software Modernisation consulting designs the safest, most cost-effective path from your current state to a modern, scalable platform.',
  'challenges' => ['Systems built on outdated languages or frameworks','No vendor support or community for current stack','Cannot hire developers for legacy technology','Security vulnerabilities with no patches available','Cannot integrate with modern APIs and services','Costly to add new features to tightly coupled codebase'],
  'outcomes' => ['Legacy system audit report','Modernisation strategy (rewrite vs refactor vs replace)','Phased migration plan with risk assessment','Data migration strategy','Parallel running and cutover plan','Team capability assessment and upskilling plan','Total cost of modernisation estimate'],
  'process' => [
    ['Legacy System Audit','Document current architecture, identify all integrations, map business logic, and quantify technical debt.'],
    ['Risk & Dependency Analysis','Identify all dependencies, data flows, and processes that the new system must replicate or replace.'],
    ['Strategy Options Analysis','Evaluate Rewrite, Refactor, Re-platform, and Replace options with cost, risk, and timeline for each.'],
    ['Migration Architecture Design','Design the target architecture and phased migration sequence to minimise disruption.'],
    ['Data Migration Planning','Design extraction, transformation, and validation procedures for migrating historical data.'],
    ['Implementation Roadmap','Phased plan with milestones, checkpoints, and parallel running periods before full cutover.'],
  ],
  'whoIsItFor' => ['CIOs managing ageing infrastructure','IT Managers unable to maintain legacy systems','Finance Directors quantifying tech debt risk','Development teams inheriting old codebases','Organisations post-acquisition needing system consolidation','Businesses whose systems limit their ability to grow'],
  'faqs' => [
    ['Is a full rewrite always necessary?','No. Many systems benefit more from targeted refactoring or re-platforming (same language, new infrastructure). We\'ll recommend the most pragmatic approach based on your specific system.'],
    ['How do we migrate without downtime?','We design parallel running periods where both old and new systems operate simultaneously, with phased user migration and automated data sync until full cutover.'],
    ['What about our historical data?','Data migration is a core part of every modernisation plan. We design extraction, cleansing, transformation, and validation procedures specific to your data.'],
    ['How long does modernisation take?','Small-to-medium systems: 3–9 months. Large enterprise platforms: 12–24 months. The assessment phase itself takes 3–4 weeks.'],
  ],
  'related' => [['digital-transformation','fa-arrow-trend-up','Digital Transformation'],['system-design','fa-drafting-compass','System Architecture'],['general','fa-comments','General Consultation']],
];
include __DIR__ . '/consultation-template.php';
require_once __DIR__ . '/../../includes/footer.php';