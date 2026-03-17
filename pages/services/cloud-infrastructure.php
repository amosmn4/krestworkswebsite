<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Cloud Infrastructure — ' . APP_NAME;
$page_description = 'Cloud hosting, auto-scaling, CI/CD pipelines, container orchestration, uptime monitoring, and managed DevOps for enterprise systems.';


$service = [
  'slug'      => 'cloud-infrastructure',
  'icon'      => 'fa-cloud',
  'title'     => 'Cloud Infrastructure',
  'color'     => '#06B6D4',
  'tagline'   => 'Reliable, monitored, auto-scaling cloud environments — so your systems run at peak performance 24/7.',
  'desc'      => 'We provision, configure, secure, and manage your cloud infrastructure — from cPanel shared hosting to multi-region AWS deployments. Our DevOps practice ensures your systems are always available, always monitored, and always cost-optimised.',
  'whatWeBuild' => [
    ['fa-server','Cloud Hosting','VPS, dedicated, and managed cloud hosting on AWS, DigitalOcean, and cPanel.'],
    ['fa-arrows-up-down','Auto-Scaling','Infrastructure that scales up during peak load and scales down to save cost.'],
    ['fa-code-branch','CI/CD Pipelines','Automated build, test, and deploy pipelines — code to production in minutes.'],
    ['fa-box','Containerisation','Docker-based deployments for consistent, portable, reproducible environments.'],
    ['fa-heart-pulse','Uptime Monitoring','24/7 monitoring with instant alerts and automatic failover procedures.'],
    ['fa-database','Backup Management','Automated daily backups, offsite replication, and tested restore procedures.'],
  ],
  'tech'      => ['AWS','DigitalOcean','cPanel','Docker','Nginx','Apache','Linux','GitHub Actions','Let\'s Encrypt','Cloudflare','Grafana','New Relic'],
  'faqs'      => [
    ['Do you manage cPanel hosting?','Yes. Most of our clients use cPanel. We handle server configuration, PHP version management, database setup, SSL certificates, and email configuration.'],
    ['What uptime SLA do you offer?','We target 99.9% uptime (less than 9 hours downtime/year) for standard deployments, and 99.95% for high-availability setups with load balancers and database replication.'],
    ['Can you migrate our existing hosting?','Yes. We conduct a full audit, migrate in stages with zero-downtime cutover, and provide rollback procedures before decommissioning the old environment.'],
    ['How do backups work?','Daily automated backups to a separate storage region. We test restores monthly and document the full recovery procedure. Backup retention is configurable — typically 30 days.'],
    ['Do you provide DDoS protection?','Yes — through Cloudflare WAF and rate limiting. We also configure nginx-level DDoS mitigation and can implement AWS Shield for high-value targets.'],
  ],
  'process'   => ['Infrastructure Audit','Architecture Design','Provisioning & Configuration','Security Hardening','CI/CD Setup','Monitoring & Alerting','Handover & Documentation'],
  'timeline'  => '1 – 4 weeks',
  'pricing'   => 'From KES 30,000/mo',
  'related'   => [['cybersecurity','fa-shield-halved','Cybersecurity'],['custom-software','fa-laptop-code','Custom Software'],['system-integration','fa-plug','System Integration']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';