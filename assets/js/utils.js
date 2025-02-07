export function submitForm(formSelector, successCallback) {
    $(formSelector).on('submit', function(e) {
        e.preventDefault();
        var $form = $(formSelector);
        var $submit = $form.find(':submit');
        var submitText = $submit.html();
        var $reset = $form.find('button[type="reset"]');
        var data = new FormData($form[0]);
        var url = $form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function() {
                var span = $('<span />').addClass('spinner-border spinner-border-sm');
                $submit.prop('disabled', true);
                $submit.html(span);
                if ($reset.length) {
                    $reset.prop('disabled', true);
                }
            }
        }).done(function(response) {
            $submit.html(submitText);
            $submit.prop('disabled', false);
            if ($reset.length) {
                $reset.prop('disabled', false);
            }
            if (successCallback !== undefined && successCallback) {
                successCallback(response, $form);
            }
        });
    });
}