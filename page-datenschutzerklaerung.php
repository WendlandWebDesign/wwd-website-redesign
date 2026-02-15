<?php
get_header();
?>
<main>
    <?php
    $datenschutz_query = new WP_Query(
        array(
            'post_type'      => 'dsgvo',
            'post_status'    => 'publish',
            'name'           => 'datenschutzerklaerung',
            'posts_per_page' => 1,
            'no_found_rows'  => true,
        )
    );

    if ( $datenschutz_query->have_posts() ) :
        while ( $datenschutz_query->have_posts() ) :
            $datenschutz_query->the_post();
            ?>

            <div class="dsgvo-holder light">
                <div class="datenschutz-content mw">
                <?php the_content(); ?>
                </div>
            </div>

            <?php
        endwhile;
    endif;

    wp_reset_postdata();
    ?>
</main>
<?php
get_footer();
