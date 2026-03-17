<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Meeting Notes Generator — AI Hub — ' . APP_NAME;
$page_description = 'Turn raw meeting transcripts or rough notes into structured minutes with decisions, action items, and owners.';


$tool = [
  'slug'         => 'meeting-notes',
  'icon'         => 'fa-microphone',
  'color'        => '#A855F7',
  'name'         => 'Meeting Notes Generator',
  'tagline'      => 'Turn raw meeting transcripts or rough notes into polished, structured minutes in one click.',
  'premium'      => false,
  'capabilities' => ['Structured meeting minutes','Action item extraction with owners','Decision summary','Follow-up item tracking','Meeting summary for absent members','Multiple output formats'],
  'tips'         => [
    'Include attendee names for better owner assignment in action items',
    'Works with raw transcripts, rough bullet notes, or voice-to-text output',
    'Mention the meeting type (e.g. "board meeting", "sprint planning") for context',
    'The more detail you provide, the more complete the output',
  ],
];

$tool_config = [
  'input_label'  => 'Paste meeting transcript or rough notes',
  'placeholder'  => "Meeting: Q3 Planning Meeting\nDate: [Date]\nAttendees: John (PM), Sarah (Dev), Mike (Finance)\n\n[Paste your raw notes or transcript here]\n\nJohn: We need to finalize the budget by end of week...\nSarah: The API integration is 80% complete...",
  'input_rows'   => 12,
  'cta_text'     => 'Generate Minutes',
  'options'      => [
    ['label' => 'Full Minutes',      'prompt' => ''],
    ['label' => 'Action Items Only', 'prompt' => ''],
    ['label' => 'Decisions Only',    'prompt' => ''],
    ['label' => 'Brief Summary',     'prompt' => ''],
  ],
];

$system_prompt = "You are an expert executive assistant specialising in creating professional meeting documentation. Transform raw meeting notes or transcripts into structured, professional meeting minutes.

Format your output as:

## Meeting Summary
**Meeting:** [Meeting name/type]
**Date:** [If mentioned]
**Attendees:** [If mentioned]

## Key Discussion Points
(Concise bullet summary of what was discussed)

## Decisions Made ✅
(Numbered list of concrete decisions reached)

## Action Items 📋
| Action | Owner | Due Date |
|--------|-------|----------|
| [action] | [person] | [date or TBD] |

## Key Concerns or Risks ⚠️
(Any issues, blockers, or risks raised)

## Next Steps
(What happens next / next meeting / follow-ups)

Be professional, accurate, and concise. Only include information present in the notes.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';