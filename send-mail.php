<?php
declare(strict_types=1);

require_once dirname(__FILE__, 4) . '/wp-load.php';

/**
 * HARTE Redirect-Basis (lokal)
 * Live später z.B.: https://wendlandwebdesign.de/kontakt/
 */
$contactUrl = 'http://localhost/wwd-redesign/kontakt/';
$redirectSuccess = $contactUrl . '?sent=1';
$redirectError   = $contactUrl . '?sent=0';
$redirectErrorRequired = $contactUrl . '?sent=0&error=required';
$redirectErrorRateLimit = $contactUrl . '?sent=0&error=rate_limit';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: ' . $redirectError, true, 302);
    exit;
}

// -------------------------
// SPAM-SCHUTZ 1: Nonce/CSRF
// -------------------------
$nonce = (string)($_POST['wwd_nonce'] ?? '');
if ($nonce === '' || !wp_verify_nonce($nonce, 'wwd_contact_form')) {
    header('Location: ' . $redirectError, true, 302);
    exit;
}

// -------------------------
// SPAM-SCHUTZ 2: Honeypot
// (wenn ausgefüllt => Bot)
// -------------------------
if (!empty($_POST['website'] ?? '')) {
    // extra leise: wie "Erfolg" verhalten, damit Bots nix lernen
    header('Location: ' . $redirectSuccess, true, 302);
    exit;
}

// -------------------------
// SPAM-SCHUTZ 3: Time-Trap
// Mindestzeit seit Laden des Formulars (Sekunden)
// -------------------------
$formTs = (int)($_POST['form_ts'] ?? 0);
$minSeconds = 3;

if ($formTs <= 0 || (time() - $formTs) < $minSeconds) {
    header('Location: ' . $redirectError, true, 302);
    exit;
}

// -------------------------
// RATE-LIMIT (30s)
// -------------------------
$ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
$lockKey = 'mail_lock_' . md5($ip . '|' . $ua);
$lockedUntil = get_transient($lockKey);

if (!empty($lockedUntil) && (int)$lockedUntil > time()) {
    header('Location: ' . $redirectErrorRateLimit, true, 302);
    exit;
}

// -------------------------
// Eingaben
// -------------------------
$name      = trim((string)($_POST['name'] ?? ''));
$firma     = trim((string)($_POST['firma'] ?? ''));
$email     = filter_var((string)($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone     = trim((string)($_POST['phone'] ?? ''));
$betreff   = trim((string)($_POST['betreff'] ?? ''));
$nachricht = trim((string)($_POST['nachricht'] ?? ''));

if ($name === '' || !$email || $phone === '' || $betreff === '' || $nachricht === '') {
    header('Location: ' . $redirectErrorRequired, true, 302);
    exit;
}

// -------------------------
// SMTP (WP PHPMailer) Hostinger
// -------------------------
add_action('phpmailer_init', function ($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'smtp.hostinger.com';
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = 'office@wendlandwebdesign.de';
    $phpmailer->Password   = 'MGP^Ge@23aZnwHuJH6qB6$Ul'; // <-- EINTRAGEN
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->Port       = 465;
    $phpmailer->CharSet    = 'UTF-8';

    // Debug (nur zum Testen):
    // $phpmailer->SMTPDebug = 2;
});

// -------------------------
// Mail
// -------------------------
$to      = 'wendlandwebdesign@gmail.com';
$subject = 'Neue Kontaktanfrage: ' . $betreff;

$messageHtml =
    "<h2>Neue Kontaktanfrage</h2>" .
    "<p><strong>Name:</strong> " . esc_html($name) . "</p>" .
    "<p><strong>Firma:</strong> " . esc_html($firma) . "</p>" .
    "<p><strong>E-Mail:</strong> " . esc_html((string)$email) . "</p>" .
    "<p><strong>Telefon:</strong> " . esc_html($phone) . "</p>" .
    "<p><strong>Betreff:</strong> " . esc_html($betreff) . "</p>" .
    "<p><strong>Nachricht:</strong><br>" . nl2br(esc_html($nachricht)) . "</p>";

$headers = [
    'Content-Type: text/html; charset=UTF-8',
    'From: Wendland Webdesign <office@wendlandwebdesign.de>',
    'Reply-To: ' . $name . ' <' . $email . '>',
];

$sent = wp_mail($to, $subject, $messageHtml, $headers);

if ($sent) {
    set_transient($lockKey, time() + 30, 30);
}

// Redirect
header('Location: ' . ($sent ? $redirectSuccess : $redirectError), true, 302);
exit;

