(function ($) {
    'use strict';

    $(function () {
        $(document).on('click', '.wpdc-duplicate-action', function (e) {
            let $link = $(this);
            let originalText = $link.text();

            // Prevent double-clicking
            if ($link.hasClass('processing')) {
                e.preventDefault();
                return false;
            }

            // Add processing class to prevent double clicks
            $link.addClass('processing');

            // Show loading state
            $link.text('Duplicating...').addClass('updating-message');

            if (!confirm('Are you sure you want to duplicate this item?')) {
                e.preventDefault();
                $link.removeClass('processing updating-message');
                $link.text(originalText);
                return false;
            }
        });

        // Enhance the duplicate action styling
        $(document).ready(function () {
            $('.wpdc-duplicate-action').each(function () {
                $(this).addClass('wpdc-link');
            });
        });

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
