<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'General Consultation — ' . APP_NAME;
$page_description = 'Not sure where to start? Tell us about your business and we\'ll map the right technology path forward.';
$consult = [
  'slug' => 'general', 'icon' => 'fa-comments', 'color' => '#6B7280',
  'title' => 'General Technology Consultation',
  'tagline' => 'Not sure where to start? Tell us about your business and we\'ll map the right technology path forward.',
  'description' => 'Sometimes you know something needs to change, but you\'re not sure what. Our General Consultation is a free, structured conversation with a senior Krestworks consultant. We listen to your challenges, ask the right questions, and give you a clear, honest assessment of where technology can help — and where it can\'t.',
  'challenges' => ['Don\'t know which system your business needs','Have competing technology proposals and need a second opinion','Just starting digital operations and need guidance','Inherited a technology environment you don\'t fully understand','Want to validate a technology idea before committing budget','Looking for a trusted technology partner to grow with'],
  'outcomes' => ['Honest technology assessment','Recommended next steps (specific, not generic)','Service or product recommendation if relevant','Referral to the right specialist if needed','Written summary of discussion and recommendations','No obligation to proceed'],
  'process' => [
    ['Brief (10 min)','You fill in a short form describing your business, challenges, and goals before the session.'],
    ['Structured Discovery (20 min)','Our consultant asks targeted questions to understand your operations, team, budget, and timeline.'],
    ['Expert Assessment (10 min)','We share our honest assessment — what technology can realistically help, what it can\'t, and what the right next step looks like.'],
    ['Written Summary','Within 24 hours, we email a written summary of key points, recommendations, and suggested next steps.'],
    ['Optional Follow-up','If a more specialised consultation is needed, we schedule it directly from the general session.'],
  ],
  'whoIsItFor' => ['Business owners new to technology','Managers evaluating technology options','Entrepreneurs building their first digital system','Anyone who\'s been let down by technology before','Teams who want a second opinion on existing plans','Anyone unsure which Krestworks service fits their need'],
  'faqs' => [
    ['Is the general consultation really free?','Yes. The first 30-minute session is completely free with no obligation to proceed. We believe in earning your trust before your business.'],
    ['Will I be pushed towards a product?','No. We recommend what fits — if that\'s a Krestworks system, great. If it\'s something else entirely, we\'ll tell you. Honest advice builds better relationships than hard selling.'],
    ['What should I prepare?','Nothing formal. Come ready to describe your business, your biggest operational challenge, and roughly what you\'d like technology to change. We guide the rest.'],
    ['What if my need is very technical?','General consultations sometimes reveal highly technical requirements. In those cases, we\'ll schedule a follow-up with the right specialist (architect, AI engineer, security expert) at no extra charge.'],
  ],
  'related' => [['digital-transformation','fa-arrow-trend-up','Digital Transformation'],['system-design','fa-drafting-compass','System Architecture'],['business-automation','fa-gears','Business Automation']],
];
include __DIR__ . '/consultation-template.php';
require_once __DIR__ . '/../../includes/footer.php';