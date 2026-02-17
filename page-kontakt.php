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
            <div class="form-holder">
                <form id="contact-form" action="<?php echo esc_url( home_url('/send-mail.php') ); ?>" method="post" class="contact-form" novalidate>
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
                    <div class="form-row checkbox-row">
                        <input type="checkbox" name="privacy" class="light" required>
                        <p class="light">Ich bin mit den <a href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzbestimmungen</a> , der Verwendung meiner Daten zur Verarbeitung meiner Anfrage und der Zusendung weiterer Informationen per E-Mail einverstanden.</p>
                    </div>

                    <div class="hp-field" aria-hidden="true">
                        <label for="website">Website</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
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
                </form>
                <p class="light" id="form-message" style="display:none;"></p>
            </div>
        </div>
    </div>

</main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("contact-form");
            const messageBox = document.getElementById("form-message");
            if (!form || !messageBox) return;

            // --- Cooldown (Reload-sicher) ---
            const COOLDOWN_SECONDS = 30;
            const STORAGE_KEY = "contactFormCooldownUntil";
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

                // Timer-Meldung unter dem Formular anzeigen (Form bleibt sichtbar!)
                showMessage(`
      <p class="error-message">
        Bitte warten Sie <strong><span id="countdown"></span></strong> Sekunden, bevor Sie erneut senden.
      </p>
    `);

                const countdownEl = document.getElementById("countdown");
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

            // --- Active-State für .form-row (Floating Labels) ---
            const textFields = form.querySelectorAll(
                "input:not([type='checkbox']):not([type='hidden']), textarea"
            );

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
                checkActive(); // wichtig bei Reload/Autofill
            });

            // --- Required-Validierung (Labels rot) ---
            const requiredTextFields = form.querySelectorAll("input[required]:not([type='checkbox']), textarea[required]");
            const privacyCheckbox = form.querySelector("input[name='privacy'][required]");

            function clearErrors() {
                form.querySelectorAll(".form-row.error").forEach(el => el.classList.remove("error"));
                const cbRow = privacyCheckbox ? privacyCheckbox.closest(".checkbox-row") : null;
                if (cbRow) cbRow.classList.remove("error");
            }

            function validateRequired() {
                let ok = true;

                const emailField = form.querySelector("input[type='email'][required]");

                // Alle required Textfelder prüfen
                requiredTextFields.forEach(field => {
                    const row = field.closest(".form-row");
                    if (!row) return;

                    const empty = field.value.trim() === "";

                    row.classList.remove("error");

                    if (empty) {
                        row.classList.add("error");
                        ok = false;
                    }
                });

                // Email zusätzlich auf Format prüfen
                if (emailField) {
                    const row = emailField.closest(".form-row");
                    const emailValue = emailField.value.trim();

                    const emailIsValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue);

                    if (emailValue === "" || !emailIsValid) {
                        if (row) row.classList.add("error");
                        ok = false;

                        showMessage("<p class='error-message'>Bitte geben Sie eine gültige Email-Adresse an</p>");
                        return false; // sofort abbrechen
                    }
                }

                // Checkbox prüfen
                if (privacyCheckbox) {
                    const cbRow = privacyCheckbox.closest(".checkbox-row");
                    if (!privacyCheckbox.checked) {
                        if (cbRow) cbRow.classList.add("error");
                        ok = false;
                    }
                }

                if (!ok) {
                    showMessage("<p class='error-message'>Bitte alle Pflichtfelder ausfüllen</p>");
                }

                return ok;
            }




            // Fehler entfernen sobald der User korrigiert
            requiredTextFields.forEach(field => {
                field.addEventListener("input", () => {
                    const row = field.closest(".form-row");
                    if (row && field.value.trim() !== "") row.classList.remove("error");
                    // Message nur entfernen, wenn sonst nichts mehr fehlt
                    if (messageBox.style.display === "block") {
                        // optional: live re-validate
                    }
                });
            });

            if (privacyCheckbox) {
                privacyCheckbox.addEventListener("change", () => {
                    const cbRow = privacyCheckbox.closest(".checkbox-row");
                    if (cbRow && privacyCheckbox.checked) cbRow.classList.remove("error");
                });
            }

            // --- Submit: Cooldown-Check -> Required-Check -> AJAX ---
            form.addEventListener("submit", async (e) => {
                e.preventDefault();

                // 1) Cooldown: Timer nur anzeigen, wenn man erneut senden will
                const cooldownUntil = readCooldownUntil();
                if (cooldownUntil && cooldownUntil > nowMs()) {
                    showTimerUnderForm(cooldownUntil);
                    return;
                }

                // 2) Frontend required validation
                clearErrors();
                if (!validateRequired()) return;

                // 3) AJAX senden
                const btn = getSubmitButton();
                if (btn) btn.disabled = true;

                showMessage("<p>Sende…</p>");

                try {
                    const res = await fetch(form.action, {
                        method: "POST",
                        body: new FormData(form)
                    });

                    const text = await res.text();
                    let data;
                    try {
                        data = JSON.parse(text);
                    } catch (err) {
                        console.error("Server-Antwort ist kein JSON:", text);
                        showMessage("<p class='error-message'>Serverfehler. Bitte Konsole prüfen.</p>");
                        if (btn) btn.disabled = false;
                        return;
                    }

                    if (data.status === "success") {
                        // Cooldown setzen (Reload-sicher)
                        setCooldownUntil(nowMs() + COOLDOWN_SECONDS * 1000);

                        // Gewünschtes Verhalten: Formular weg, nur Erfolgstext, kein Timer
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

