<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'System Integration — ' . APP_NAME;
$page_description = 'ERP, API, payment, biometric, and third-party integrations — connect your business tools into one seamless ecosystem.';


$service = [
  'slug'      => 'system-integration',
  'icon'      => 'fa-plug',
  'title'     => 'System Integration',
  'color'     => '#F97316',
  'tagline'   => 'Connect your tools, eliminate silos, and automate cross-system workflows.',
  'desc'      => 'Most businesses run on 5–15 disconnected tools. We connect them. From ERP to payment gateways, biometric devices to third-party APIs — we architect integrations that eliminate manual data entry, reduce errors, and give you a single source of truth.',
  'whatWeBuild' => [
    ['fa-diagram-project','ERP Integration','Connect your ERP to HR, payroll, inventory, and financial systems.'],
    ['fa-exchange-alt','API Development','Build and expose RESTful or GraphQL APIs for internal and external consumers.'],
    ['fa-credit-card','Payment Integration','M-PESA Daraja, Stripe, Flutterwave, PayPal — full payment cycle integration.'],
    ['fa-fingerprint','Biometric/Hardware','Biometric attendance, access control, barcode scanners, and POS hardware.'],
    ['fa-database','Data Migration','Safely migrate data from legacy systems, spreadsheets, or third-party platforms.'],
    ['fa-arrows-rotate','ETL Pipelines','Extract, transform, and load data between systems on schedule or in real-time.'],
  ],
  'tech'      => ['REST APIs','GraphQL','WebSockets','M-PESA Daraja','Stripe','OAuth2','JWT','Webhooks','SAP','QuickBooks','Twilio','SendGrid'],
  'faqs'      => [
    ['Can you integrate with SAP or Odoo?','Yes. We have experience integrating with SAP (via RFC/BAPI), Odoo (XML-RPC/REST), and most enterprise ERPs through their published APIs.'],
    ['How do you handle M-PESA integration?','We implement Safaricom Daraja API — STK Push, C2B, B2C, and transaction status. We handle all sandbox testing, go-live, and error handling.'],
    ['What if the third-party system has no API?','We can use web scraping, RPA bots, or file-based integration (CSV/SFTP) as fallbacks when APIs are not available.'],
    ['How do you ensure data integrity during migration?','We run dual-write periods (writing to both old and new systems simultaneously), automated reconciliation checks, and rollback procedures before decommissioning the legacy system.'],
    ['Do integrations break when third-party systems update?','We build versioned integrations with health monitoring and alerting. When an upstream API changes, our monitoring catches it immediately.'],
  ],
  'process'   => ['Integration Discovery & Mapping','API Contract Design','Auth & Security Setup','Development & Unit Tests','End-to-End Integration Tests','UAT & Data Validation','Go-Live & Monitoring'],
  'timeline'  => '2 – 12 weeks',
  'pricing'   => 'From KES 60,000',
  'related'   => [['custom-software','fa-laptop-code','Custom Software'],['cybersecurity','fa-shield-halved','Cybersecurity'],['cloud-infrastructure','fa-cloud','Cloud Infrastructure']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';