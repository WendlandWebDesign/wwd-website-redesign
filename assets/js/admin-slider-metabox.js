/* global jQuery, wp */
jQuery(function ($) {
	'use strict';

	function initMediaField($field) {
		var $selectBtn = $field.find('.wwd-media-select');
		var $removeBtn = $field.find('.wwd-media-remove');
		var $input = $field.find('input[type="hidden"]');
		var $preview = $field.find('.wwd-media-preview');
		var frame;

		$selectBtn.on('click', function (e) {
			e.preventDefault();

			if (frame) {
				frame.open();
				return;
			}

			frame = wp.media({
				title: 'Bild auswaehlen',
				button: { text: 'Bild verwenden' },
				multiple: false
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				if (!attachment || !attachment.id) {
					return;
				}
				$input.val(attachment.id);
				if (attachment.sizes && attachment.sizes.medium) {
					$preview.html('<img src="' + attachment.sizes.medium.url + '" alt="" />');
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

	$('.wwd-media-field').each(function () {
		initMediaField($(this));
	});
});
