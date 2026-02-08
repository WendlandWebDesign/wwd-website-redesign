jQuery(function ($) {
  // Diagnostic marker to verify enqueue in the admin page.
  console.log('[admin-seitenbilder] loaded');

  function openMediaFrame($button) {
    if (typeof wp === 'undefined' || !wp.media) {
      console.warn('[admin-seitenbilder] wp.media unavailable');
      return;
    }

    const $field = $button.closest('.wwd-media-field');
    const inputField = $field.length ? $field.find('input[type="text"]').first() : $button.prevAll('input').first();
    const preview = $field.length ? $field.find('img').first() : $button.nextAll('img').first();

    if (!inputField.length) {
      return;
    }

    const frame = wp.media({
      title: 'Bild auswählen',
      multiple: false,
      library: { type: 'image' },
      button: { text: 'Bild übernehmen' }
    });

    frame.on('select', function () {
      const attachment = frame.state().get('selection').first().toJSON();
      if (!attachment || !attachment.url) {
        return;
      }
      inputField.val(attachment.url).attr('data-attachment-id', attachment.id || '');
      if (preview.length) {
        preview.attr('src', attachment.url).show();
      }
    });

    frame.open();
  }

  $(document).on('click', '.wwd-upload-button, .js-referenzen-media-select', function (e) {
    e.preventDefault();
    openMediaFrame($(this));
  });

  $(document).on('click', '.wwd-media-remove, .js-referenzen-media-remove', function (e) {
    e.preventDefault();
    const $field = $(this).closest('.wwd-media-field');
    const inputField = $field.find('input[type="text"]').first();
    const preview = $field.find('img').first();
    if (inputField.length) {
      inputField.val('').removeAttr('data-attachment-id');
    }
    if (preview.length) {
      preview.attr('src', '').hide();
    }
  });
});
