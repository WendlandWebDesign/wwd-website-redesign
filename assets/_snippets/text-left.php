<?php
$post_id = isset( $post_id ) ? (int) $post_id : get_the_ID();

$accent  = get_post_meta( $post_id, 'text_left_accent', true );
$heading = get_post_meta( $post_id, 'text_left_heading', true );
$text    = get_post_meta( $post_id, 'text_left_text', true );

$image_id  = absint( get_post_meta( $post_id, 'text_left_image', true ) );
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
$image_alt = $image_id ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';

if ( isset( $layout_data ) && is_array( $layout_data ) && ! empty( $layout_data ) ) {
	$accent    = isset( $layout_data['text_left_accent'] ) ? (string) $layout_data['text_left_accent'] : $accent;
	$heading   = isset( $layout_data['text_left_heading'] ) ? (string) $layout_data['text_left_heading'] : $heading;
	$text      = isset( $layout_data['text_left_text'] ) ? (string) $layout_data['text_left_text'] : $text;
	$image_url = isset( $layout_data['text_left_image_url'] ) ? (string) $layout_data['text_left_image_url'] : $image_url;
	$image_alt = isset( $layout_data['text_left_image_alt'] ) ? (string) $layout_data['text_left_image_alt'] : $image_alt;
}

if ( ! $image_alt ) {
	$image_alt = get_the_title( $post_id );
}
?>

<div class="two-img-layout-holder">
	<div class="two-img-layout mw-small">
		<?php if ( $accent || $heading || $text ) : ?>
			<div class="txt-holder reveal">
				<?php if ( $accent ) : ?>
					<p class="ac"><?php echo esc_html( $accent ); ?></p>
				<?php endif; ?>
				<div>
					<?php if ( $heading ) : ?>
						<p class="mini-heading light"><?php echo esc_html( $heading ); ?></p>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<p class="light"><?php echo wp_kses_post( $text ); ?></p>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( $image_url ) : ?>
			<div class="img-holder">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" decoding="async">
			</div>
		<?php endif; ?>
	</div>
</div>
