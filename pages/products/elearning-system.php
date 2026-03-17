<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'eLearning Management System — ' . APP_NAME;
$page_description = 'Full-featured LMS for academic institutions and corporate training — courses, exams, certifications, live classes, and learning analytics.';


$product = [
  'name'       => 'eLearning Management System',
  'icon'       => 'fa-graduation-cap',
  'color'      => '#22C55E',
  'tagline'    => 'Deliver, manage, and measure learning at scale — from classroom to cloud.',
  'deployment' => ['Cloud','SaaS','On-Premise'],
  'industries' => ['Education','Corporate','NGOs','Government'],
  'price_from' => 'KES 45,000',
  'delivery'   => '6 – 12 weeks',
  'user_types' => 'Admin, Instructor, Student, Parent',
  'mobile'     => 'Responsive + Student Mobile App',
  'languages'  => 'English, Swahili, French',
  'chart_data' => [30,48,55,70,82,91],
  'stats'      => [['∞','Students','Courses'],['HD','Video Support'],['Auto','Certification'],['Real-time','Progress']],
  'modules'    => [
    ['Course Management','Create structured courses with lessons, videos, PDFs, and SCORM content.',['Lesson Builder','Video Upload','SCORM Support','Course Scheduling','Prerequisites']],
    ['Student Portal','Personalised dashboard with enrolled courses, progress, and upcoming assignments.',['My Courses','Progress Tracking','Deadlines','Discussion Board','Resource Library']],
    ['Online Exams & Assessments','Timed exams, quizzes, assignments, and automated grading with anti-cheat features.',['Timed Exams','Question Banks','Auto-grading','Anti-cheat','Results Analysis']],
    ['Live Classes','Integrated virtual classroom with recording, attendance, and breakout rooms.',['Video Conferencing','Recording','Attendance','Breakout Rooms','Chat & Polls']],
    ['Certification System','Auto-issue certificates on course completion with verifiable QR codes.',['Certificate Templates','Auto-issue','QR Verification','Certificate Registry','Expiry Management']],
    ['Grading & Transcripts','Gradebook, weighted grading, GPA calculation, and official transcripts.',['Gradebook','Weighted Grades','GPA Calculator','Transcripts','Grade Appeals']],
    ['Content Library','Shared repository of reusable learning materials across courses.',['Media Library','Document Store','SCORM Library','Version Control','Tagging & Search']],
    ['Learning Analytics','Dashboards tracking completion rates, scores, engagement, and at-risk students.',['Completion Rates','Score Trends','Engagement Metrics','At-risk Alerts','Instructor Reports']],
  ],
  'related'    => [['hr-system','fa-users','HR System'],['decision-support-system','fa-chart-line','Decision Support'],['crm-system','fa-handshake','CRM System']],
];

$features_detailed = [
  ['fa-book-open','Course Builder','Drag-and-drop course builder with video, PDF, SCORM, and quiz content types.'],
  ['fa-user-graduate','Student Portal','Personalised learning dashboard with progress tracking and deadline reminders.'],
  ['fa-pen-to-square','Online Exams','Timed exams with randomised questions, auto-grading, and plagiarism detection.'],
  ['fa-video','Live Classes','Integrated virtual classroom with recording, polls, breakout rooms, and attendance.'],
  ['fa-certificate','Certification','Auto-issued certificates with QR-code verification and expiry management.'],
  ['fa-chart-bar','Gradebook','Weighted gradebook, GPA calculation, and official transcript generation.'],
  ['fa-users','Bulk Enrollment','CSV bulk enrollment, group management, and automated welcome emails.'],
  ['fa-brain','AI Tutoring','AI-powered student support, content recommendations, and at-risk alerts.'],
];

$faqs = [
  ['Does it support SCORM content?','Yes. We support SCORM 1.2 and 2004, xAPI (Tin Can), and AICC content packages. You can import existing e-learning content directly.'],
  ['Can it handle large student numbers?','Yes. The system is load-tested for 10,000+ concurrent users with horizontal scaling available on cloud deployments.'],
  ['Is there a mobile app for students?','Yes. There is a progressive web app (PWA) that students can install on their phones. A dedicated mobile app (React Native) is available as an add-on.'],
  ['Can we white-label it for our institution?','Yes. The system fully supports custom branding — your logo, colours, domain, and email templates. Students see only your brand.'],
  ['How are certificates verified?','Each certificate has a unique QR code that links to a public verification page — no login required for verification. Useful for employer verification.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Video.js','Chart.js']],
  ['Backend',['PHP','Laravel','Queue Workers','WebSockets']],
  ['Database',['MySQL','Redis','Elasticsearch (search)']],
  ['Integrations',['Zoom API','M-PESA','PayPal','Email/SMS']],
  ['Infrastructure',['AWS S3 (media)','CloudFront CDN','Docker','cPanel']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';