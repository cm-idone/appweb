'use strict';

$(document).ready(function()
{
    $('[name="age"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="id"]').on('keyup', function()
    {
        validate_string(['uppercase','lowercase','int'], $(this).val(), $(this));
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('form[name="covid"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        $.ajax({
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                check_form_errors(form, response, function()
                {
                    open_notification_modal('success', response.message);
                });
            }
        });
    });
});
