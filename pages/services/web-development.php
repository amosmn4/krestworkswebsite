<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'Web Development — ' . APP_NAME;
$page_description = 'Business websites, SaaS platforms, e-commerce, web portals, and progressive web apps — built for performance, SEO, and conversion.';


$service = [
  'slug'      => 'web-development',
  'icon'      => 'fa-globe',
  'title'     => 'Web Development',
  'color'     => '#3B82F6',
  'tagline'   => 'High-performance web experiences that convert, retain, and scale.',
  'desc'      => 'From high-converting business websites to complex SaaS platforms — we build web experiences optimised for speed, SEO, and user engagement. Every site we build is mobile-first, accessible, and production-hardened.',
  'whatWeBuild' => [
    ['fa-briefcase','Business Websites','Corporate sites, landing pages, and digital brochures that convert visitors.'],
    ['fa-cubes','SaaS Platforms','Multi-tenant web applications with subscription billing and user portals.'],
    ['fa-store','E-Commerce','Online stores with payment integration, inventory, and order management.'],
    ['fa-portal-enter','Web Portals','Client portals, employee portals, partner dashboards — secure and role-based.'],
    ['fa-mobile-screen','Progressive Web Apps','App-like web experiences that work offline and can be installed on devices.'],
    ['fa-pen-nib','CMS Development','Headless CMS, WordPress, and custom admin panels for content teams.'],
  ],
  'tech'      => ['HTML5','CSS3','JavaScript','React','PHP','Laravel','Tailwind','Bootstrap','MySQL','Redis','Nginx','Webpack'],
  'faqs'      => [
    ['Do you build WordPress sites?','We build on WordPress when it fits the requirement — typically for content-heavy sites. For complex functionality, we recommend custom PHP/Laravel or React-based solutions.'],
    ['How fast will my website be?','We target sub-2-second load times through image optimisation, CDN integration, code splitting, lazy loading, and server-side caching.'],
    ['Will my site rank on Google?','We implement technical SEO from day one: semantic HTML, meta tags, Open Graph, structured data, sitemap, robots.txt, and Core Web Vitals optimisation.'],
    ['Do you provide hosting for websites?','Yes. We offer managed hosting on cPanel, cloud VPS, or CDN-backed static hosting depending on your site type and traffic expectations.'],
    ['Can you redesign an existing site?','Yes — we can redesign visually while preserving content, URLs, and SEO rankings. We conduct a full audit before migrating anything.'],
  ],
  'process'   => ['Discovery & Sitemap','Wireframing & Prototyping','UI Design & Approval','Frontend Development','Backend & CMS Integration','SEO & Performance Audit','Testing & Launch'],
  'timeline'  => '2 – 10 weeks',
  'pricing'   => 'From KES 80,000',
  'related'   => [['custom-software','fa-laptop-code','Custom Software'],['cybersecurity','fa-shield-halved','Cybersecurity'],['cloud-infrastructure','fa-cloud','Cloud Infrastructure']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';