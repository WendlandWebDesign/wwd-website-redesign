<?php
get_header();
?>


<?php
    $heroImgSrc = esc_url(get_option('kontakt'));
    $heroTxt = "Kontakt";
    $formError = '';
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $ua = (string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
    $lockKey = 'mail_lock_' . md5($ip . '|' . $ua);
    $lockedUntil = (int) get_transient($lockKey);
    $lockRemaining = max(0, $lockedUntil - time());
    $mailSent = false;
    $requiredFieldKeys = ['name', 'email', 'phone', 'betreff', 'nachricht', 'privacy'];
    $invalidFields = [];
    $sentParam = isset($_GET['sent']) ? sanitize_text_field(wp_unslash((string)$_GET['sent'])) : '';
    $successToken = isset($_GET['success_token']) ? sanitize_text_field(wp_unslash((string)$_GET['success_token'])) : '';
    $invalidParam = isset($_GET['invalid']) ? sanitize_text_field(wp_unslash((string)$_GET['invalid'])) : '';
    if ($sentParam === '1' && $successToken !== '') {
        $mailSent = (bool) get_transient('mail_success_' . $successToken);
    }
    if ($invalidParam !== '') {
        foreach (explode(',', $invalidParam) as $fieldName) {
            $fieldName = sanitize_key($fieldName);
            if (in_array($fieldName, $requiredFieldKeys, true) && !in_array($fieldName, $invalidFields, true)) {
                $invalidFields[] = $fieldName;
            }
        }
    }
    $errorParam = isset($_GET['error']) ? sanitize_text_field(wp_unslash((string)$_GET['error'])) : '';
    $invalidClass = static function (string $fieldName) use ($invalidFields): string {
        return in_array($fieldName, $invalidFields, true) ? ' is-invalid' : '';
    };

    if ($errorParam === 'required') {
        $formError = 'Bitte alle Pflichtfelder ausfüllen';
    } elseif ($errorParam === 'rate_limit') {
        $formError = 'Bitte warten Sie 30 Sekunden, bevor Sie erneut senden.';
    }
?>

<main>

    <?php include_once "assets/_snippets/hero.php"; ?>


    <div class="contact-holder">
        <div class="contact mw-small">
            <div class="contact-info">
                <h4>Erzählen Sie uns<br>von Ihrem <span>Projekt.</span></h4>
                <div class="phone" onclick="window.location.href='tel:+4915238976827'">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/phone-thin-blue.svg' ); ?>" alt="">
                    <p>0152 389 768 27</p>
                </div>
                <div class="phone">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/mail-thick-blue.svg' ); ?>" alt="">
                    <p>office@wenlandwebdesign.de</p>
                </div>
            </div>
            <?php if (! $mailSent) : ?>
                <form action="<?php echo esc_url( get_stylesheet_directory_uri() . '/send-mail.php' ); ?>" class="contact-form" method="post">
                    <?php wp_nonce_field('wwd_contact_form', 'wwd_nonce'); ?>
                    <input type="hidden" name="form_ts" value="<?php echo time(); ?>">
                    <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;">
                        <label for="website">Website</label>
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row">
                        <input type="text" name="name" id="name" class="light" required>
                        <label class="default<?php echo esc_attr($invalidClass('name')); ?>" for="name">Name</label>
                    </div>

                    <div class="form-row">
                        <input type="text" name="firma" id="firma" class="light">
                        <label class="default" for="firma">Firma</label>
                    </div>

                    <div class="form-row">
                        <input type="email" name="email" id="email" class="light" required>
                        <label class="default<?php echo esc_attr($invalidClass('email')); ?>" for="email">Email</label>
                    </div>

                    <div class="form-row">
                        <input type="text" name="phone" id="phone" class="light">
                        <label class="default<?php echo esc_attr($invalidClass('phone')); ?>" for="phone">Telefon</label>
                    </div>

                    <div class="form-row">
                        <input type="text" name="betreff" id="betreff" class="light" required>
                        <label class="default<?php echo esc_attr($invalidClass('betreff')); ?>" for="betreff">Betreff</label>
                    </div>

                    <div class="form-row">
                        <textarea name="nachricht" id="nachricht" class="light" required></textarea>
                        <label class="default<?php echo esc_attr($invalidClass('nachricht')); ?>" for="nachricht">Nachricht</label>
                    </div>
                    <div class="form-row checkbox-row">
                        <input
                            type="checkbox"
                            name="privacy"
                            id="privacy"
                            value="1"
                            class="light<?php echo esc_attr($invalidClass('privacy')); ?>"
                            required
                        >
                        <label class="light" for="privacy">Ich bin mit den <a href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzbestimmungen</a> , der Verwendung meiner Daten zur Verarbeitung meiner Anfrage und der Zusendung weiterer Informationen per E-Mail einverstanden.</label>
                    </div>

                    <!-- WICHTIG: kein onclick-redirect, sondern submit -->
                    <button type="submit" class="btn light" data-hero-snake-load="1">
                        <span class="btn__border" aria-hidden="true">
                            <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                                <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                            </svg>
                        </span>
                        <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Absenden</p>
                    </button>
                    <p id="contact-form-message" class="contact-form-message light" aria-live="polite"><?php echo $formError !== '' ? esc_html($formError) : ''; ?></p>
                    <?php if ($lockRemaining > 0) : ?>
                        <div id="mail-lock" data-remaining="<?php echo esc_attr((string) $lockRemaining); ?>" hidden></div>
                    <?php endif; ?>
                </form>
            <?php else : ?>
                <div class="contact-form form-success-message light" aria-live="polite">
                    <p>Vielen Dank! Ihre Nachricht wurde erfolgreich versendet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>

<?php
get_footer();
