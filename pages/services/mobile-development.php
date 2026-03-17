<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Mobile App Development — ' . APP_NAME;
$page_description = 'Native iOS & Android apps and cross-platform mobile applications for enterprise and consumer use cases.';


$service = [
  'slug'      => 'mobile-development',
  'icon'      => 'fa-mobile-alt',
  'title'     => 'Mobile App Development',
  'color'     => '#22C55E',
  'tagline'   => 'Enterprise-grade mobile apps for iOS and Android — built for real-world business use.',
  'desc'      => 'We build native and cross-platform mobile applications that deliver seamless, high-performance experiences. From field service apps and mobile ERP to customer-facing consumer apps — we architect for offline capability, device integration, and enterprise security.',
  'whatWeBuild' => [
    ['fa-apple','iOS Applications','Native Swift applications for iPhone and iPad with full App Store deployment.'],
    ['fa-android','Android Apps','Native Kotlin apps optimised for the Android ecosystem and Google Play.'],
    ['fa-layer-group','Cross-Platform','React Native and Flutter apps that share codebases across iOS and Android.'],
    ['fa-hard-hat','Field Service Apps','Offline-capable apps for field teams — inspections, deliveries, maintenance.'],
    ['fa-building','Mobile ERP','Access enterprise systems on mobile — approvals, dashboards, notifications.'],
    ['fa-credit-card','Mobile Payments','M-PESA, Stripe, and payment gateway integration within mobile apps.'],
  ],
  'tech'      => ['React Native','Flutter','Swift','Kotlin','Firebase','REST APIs','M-PESA','Push Notifications','SQLite','Redux'],
  'faqs'      => [
    ['Cross-platform or native — which is better?','For most enterprise apps, React Native or Flutter provides 80–90% of native performance at significantly lower cost. We recommend native only when deep device APIs (AR, advanced sensors) are required.'],
    ['How do you handle app updates?','Over-the-air updates for non-binary changes, full App Store/Play Store releases for major versions. We set up CI/CD pipelines so updates deploy automatically.'],
    ['Can the app work offline?','Yes. We design offline-first apps with local SQLite/Realm databases and background sync when connectivity is restored.'],
    ['Do you handle App Store submission?','Yes. We manage the full submission process including screenshots, metadata, review compliance, and responding to rejection notices.'],
    ['Can you integrate with our existing backend?','Absolutely. We build against your existing APIs or design new API contracts as part of the project.'],
  ],
  'process'   => ['Discovery & UX Research','Wireframing & Prototype','UI Design','Development Sprints','Device & OS Testing','Beta Release & Feedback','App Store Submission','Launch & Monitoring'],
  'timeline'  => '6 – 20 weeks',
  'pricing'   => 'From KES 200,000',
  'related'   => [['web-development','fa-globe','Web Development'],['custom-software','fa-laptop-code','Custom Software'],['ai-solutions','fa-robot','AI Solutions']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';