<?php
get_header();
?>

<main class="single">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();

            if ( has_post_thumbnail() ) {
                $heroimgsrc = get_the_post_thumbnail_url( get_the_ID(), 'full' );
            } else {
                $heroimgsrc = '';
            }

            $herotxt = get_the_title();
            include_once get_template_directory() . '/assets/_snippets/hero.php';
            ?>
            <div class="single-content light">
                <p class="light">
                    <?php the_content(); ?>
                </p>
            </div>
            <?php
        endwhile;
    endif;
    ?>

</main>


<?php
get_footer();
