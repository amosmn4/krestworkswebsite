<?php
require_once __DIR__ . '/../config/config.php';

// ============================================================
// KRESTWORKS — MAIL SYSTEM
// Uses PHP's mail() as base, with cURL SMTP for production.
// To use SendGrid/Mailgun, set MAIL_HOST, MAIL_USER, MAIL_PASS.
// ============================================================

/**
 * Send an email via SMTP using cURL (works on cPanel).
 */
function sendMail(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody = ''): bool {
    // Sanitize
    $toEmail = filter_var(trim($toEmail), FILTER_VALIDATE_EMAIL);
    if (!$toEmail) {
        error_log("Mailer: invalid recipient email.");
        return false;
    }

    $toName    = htmlspecialchars_decode(strip_tags($toName));
    $subject   = strip_tags($subject);
    $textBody  = $textBody ?: strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", $htmlBody));
    $messageId = '<' . uniqid('kw_', true) . '@' . parse_url(BASE_URL, PHP_URL_HOST) . '>';

    // Full HTML email wrapper
    $fullHtml = emailWrapper($subject, $htmlBody);

    if (APP_ENV === 'development' || empty(MAIL_PASS)) {
        // Dev: use PHP mail()
        $headers  = "From: " . MAIL_FROM_NAME . " <" . MAIL_FROM . ">\r\n";
        $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
        $headers .= "To: {$toName} <{$toEmail}>\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "Message-ID: {$messageId}\r\n";
        $headers .= "X-Mailer: Krestworks/" . APP_VERSION . "\r\n";
        $result = mail($toEmail, $subject, $fullHtml, $headers);
        if (!$result) error_log("Mailer: PHP mail() failed to {$toEmail}");
        return $result;
    }

    // Production: SMTP via cURL
    return sendSmtpCurl($toEmail, $toName, $subject, $fullHtml, $textBody, $messageId);
}

/**
 * Send via SMTP using cURL — works on standard cPanel PHP hosting.
 */
function sendSmtpCurl(
    string $toEmail, string $toName, string $subject,
    string $htmlBody, string $textBody, string $messageId
): bool {
    $boundary = md5(uniqid('kw', true));
    $from     = MAIL_FROM_NAME . ' <' . MAIL_FROM . '>';
    $to       = "{$toName} <{$toEmail}>";
    $date     = date('r');

    // Build RFC 2822 MIME message
    $rawMessage  = "From: {$from}\r\n";
    $rawMessage .= "To: {$to}\r\n";
    $rawMessage .= "Subject: {$subject}\r\n";
    $rawMessage .= "Date: {$date}\r\n";
    $rawMessage .= "Message-ID: {$messageId}\r\n";
    $rawMessage .= "MIME-Version: 1.0\r\n";
    $rawMessage .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";
    $rawMessage .= "\r\n";
    $rawMessage .= "--{$boundary}\r\n";
    $rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
    $rawMessage .= quoted_printable_encode($textBody) . "\r\n";
    $rawMessage .= "--{$boundary}\r\n";
    $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n";
    $rawMessage .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
    $rawMessage .= quoted_printable_encode($htmlBody) . "\r\n";
    $rawMessage .= "--{$boundary}--\r\n";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'smtp://' . MAIL_HOST . ':' . MAIL_PORT,
        CURLOPT_USE_SSL        => CURLUSESSL_ALL,
        CURLOPT_MAIL_FROM      => '<' . MAIL_FROM . '>',
        CURLOPT_MAIL_RCPT      => ['<' . $toEmail . '>'],
        CURLOPT_USERNAME       => MAIL_USER,
        CURLOPT_PASSWORD       => MAIL_PASS,
        CURLOPT_UPLOAD         => true,
        CURLOPT_INFILESIZE     => strlen($rawMessage),
        CURLOPT_READFUNCTION   => (function() use ($rawMessage) {
            $sent = false;
            return function($ch, $fd, $len) use ($rawMessage, &$sent) {
                if ($sent) return '';
                $sent = true;
                return $rawMessage;
            };
        })(),
        CURLOPT_VERBOSE        => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);

    curl_exec($ch);
    $err  = curl_error($ch);
    $code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($err) {
        error_log("Mailer SMTP cURL error to {$toEmail}: {$err}");
        return false;
    }

    return true;
}

