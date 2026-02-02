<?php
get_header();
?>


<?php
$seitenbilder = get_option( 'wwd_seitenbilder', array() );
$home_id = isset( $seitenbilder['home'] ) ? absint( $seitenbilder['home'] ) : 0;
if ( ! $home_id ) {
    $legacy_url = get_option( 'home' );
    $home_id = $legacy_url ? absint( attachment_url_to_postid( $legacy_url ) ) : 0;
}
?>

<div class="home-hero">
    <?php
    if ( $home_id ) {
        echo wp_get_attachment_image( $home_id, 'full', false, array( 'class' => 'hero-img' ) );
    }
    ?>
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
