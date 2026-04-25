(function ($) {
  'use strict';

  $(document).on('click', '[data-xibufz-confirm]', function (event) {
    var message = $(this).attr('data-xibufz-confirm');
    if (message && !window.confirm(message)) {
      event.preventDefault();
    }
  });

  $(document).on('click', '.xibufz-media-button', function (event) {
    event.preventDefault();

    var $button = $(this);
    var $input = $button.siblings('.xibufz-media-url').first();

    var frame = wp.media({
      title: '选择图片',
      button: {
        text: '使用这张图片'
      },
      multiple: false
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      if (attachment && attachment.url) {
        $input.val(attachment.url).trigger('change');
      }
    });

    frame.open();
  });
})(jQuery);
