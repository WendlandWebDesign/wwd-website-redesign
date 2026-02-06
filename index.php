<?php
get_header();
?>



<div class="home-hero">

    <img class="hero-img" src="<?php echo esc_url(get_option('home-img')); ?>" alt="hero image">
    <div class="home-hero-inner mw">
        <h2 class="light">Kreativität trifft<br>Technologie</h2>
        <h1 class="light"><span>W</span>endland <span>W</span>eb <span>D</span>esign</h1>
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
            <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>kostenloses Erstgespräch</p>
        </button>
    </div>
    <img class="hero-fächer" src="<?php echo esc_url(get_option('faecher-home')); ?>" alt="website-faecher">
    <div class="img-transition-bottom"></div>
</div>

<div class="two-img-layout-holder">
    <h3>Unser Ansatz</h3>
    <div class="two-img-layout mw-small">
        <div class="img-holder">
            <img src="<?php echo esc_url(get_option('ansatz-1')); ?>" alt="">
            <img src="<?php echo esc_url(get_option('ansatz-2')); ?>" alt="">
        </div>
        <div class="txt-holder">
            <p class="light mini-heading">Digital. Durchdacht.</p>
            <p class="light">Wir sind eine Webagentur und begleiten Projekte von der ersten Zeile Code bis zur Sichtbarkeit bei Google. Wir entwickeln moderne Websites, sorgen für stabiles Hosting im Hintergrund und optimieren alles so, dass deine Seite nicht nur läuft, sondern gefunden wird.</p>
        </div>
    </div>
</div>


<div class="three-img-layout-holder">
    <h3>Das machen wir möglich</h3>
    <div class="three-img-layout mw-small">
        <div class="img-holder">
            <img src="<?php echo esc_url(get_option('ansatz-1')); ?>" alt="">
            <p class="light mini-heading">WEBENTWICKLUNG</p>
        </div>
        <div class="img-holder">
            <img src="<?php echo esc_url(get_option('ansatz-1')); ?>" alt="">
            <p class="light mini-heading">HOSTING</p>
        </div>
        <div class="img-holder">
            <img src="<?php echo esc_url(get_option('ansatz-1')); ?>" alt="">
            <p class="light mini-heading">SEO</p>
        </div>
        <div class="txt-holder">
            <p class="light mini-heading">Kleine Überschrift blabla bi bapo</p>
            <p class="light">Ich gestalte moderne, schnelle und benutzerfreundliche Websites, die Marken sichtbar machen und Kunden überzeugen. </p>
            <button onclick="window.location.href='<?php echo esc_url( home_url( '/dienstleistungen/' ) ); ?>'" class="btn light">
                <span class="btn__border" aria-hidden="true">
                    <svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
                        <path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                        <path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
                    </svg>
                </span>
                <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Entdecke unser Angebot</p>
            </button>
        </div>
    </div>
</div>




<?php
get_footer();



