<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Document Summarizer — AI Hub — ' . APP_NAME;
$page_description = 'Paste any document and get a structured AI summary with key points, action items, and insights extracted in seconds.';


$tool = [
  'slug'         => 'document-summarizer',
  'icon'         => 'fa-file-lines',
  'color'        => '#F5A800',
  'name'         => 'Document Summarizer',
  'tagline'      => 'Turn long documents into structured summaries with key points, decisions, and action items — in seconds.',
  'premium'      => false,
  'capabilities' => ['Executive summary generation','Key points extraction','Action item detection','Decision logging','Risk/concern flagging','Multiple output formats'],
  'tips'         => [
    'Paste the full document for better context — not just excerpts',
    'Works best with reports, proposals, meeting transcripts, and contracts',
    'Use the format options to get output structured for your use case',
    'For very long documents, split into sections of ~4000 words',
  ],
];

$tool_config = [
  'input_label'  => 'Paste your document content',
  'placeholder'  => "Paste your document, report, article, or transcript here...\n\nTip: The longer and more complete the input, the better the summary.",
  'input_rows'   => 10,
  'cta_text'     => 'Summarize Document',
  'options'      => [
    ['label' => 'Executive Summary',  'prompt' => ''],
    ['label' => 'Key Points Only',    'prompt' => ''],
    ['label' => 'Action Items',       'prompt' => ''],
    ['label' => 'Meeting Minutes',    'prompt' => ''],
  ],
];

$system_prompt = "You are an expert document analyst for Krestworks Solutions. Your task is to analyze documents and produce professional, structured summaries.

Format your output clearly with sections using ## headers. Always include:
## Executive Summary
(2-3 sentence high-level overview)

## Key Points
(bullet list of the most important information)

## Action Items / Next Steps
(if any are mentioned or implied)

## Key Decisions or Conclusions
(if applicable)

## Risks or Concerns
(if any are mentioned)

Be concise, professional, and business-focused. Do not add information not present in the document. If the content is unclear, note that.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';