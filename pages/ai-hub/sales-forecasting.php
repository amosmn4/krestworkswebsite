<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Sales Forecasting AI — AI Hub — ' . APP_NAME;
$page_description = 'AI-powered sales forecasting with revenue projections, pipeline analysis, and growth scenario modelling.';


$tool = [
  'slug'         => 'sales-forecasting',
  'icon'         => 'fa-chart-line',
  'color'        => '#22C55E',
  'name'         => 'Sales Forecasting AI',
  'tagline'      => 'Feed your sales data and pipeline — get AI revenue forecasts with scenarios, risk factors, and growth recommendations.',
  'premium'      => true,
  'capabilities' => ['Revenue forecasting','Pipeline analysis','Scenario modelling (optimistic/base/pessimistic)','Seasonality detection','Growth driver identification','Sales team performance analysis'],
  'tips'         => [
    'Include at least 3-6 months of historical sales data',
    'Mention your average deal size and sales cycle length',
    'Include current pipeline value and stage breakdown',
    'Describe any known seasonal patterns or upcoming events',
  ],
];

$tool_config = [
  'input_label'  => 'Paste your sales data and pipeline information',
  'placeholder'  => "Business Type: [B2B SaaS / Retail / Services]\nAvg Deal Size: KES [amount]\nSales Cycle: [e.g. 4-6 weeks]\n\n--- Historical Sales (last 6 months) ---\nJan: 1,200,000\nFeb: 980,000\nMar: 1,450,000\n...\n\n--- Current Pipeline ---\nProposal Stage: 4 deals × avg KES 350,000\nNegotiation: 2 deals × avg KES 600,000\nClose Expected: 3 deals this month\n\n--- Context ---\nTeam size: 3 sales reps\nNew marketing campaign launching next month\nNew product feature releasing Q3",
  'input_rows'   => 14,
  'cta_text'     => 'Forecast Sales',
  'options'      => [
    ['label' => 'Full Forecast',       'prompt' => ''],
    ['label' => 'Pipeline Analysis',   'prompt' => ''],
    ['label' => 'Scenario Modelling',  'prompt' => ''],
    ['label' => 'Growth Strategy',     'prompt' => ''],
  ],
];

$system_prompt = "You are an expert sales analyst and revenue forecasting specialist with deep experience in B2B and B2C sales across East African markets. Produce a rigorous, data-driven sales forecast.

Format as:

## Sales Performance Summary
(Current state and trend summary)

## Revenue Forecast — Next 90 Days

| Scenario | Month 1 | Month 2 | Month 3 | Total |
|----------|---------|---------|---------|-------|
| Pessimistic | | | | |
| Base Case | | | | |
| Optimistic | | | | |

**Key Assumptions:** (List the assumptions behind each scenario)

## Pipeline Analysis
(Assessment of current pipeline health, coverage ratio, conversion likelihood)

## Seasonality & Trend Factors
(Patterns detected in historical data)

## Top Growth Drivers
(What will most impact revenue growth)

## Risk Factors & Mitigation
(What could cause underperformance and how to address it)

## Sales Team Recommendations
(Actions the sales team should prioritise this month)

## Leading Indicators to Watch
(Metrics to track to validate or revise the forecast)

Show calculations. Flag key assumptions. Be realistic — accurate forecasts are more valuable than optimistic ones.";

include __DIR__ . '/tool-template.php';
require_once __DIR__ . '/../../includes/footer.php';