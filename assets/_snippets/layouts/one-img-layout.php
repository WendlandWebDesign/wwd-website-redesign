<?php
if ( ! isset( $data, $post_id ) || ! is_array( $data ) ) {
	return;
}

$headline  = isset( $data['_section_headline'] ) ? (string) $data['_section_headline'] : '';
$mini_head = isset( $data['_section_mini_heading'] ) ? (string) $data['_section_mini_heading'] : '';
$text      = isset( $data['_section_text'] ) ? (string) $data['_section_text'] : '';
$cta_label = isset( $data['_cta_label'] ) ? (string) $data['_cta_label'] : '';
$cta_url   = isset( $data['_cta_url'] ) ? (string) $data['_cta_url'] : '';

$img_1_id  = isset( $data['_img_1_id'] ) ? absint( $data['_img_1_id'] ) : 0;
$img_1_url = $img_1_id ? wp_get_attachment_image_url( $img_1_id, 'large' ) : '';

$bottom_items = array();
for ( $i = 1; $i <= 6; $i++ ) {
	$key = "one_img_bottom_p_{$i}";
	if ( empty( $data[ $key ] ) ) {
		continue;
	}
	$item = trim( (string) $data[ $key ] );
	if ( '' !== $item ) {
		$bottom_items[] = $item;
	}
}
?>
<div class="one-img-layout-holder">
	<?php if ( '' !== $headline ) : ?>
		<h3 class="mw-small"><?php echo esc_html( $headline ); ?></h3>
	<?php endif; ?>
	<div class="one-img-layout mw-small">
		<?php if ( '' !== $text || '' !== $mini_head ) : ?>
			<div class="txt-holder reveal">
				<?php if ( '' !== $mini_head ) : ?>
					<p class="mini-heading light"><?php echo esc_html( $mini_head ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $text ) : ?>
					<p class="light"><?php echo wp_kses_post( $text ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( $img_1_url ) : ?>
			<div class="img-holder">
				<img src="<?php echo esc_url( $img_1_url ); ?>" alt="<?php echo esc_attr( get_post_meta( $img_1_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ) ); ?>">
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
	</div>
</div>
