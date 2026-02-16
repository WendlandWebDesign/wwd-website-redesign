<?php
get_header();
?>


<?php
$heroImgSrc = esc_url(get_option('home-img'));
$heroTxt = "Website-Check";

$websiteCheckUrl = get_permalink();
if (!is_string($websiteCheckUrl) || $websiteCheckUrl === '') {
    $websiteCheckUrl = home_url('/website-check/');
}

$wcFormError = '';
$wcMailSent = false;
$wcInvalidFields = [];
$wcAllowedInvalidFields = ['domain', 'wc_email', 'privacy'];

$wcIp = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$wcUa = (string)($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
$wcLockKey = 'website_check_mail_lock_' . md5($wcIp . '|' . $wcUa);
$wcLockedUntil = (int) get_transient($wcLockKey);
$wcLockRemaining = max(0, $wcLockedUntil - time());

$wcSentParam = isset($_GET['sent']) ? sanitize_text_field(wp_unslash((string)$_GET['sent'])) : '';
$wcSuccessToken = isset($_GET['success_token']) ? sanitize_text_field(wp_unslash((string)$_GET['success_token'])) : '';
$wcErrorParam = isset($_GET['error']) ? sanitize_text_field(wp_unslash((string)$_GET['error'])) : '';
$wcInvalidParam = isset($_GET['invalid']) ? sanitize_text_field(wp_unslash((string)$_GET['invalid'])) : '';

if ($wcSentParam === '1' && $wcSuccessToken !== '') {
    $wcMailSent = (bool) get_transient('website_check_mail_success_' . $wcSuccessToken);
}

if ($wcInvalidParam !== '') {
    foreach (explode(',', $wcInvalidParam) as $wcFieldName) {
        $wcFieldName = sanitize_key($wcFieldName);
        if (in_array($wcFieldName, $wcAllowedInvalidFields, true) && !in_array($wcFieldName, $wcInvalidFields, true)) {
            $wcInvalidFields[] = $wcFieldName;
        }
    }
}

if ($wcErrorParam === 'required') {
    $wcFormError = 'Bitte alle Pflichtfelder ausfüllen';
} elseif ($wcErrorParam === 'rate_limit') {
    $wcFormError = 'Bitte warten Sie 30 Sekunden, bevor Sie erneut senden.';
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['website_check_nonce'])) {
    $wcNonce = (string)($_POST['website_check_nonce'] ?? '');
    if ($wcNonce === '' || !wp_verify_nonce($wcNonce, 'website_check_form')) {
        wp_safe_redirect(add_query_arg(['sent' => '0'], $websiteCheckUrl));
        exit;
    }

    if ($wcLockRemaining > 0) {
        wp_safe_redirect(add_query_arg(['sent' => '0', 'error' => 'rate_limit'], $websiteCheckUrl));
        exit;
    }

    $wcDomain = trim((string)($_POST['domain'] ?? ''));
    $wcEmailRaw = trim((string)($_POST['wc-email'] ?? ''));
    $wcEmail = filter_var($wcEmailRaw, FILTER_VALIDATE_EMAIL);
    $wcPrivacy = (string)($_POST['privacy'] ?? '');

    $wcSubmitInvalidFields = [];
    if ($wcDomain === '') {
        $wcSubmitInvalidFields[] = 'domain';
    }
    if (!$wcEmail) {
        $wcSubmitInvalidFields[] = 'wc_email';
    }
    if (empty($wcPrivacy)) {
        $wcSubmitInvalidFields[] = 'privacy';
    }

    if (!empty($wcSubmitInvalidFields)) {
        wp_safe_redirect(
            add_query_arg(
                [
                    'sent' => '0',
                    'error' => 'required',
                    'invalid' => implode(',', $wcSubmitInvalidFields),
                ],
                $websiteCheckUrl
            )
        );
        exit;
    }

    add_action('phpmailer_init', function ($phpmailer) {
        $phpmailer->isSMTP();
        $phpmailer->Host       = 'smtp.hostinger.com';
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Username   = 'office@wendlandwebdesign.de';
        $phpmailer->Password   = 'MGP^Ge@23aZnwHuJH6qB6$Ul';
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->Port       = 465;
        $phpmailer->CharSet    = 'UTF-8';
    });

    $wcMailTo = 'office@wendlandwebdesign.de';
    $wcMailSubject = 'Neue Website-Check Anfrage';
    $wcMessageHtml =
        "<h2>Neue Website-Check Anfrage</h2>" .
        "<p><strong>Domain:</strong> " . esc_html($wcDomain) . "</p>" .
        "<p><strong>E-Mail:</strong> " . esc_html((string)$wcEmail) . "</p>";

    $wcHeaders = [
        'Content-Type: text/html; charset=UTF-8',
        'From: Wendland Webdesign <office@wendlandwebdesign.de>',
        'Reply-To: <' . $wcEmail . '>',
    ];

    $wcSent = wp_mail($wcMailTo, $wcMailSubject, $wcMessageHtml, $wcHeaders);

    if ($wcSent) {
        set_transient($wcLockKey, time() + 30, 30);
        $wcSuccessTokenNew = wp_generate_password(20, false, false);
        set_transient('website_check_mail_success_' . $wcSuccessTokenNew, 1, 300);
        wp_safe_redirect(
            add_query_arg(
                [
                    'sent' => '1',
                    'success_token' => $wcSuccessTokenNew,
                ],
                $websiteCheckUrl
            )
        );
        exit;
    }

    wp_safe_redirect(add_query_arg(['sent' => '0'], $websiteCheckUrl));
    exit;
}

