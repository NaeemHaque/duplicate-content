(function ($) {
    'use strict';

    $(function () {
        // Settings form submission
        $('#wpdc-settings-form').on('submit', function (e) {
            let $form = $(this);
            let $submitButton = $form.find('input[type="submit"]');
            let originalText = $submitButton.val();

            $submitButton.val('Saving...').prop('disabled', true);

            setTimeout(function () {
                $submitButton.val(originalText).prop('disabled', false);
            }, 2000);
        });

    });

})(jQuery);
