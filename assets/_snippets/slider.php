<?php
$layout_id = isset( $post_id ) ? (int) $post_id : get_the_ID();
if ( ! $layout_id ) {
	return;
}

$slides = array();

if ( isset( $layout_data ) && is_array( $layout_data ) && isset( $layout_data['slides'] ) && is_array( $layout_data['slides'] ) ) {
	foreach ( array_slice( $layout_data['slides'], 0, 3 ) as $slide ) {
		if ( ! is_array( $slide ) ) {
			continue;
		}

		$url     = isset( $slide['image_url'] ) ? (string) $slide['image_url'] : '';
		$alt     = isset( $slide['image_alt'] ) ? (string) $slide['image_alt'] : '';
		$heading = isset( $slide['heading'] ) ? (string) $slide['heading'] : '';
		$text    = isset( $slide['text'] ) ? (string) $slide['text'] : '';
		$cta_url = isset( $slide['cta_url'] ) ? (string) $slide['cta_url'] : '';
		$cta_lbl = isset( $slide['cta_label'] ) ? (string) $slide['cta_label'] : 'Zum Projekt';

		if ( '' === $url || '' === $heading || '' === $text ) {
			continue;
		}
		if ( '' === $alt ) {
			$alt = $heading;
		}
		if ( '' === $cta_url ) {
			$cta_url = home_url( '/kontakt/' );
		}

		$slides[] = array(
			'url'       => $url,
			'alt'       => $alt,
			'heading'   => $heading,
			'text'      => $text,
			'cta_url'   => $cta_url,
			'cta_label' => $cta_lbl,
		);
	}
} else {
	for ( $i = 1; $i <= 3; $i++ ) {
		$img_id  = absint( get_post_meta( $layout_id, "_slider_slide_{$i}_image", true ) );
		$heading = get_post_meta( $layout_id, "_slider_slide_{$i}_heading", true );
		$text    = get_post_meta( $layout_id, "_slider_slide_{$i}_text", true );

		$url = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
		$alt = $img_id ? get_post_meta( $img_id, '_wp_attachment_image_alt', true ) : '';

		if ( $img_id <= 0 || '' === $heading || '' === $text || ! $url ) {
			return;
		}

		$slides[] = array(
			'url'       => $url,
			'alt'       => $alt,
			'heading'   => $heading,
			'text'      => $text,
			'cta_url'   => home_url( '/kontakt/' ),
			'cta_label' => 'Zum Projekt',
		);
	}
}

if ( 3 !== count( $slides ) ) {
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
							<img src="<?php echo esc_url( $slide['url'] ); ?>" alt="<?php echo esc_attr( $slide['alt'] ); ?>">
						</div>
						<div class="right">
							<div class="txt-holder">
								<p class="light slider-heading"><?php echo esc_html( $slide['heading'] ); ?></p>
								<p class="light"><?php echo esc_html( $slide['text'] ); ?></p>
								<button onclick="window.location.href='<?php echo esc_url( $slide['cta_url'] ); ?>'" class="btn light" data-slide-snake-btn="1">
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
								</button>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
