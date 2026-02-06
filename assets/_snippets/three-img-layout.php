<div class="three-img-layout-holder">
    <h3 class="mw-small">Das machen wir möglich</h3>
    <div class="three-img-layout mw-small">
        <div class="img-holder reveal">
            <img src="<?php echo esc_url(get_option('das-machen-wir-moeglich-1')); ?>" alt="">
            <p class="light mini-heading">WEBENTWICKLUNG</p>
        </div>
        <div class="img-holder reveal">
            <img src="<?php echo esc_url(get_option('das-machen-wir-moeglich-2')); ?>" alt="">
            <p class="light mini-heading">HOSTING</p>
        </div>
        <div class="img-holder reveal">
            <img src="<?php echo esc_url(get_option('das-machen-wir-moeglich-3')); ?>" alt="">
            <p class="light mini-heading">SEO</p>
        </div>
        <div class="txt-holder reveal">
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