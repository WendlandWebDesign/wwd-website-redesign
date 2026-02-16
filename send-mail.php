<?php
declare(strict_types=1);

require_once dirname(__FILE__, 4) . '/wp-load.php';

if (function_exists('wwd_contact_form_log')) {
    wwd_contact_form_log('legacy send-mail.php reached', array('method' => $_SERVER['REQUEST_METHOD'] ?? ''));
}

if (function_exists('wwd_handle_send_mail_contact')) {
    wwd_handle_send_mail_contact();
}

wp_safe_redirect(home_url('/kontakt/?sent=0'));
exit;