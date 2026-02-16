<?php
get_header();
?>
<main>
    <?php
    $impressum_query = new WP_Query(
        array(
            'post_type'      => 'dsgvo',
            'post_status'    => 'publish',
            'name'           => 'impressum',
            'posts_per_page' => 1,
            'no_found_rows'  => true,
        )
    );

    if ( $impressum_query->have_posts() ) :
        while ( $impressum_query->have_posts() ) :
            $impressum_query->the_post();
            ?>

            <div class="dsgvo-holder light">
                <div class="impressum-content-holder mw">
                    <div class="impressum-content">
                        <?php the_content(); ?>
                    </div>
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
