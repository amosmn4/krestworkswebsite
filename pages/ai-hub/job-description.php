<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Job Description Generator — AI Hub — ' . APP_NAME;
$page_description = 'Generate complete, ATS-optimised job descriptions from a role title and key requirements.';


$tool = [
  'slug'         => 'job-description',
  'icon'         => 'fa-briefcase',
  'color'        => '#EF4444',
  'name'         => 'Job Description Generator',
  'tagline'      => 'Enter a role title and key requirements — get a complete, ATS-optimised job description ready to post.',
  'premium'      => false,
  'capabilities' => ['Full job description generation','ATS-optimised formatting','Skills and requirements structuring','Company culture section','Multiple seniority levels','Benefits section'],
  'tips'         => [
    'Include the industry and company size for tailored output',
    'List key responsibilities you know the role will involve',
    'Specify seniority level: junior, mid, senior, lead, C-level',
    'Mention must-have vs. nice-to-have skills for better accuracy',
  ],
];

$tool_config = [
  'input_label'  => 'Describe the role you\'re hiring for',
  'placeholder'  => "Role: Senior PHP Developer\nIndustry: Healthcare Tech\nCompany: Mid-size startup, 50 employees\nSeniority: Senior (5+ years)\nLocation: Nairobi / Remote\n\nKey responsibilities:\n- Build and maintain our hospital management system\n- Lead a team of 3 junior developers\n\nMust-have skills: PHP, Laravel, MySQL, REST APIs\nNice-to-have: React, Docker, NHIF integration experience",
  'input_rows'   => 10,
  'cta_text'     => 'Generate JD',
  'options'      => [
    ['label' => 'Full JD',        'prompt' => ''],
    ['label' => 'Tech Role',      'prompt' => ''],
    ['label' => 'Sales Role',     'prompt' => ''],
    ['label' => 'Executive Role', 'prompt' => ''],
  ],
];

$system_prompt = "You are a senior HR professional and talent acquisition specialist. Create compelling, accurate, ATS-optimised job descriptions that attract the right candidates.

Format the job description as:

## [Job Title]
**Department:** [If applicable]
**Location:** [Location / Remote]
**Type:** [Full-time / Contract]
**Seniority:** [Level]

## About the Role
(2-3 compelling paragraphs about the opportunity and its impact)

## Key Responsibilities
(Bullet list of 6-10 specific responsibilities)

## Requirements
**Must Have:**
- (List essential requirements)

**Nice to Have:**
- (List preferred requirements)

## What We Offer
(Benefits, culture, growth opportunities)

## About [Company / Company Type]
(Brief 2-3 sentence company description based on what was provided)

---

Write in an inclusive, direct, and compelling style. Avoid corporate jargon. Make it sound like a real opportunity worth applying for.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';