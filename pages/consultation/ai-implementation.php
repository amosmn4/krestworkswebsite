<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'AI Implementation Consulting — ' . APP_NAME;
$page_description = 'Identify high-ROI AI use cases for your business and build a practical implementation roadmap. Our AI consulting focuses on deployable solutions, not demos.';
$consult = [
  'slug' => 'ai-implementation', 'icon' => 'fa-robot', 'color' => '#A855F7',
  'title' => 'AI Implementation Consulting',
  'tagline' => 'Identify high-ROI AI use cases for your business and build a practical implementation roadmap.',
  'description' => 'AI is not a feature you add — it\'s a capability you build. Our AI Implementation consulting helps you identify where AI genuinely adds value in your organisation, which tools and models to use, how to integrate them into existing workflows, and how to measure success. We focus on practical, deployable AI — not demos.',
  'challenges' => ['Unsure where to start with AI','Existing AI initiatives that failed to deliver value','Manual document processing consuming analyst time','No visibility into predictive business signals','Customer support overwhelmed with repetitive queries','Data sitting unused with no intelligence layer'],
  'outcomes' => ['AI readiness assessment','Prioritised use case list with ROI estimates','Data requirements analysis','Technology stack recommendation (models, tools, infra)','Implementation roadmap (phased)','Build vs buy vs partner analysis','Success metrics and monitoring framework'],
  'process' => [
    ['AI Readiness Assessment','Assess your data quality, team capabilities, existing tech stack, and governance readiness for AI.'],
    ['Use Case Discovery','Identify and score AI opportunities across your operations by feasibility, data availability, and potential ROI.'],
    ['Feasibility & Data Analysis','Deep dive into the top 3 use cases — data availability, model selection, and integration complexity.'],
    ['Technology Recommendation','Recommend the right AI tools, APIs, and infrastructure for each use case.'],
    ['Roadmap & Business Case','Produce a phased AI roadmap with budget estimates and projected ROI for leadership approval.'],
    ['Implementation Kickoff (Optional)','If you proceed with Krestworks, we begin development. If not, the roadmap is fully yours to execute elsewhere.'],
  ],
  'whoIsItFor' => ['CEOs wanting to understand AI ROI','CTOs evaluating AI tool selection','Operations Managers identifying automation targets','Data teams building the business case for ML','Product teams adding AI features','HR and Finance teams exploring intelligent automation'],
  'faqs' => [
    ['We don\'t have a data science team — can we still implement AI?','Yes. Modern AI (LLMs, foundation models) requires far less internal data science expertise than traditional ML. We design implementations your existing team can maintain.'],
    ['How do we know if our data is ready for AI?','Part of the engagement is a data maturity assessment. We\'ll tell you honestly where gaps exist and what\'s needed before proceeding.'],
    ['What AI tools does Krestworks recommend?','We\'re tool-agnostic. We recommend based on your specific needs — Anthropic Claude API, OpenAI, Hugging Face, custom Python models, or simple rule-based automation where AI isn\'t needed.'],
    ['How long before we see results from AI?','Quick wins (document summarisation, chatbots, classification) can be live in 4–8 weeks. Predictive models typically take 3–6 months from data preparation to production.'],
  ],
  'related' => [['digital-transformation','fa-arrow-trend-up','Digital Transformation'],['business-automation','fa-gears','Business Automation'],['system-design','fa-drafting-compass','System Architecture']],
];
include __DIR__ . '/consultation-template.php';
require_once __DIR__ . '/../../includes/footer.php';