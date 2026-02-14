<?php
$text     = trim( (string) get_post_meta( get_the_ID(), 'balken_text', true ) );
$btn_text = trim( (string) get_post_meta( get_the_ID(), 'balken_button_text', true ) );
$btn_url  = trim( (string) get_post_meta( get_the_ID(), 'balken_button_url', true ) );

$final_url = '' !== $btn_url ? $btn_url : home_url( '/kontakt/' );
?>
<div class="balken-layout">
	<div class="balken-layout__inner">
		<div class="balken-layout__text">
			<p class="light">
				<?php echo esc_html( $text ); ?>
			</p>
		</div>
		<?php if ( $btn_text !== '' ) : ?>
			<div class="balken-layout__btn">
				<button onclick="window.location.href='<?php echo esc_url( $final_url ); ?>'" class="btn light" data-hero-snake-load="1">
					<span class="btn__border" aria-hidden="true">
						<svg class="btn__svg" viewBox="0 0 100 40" preserveAspectRatio="none">
							<path class="btn__path" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
							<path class="btn__seg btn__seg--1" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
							<path class="btn__seg btn__seg--2" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
							<path class="btn__seg btn__seg--3" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
							<path class="btn__seg btn__seg--4" vector-effect="non-scaling-stroke" d="M2,2 H98 Q100,2 100,4 V36 Q100,38 98,38 H2 Q0,38 0,36 V4 Q0,2 2,2 Z"/>
						</svg>
					</span>
					<p><?php echo wwd_inline_svg( 'arrow-white.svg', array( 'class' => 'icon--arrow-white', 'aria_hidden' => true ) ); ?><?php echo esc_html( $btn_text ); ?></p>
				</button>
			</div>
		<?php endif; ?>
	</div>
</div>
