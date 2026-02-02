jQuery(function ($) {
  // Diagnostic marker to verify enqueue in the admin page.
  console.log('[admin-seitenbilder] loaded');

  $('.wwd-upload-button').on('click', function (e) {
    e.preventDefault();

    const button = $(this);
    const inputField = button.prevAll('input').first();
    const preview = button.nextAll('img').first();

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
      inputField.val(attachment.url).attr('data-attachment-id', attachment.id);
      if (preview.length) {
        preview.attr('src', attachment.url).show();
      }
    });

    frame.open();
  });
});