$wcInvalidClass = static function (string $wcFieldName) use ($wcInvalidFields): string {
    return in_array($wcFieldName, $wcInvalidFields, true) ? ' is-invalid' : '';
};
?>

<main>
    <?php include_once "assets/_snippets/hero.php"; ?>

    <?php
    $allowed_layouts = function_exists( 'wwd_get_allowed_layouts' ) ? wwd_get_allowed_layouts() : array();
    $default_layout  = 'two-img-layout';
    $first_post_id   = 0;

    $render_website_check_post = static function( $post_id ) use ( $allowed_layouts, $default_layout ) {
        $layout = get_post_meta( $post_id, '_layout_template', true );
        if ( empty( $layout ) || ! isset( $allowed_layouts[ $layout ] ) ) {
            $layout = $default_layout;
        }

        $meta = array(
            'mini_heading' => get_post_meta( $post_id, '_section_mini_heading', true ),
            'headline'     => get_post_meta( $post_id, '_section_headline', true ),
            'text'         => get_post_meta( $post_id, '_section_text', true ),
            'cta_label'    => get_post_meta( $post_id, '_cta_label', true ),
            'cta_url'      => get_post_meta( $post_id, '_cta_url', true ),
            'img_1_id'     => absint( get_post_meta( $post_id, '_img_1_id', true ) ),
            'img_2_id'     => absint( get_post_meta( $post_id, '_img_2_id', true ) ),
            'img_3_id'     => absint( get_post_meta( $post_id, '_img_3_id', true ) ),
        );

        if ( empty( $allowed_layouts[ $default_layout ] ) ) {
            return;
        }

        $snippet_rel_path = isset( $allowed_layouts[ $layout ] ) ? $allowed_layouts[ $layout ] : $allowed_layouts[ $default_layout ];
        include get_template_directory() . '/' . $snippet_rel_path;
    };

    $first_query = new WP_Query(
        array(
            'post_type'      => 'website_check',
            'posts_per_page' => 1,
            'orderby'        => array(
                'menu_order' => 'ASC',
                'date'       => 'DESC',
            ),
            'no_found_rows'  => true,
        )
    );

    if ( $first_query->have_posts() ) :
        while ( $first_query->have_posts() ) :
            $first_query->the_post();
            $first_post_id = get_the_ID();
            $render_website_check_post( $first_post_id );
        endwhile;
    endif;

    wp_reset_postdata();
    ?>


    <div class="contact-holder">
        <div class="contact mw-small wc-form">
            <div class="contact-info">
                <h4>Jetzt<br>kostenlosen <br><span>Websitecheck</span><br>anfordern!</h4>
            </div>
            <?php if (!$wcMailSent) : ?>
                <form action="<?php echo esc_url($websiteCheckUrl); ?>" class="contact-form" method="post" id="website-check-form">
                    <?php wp_nonce_field('website_check_form', 'website_check_nonce'); ?>
                    <div class="form-row">
                        <input class="light" type="text" id="domain" name="domain" required>
                        <label class="default<?php echo esc_attr($wcInvalidClass('domain')); ?>" for="domain">Ihre Domain</label>
                    </div>
                    <div class="form-row">
                        <input class="light" type="email" id="wc-email" name="wc-email" required>
                        <label class="default<?php echo esc_attr($wcInvalidClass('wc_email')); ?>" for="wc-email">Email</label>
                    </div>
                    <div class="form-row checkbox-row">
                        <input type="checkbox" class="light<?php echo esc_attr($wcInvalidClass('privacy')); ?>" name="privacy" id="website-check-privacy" value="1" required>
                        <label class="light" for="website-check-privacy">Ich bin mit den <a href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzbestimmungen</a> , der Verwendung meiner Daten zur Verarbeitung meiner Anfrage und der Zusendung weiterer Informationen per E-Mail einverstanden.</label>
                    </div>
                    <button type="submit" class="btn light">
                        <span class="btn__border" aria-hidden="true">
                            <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                                <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                                <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                            </svg>
                        </span>
                        <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Anfordern</p>
                    </button>
                    <p id="website-check-form-error" class="contact-form-message light" aria-live="polite"><?php echo $wcFormError !== '' ? esc_html($wcFormError) : ''; ?></p>
                    <?php if ($wcLockRemaining > 0) : ?>
                        <div id="website-check-mail-lock" data-remaining="<?php echo esc_attr((string)$wcLockRemaining); ?>" hidden></div>
                    <?php endif; ?>
                </form>
            <?php else : ?>
                <div class="contact-form form-success-message light" aria-live="polite">
                    <p>Vielen Dank! Ihre Nachricht wurde erfolgreich versendet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    $rest_args = array(
        'post_type'      => 'website_check',
        'posts_per_page' => -1,
        'orderby'        => array(
            'menu_order' => 'ASC',
            'date'       => 'DESC',
        ),
    );

    if ( $first_post_id > 0 ) {
        $rest_args['post__not_in'] = array( $first_post_id );
    }

    $rest_query = new WP_Query( $rest_args );

    if ( $rest_query->have_posts() ) :
        while ( $rest_query->have_posts() ) :
            $rest_query->the_post();
            $render_website_check_post( get_the_ID() );
        endwhile;
    endif;

    wp_reset_postdata();
    ?>

</main>


<?php
get_footer();
