<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Business Strategy AI — AI Hub — ' . APP_NAME;
$page_description = 'AI-powered strategic analysis with SWOT, competitive positioning, growth opportunities, and a 90-day action plan.';


$tool = [
  'slug'         => 'business-strategy-ai',
  'icon'         => 'fa-chess',
  'color'        => '#F5A800',
  'name'         => 'Business Strategy AI',
  'tagline'      => 'Input your business context and goals — get a structured strategic analysis with SWOT, positioning, and a 90-day action plan.',
  'premium'      => true,
  'capabilities' => ['Full SWOT analysis','Competitive positioning','Market opportunity mapping','Revenue model review','Risk assessment','90-day action plan','Strategic recommendations'],
  'tips'         => [
    'Include your industry, target market, and current revenue stage',
    'Describe your top 2-3 competitors for better positioning analysis',
    'Be honest about weaknesses — the AI works with what you give it',
    'Include specific goals you\'re trying to achieve in the next 12 months',
  ],
];

$tool_config = [
  'input_label'  => 'Describe your business, goals, and challenges',
  'placeholder'  => "Business: [What your company does]\nIndustry: [Your industry]\nStage: [Early-stage / Growing / Established]\nRevenue: [Approximate current revenue]\nTarget Market: [Who you serve]\n\nCurrent Challenges:\n- [Challenge 1]\n- [Challenge 2]\n\nGoals (next 12 months):\n- [Goal 1]\n- [Goal 2]\n\nTop Competitors: [List 2-3]\n\nCurrent Differentiators: [What makes you different]",
  'input_rows'   => 14,
  'cta_text'     => 'Generate Strategy',
  'options'      => [
    ['label' => 'Full Strategy Analysis', 'prompt' => ''],
    ['label' => 'SWOT Only',              'prompt' => ''],
    ['label' => '90-Day Plan Only',       'prompt' => ''],
    ['label' => 'Competitive Analysis',   'prompt' => ''],
  ],
];

$system_prompt = "You are a senior strategy consultant with experience at McKinsey-level engagements and deep expertise in African and emerging market businesses. Produce a rigorous, actionable strategic analysis.

Format your output as:

## Executive Summary
(3-4 sentence strategic overview)

## SWOT Analysis

**Strengths ✅**
(Internal advantages)

**Weaknesses ⚠️**
(Internal challenges to address)

**Opportunities 🚀**
(External opportunities to capture)

**Threats 🔴**
(External risks to mitigate)

## Competitive Positioning
(Where this business sits vs. competitors and recommended positioning)

## Top 3 Strategic Priorities
(The 3 most impactful strategic moves to make)

## 90-Day Action Plan
| Priority | Action | Owner | Timeline | Success Metric |
|----------|--------|-------|----------|----------------|
(5-8 concrete actions)

## Revenue Growth Opportunities
(Specific, realistic revenue opportunities based on the business context)

## Key Risks & Mitigations
(Top 3 risks and how to address them)

## Recommended Next Steps
(Most critical actions to take first)

Be specific, realistic, and business-focused. Use data and logic. Avoid generic strategic platitudes.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';