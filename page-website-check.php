<?php
get_header();
?>


<?php
$heroImgSrc = esc_url(get_option('home-img'));
$heroTxt = "Website-Check";
?>

<main>
    <?php include_once "assets/_snippets/hero.php"; ?>


    <div class="contact-holder">
        <div class="contact mw-small wc-form">
            <div class="contact-info">
                <h4>Jetzt kostenlosen <br><span>Websitecheck</span><br>anfordern!</h4>
            </div>
            <form action="" class="contact-form" method="post">
                <div class="form-row">
                    <input class="light" type="text" id="domain" name="domain">
                    <label class="light" for="domain">Ihre Domain</label>
                </div>
                <div class="form-row">
                    <input class="light" type="email" id="wc-email" name="wc-email">
                    <label class="light" for="wc-email">Email</label>
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
            </form>
        </div>
    </div>

</main>


<?php
get_footer();