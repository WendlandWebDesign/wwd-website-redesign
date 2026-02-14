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
				<?php $answer_id = 'faq-a-' . (int) $post_id . '-' . (int) ( $index + 1 ); ?>
				<div class="faq__item">
					<button
						type="button"
						class="faq__q mini-heading"
						aria-expanded="false"
						aria-controls="<?php echo esc_attr( $answer_id ); ?>"
					>
						<span class="faq__qtext"><?php echo esc_html( $item['question'] ); ?></span>
						<span class="faq__icon" aria-hidden="true">
							<span class="faq__icon-plus">+</span>
							<span class="faq__icon-minus">−</span>
						</span>
					</button>
					<div id="<?php echo esc_attr( $answer_id ); ?>" class="faq__a" aria-hidden="true">
						<div class="faq__acontent">
							<p class="light"><?php echo nl2br( esc_html( $item['answer'] ) ); ?></p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
