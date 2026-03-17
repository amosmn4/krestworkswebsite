<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Resume Analyzer — AI Hub — ' . APP_NAME;
$page_description = 'Get a professional AI analysis of any resume — ATS score, strengths, gaps, and specific improvement recommendations.';


$tool = [
  'slug'         => 'resume-analyzer',
  'icon'         => 'fa-id-card',
  'color'        => '#3B82F6',
  'name'         => 'Resume Analyzer',
  'tagline'      => 'Get an honest, professional analysis of any resume — with ATS compatibility score and actionable improvement tips.',
  'premium'      => false,
  'capabilities' => ['ATS compatibility scoring','Skills gap analysis','Formatting & structure review','Achievement impact scoring','Improvement recommendations','Role-fit analysis'],
  'tips'         => [
    'Paste the full resume text — include all sections',
    'Optionally mention the target job title for role-specific feedback',
    'Works for any career level from entry-level to C-suite',
    'For best ATS analysis, paste the plain text version of the resume',
  ],
];

$tool_config = [
  'input_label'  => 'Paste resume text (optionally mention target job role at the top)',
  'placeholder'  => "Target Role: [e.g. Senior Software Engineer]\n\n[Paste full resume text here]\n\nName:\nSummary:\nExperience:\n...",
  'input_rows'   => 12,
  'cta_text'     => 'Analyze Resume',
  'options'      => [
    ['label' => 'Full Analysis',       'prompt' => ''],
    ['label' => 'ATS Score Only',      'prompt' => ''],
    ['label' => 'Skills Gap Analysis', 'prompt' => ''],
    ['label' => 'Rewrite Summary',     'prompt' => ''],
  ],
];

$system_prompt = "You are a senior HR consultant and resume expert with 15+ years of experience hiring for technical and executive roles. Analyze the provided resume and give professional, actionable feedback.

Format your output with:
## Overall Assessment
(Brief 2-3 sentence overall impression)

## ATS Compatibility Score: X/10
(Explain the score and what affects ATS parsing)

## Strengths ✅
(What works well — be specific)

## Areas for Improvement ⚠️
(Specific, actionable improvements)

## Skills Gap Analysis
(Missing skills based on apparent target role or stated career level)

## Impact Improvements
(Specific achievement statements that could be stronger — suggest rewrites)

## Recommended Actions
(Prioritised list of changes to make)

Be honest but constructive. Give specific, actionable advice — not generic tips.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';