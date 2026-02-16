<?php
$post_id = isset( $post_id ) ? (int) $post_id : get_the_ID();

$headline  = isset( $meta['headline'] ) ? $meta['headline'] : '';
$mini_head = isset( $meta['mini_heading'] ) ? $meta['mini_heading'] : '';
$text      = isset( $meta['text'] ) ? $meta['text'] : '';
$cta_label = isset( $meta['cta_label'] ) ? $meta['cta_label'] : '';
$cta_url   = isset( $meta['cta_url'] ) ? $meta['cta_url'] : '';

$img_1_id = isset( $meta['img_1_id'] ) ? (int) $meta['img_1_id'] : 0;
$img_2_id = isset( $meta['img_2_id'] ) ? (int) $meta['img_2_id'] : 0;
$img_3_id = isset( $meta['img_3_id'] ) ? (int) $meta['img_3_id'] : 0;

$img_1_url = $img_1_id ? wp_get_attachment_image_url( $img_1_id, 'large' ) : '';
$img_2_url = $img_2_id ? wp_get_attachment_image_url( $img_2_id, 'large' ) : '';
$img_3_url = $img_3_id ? wp_get_attachment_image_url( $img_3_id, 'large' ) : '';
$img_1_alt = $img_1_id ? get_post_meta( $img_1_id, '_wp_attachment_image_alt', true ) : '';
$img_2_alt = $img_2_id ? get_post_meta( $img_2_id, '_wp_attachment_image_alt', true ) : '';
$img_3_alt = $img_3_id ? get_post_meta( $img_3_id, '_wp_attachment_image_alt', true ) : '';

$t1 = get_post_meta( $post_id, 'three_img_text_1', true );
$t2 = get_post_meta( $post_id, 'three_img_text_2', true );
$t3 = get_post_meta( $post_id, 'three_img_text_3', true );

if ( isset( $layout_data ) && is_array( $layout_data ) && ! empty( $layout_data ) ) {
	$headline  = isset( $layout_data['headline'] ) ? (string) $layout_data['headline'] : $headline;
	$mini_head = isset( $layout_data['mini_heading'] ) ? (string) $layout_data['mini_heading'] : $mini_head;
	$text      = isset( $layout_data['text'] ) ? (string) $layout_data['text'] : $text;
	$cta_label = isset( $layout_data['cta_label'] ) ? (string) $layout_data['cta_label'] : $cta_label;
	$cta_url   = isset( $layout_data['cta_url'] ) ? (string) $layout_data['cta_url'] : $cta_url;

	$img_1_url = isset( $layout_data['img_1_url'] ) ? (string) $layout_data['img_1_url'] : $img_1_url;
	$img_2_url = isset( $layout_data['img_2_url'] ) ? (string) $layout_data['img_2_url'] : $img_2_url;
	$img_3_url = isset( $layout_data['img_3_url'] ) ? (string) $layout_data['img_3_url'] : $img_3_url;
	$img_1_alt = isset( $layout_data['img_1_alt'] ) ? (string) $layout_data['img_1_alt'] : $img_1_alt;
	$img_2_alt = isset( $layout_data['img_2_alt'] ) ? (string) $layout_data['img_2_alt'] : $img_2_alt;
	$img_3_alt = isset( $layout_data['img_3_alt'] ) ? (string) $layout_data['img_3_alt'] : $img_3_alt;

	$t1 = isset( $layout_data['img_1_text'] ) ? (string) $layout_data['img_1_text'] : $t1;
	$t2 = isset( $layout_data['img_2_text'] ) ? (string) $layout_data['img_2_text'] : $t2;
	$t3 = isset( $layout_data['img_3_text'] ) ? (string) $layout_data['img_3_text'] : $t3;
}

if ( ! $img_1_alt ) {
	$img_1_alt = get_the_title( $post_id );
}
if ( ! $img_2_alt ) {
	$img_2_alt = get_the_title( $post_id );
}
if ( ! $img_3_alt ) {
	$img_3_alt = get_the_title( $post_id );
}
?>

<div class="three-img-layout-holder">
	<?php if ( $headline ) : ?>
		<h3 class="mw-small"><?php echo esc_html( $headline ); ?></h3>
	<?php endif; ?>
	<div class="three-img-layout mw-small">
		<?php if ( $img_1_url ) : ?>
			<div class="img-holder reveal">
				<p class="mini-heading light"><?php echo esc_html( $t1 ); ?></p>
				<img src="<?php echo esc_url( $img_1_url ); ?>" alt="<?php echo esc_attr( $img_1_alt ); ?>">
			</div>
		<?php endif; ?>
		<?php if ( $img_2_url ) : ?>
			<div class="img-holder reveal">
				<p class="mini-heading light"><?php echo esc_html( $t2 ); ?></p>
				<img src="<?php echo esc_url( $img_2_url ); ?>" alt="<?php echo esc_attr( $img_2_alt ); ?>">
			</div>
		<?php endif; ?>
		<?php if ( $img_3_url ) : ?>
			<div class="img-holder reveal">
				<p class="mini-heading light"><?php echo esc_html( $t3 ); ?></p>
				<img src="<?php echo esc_url( $img_3_url ); ?>" alt="<?php echo esc_attr( $img_3_alt ); ?>">
			</div>
		<?php endif; ?>
		<?php if ( $text || ( $cta_label && $cta_url ) ) : ?>
			<div class="txt-holder reveal">
				<?php if ( $mini_head ) : ?>
					<p class="mini-heading light"><?php echo esc_html( $mini_head ); ?></p>
				<?php endif; ?>
				<?php if ( $text ) : ?>
					<p class="light"><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>
				<?php if ( $cta_label && $cta_url ) : ?>
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
