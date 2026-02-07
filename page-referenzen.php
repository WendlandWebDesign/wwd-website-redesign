<?php
get_header();
?>

<?php
    $heroImgSrc = esc_url(get_option('home-img'));
    $heroTxt = "Unsere Kunden";
?>

<main>
    <?php include_once "assets/_snippets/hero.php"; ?>

    <div class="kunden-holder">
        <div class="kunden mw">
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('faecher-home')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
            <a class="kunden-card reveal" href="#">
                <img src="<?php echo esc_url(get_option('home-img')); ?>" alt="">
                <p class="light" href="">privatbrauerei-laase.de</p>
            </a>
        </div>
    </div>


    <div class="die-nächsten-holder">
        <div class="img-transition-top"></div>
        <div class="die-nächsten-inner mw">
            <h3 class="light">Sind Sie die Nächsten?</h3>
            <button onclick="window.location.href='<?php echo esc_url( home_url( '/kontakt/' ) ); ?>'" class="btn light">
                <span class="btn__border" aria-hidden="true">
                    <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                    </svg>
                </span>
                <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Jetzt Kontakt aufnehmen!</p>
            </button>
        </div>
        <div class="img-transition-bottom"></div>
    </div>


</main>


<?php
get_footer();