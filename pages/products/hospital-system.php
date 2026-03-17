<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title = 'Hospital Management System — ' . APP_NAME;
$page_description = 'Integrated healthcare operations — EMR, appointments, billing, pharmacy, lab management, and clinical analytics in one connected system.';


$product = [
  'name'       => 'Hospital Management System',
  'icon'       => 'fa-hospital',
  'color'      => '#8B5CF6',
  'tagline'    => 'Connected healthcare operations — from patient registration to discharge and billing.',
  'deployment' => ['Cloud','On-Premise','Hybrid'],
  'industries' => ['Hospitals','Clinics','Health Centres','Laboratories'],
  'price_from' => 'KES 90,000',
  'delivery'   => '12 – 20 weeks',
  'user_types' => 'Admin, Doctor, Nurse, Pharmacist, Lab, Billing, Patient',
  'mobile'     => 'Clinical Mobile + Patient Portal',
  'languages'  => 'English, Swahili',
  'chart_data' => [50,62,70,78,85,92],
  'stats'      => [['EMR','Digital Records'],['Auto','Billing'],['Lab','Integrated'],['NHIF','Ready']],
  'modules'    => [
    ['Patient Registration & EMR','Digital patient records with medical history, allergies, and visit notes.',['Patient Registration','Medical History','Allergy Flags','Visit Notes','Document Uploads']],
    ['Appointment Management','Online and walk-in appointment booking with doctor scheduling and reminders.',['Appointment Booking','Doctor Schedule','SMS Reminders','Walk-in Queue','Appointment History']],
    ['Doctor & Clinical Portal','Clinical workspace for doctors — patient queue, consultation notes, and prescriptions.',['Patient Queue','SOAP Notes','Prescription Writing','Lab Requests','Referral Letters']],
    ['Pharmacy Management','Dispensing, stock management, drug interaction alerts, and purchase orders.',['Drug Dispensing','Stock Management','Drug Interactions','Reorder Alerts','Pharmacy Reports']],
    ['Laboratory Management','Test requests, sample tracking, result entry, and result delivery to doctors.',['Test Requests','Sample Tracking','Result Entry','Auto-delivery','Lab Reports']],
    ['Billing & Insurance','Itemised billing, NHIF/SHA claims, insurance pre-auth, and payment collection.',['Itemised Billing','NHIF Claims','Insurance Pre-auth','Payment Collection','Billing Reports']],
    ['Nurse Station','Nursing notes, vital signs, medication administration, and ward management.',['Vital Signs','Nursing Notes','Medication Admin','Ward Management','Shift Handover']],
    ['Clinical Analytics','Dashboards for patient volumes, revenue, morbidity, and clinical quality metrics.',['Patient Volume','Revenue Analytics','Morbidity Reports','Clinical KPIs','Custom Reports']],
  ],
  'related'    => [['hr-system','fa-users','HR System'],['decision-support-system','fa-chart-line','Decision Support'],['procurement-system','fa-shopping-cart','Procurement']],
];

$features_detailed = [
  ['fa-file-medical','Electronic Medical Records','Comprehensive EMR with medical history, allergies, chronic conditions, and visit notes.'],
  ['fa-calendar','Appointment System','Online booking, walk-in queue management, and SMS/email reminders.'],
  ['fa-stethoscope','Doctor Portal','Clinical workspace with SOAP notes, prescriptions, lab requests, and referral letters.'],
  ['fa-pills','Pharmacy Module','Drug dispensing with interaction alerts, stock management, and automated reorder.'],
  ['fa-microscope','Laboratory Integration','Lab test requests, sample tracking, result entry, and auto-delivery to ordering doctor.'],
  ['fa-file-invoice-dollar','Billing & NHIF','Itemised billing with NHIF/SHA claim generation, insurance pre-auth, and payment tracking.'],
  ['fa-heart-pulse','Nurse Station','Vital signs, nursing notes, medication administration records, and ward management.'],
  ['fa-brain','Clinical AI','AI-assisted diagnosis suggestions, drug interaction warnings, and readmission risk alerts.'],
];

$faqs = [
  ['Is it compliant with Kenya Ministry of Health standards?','Yes. We align with Kenya EMR standards and can integrate with the national HIS (DHIS2) for aggregate reporting.'],
  ['Does it process NHIF claims?','Yes. The billing module generates NHIF-format claim files that can be uploaded directly to NHIF Clinisys. SHA integration is also available.'],
  ['Can patients book appointments online?','Yes. There is a patient-facing portal (web and mobile) for appointment booking, viewing results, and accessing discharge summaries.'],
  ['Does it work offline?','The on-premise deployment works without internet. Cloud deployments have offline mode for critical functions (patient registration, vitals) with background sync.'],
  ['How is patient data privacy handled?','All patient data is encrypted at rest. Access is strictly role-based — a pharmacy staff member cannot access clinical notes. Full audit logs track every record access.'],
];

$tech_stack = [
  ['Frontend',['React','Tailwind CSS','Chart.js','Print CSS']],
  ['Backend',['PHP','Laravel','HL7 FHIR','Queue Workers']],
  ['Database',['MySQL','Encrypted PHI columns','Redis']],
  ['Integrations',['NHIF Clinisys','DHIS2','SMS Gateway','M-PESA']],
  ['Infrastructure',['cPanel / AWS','Docker','On-premise Linux','SSL']],
];

include __DIR__ . '/product-template.php';
require_once __DIR__ . '/../../includes/footer.php';