/**
 * Load and render an email template.
 */
function renderTemplate(string $templateName, array $vars = []): string {
    $file = MAIL_TEMPLATES_PATH . '/' . $templateName . '.php';
    if (!file_exists($file)) {
        error_log("Mail template not found: {$templateName}");
        return implode('<br>', array_map(fn($k, $v) => "<b>{$k}:</b> {$v}", array_keys($vars), $vars));
    }
    extract($vars, EXTR_SKIP);
    ob_start();
    include $file;
    return ob_get_clean();
}

/**
 * Wrap email content in the Krestworks HTML email template.
 */
function emailWrapper(string $title, string $content): string {
    $appName = APP_NAME;
    $baseUrl = BASE_URL;
    $year    = date('Y');
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$title}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', Arial, sans-serif; background: #f1f3f5; color: #1a1a1a; }
    .email-wrapper { max-width: 600px; margin: 0 auto; background: #ffffff; }
    .email-header { background: #0A0F1A; padding: 32px 40px; text-align: center; }
    .email-header img { height: 44px; }
    .email-header h1 { color: #F5A800; font-size: 22px; margin-top: 10px; font-weight: 700; letter-spacing: -0.5px; }
    .email-header p { color: rgba(255,255,255,0.5); font-size: 12px; margin-top: 4px; }
    .email-body { padding: 40px; }
    .email-body h2 { font-size: 20px; color: #0A0F1A; margin-bottom: 16px; }
    .email-body p { font-size: 14px; color: #374151; line-height: 1.75; margin-bottom: 14px; }
    .email-body strong { color: #0A0F1A; }
    .info-row { display: flex; padding: 10px 0; border-bottom: 1px solid #f3f4f6; }
    .info-label { font-size: 12px; font-weight: 700; color: #6B7280; text-transform: uppercase; letter-spacing: 0.05em; width: 140px; flex-shrink: 0; padding-top: 2px; }
    .info-value { font-size: 14px; color: #111827; flex: 1; }
    .kw-btn-email { display: inline-block; background: #F5A800; color: #0A0F1A !important; padding: 13px 28px; border-radius: 999px; font-weight: 700; font-size: 14px; text-decoration: none; margin: 8px 0; }
    .alert-box { background: #FFF9EC; border: 1px solid #F5A80040; border-left: 4px solid #F5A800; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
    .alert-box p { margin: 0; font-size: 13px; color: #92400E; }
    .email-footer { background: #0A0F1A; padding: 24px 40px; text-align: center; }
    .email-footer p { font-size: 11px; color: rgba(255,255,255,0.3); line-height: 1.7; }
    .email-footer a { color: #F5A800; text-decoration: none; }
    @media (max-width: 600px) {
      .email-body { padding: 24px 20px; }
      .info-row { flex-direction: column; gap: 2px; }
      .info-label { width: auto; }
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <div class="email-header">
      <h1>{$appName}</h1>
      <p>Digital Systems for Modern Businesses</p>
    </div>
    <div class="email-body">
      {$content}
    </div>
    <div class="email-footer">
      <p>
        &copy; {$year} {$appName}. All rights reserved.<br>
        <a href="{$baseUrl}">{$baseUrl}</a> &nbsp;|&nbsp;
        <a href="{$baseUrl}/privacy">Privacy Policy</a> &nbsp;|&nbsp;
        <a href="{$baseUrl}/contact">Contact Us</a>
      </p>
    </div>
  </div>
</body>
</html>
HTML;
}