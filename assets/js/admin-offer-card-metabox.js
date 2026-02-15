/* global jQuery */
jQuery(function ($) {
	'use strict';

	var $repeater = $('#wwd-offer-card-repeater');
	if (! $repeater.length) {
		return;
	}

	function updateRemoveButtons() {
		var $rows = $repeater.find('.wwd-offer-card-row').not('.wwd-offer-card-template');
		$rows.find('.wwd-offer-card-remove').prop('disabled', $rows.length <= 1);
	}

	$('#wwd-offer-card-add').on('click', function (e) {
		e.preventDefault();
		var nextIndex = parseInt($repeater.attr('data-next-index'), 10);
		if (isNaN(nextIndex)) {
			nextIndex = $repeater.find('.wwd-offer-card-row').not('.wwd-offer-card-template').length;
		}

		var $template = $repeater.find('.wwd-offer-card-template').first();
		if (! $template.length) {
			return;
		}

		var html = $template.prop('outerHTML').replace(/__INDEX__/g, String(nextIndex));
		var $newRow = $(html)
			.removeClass('wwd-offer-card-template')
			.removeAttr('style')
			.attr('data-offer-card-index', String(nextIndex));

		$newRow.find('input[type="text"], textarea').val('');
		$repeater.append($newRow);
		$repeater.attr('data-next-index', String(nextIndex + 1));
		updateRemoveButtons();
	});

	$repeater.on('click', '.wwd-offer-card-remove', function (e) {
		e.preventDefault();
		var $rows = $repeater.find('.wwd-offer-card-row').not('.wwd-offer-card-template');
		if ($rows.length <= 1) {
			$rows.find('input[type="text"], textarea').val('');
			return;
		}
		$(this).closest('.wwd-offer-card-row').remove();
		updateRemoveButtons();
	});

	updateRemoveButtons();
});
