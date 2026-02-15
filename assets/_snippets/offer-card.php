
<div class="dienstleistungen-cards-holder">
	<div class="dienstleistungen-cards mw">
		<?php foreach ( $cards as $card ) : ?>
			<?php if ( ! $card['has_data'] ) : ?>
				<?php continue; ?>
			<?php endif; ?>
			<div class="dienstleistung-card">
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