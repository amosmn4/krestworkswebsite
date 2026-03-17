<?php
require_once __DIR__ . '/../../includes/header.php';
$page_title       = 'AI Solutions — ' . APP_NAME;
$page_description = 'AI copilots, intelligent chatbots, predictive analytics, NLP, computer vision, and ML model integration for enterprise systems.';


$service = [
  'slug'      => 'ai-solutions',
  'icon'      => 'fa-robot',
  'title'     => 'AI Solutions',
  'color'     => '#A855F7',
  'tagline'   => 'Embed intelligence into your operations — from smart automation to predictive decision support.',
  'desc'      => 'We integrate production-grade AI into your business systems — not demos, not prototypes. From intelligent copilots and NLP document processing to predictive analytics and computer vision pipelines, we deliver AI that works in real business environments.',
  'whatWeBuild' => [
    ['fa-robot','AI Copilots','Context-aware assistants embedded into your enterprise systems.'],
    ['fa-comments','Intelligent Chatbots','Multi-turn conversational AI for customer support, HR, and sales.'],
    ['fa-chart-line','Predictive Analytics','Forecast demand, churn, revenue, and risk using ML models.'],
    ['fa-eye','Computer Vision','Document scanning, facial recognition, quality control, and object detection.'],
    ['fa-file-alt','NLP & Document AI','Extract, classify, and summarise information from documents at scale.'],
    ['fa-gears','Automation Bots','Intelligent RPA bots that handle repetitive tasks across your systems.'],
  ],
  'tech'      => ['Python','TensorFlow','PyTorch','Anthropic Claude','OpenAI','LangChain','FastAPI','Hugging Face','scikit-learn','Pandas','NumPy','OpenCV'],
  'faqs'      => [
    ['Do you build AI from scratch or use existing models?','Both. For most business use cases, fine-tuning or RAG (Retrieval-Augmented Generation) on top of foundation models (Claude, GPT) is faster and more cost-effective than training from scratch. We always recommend the most pragmatic approach.'],
    ['How much data do I need?','It depends on the use case. Chatbots and document AI can work with minimal data using foundation models. Predictive analytics typically needs 12–24 months of historical data. We assess your data maturity during discovery.'],
    ['How do you handle AI accuracy and hallucinations?','We implement validation layers, confidence thresholds, human-in-the-loop checkpoints, and comprehensive testing against your real data before production deployment.'],
    ['Is the AI data secure?','Yes. We can deploy AI entirely within your infrastructure (on-premise or private cloud) with no data leaving your environment. For cloud AI APIs, we implement data minimisation and encryption in transit.'],
    ['Can AI integrate with our existing software?','Yes. We expose AI capabilities as internal APIs that plug into your existing workflows — your teams interact with it through the tools they already use.'],
  ],
  'process'   => ['Use Case Discovery','Data Assessment','Model Selection & Architecture','Prototype & Evaluation','Integration Development','Testing & Bias Audit','Deployment & Monitoring','Continuous Improvement'],
  'timeline'  => '4 – 16 weeks',
  'pricing'   => 'From KES 150,000',
  'related'   => [['custom-software','fa-laptop-code','Custom Software'],['system-integration','fa-plug','System Integration'],['consulting','fa-lightbulb','Consulting']],
];

include __DIR__ . '/service-template.php';
require_once __DIR__ . '/../../includes/footer.php';