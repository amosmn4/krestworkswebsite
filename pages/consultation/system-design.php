<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'System Architecture Consultation — ' . APP_NAME;
$page_description = 'Design scalable, maintainable, and secure systems before a single line of code is written. Our System Architecture consulting helps you build the right technical foundation to save 10x in future rework costs.';
$consult = [
  'slug' => 'system-design', 'icon' => 'fa-drafting-compass', 'color' => '#3B82F6',
  'title' => 'System Architecture Consultation',
  'tagline' => 'Design scalable, maintainable, and secure systems before a single line of code is written.',
  'description' => 'Poor architecture is the #1 cause of expensive rebuilds. Our System Architecture consulting helps you design the right technical foundation — database schema, API contracts, service boundaries, scaling strategy, and security architecture — before development begins. This saves 10x in future rework costs.',
  'challenges' => ['Rebuilding a system that outgrew its architecture','Slow performance under increasing load','High technical debt slowing new feature delivery','Security vulnerabilities in current design','Difficulty integrating new systems with legacy platforms','No clear separation of concerns in codebase'],
  'outcomes' => ['System architecture document','Database schema and data model','API design specification','Technology stack recommendation','Scalability and performance plan','Security architecture review','Infrastructure design','Development team onboarding guide'],
  'process' => [
    ['Requirements Deep-Dive','Extract functional and non-functional requirements — performance targets, user load, data volumes, integrations needed.'],
    ['Architecture Options Analysis','Compare and evaluate 2–3 architectural approaches with trade-off analysis for each.'],
    ['Recommended Architecture Design','Produce detailed architecture diagram, data flow, and component interaction documentation.'],
    ['Database & API Design','Design normalised database schema, API contracts, and authentication model.'],
    ['Security Architecture Review','Identify security requirements and design appropriate controls at each layer.'],
    ['Developer Handover','Present to your development team, answer questions, and refine based on feedback before development starts.'],
  ],
  'whoIsItFor' => ['CTOs and Technical Leads','Development team leads starting a new project','Product Managers scoping complex systems','Startups building their first platform','Organisations planning a system rebuild','Teams inheriting poorly structured legacy systems'],
  'faqs' => [
    ['When in the project should we do this?','Before development starts — ideally before you write your first line of code. We can also engage mid-project to course-correct an evolving architecture.'],
    ['What do we receive at the end?','A complete architecture document including diagrams, data models, API specs, tech stack decisions with rationale, and a development guide your team can follow.'],
    ['Do you review existing architectures?','Yes — we conduct architecture audits. We review your current system, identify structural issues and technical debt, and produce a remediation plan.'],
    ['How long does the engagement take?','For a new system: 1–3 weeks. For a full audit of an existing system: 2–4 weeks depending on complexity.'],
  ],
  'related' => [['digital-transformation','fa-arrow-trend-up','Digital Transformation'],['ai-implementation','fa-robot','AI Implementation'],['software-modernization','fa-recycle','Software Modernisation']],
];
include __DIR__ . '/consultation-template.php';
require_once __DIR__ . '/../../includes/footer.php';