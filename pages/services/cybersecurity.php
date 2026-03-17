<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Cybersecurity — ' . APP_NAME;
$page_description = 'Security audits, penetration testing, OWASP compliance, data protection, access management, and incident response for enterprise systems.';


$service = [
  'slug'      => 'cybersecurity',
  'icon'      => 'fa-shield-halved',
  'title'     => 'Cybersecurity',
  'color'     => '#8B5CF6',
  'tagline'   => 'Proactive security that protects your systems, data, and reputation before breaches happen.',
  'desc'      => 'Cybersecurity is not a feature — it\'s a foundation. We embed security into every system we build and offer standalone security engagements for existing platforms. From penetration testing and OWASP audits to access management and data compliance — we protect what matters.',
  'whatWeBuild' => [
    ['fa-magnifying-glass','Security Audits','Comprehensive review of code, infrastructure, configuration, and access controls.'],
    ['fa-bug','Penetration Testing','Simulated attacks to identify exploitable vulnerabilities before real attackers do.'],
    ['fa-list-check','OWASP Compliance','Systematic assessment and remediation against the OWASP Top 10 and ASVS.'],
    ['fa-lock','Data Encryption','Encryption at rest and in transit — database columns, file storage, and API payloads.'],
    ['fa-users-gear','Access Management','RBAC, MFA, SSO, session management, and privilege escalation controls.'],
    ['fa-triangle-exclamation','Incident Response','Documented response procedures, containment playbooks, and post-incident analysis.'],
  ],
  'tech'      => ['OWASP','SSL/TLS','WAF','2FA/MFA','SIEM','RBAC','CSRF','SQL Injection Prevention','XSS Protection','bcrypt','JWT','ISO 27001'],
  'faqs'      => [
    ['What does a security audit include?','Code review for common vulnerabilities, infrastructure configuration review, authentication and session management assessment, input validation testing, dependency vulnerability scan, and a written report with severity ratings.'],
    ['How often should we conduct penetration tests?','At minimum annually, and after any major system change, new feature release, or third-party integration. High-risk systems (financial, healthcare) should test quarterly.'],
    ['Do you offer ongoing security monitoring?','Yes. We can deploy SIEM solutions, set up log aggregation and alerting, and provide monthly security posture reports.'],
    ['What is OWASP ASVS?','The Application Security Verification Standard — a framework of 300+ security requirements across authentication, session management, cryptography, data protection, and more. We use it as a baseline for all secure development.'],
    ['Can you help with GDPR or data compliance?','Yes. We assist with data mapping, privacy impact assessments, consent management, and implementing the technical controls required by GDPR, Kenya Data Protection Act, and similar regulations.'],
  ],
  'process'   => ['Threat Modelling','Vulnerability Scanning','Manual Penetration Testing','Findings Documentation','Remediation Support','Re-testing & Sign-off','Ongoing Monitoring'],
  'timeline'  => '1 – 6 weeks',
  'pricing'   => 'From KES 80,000',
  'related'   => [['cloud-infrastructure','fa-cloud','Cloud Infrastructure'],['consulting','fa-lightbulb','Consulting'],['custom-software','fa-laptop-code','Custom Software']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';