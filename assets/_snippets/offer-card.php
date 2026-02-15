<?php
$post_id = get_the_ID();
$cards   = get_post_meta( $post_id, 'offer_cards', true );

if ( ! is_array( $cards ) ) {
	$cards = array();
}
?>

<div class="dienstleistungen-cards-holder">
	<div class="dienstleistungen-cards mw">
		<?php foreach ( $cards as $card ) : ?>
			<?php
			if ( ! is_array( $card ) ) {
				continue;
			}

			$title = isset( $card['title'] ) ? (string) $card['title'] : '';
			$text  = isset( $card['text'] ) ? (string) $card['text'] : '';
			$price = isset( $card['price'] ) ? (string) $card['price'] : '';

			$bullets = array();
			if ( isset( $card['bullets'] ) && is_array( $card['bullets'] ) ) {
				foreach ( array_slice( $card['bullets'], 0, 6 ) as $bullet ) {
					$bullet = (string) $bullet;
					if ( '' !== $bullet ) {
						$bullets[] = $bullet;
					}
				}
			}

			if ( '' === $title && '' === wp_strip_all_tags( $text ) && empty( $bullets ) && '' === $price ) {
				continue;
			}
			?>
			<div class="dienstleistung-card offer-card">
				<div class="offer-card__body">
					<?php if ( $title ) : ?>
						<p class="ac mini-heading"><?php echo esc_html( $title ); ?></p>
					<?php endif; ?>
					<?php if ( $text ) : ?>
						<p class="dark"><?php echo wp_kses_post( $text ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $bullets ) ) : ?>
						<ul>
							<?php foreach ( $bullets as $item ) : ?>
								<li>
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/check.svg' ); ?>" alt="">
									<p class="dark"><?php echo esc_html( $item ); ?></p>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<?php if ( $price ) : ?>
					<div class="offer-card__price">
						<p class="offer-card-price"><?php echo esc_html( $price ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
