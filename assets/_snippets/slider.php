<?php
$slides = array();

// Uses WWD_SLIDER_BUTTON_LINK_META_KEY from functions.php as shared backend/frontend key.
$slider_query = new WP_Query(
	array(
		'post_type'      => 'slider_slide',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
	)
);

if ( $slider_query->have_posts() ) {
	while ( $slider_query->have_posts() ) {
		$slider_query->the_post();

		$slide_id = get_the_ID();
		$image    = get_the_post_thumbnail_url( $slide_id, 'full' );
		$thumb_id = get_post_thumbnail_id( $slide_id );
		$alt      = $thumb_id ? get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) : '';
		$heading  = get_the_title( $slide_id );
		$text     = get_the_excerpt( $slide_id );
		$cta_url  = get_post_meta( $slide_id, WWD_SLIDER_BUTTON_LINK_META_KEY, true );

		if ( '' === $text ) {
			$text = get_the_content( null, false, $slide_id );
		}

		$slides[] = array(
			'url'       => $image ? $image : '',
			'alt'       => '' !== $alt ? $alt : ( $heading ? $heading : '' ),
			'heading'   => $heading ? $heading : '',
			'text'      => $text ? $text : '',
			'cta_url'   => $cta_url ? $cta_url : '',
			'cta_label' => 'Zum Projekt',
		);
	}
	wp_reset_postdata();
}

if ( 0 === count( $slides ) ) {
	return;
}
?>
	<div class="slider-holder">
		<div class="slider-bar mw-small">
			<?php foreach ( $slides as $slide ) : ?>
				<p class="indikator mini-heading"><?php echo esc_html( $slide['heading'] ); ?></p>
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
			<div class="slider">
				<?php foreach ( $slides as $slide ) : ?>
					<div class="slide">
						<div class="left">
							<div class="img-transition-bottom"></div>
							<div class="img-transition-right"></div>
							<?php if ( '' !== $slide['url'] ) : ?>
								<img src="<?php echo esc_url( $slide['url'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>">
							<?php endif; ?>
						</div>
						<div class="right">
							<div class="txt-holder">
								<p class="light slider-heading"><?php echo esc_html( $slide['heading'] ); ?></p>
								<?php if ( '' !== $slide['text'] ) : ?>
									<p class="light"><?php echo wp_kses_post( $slide['text'] ); ?></p>
								<?php endif; ?>
								<?php if ( '' !== $slide['cta_url'] ) : ?>
									<a href="<?php echo esc_url( $slide['cta_url'] ); ?>" class="btn light" data-slide-snake-btn="1">
										<span class="btn__border" aria-hidden="true">
											<svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
												<path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
												<path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
												<path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
												<path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
												<path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
											</svg>
										</span>
										<p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?><?php echo esc_html( $slide['cta_label'] ); ?></p>
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
