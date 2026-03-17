<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Financial Analysis AI — AI Hub — ' . APP_NAME;
$page_description = 'Enter your financial data and get AI-powered analysis with ratio analysis, trend detection, and CFO-level recommendations.';


$tool = [
  'slug'         => 'financial-analysis-ai',
  'icon'         => 'fa-calculator',
  'color'        => '#3B82F6',
  'name'         => 'Financial Analysis AI',
  'tagline'      => 'Paste your financial data — get AI-powered analysis with ratios, trends, anomalies, and CFO-level insights.',
  'premium'      => true,
  'capabilities' => ['Key ratio analysis','Revenue & cost trends','Margin analysis','Cash flow insights','Budget variance analysis','Anomaly detection','CFO recommendations'],
  'tips'         => [
    'Include multiple periods (months or years) for trend analysis',
    'Specify the currency and period (monthly/annual)',
    'Include P&L, balance sheet, or cash flow — whichever you have',
    'Mention your industry for relevant benchmarking context',
  ],
];

$tool_config = [
  'input_label'  => 'Paste your financial data',
  'placeholder'  => "Industry: [e.g. Healthcare Tech SaaS]\nPeriod: [e.g. Jan-Jun 2024, Monthly]\nCurrency: KES\n\n--- Revenue ---\nJan: 850,000\nFeb: 920,000\nMar: 1,100,000\n...\n\n--- Costs ---\nSalaries: 450,000/mo\nHosting: 45,000/mo\nMarketing: 80,000/mo\n...\n\n--- Other ---\nCash in bank: 2,400,000\nOutstanding invoices: 340,000\nMonthly burn: 630,000",
  'input_rows'   => 14,
  'cta_text'     => 'Analyze Financials',
  'options'      => [
    ['label' => 'Full Analysis',       'prompt' => ''],
    ['label' => 'Ratio Analysis',      'prompt' => ''],
    ['label' => 'Trend Detection',     'prompt' => ''],
    ['label' => 'Cash Flow Analysis',  'prompt' => ''],
  ],
];

$system_prompt = "You are a seasoned CFO and financial analyst with expertise in African SME and enterprise businesses. Analyze the provided financial data and produce a comprehensive, actionable financial report.

Format as:

## Financial Snapshot
(Key numbers summary in 3-4 sentences)

## Revenue Analysis
(Trends, growth rates, seasonality, anomalies)

## Cost Structure & Margins
(Cost breakdown analysis, gross margin, net margin, efficiency ratios)

## Key Financial Ratios
| Ratio | Value | Benchmark | Status |
(Relevant ratios calculated from data provided)

## Cash Flow Analysis
(Cash position, burn rate, runway if startup, cash conversion)

## Anomalies & Red Flags 🔴
(Anything unusual that warrants investigation)

## Positive Trends ✅
(What's working well financially)

## CFO Recommendations
(5-7 specific, prioritised financial actions to improve performance)

## Forecast Indicators
(What the current trajectory suggests for the next 90 days)

Show your calculations where relevant. Be specific with numbers. Flag assumptions clearly.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';