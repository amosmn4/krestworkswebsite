<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Code Assistant — AI Hub — ' . APP_NAME;
$page_description = 'AI code review, debugging, explanation, and generation. Supports PHP, Python, JavaScript, TypeScript, SQL, and more.';


$tool = [
  'slug'         => 'code-assistant',
  'icon'         => 'fa-code',
  'color'        => '#22C55E',
  'name'         => 'Code Assistant',
  'tagline'      => 'Review, debug, explain, refactor, or write code from description. Supports all major languages.',
  'premium'      => false,
  'capabilities' => ['Code review with issues & fixes','Bug detection & debugging help','Code explanation (plain English)','Refactoring suggestions','Write code from description','SQL query optimisation'],
  'tips'         => [
    'Specify your language and framework for better results',
    'Describe the bug or what the code should do when asking for help',
    'For code review, paste the full function or class context',
    'Use Ctrl+Enter to submit quickly',
  ],
];

$tool_config = [
  'input_label'  => 'Paste your code or describe what you need',
  'placeholder'  => "// Option 1: Paste code for review or debugging\nfunction calculateTotal(\$items) {\n  // Your code here\n}\n\n// Option 2: Describe what you need\n// 'Write a PHP function that validates an email and checks if it exists in MySQL'\n\n// Option 3: Paste error message with code\n// Error: undefined variable...",
  'input_rows'   => 14,
  'cta_text'     => 'Analyze Code',
  'options'      => [
    ['label' => 'Review & Debug',     'prompt' => ''],
    ['label' => 'Explain This Code',  'prompt' => ''],
    ['label' => 'Refactor',           'prompt' => ''],
    ['label' => 'Write From Scratch', 'prompt' => ''],
  ],
];

$system_prompt = "You are a senior software engineer specialising in PHP, JavaScript, Python, TypeScript, SQL, and modern web frameworks (Laravel, React, Node.js, Django). Your role is to help developers write better code.

When reviewing or debugging code:
## Issues Found
(List bugs, security vulnerabilities, performance issues — with line references if possible)

## Fixed Code
(Provide corrected code in a code block)

## Explanation
(Explain what was wrong and why the fix works)

## Improvements Suggested
(Optional: additional improvements beyond the immediate fix)

When explaining code:
## What This Code Does
(Plain English explanation)

## Key Logic
(Walk through the important parts)

## Potential Issues
(Flag anything that could be problematic)

When writing code from description:
## Implementation
(Code block with the full implementation)

## Usage Example
(How to use it)

## Notes
(Any assumptions made or alternatives to consider)

Always use proper code blocks with language labels. Be precise and educational.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';