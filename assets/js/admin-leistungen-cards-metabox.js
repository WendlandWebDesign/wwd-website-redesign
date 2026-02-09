/* global jQuery, wp */
jQuery(function ($) {
	'use strict';

	function initCard($card) {
		var $selectBtn = $card.find('.wwd-leistungen-icon-select');
		var $removeBtn = $card.find('.wwd-leistungen-icon-remove');
		var $input = $card.find('.wwd-leistungen-icon-id');
		var $preview = $card.find('.wwd-leistungen-icon-preview');
		var frame;

		$selectBtn.on('click', function (e) {
			e.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'Icon auswaehlen',
				button: { text: 'Icon verwenden' },
				multiple: false,
				library: {
					type: 'image',
					tax_query: [
						{
							taxonomy: 'media_category',
							field: 'slug',
							terms: ['icons']
						}
					]
				}
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				if (!attachment || !attachment.id) {
					return;
				}

				$input.val(attachment.id);
				if (attachment.sizes && attachment.sizes.thumbnail) {
					$preview.html('<img src="' + attachment.sizes.thumbnail.url + '" alt="" />');
				} else if (attachment.url) {
					$preview.html('<img src="' + attachment.url + '" alt="" />');
				}
			});

			frame.open();
		});

		$removeBtn.on('click', function (e) {
			e.preventDefault();
			$input.val('');
			$preview.empty();
		});
	}

	$('.wwd-leistungen-card').each(function () {
		initCard($(this));
	});
});
