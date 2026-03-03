<?php
/**
 * Footer Template
 */
?>

<footer>
    <div class="footer-holder mw-small">
        <div class="top">
            <ul>
                <li><p class="default">Unternehmen</p></li>
                <li><a class="light" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                <li><a class="light" href="<?php echo esc_url( home_url( '/news/' ) ); ?>">News</a></li>
        <!--    <li><a class="light" href="<?php echo esc_url( home_url( '/ueber-uns/' ) ); ?>">Über uns</a></li>    -->
                <li><a class="ac" href="<?php echo esc_url( home_url( '/kontakt/' ) ); ?>">Kontakt</a></li>
            </ul>
            <ul>
                <li><p class="default">Leistungen</p></li>
                <li><a class="light" href="<?php echo esc_url( home_url( '/referenzen/' ) ); ?>">Referenzen</a></li>
                <li><a class="light" href="<?php echo esc_url( home_url( '/dienstleistungen/' ) ); ?>">Webentwicklung</a></li>
                <li><a class="light" href="<?php echo esc_url( home_url( '/ki-integration/' ) ); ?>">KI-Integration</a></li>
                <li><a class="ac" href="<?php echo esc_url( home_url( '/website-check/' ) ); ?>">kostenloser Websitecheck</a></li>
            </ul>
            <div class="socials">
                <div class="social-holder" onclick="window.open('https://x.com/Wendland_Design')"><?php echo wwd_inline_svg( 'x.svg', array( 'class' => 'social', 'aria_hidden' => true ) ); ?></div>
                <div class="social-holder" onclick="window.open('https://www.instagram.com/wendland_web_design/')"><?php echo wwd_inline_svg( 'instagram.svg', array( 'class' => 'social', 'aria_hidden' => true ) ); ?></div>
            </div>
        </div>
        <div class="bottom">
            <ul>
				<li><a class="default" href="<?php echo esc_url( home_url( '/impressum/' ) ); ?>">Impressum</a></li>
                <li><a class="default" href="<?php echo esc_url( home_url( '/datenschutzerklaerung/' ) ); ?>">Datenschutzerklärung</a></li>
				<li class="cookies"><?php echo do_shortcode('[rcb-consent type="change" tag="a" text="Cookies"]'); ?></li>
            </ul>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
