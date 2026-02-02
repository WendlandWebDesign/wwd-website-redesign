jQuery(function ($) {
    $(document).on('click', '.wwd-media-select', function (e) {
        e.preventDefault();

        const $button = $(this);
        const $field = $button.closest('.wwd-seitenbilder-field');
        const $input = $field.find('.wwd-media-id');
        const $preview = $field.find('.wwd-seitenbilder-preview');
        const $remove = $field.find('.wwd-media-remove');

        const frame = wp.media({
            title: $button.data('title') || 'Bild auswählen',
            button: { text: $button.data('button') || 'Bild verwenden' },
            multiple: false,
        });

        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            if (!attachment || !attachment.id) {
                return;
            }

            const url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
            $input.val(attachment.id);

            if ($preview.find('img').length) {
                $preview.find('img').attr('src', url);
            } else {
                $preview.html('<img class="wwd-seitenbilder-preview-img" alt="" src="' + url + '">');
            }

            $preview.removeClass('is-hidden');
            $remove.removeClass('is-hidden');
        });

        frame.open();
    });

    $(document).on('click', '.wwd-media-remove', function (e) {
        e.preventDefault();

        const $field = $(this).closest('.wwd-seitenbilder-field');
        $field.find('.wwd-media-id').val('');
        $field.find('.wwd-seitenbilder-preview').addClass('is-hidden').find('img').remove();
        $(this).addClass('is-hidden');
    });
});
