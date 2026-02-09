<?php
$slider_query = new WP_Query(
	array(
		'post_type'      => 'slider_slide',
		'posts_per_page' => 3,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( $slider_query->have_posts() ) :
	$slides = $slider_query->posts;
	?>
	<div class="slider-holder">
		<div class="slider-bar mw-small">
			<?php foreach ( $slides as $post ) : ?>
				<?php setup_postdata( $post ); ?>
				<p class="indikator mini-heading"><?php echo esc_html( get_the_title() ); ?></p>
			<?php endforeach; ?>
		</div>
		<div class="slider-buttons">
			<div class="slider-btn prev">
				<?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>
			</div>
			<div class="slider-btn next">
				<?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>
			</div>
		</div>
		<div class="slider-wrapper">
			<div class="img-transition-top"></div>
			<div class="slider">
				<?php foreach ( $slides as $post ) : ?>
					<?php
					setup_postdata( $post );
					$thumb_id = get_post_thumbnail_id();
					$img_url  = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'full' ) : '';
					$alt      = $thumb_id ? get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) : '';
					$excerpt  = get_the_excerpt();
					?>
					<div class="slide">
						<div class="left">
							<div class="img-transition-bottom"></div>
							<div class="img-transition-right"></div>
							<?php if ( $img_url ) : ?>
								<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
							<?php endif; ?>
						</div>
						<div class="right">
							<div class="txt-holder">
								<p class="light slider-heading"><?php echo esc_html( get_the_title() ); ?></p>
								<?php if ( $excerpt ) : ?>
									<p class="light"><?php echo esc_html( $excerpt ); ?></p>
								<?php endif; ?>
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
									<p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>Zum Projekt</p>
								</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="img-transition-bottom"></div>
		</div>
	</div>
	<?php
	wp_reset_postdata();
elseif ( current_user_can( 'edit_posts' ) ) :
	?>
	<p class="mw-small"><?php echo esc_html( 'Keine Slides gefunden. Bitte Einträge im CPT "Slider" anlegen.' ); ?></p>
	<?php
endif;
