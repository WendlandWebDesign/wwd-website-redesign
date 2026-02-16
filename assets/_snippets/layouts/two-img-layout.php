<?php
if ( ! isset( $data, $post_id ) || ! is_array( $data ) ) {
	return;
}

$headline  = isset( $data['_section_headline'] ) ? (string) $data['_section_headline'] : '';
$mini_head = isset( $data['_section_mini_heading'] ) ? (string) $data['_section_mini_heading'] : '';
$text      = isset( $data['_section_text'] ) ? (string) $data['_section_text'] : '';
$cta_label = isset( $data['_cta_label'] ) ? (string) $data['_cta_label'] : '';
$cta_url   = isset( $data['_cta_url'] ) ? (string) $data['_cta_url'] : '';

$img_1_id = isset( $data['_img_1_id'] ) ? absint( $data['_img_1_id'] ) : 0;
$img_2_id = isset( $data['_img_2_id'] ) ? absint( $data['_img_2_id'] ) : 0;

$img_1_url = $img_1_id ? wp_get_attachment_image_url( $img_1_id, 'large' ) : '';
$img_2_url = $img_2_id ? wp_get_attachment_image_url( $img_2_id, 'large' ) : '';
?>
<div class="two-img-layout-holder">
	<?php if ( '' !== $headline ) : ?>
		<h3 class="mw-small"><?php echo esc_html( $headline ); ?></h3>
	<?php endif; ?>
	<div class="two-img-layout mw-small">
		<?php if ( $img_1_url || $img_2_url ) : ?>
			<div class="img-holder">
				<?php if ( $img_1_url ) : ?>
					<img data-twoimg="img1" src="<?php echo esc_url( $img_1_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $img_1_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ) ); ?>">
				<?php endif; ?>
				<?php if ( $img_2_url ) : ?>
					<img data-twoimg="img2" src="<?php echo esc_url( $img_2_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $img_2_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ) ); ?>">
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( '' !== $text || ( '' !== $cta_label && '' !== $cta_url ) ) : ?>
			<div class="txt-holder reveal">
				<?php if ( '' !== $mini_head ) : ?>
					<p class="mini-heading light"><?php echo esc_html( $mini_head ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $text ) : ?>
					<p class="light"><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $cta_label && '' !== $cta_url ) : ?>
					<button onclick="window.location.href='<?php echo esc_url( $cta_url ); ?>'" class="btn light">
						<span class="btn__border" aria-hidden="true">
							<svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
								<path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
								<path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
								<path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
								<path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
								<path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
							</svg>
						</span>
						<p>
							<?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?>
							<?php echo esc_html( $cta_label ); ?>
						</p>
					</button>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
