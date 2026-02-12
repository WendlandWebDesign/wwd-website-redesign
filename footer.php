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
                <li><a class="light" href="">Home</a></li>
                <li><a class="light" href="">News</a></li>
                <li><a class="light" href="">Über uns</a></li>
                <li><a class="ac" href="">Kontakt</a></li>
            </ul>
            <ul>
                <li><p class="default">Leistungen</p></li>
                <li><a class="light" href="">Referenzen</a></li>
                <li><a class="light" href="">Webentwicklung</a></li>
                <li><a class="light" href="">KI-Integration</a></li>
                <li><a class="ac" href="">kostenloser Websitecheck</a></li>
            </ul>
            <div class="socials">
                <?php echo wwd_inline_svg( 'facebook.svg', array( 'class' => 'social', 'aria_hidden' => true ) ); ?>
                <?php echo wwd_inline_svg( 'x.svg', array( 'class' => 'social', 'aria_hidden' => true ) ); ?>
                <?php echo wwd_inline_svg( 'instagram.svg', array( 'class' => 'social', 'aria_hidden' => true ) ); ?>
            </div>
        </div>
        <div class="bottom">
            <ul>
                <li><a class="default" href="">Cookies</a></li>
                <li><a class="default" href="">Datenschutzerklärung</a></li>
                <li><a class="default" href="">Impressum</a></li>
            </ul>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
