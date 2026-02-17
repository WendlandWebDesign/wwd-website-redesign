<?php
get_header();
?>


<?php
    $heroImgSrc = esc_url(get_option('kontakt'));
    $heroTxt = "Kontakt";
?>

<main>

    <?php include_once "assets/_snippets/hero.php"; ?>


    <div class="contact-holder">
        <div class="contact mw-small">
            <div class="contact-info">
                <h4>ErzÃ¤hlen Sie uns<br>von Ihrem <span>Projekt.</span></h4>
                <div class="phone" onclick="window.location.href='tel:+4915238976827'">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/phone-thin-blue.svg' ); ?>" alt="">
                    <p>0152 389 768 27</p>
                </div>
                <div class="phone">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/mail-thick-blue.svg' ); ?>" alt="">
                    <p>office@wenlandwebdesign.de</p>
                </div>
            </div>
            <form id="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="contact-form" method="post" data-form="contact">
                <input type="hidden" name="action" value="wwd_send_mail_contact">
                <?php wp_nonce_field('wwd_contact_form', 'wwd_nonce'); ?>
                <input type="hidden" name="form_ts" value="<?php echo time(); ?>">
                <div style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden;">
                    <label for="website">Website</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="form-row">
                    <input type="text" name="name" id="name" class="light" required>
                    <label class="default" for="name">Name</label>
                </div>

                <div class="form-row">
                    <input type="text" name="firma" id="firma" class="light">
                    <label class="default" for="firma">Firma</label>
                </div>

                <div class="form-row">
                    <input type="email" name="email" id="email" class="light" required>
                    <label class="default" for="email">Email</label>
                </div>

                <div class="form-row">
                    <input type="text" name="phone" id="phone" class="light">
                    <label class="default" for="phone">Telefon</label>
                </div>

                <div class="form-row">
                    <input type="text" name="betreff" id="betreff" class="light" required>
                    <label class="default" for="betreff">Betreff</label>
                </div>

                <div class="form-row">
                    <textarea name="nachricht" id="nachricht" class="light"></textarea>
                    <label class="default" for="nachricht">Nachricht</label>
                </div>
                <div class="form-row">
                    <input type="checkbox" class="light" required>
                    <p class="light">Ich bin mit den <a href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzbestimmungen</a> , der Verwendung meiner Daten zur Verarbeitung meiner Anfrage und der Zusendung weiterer Informationen per E-Mail einverstanden.</p>
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
                <p id="contact-form-message" class="form-feedback-message default" data-contact-form-message hidden></p>
            </form>
            <div id="contact-form-success" class="form-success-message default" data-contact-form-success hidden>Formular wurde erfolgreich versendet</div>
        </div>
    </div>

</main>

<?php
get_footer();

