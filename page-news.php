<?php
get_header();
?>

<main>
    <?php
        $heroImgSrc = esc_url(get_option('news'));
        $heroTxt = "News";

    ?>

    <?php include_once "assets/_snippets/hero.php" ?>
<div class="news-holder mw">
    <div class="news-sammlung">
    <?php
        $news_query = wwd_get_news_query();
        if ( $news_query->have_posts() ) :
            while ( $news_query->have_posts() ) :
                $news_query->the_post();
                ?>
                <div class="nav-card nav-card--clickable js-right-card" onclick="window.location.href='<?php echo esc_url( get_permalink() ); ?>';" role="link" tabindex="0">
                    <div class="card-img-holder">
                        <?php
                        if ( has_post_thumbnail() ) {
                            echo wp_get_attachment_image( get_post_thumbnail_id(), 'medium', false, array( 'class' => 'card-img' ) );
                        } else {
                            ?>
                            <div class="nav-card__placeholder"></div>
                            <?php
                        }
                        ?>
                    </div>
                    <p class="light nav-card__text-row"><?php echo esc_html( get_the_title() ); ?> <?php echo wwd_inline_svg( 'corner-arrow-light.svg', array( 'class' => 'corner-arrow', 'aria_hidden' => true ) ); ?></p>
                    <?php $excerpt = get_the_excerpt(); ?>
                    <?php if ( ! empty( $excerpt ) ) : ?>
                        <?php $trimmed_excerpt = wp_trim_words( $excerpt, 30, '...' ); ?>
                        <p class="news-card__excerpt"><?php echo esc_html( $trimmed_excerpt ); ?></p>
                    <?php endif; ?>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        endif;
    ?>
</div>
</main>
<?php
get_footer();


