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
			$price = isset( $card['price'] ) ? (string) $card['price'] : '';
			$price_note = isset( $card['price_note'] ) ? (string) $card['price_note'] : '';
			$cta_url = isset( $card['cta_url'] ) ? (string) $card['cta_url'] : '';
			$cta_label = isset( $card['cta_label'] ) ? (string) $card['cta_label'] : '';

			$bullets = array();
			if ( isset( $card['bullets'] ) && is_array( $card['bullets'] ) ) {
				foreach ( array_slice( $card['bullets'], 0, 6 ) as $bullet ) {
					$bullet = (string) $bullet;
					if ( '' !== $bullet ) {
						$bullets[] = $bullet;
					}
				}
			}

			if ( '' === $title && empty( $bullets ) && '' === $price && '' === $price_note && '' === $cta_url && '' === $cta_label ) {
				continue;
			}
			?>
			<div class="dienstleistung-card offer-card">
				<?php if ( $title ) : ?>
					<p class="offer-card__title"><?php echo esc_html( $title ); ?></p>
				<?php endif; ?>
				<?php if ( $price ) : ?>
					<p class="offer-card__price"><?php echo esc_html( $price ); ?></p>
				<?php endif; ?>
				<?php if ( $price_note ) : ?>
					<p class="offer-card__price-note"><?php echo esc_html( $price_note ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $bullets ) ) : ?>
					<ul>
						<?php foreach ( $bullets as $item ) : ?>
							<li>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/icons/checkmark.svg' ); ?>" alt="">
								<p class="light"><?php echo esc_html( $item ); ?></p>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $cta_url && $cta_label ) : ?>
					<div class="offer-card__cta">
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
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>
