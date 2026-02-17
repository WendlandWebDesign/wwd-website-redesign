<?php
get_header();
?>


<?php
	$heroImgSrc = esc_url(get_option('website-check'));
	$heroTxt = "Website-Check";
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
            <div class="form-holder">
                <form action="<?php echo esc_url( home_url('/wc-send-mail.php') ); ?>" class="contact-form" method="post" id="website-check-form" novalidate data-endpoint="<?php echo esc_url( home_url('/wc-send-mail.php') ); ?>">
                    <input type="hidden" name="action" value="website_check_form">

                    <!-- Honeypot -->
                    <div class="hp-field" aria-hidden="true">
                        <label for="wc-website">Website</label>
                        <input type="text" id="wc-website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-row">
                        <input class="light" type="text" id="domain" name="domain" required>
                        <label class="default" for="domain">Ihre Domain</label>
                    </div>

                    <div class="form-row">
                        <input class="light" type="email" id="wc-email" name="wc_email" required>
                        <label class="default" for="wc-email">Email</label>
                    </div>

                    <div class="form-row checkbox-row">
                        <input type="checkbox" class="light" name="privacy" id="website-check-privacy" value="1" required>
                        <p class="light">Ich bin mit den <a href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzbestimmungen</a> , der Verwendung meiner Daten zur Verarbeitung meiner Anfrage und der Zusendung weiterer Informationen per E-Mail einverstanden.</p>
                    </div>

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
                </form>

                <p id="wc-form-message" class="form-message light" style="display:none;"></p>

            </div>
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


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("website-check-form");
            const messageBox = document.getElementById("wc-form-message");
            if (!form || !messageBox) return;

            // --- Cooldown (Reload-sicher) ---
            const COOLDOWN_SECONDS = 30;
            const STORAGE_KEY = "websiteCheckCooldownUntil";
            let countdownInterval = null;

            const nowMs = () => Date.now();
            const readCooldownUntil = () => {
                const v = localStorage.getItem(STORAGE_KEY);
                const t = v ? parseInt(v, 10) : 0;
                return Number.isFinite(t) ? t : 0;
            };
            const setCooldownUntil = (untilMs) => localStorage.setItem(STORAGE_KEY, String(untilMs));
            const clearCooldown = () => localStorage.removeItem(STORAGE_KEY);

            const getSubmitButton = () => form.querySelector("button[type='submit']");

            function clearCountdownTimer() {
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                    countdownInterval = null;
                }
            }

            function showMessage(html) {
                messageBox.style.display = "block";
                messageBox.innerHTML = html;
            }

            function hideMessage() {
                messageBox.style.display = "none";
                messageBox.innerHTML = "";
            }

            function showTimerUnderForm(untilMs) {
                clearCountdownTimer();

                showMessage(`
      <p class="error-message">
        Bitte warten Sie <strong><span id="wc-countdown"></span></strong> Sekunden, bevor Sie erneut senden.
      </p>
    `);

                const countdownEl = document.getElementById("wc-countdown");
                const btn = getSubmitButton();
                if (btn) btn.disabled = true;

                const tick = () => {
                    const msLeft = untilMs - nowMs();
                    const secLeft = Math.max(0, Math.ceil(msLeft / 1000));
                    if (countdownEl) countdownEl.textContent = String(secLeft);

                    if (msLeft <= 0) {
                        clearCountdownTimer();
                        hideMessage();
                        if (btn) btn.disabled = false;
                        clearCooldown();
                    }
                };

                tick();
                countdownInterval = setInterval(tick, 250);
            }

            // --- Active-State für .form-row ---
            const textFields = form.querySelectorAll("input:not([type='checkbox']):not([type='hidden']), textarea");
            textFields.forEach(field => {
                const row = field.closest(".form-row");
                if (!row) return;

                const checkActive = () => {
                    if (field.value.trim() !== "") row.classList.add("active");
                    else row.classList.remove("active");
                };

                field.addEventListener("input", checkActive);
                field.addEventListener("focus", () => row.classList.add("active"));
                field.addEventListener("blur", checkActive);
                checkActive();
            });

            // --- Required + Email validation ---
            const requiredTextFields = form.querySelectorAll("input[required]:not([type='checkbox']), textarea[required]");
            const privacyCheckbox = form.querySelector("input[name='privacy'][required]");
            const emailField = form.querySelector("#wc-email");

            function clearErrors() {
                form.querySelectorAll(".form-row.error").forEach(el => el.classList.remove("error"));
                const cbRow = privacyCheckbox ? privacyCheckbox.closest(".checkbox-row") : null;
                if (cbRow) cbRow.classList.remove("error");
            }

            function isEmailValid(value) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
            }

            function validateAll() {
                clearErrors();

                // 1) Required fields
                let ok = true;
                requiredTextFields.forEach(field => {
                    const row = field.closest(".form-row");
                    if (!row) return;

                    const empty = field.value.trim() === "";
                    row.classList.toggle("error", empty);
                    if (empty) ok = false;
                });

                // 2) Privacy checkbox
                if (privacyCheckbox) {
                    const cbRow = privacyCheckbox.closest(".checkbox-row");
                    const bad = !privacyCheckbox.checked;
                    if (cbRow) cbRow.classList.toggle("error", bad);
                    if (bad) ok = false;
                }

                if (!ok) {
                    showMessage("<p class='error-message'>Bitte alle Pflichtfelder ausfüllen</p>");
                    return false;
                }

                // 3) Email format (eigene Meldung)
                if (emailField) {
                    const val = emailField.value.trim();
                    const row = emailField.closest(".form-row");
                    if (!isEmailValid(val)) {
                        if (row) row.classList.add("error");
                        showMessage("<p class='error-message'>Bitte geben Sie eine gültige Email-Adresse an</p>");
                        return false;
                    }
                }

                hideMessage();
                return true;
            }

            // Fehlerzustände beim Tippen entfernen
            requiredTextFields.forEach(field => {
                field.addEventListener("input", () => {
                    const row = field.closest(".form-row");
                    if (row && field.value.trim() !== "") row.classList.remove("error");
                });
            });

            if (privacyCheckbox) {
                privacyCheckbox.addEventListener("change", () => {
                    const cbRow = privacyCheckbox.closest(".checkbox-row");
                    if (cbRow && privacyCheckbox.checked) cbRow.classList.remove("error");
                });
            }

            if (emailField) {
                emailField.addEventListener("input", () => {
                    const row = emailField.closest(".form-row");
                    const val = emailField.value.trim();
                    if (row && isEmailValid(val)) row.classList.remove("error");
                });
            }

            // --- Submit: Cooldown -> Validation -> AJAX ---
            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                const cooldownUntil = readCooldownUntil();
                if (cooldownUntil && cooldownUntil > nowMs()) {
                    showTimerUnderForm(cooldownUntil);
                    return;
                }

                if (!validateAll()) return;

                const btn = getSubmitButton();
                if (btn) btn.disabled = true;

                showMessage("<p>Sende…</p>");

                try {
                    const endpoint = form.dataset.endpoint;
// alternativ dynamisch: const endpoint = window.location.origin + "/wwd-redesign/wc-send-mail.php";

                    const res = await fetch(endpoint, {
                        method: "POST",
                        body: new FormData(form),
                        credentials: "same-origin",
                        cache: "no-store"
                    });


                    const text = await res.text();

// TEMP: Response sichtbar machen (damit du sofort siehst was kommt)
                    console.log("wc-send-mail response status:", res.status);
                    console.log("wc-send-mail response text:", text);

                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (err) {
                        showMessage("<p class='error-message'>Server-Antwort ist kein JSON:<br><small style=\"word-break:break-word;\">" +
                            text.replace(/</g,"&lt;").slice(0, 300) +
                            "</small></p>");
                        if (btn) btn.disabled = false;
                        return;
                    }


                    if (data.status === "success") {
                        setCooldownUntil(nowMs() + COOLDOWN_SECONDS * 1000);

                        form.style.display = "none";
                        showMessage("<p class='success-message'>Ihre Nachricht wurde versendet</p>");
                    } else {
                        showMessage("<p class='error-message'>" + (data.message || "Fehler") + "</p>");
                        if (btn) btn.disabled = false;
                    }

                } catch (err) {
                    console.error(err);
                    showMessage("<p class='error-message'>Netzwerkfehler. Bitte erneut versuchen.</p>");
                    if (btn) btn.disabled = false;
                }
            });
        });
    </script>



<?php
get_footer();
