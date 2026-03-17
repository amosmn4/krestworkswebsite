<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Professional Email Writer — AI Hub — ' . APP_NAME;
$page_description = 'Describe your email situation and tone — get a professionally written, ready-to-send email instantly.';


$tool = [
  'slug'         => 'email-writer',
  'icon'         => 'fa-envelope',
  'color'        => '#F97316',
  'name'         => 'Professional Email Writer',
  'tagline'      => 'Describe your email situation. Get a professionally written, ready-to-send email — any tone, any context.',
  'premium'      => false,
  'capabilities' => ['Formal business emails','Follow-up emails','Cold outreach emails','Complaint handling','Apology emails','Client update emails'],
  'tips'         => [
    'Describe the relationship (e.g. "client I\'ve worked with for 2 years")',
    'Mention the desired tone: formal, friendly, firm, apologetic',
    'Include key points or facts that must be in the email',
    'You can also paste a received email and ask for a reply',
  ],
];

$tool_config = [
  'input_label'  => 'Describe the email you need to write',
  'placeholder'  => "Describe what you need:\n\nExample: 'Write a professional follow-up email to a client who hasn't responded to our proposal for 2 weeks. Tone: friendly but direct. We need a decision by Friday.'\n\nOr paste a received email for a reply:\n'Reply to this email declining the meeting but suggesting an alternative...'\n[paste received email]",
  'input_rows'   => 8,
  'cta_text'     => 'Write Email',
  'options'      => [
    ['label' => 'Follow-up',       'prompt' => ''],
    ['label' => 'Cold Outreach',   'prompt' => ''],
    ['label' => 'Complaint',       'prompt' => ''],
    ['label' => 'Apology',         'prompt' => ''],
  ],
];

$system_prompt = "You are an expert business communications specialist. Write professional, effective emails that achieve their purpose while maintaining appropriate tone and relationships.

Format your output as:

**Subject:** [Subject line]

---

[Email body]

---

**Tone:** [Brief note on tone used]
**Tips:** [1-2 suggestions for personalisation or sending strategy]

Write emails that are:
- Appropriately concise (no padding)
- Clear about the desired action or outcome
- Professional but human in tone
- Free of jargon and corporate speak
- Properly structured with opening, body, and closing

Generate the complete, ready-to-send email.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';