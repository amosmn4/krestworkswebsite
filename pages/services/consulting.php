<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Technology Consulting — ' . APP_NAME;
$page_description = 'System architecture, digital transformation strategy, AI roadmapping, software modernization, and CTO-as-a-Service from experienced engineers.';


$service = [
  'slug'      => 'consulting',
  'icon'      => 'fa-lightbulb',
  'title'     => 'Technology Consulting',
  'color'     => '#EF4444',
  'tagline'   => 'Strategic technology guidance from architects who have built and scaled enterprise systems.',
  'desc'      => 'Great software starts with great decisions. Our consulting practice helps organizations navigate technology choices, architecture trade-offs, and digital transformation journeys — with experienced engineers, not slide-deck consultants. We\'ve built the systems we advise on.',
  'whatWeBuild' => [
    ['fa-drafting-compass','System Architecture','Design scalable, maintainable architecture before writing code — saving 10x in future rework.'],
    ['fa-arrows-rotate','Digital Transformation','Roadmap and execute the shift from manual processes to digital, data-driven operations.'],
    ['fa-brain','AI Strategy','Identify high-ROI AI use cases, select the right models, and build your implementation roadmap.'],
    ['fa-wrench','Software Modernization','Assess, plan, and execute the migration of legacy systems to modern, maintainable platforms.'],
    ['fa-magnifying-glass','Technology Audits','Independent review of your current systems, architecture, security posture, and tech debt.'],
    ['fa-user-tie','CTO-as-a-Service','Fractional CTO for startups and growing businesses that need senior technical leadership.'],
  ],
  'tech'      => ['Architecture Patterns','Agile','TOGAF','ITIL','DevOps','Cloud Strategy','AI/ML Roadmapping','Security Frameworks','Cost Optimisation'],
  'faqs'      => [
    ['What\'s the typical engagement model?','Most consulting engagements start with a Discovery Sprint (1–2 weeks) producing a written assessment and recommendations. We then offer implementation support, ongoing advisory, or handoff to your team.'],
    ['Do your consultants have real engineering experience?','Yes. All our consultants have hands-on experience building enterprise systems. We don\'t hire career consultants — we hire engineers who can also consult.'],
    ['Can you work with our existing development team?','Absolutely. We frequently embed alongside in-house teams to provide architectural oversight, code review, and decision support without taking over development.'],
    ['What does a technology audit cover?','Code quality and maintainability, database design, API security, infrastructure resilience, documentation, CI/CD maturity, and alignment with business requirements.'],
    ['What is CTO-as-a-Service?','A fractional CTO engagement where we provide senior technical leadership — hiring guidance, architecture decisions, vendor selection, and technology strategy — on a part-time basis.'],
  ],
  'process'   => ['Stakeholder Interviews','Current State Assessment','Gap & Risk Analysis','Strategy Documentation','Recommendations Presentation','Implementation Planning','Ongoing Advisory'],
  'timeline'  => '1 week – Ongoing',
  'pricing'   => 'From KES 50,000',
  'related'   => [['custom-software','fa-laptop-code','Custom Software'],['ai-solutions','fa-robot','AI Solutions'],['cybersecurity','fa-shield-halved','Cybersecurity']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';