<?php
$post_id = get_the_ID();
$cards   = array();

for ( $i = 1; $i <= 3; $i++ ) {
	$icon_id  = absint( get_post_meta( $post_id, "_leistungen_card_{$i}_icon", true ) );
	$heading  = get_post_meta( $post_id, "_leistungen_card_{$i}_heading", true );
	$text     = get_post_meta( $post_id, "_leistungen_card_{$i}_text", true );
	$icon_url = $icon_id ? wp_get_attachment_image_url( $icon_id, 'full' ) : '';
	$icon_alt = $icon_id ? get_post_meta( $icon_id, '_wp_attachment_image_alt', true ) : '';

	if ( ! $icon_alt ) {
		$icon_alt = $heading ? $heading : get_the_title( $post_id );
	}

	$cards[] = array(
		'icon_url' => $icon_url,
		'icon_alt' => $icon_alt,
		'heading'  => $heading,
		'text'     => $text,
		'has_data' => ( $heading || $text || $icon_url ),
	);
}
?>

<div class="dienstleistungen-cards-holder">
	<div class="dienstleistungen-cards mw">
		<?php foreach ( $cards as $card ) : ?>
			<?php if ( ! $card['has_data'] ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<div class="dienstleistung-card">
				<div class="icon-holder">
					<?php if ( $card['icon_url'] ) : ?>
						<img src="<?php echo esc_url( $card['icon_url'] ); ?>" alt="<?php echo esc_attr( $card['icon_alt'] ); ?>">
					<?php endif; ?>
				</div>
				<?php if ( $card['heading'] ) : ?>
					<p class="light mini-heading"><?php echo esc_html( $card['heading'] ); ?></p>
				<?php endif; ?>
				<?php if ( $card['text'] ) : ?>
					<p><?php echo esc_html( $card['text'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
