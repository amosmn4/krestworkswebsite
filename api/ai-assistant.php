<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/rate_limiter.php';

header('Content-Type: application/json');

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Method not allowed.', [], 405);
}

// Rate limit AI requests — 30 per hour per IP
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
define('RATE_LIMIT_MAX', 30); // Override for AI endpoint

if (!checkRateLimit('ai_assistant', $ip)) {
    jsonResponse(false, 'Too many requests. Please wait a moment.', [], 429);
}

// Parse JSON body
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);

if (!$body || !isset($body['messages']) || !is_array($body['messages'])) {
    jsonResponse(false, 'Invalid request format.', [], 400);
}

// Sanitize messages — keep only role + content, max 20 turns
$messages = [];
$turns = 0;
foreach (array_slice($body['messages'], -20) as $msg) {
    if (!isset($msg['role'], $msg['content'])) continue;
    if (!in_array($msg['role'], ['user', 'assistant'])) continue;
    $content = substr(strip_tags((string)$msg['content']), 0, 4000);
    if (empty(trim($content))) continue;
    $messages[] = ['role' => $msg['role'], 'content' => $content];
    $turns++;
}

if (empty($messages)) {
    jsonResponse(false, 'No valid messages provided.', [], 400);
}

// System prompt
$systemPrompt = $body['system'] ?? 'You are Krest, the AI assistant for Krestworks Solutions — an enterprise software company based in Kenya. Be helpful, professional, and concise.';
$systemPrompt = substr(strip_tags($systemPrompt), 0, 3000);

// Check API key
if (empty(AI_API_KEY)) {
    // Dev fallback: return a canned response so the UI still works
    $lastMsg = end($messages)['content'] ?? '';
    $fallback = generateFallbackResponse($lastMsg);
    jsonResponse(true, 'OK', ['reply' => $fallback]);
}

// Call Anthropic API
$payload = json_encode([
    'model'      => AI_MODEL,
    'max_tokens' => AI_MAX_TOKENS,
    'system'     => $systemPrompt,
    'messages'   => $messages,
]);

$ch = curl_init(AI_ENDPOINT);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'x-api-key: ' . AI_API_KEY,
        'anthropic-version: 2023-06-01',
    ],
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($curlErr) {
    error_log('Krest AI cURL error: ' . $curlErr);
    jsonResponse(false, 'AI service connection failed.', [], 503);
}

if ($httpCode !== 200) {
    error_log('Krest AI API error ' . $httpCode . ': ' . $response);
    jsonResponse(false, 'AI service returned an error. Please try again.', [], 503);
}

$data = json_decode($response, true);
$reply = $data['content'][0]['text'] ?? null;

if (!$reply) {
    jsonResponse(false, 'No response from AI.', [], 503);
}

// Log usage (non-blocking)
try {
    require_once __DIR__ . '/../includes/db.php';
    $tokensUsed = ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0);
    $userId = $_SESSION['user_id'] ?? null;
    db()->prepare('INSERT INTO ai_tool_usage (tool_slug, user_id, ip_address, tokens_used) VALUES (?, ?, ?, ?)')
       ->execute(['krest-ai-assistant', $userId, $ip, $tokensUsed]);
} catch (Exception $e) {
    error_log('AI usage log error: ' . $e->getMessage());
}

jsonResponse(true, 'OK', ['reply' => $reply]);

// ---- Dev fallback responses ----
function generateFallbackResponse(string $input): string {
    $input = strtolower($input);
    if (str_contains($input, 'product') || str_contains($input, 'system')) {
        return "Krestworks offers 9 enterprise systems including HR Management, Procurement, eLearning, Real Estate, Supply Chain, CRM, Hospital Management, Decision Support, and POS. Which system are you interested in exploring?";
    }
    if (str_contains($input, 'price') || str_contains($input, 'cost')) {
        return "Our pricing depends on the system, deployment type, and your team size. We offer monthly subscriptions, annual plans, and enterprise licenses. I'd recommend visiting our **Pricing** page or booking a consultation for a tailored quote.";
    }
    if (str_contains($input, 'demo')) {
        return "You can request a live demo, access a recorded walkthrough, or get sandbox access directly from our **Demo Center**. Would you like me to direct you there?";
    }
    if (str_contains($input, 'contact') || str_contains($input, 'reach')) {
        return "You can reach Krestworks via email at **" . COMPANY_EMAIL . "**, WhatsApp at **" . WHATSAPP_DISPLAY . "**, or through our Contact page. Our team typically responds within a few hours.";
    }
    return "I'm Krest, your AI assistant for Krestworks Solutions. I can help you explore our enterprise systems, services, pricing, or book a demo. What would you like to know?";
}