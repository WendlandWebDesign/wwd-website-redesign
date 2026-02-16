<?php
$post_id = isset( $post_id ) ? (int) $post_id : get_the_ID();

$headline  = isset( $meta['headline'] ) ? $meta['headline'] : '';
$mini_head = isset( $meta['mini_heading'] ) ? $meta['mini_heading'] : '';
$text      = isset( $meta['text'] ) ? $meta['text'] : '';
$cta_label = isset( $meta['cta_label'] ) ? $meta['cta_label'] : '';
$cta_url   = isset( $meta['cta_url'] ) ? $meta['cta_url'] : '';

$img_1_id  = isset( $meta['img_1_id'] ) ? (int) $meta['img_1_id'] : 0;
$img_1_url = $img_1_id ? wp_get_attachment_image_url( $img_1_id, 'large' ) : '';
$img_1_alt = $img_1_id ? get_post_meta( $img_1_id, '_wp_attachment_image_alt', true ) : '';

$bottom_items = array();
if ( isset( $layout_data ) && is_array( $layout_data ) && ! empty( $layout_data ) ) {
	$headline  = isset( $layout_data['headline'] ) ? (string) $layout_data['headline'] : $headline;
	$mini_head = isset( $layout_data['mini_heading'] ) ? (string) $layout_data['mini_heading'] : $mini_head;
	$text      = isset( $layout_data['text'] ) ? (string) $layout_data['text'] : $text;
	$cta_label = isset( $layout_data['cta_label'] ) ? (string) $layout_data['cta_label'] : $cta_label;
	$cta_url   = isset( $layout_data['cta_url'] ) ? (string) $layout_data['cta_url'] : $cta_url;
	$img_1_url = isset( $layout_data['img_1_url'] ) ? (string) $layout_data['img_1_url'] : $img_1_url;
	$img_1_alt = isset( $layout_data['img_1_alt'] ) ? (string) $layout_data['img_1_alt'] : $img_1_alt;

	if ( isset( $layout_data['bottom_items'] ) && is_array( $layout_data['bottom_items'] ) ) {
		foreach ( array_slice( $layout_data['bottom_items'], 0, 6 ) as $item ) {
			$item_text = is_array( $item ) ? ( isset( $item['text'] ) ? (string) $item['text'] : '' ) : (string) $item;
			$item_text = trim( $item_text );
			if ( '' !== $item_text ) {
				$bottom_items[] = $item_text;
			}
		}
	}
} else {
	for ( $i = 1; $i <= 6; $i++ ) {
		$value = get_post_meta( $post_id, "one_img_bottom_p_{$i}", true );
		if ( '' !== $value ) {
			$bottom_items[] = $value;
		}
	}
}

if ( ! $img_1_alt ) {
	$img_1_alt = get_the_title( $post_id );
}
?>

<div class="one-img-layout-holder">
	<?php if ( $headline ) : ?>
		<h3 class="mw-small"><?php echo esc_html( $headline ); ?></h3>
	<?php endif; ?>
	<div class="one-img-layout mw-small">
		<?php if ( $text || $mini_head ) : ?>
			<div class="txt-holder reveal">
				<?php if ( $mini_head ) : ?>
					<p class="mini-heading light"><?php echo esc_html( $mini_head ); ?></p>
				<?php endif; ?>
				<?php if ( $text ) : ?>
					<p class="light"><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( $img_1_url ) : ?>
			<div class="img-holder">
				<img src="<?php echo esc_url( $img_1_url ); ?>" alt="<?php echo esc_attr( $img_1_alt ); ?>">
			</div>
		<?php endif; ?>
		<div class="bottom-holder">
			<?php if ( ! empty( $bottom_items ) ) : ?>
				<ul>
					<?php foreach ( $bottom_items as $item ) : ?>
						<li>
							<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/check.svg' ); ?>" alt="">
							<p class="light"><?php echo esc_html( $item ); ?></p>
						</li>
					<?php endforeach; ?>
				</ul>
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
			</ul>
		</div>
	</div>
</div>
