<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script>document.documentElement.classList.add('js');</script>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="page-fade-overlay" aria-hidden="true"></div>
<?php wp_body_open(); ?>
	<?php
	get_template_part( 'assets/_snippets/nav' );
	?>
	<div class="site-overlay" aria-hidden="true"></div>
