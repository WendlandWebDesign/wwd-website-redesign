<?php
get_header();
?>


<?php
$heroImgSrc = esc_url(get_option('home-img'));
$heroTxt = "Websitecheck";
?>

<main>
    <?php include_once "assets/_snippets/hero.php"; ?>


</main>


<?php
get_footer();