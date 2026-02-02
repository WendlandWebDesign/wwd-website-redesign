<?php
get_header();
?>


<div class="home-hero">
    <img class="hero-img" src="<?php echo esc_url(get_option('home')); ?>" alt="">
    <div class="home-hero-inner mw">
        <h2 class="light">Kreativität trifft<br>Technologie</h2>
        <h1 class="light"><span>W</span>endland <span>W</span>eb <span>D</span>esign</h1>
        <button onclick="window.location.href='<?php echo esc_url( home_url( '/kontakt/' ) ); ?>'" class="btn light">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>kostenloses Erstgespräch vereinbaren!</p>
        </button>
    </div>
</div>






<?php
get_footer();