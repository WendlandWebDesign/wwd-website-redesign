<?php
$post_id      = get_the_ID();
$faq_headline = trim( (string) get_post_meta( $post_id, 'faq_headline', true ) );
$faq_items    = array();

for ( $i = 1; $i <= 10; $i++ ) {
	$question = trim( (string) get_post_meta( $post_id, 'faq_q_' . $i, true ) );
	$answer   = trim( (string) get_post_meta( $post_id, 'faq_a_' . $i, true ) );

	if ( '' === $question || '' === $answer ) {
		continue;
	}

	$faq_items[] = array(
		'question' => $question,
		'answer'   => $answer,
	);
}

if ( empty( $faq_items ) ) {
	return;
}
?>
<div class="faq-layout">
	<div class="faq-layout__inner mw">
		<?php if ( '' !== $faq_headline ) : ?>
			<h2 class="faq-layout__headline"><?php echo esc_html( $faq_headline ); ?></h2>
		<?php endif; ?>

		<div class="faq-accordion" data-faq-accordion>
			<?php foreach ( $faq_items as $index => $item ) : ?>
				<?php $answer_id = 'faq-answer-' . (int) $post_id . '-' . (int) ( $index + 1 ); ?>
				<div class="faq-item light">
					<button
						type="button"
						class="faq-item__trigger"
						aria-expanded="false"
						aria-controls="<?php echo esc_attr( $answer_id ); ?>"
					>
						<span class="faq-item__question"><?php echo esc_html( $item['question'] ); ?></span>
						<span class="faq-item__icon" aria-hidden="true">+</span>
					</button>
					<div id="<?php echo esc_attr( $answer_id ); ?>" class="faq-item__answer" hidden>
						<p><?php echo wp_kses_post( nl2br( esc_html( $item['answer'] ) ) ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
<script>
	(function () {
		if (window.__wwdFaqAccordionInit) {
			return;
		}
		window.__wwdFaqAccordionInit = true;

		document.addEventListener('click', function (event) {
			var trigger = event.target.closest('.faq-item__trigger');
			if (!trigger) {
				return;
			}

			var item = trigger.closest('.faq-item');
			if (!item) {
				return;
			}

			var answer = item.querySelector('.faq-item__answer');
			var icon = trigger.querySelector('.faq-item__icon');
			var isExpanded = trigger.getAttribute('aria-expanded') === 'true';

			trigger.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
			if (answer) {
				answer.hidden = isExpanded;
			}
			if (icon) {
				icon.textContent = isExpanded ? '+' : '-';
			}
		});
	})();
</script>
