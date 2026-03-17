<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Data Insight Generator — AI Hub — ' . APP_NAME;
$page_description = 'Paste tabular data and get automated statistical analysis, pattern detection, outlier alerts, and business narrative.';


$tool = [
  'slug'         => 'data-insight-generator',
  'icon'         => 'fa-magnifying-glass-chart',
  'color'        => '#A855F7',
  'name'         => 'Data Insight Generator',
  'tagline'      => 'Paste raw data — get statistical analysis, pattern detection, outlier alerts, and a business narrative automatically.',
  'premium'      => true,
  'capabilities' => ['Statistical summary (mean, median, ranges)','Pattern & trend detection','Outlier and anomaly identification','Correlation analysis','Business narrative generation','Chart type recommendations'],
  'tips'         => [
    'Paste data in CSV format with headers in the first row',
    'Include column descriptions if the header names aren\'t clear',
    'Works best with up to ~200 rows for detailed analysis',
    'Mention what business question you\'re trying to answer',
  ],
];

$tool_config = [
  'input_label'  => 'Paste your CSV data (with headers)',
  'placeholder'  => "Business question: [What are you trying to understand?]\nContext: [What is this data about?]\n\n--- CSV Data ---\nDate,Product,Revenue,Units,Region\n2024-01-01,ProductA,45000,12,Nairobi\n2024-01-01,ProductB,23000,8,Nairobi\n2024-01-02,ProductA,52000,14,Mombasa\n...",
  'input_rows'   => 14,
  'cta_text'     => 'Generate Insights',
  'options'      => [
    ['label' => 'Full Analysis',      'prompt' => ''],
    ['label' => 'Pattern Detection',  'prompt' => ''],
    ['label' => 'Outlier Analysis',   'prompt' => ''],
    ['label' => 'Business Narrative', 'prompt' => ''],
  ],
];

$system_prompt = "You are a senior data scientist and business intelligence analyst. Analyze the provided dataset and produce a comprehensive, actionable data insight report.

Format as:

## Dataset Overview
(Dimensions, column summary, data quality notes)

## Statistical Summary
(Key statistics for numerical columns: min, max, mean, median, notable ranges)

## Key Patterns & Trends 📈
(What the data shows about behaviour, trends, or distributions)

## Outliers & Anomalies ⚠️
(Data points that deviate significantly — flag with specific values)

## Correlations & Relationships
(Relationships between variables that are meaningful)

## Business Insights 💡
(The 3-5 most important business conclusions from this data)

## Recommended Actions
(Specific actions based on the data findings)

## Suggested Visualisations
(Chart types that would best communicate these insights and why)

## Data Quality Notes
(Missing values, inconsistencies, or limitations to be aware of)

Be specific with numbers. Reference actual data points. Focus on insights that drive decisions — not just statistics for statistics' sake.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';