<?php
$hero_img_src = isset( $heroimgsrc ) ? $heroimgsrc : ( isset( $heroImgSrc ) ? $heroImgSrc : '' );
$hero_txt     = isset( $herotxt ) ? $herotxt : ( isset( $heroTxt ) ? $heroTxt : '' );
?>
<div class="hero observe-nav">
    <img src="<?php echo esc_url( $hero_img_src ); ?>" alt="">
     <div class="hero-inner mw">
         <h1 class="light reveal"><?php echo esc_html( $hero_txt ); ?></h1>
     </div>
    <div class="img-transition-bottom"></div>
</div>
