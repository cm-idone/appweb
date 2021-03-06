'use strict';

$(document).ready(function()
{
    $('form[name="start_authentication"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        data.append('action','start_authentication');

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

    $('[data-action="end_authentication"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=end_authentication',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    open_notification_modal('success', response.message);
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });
});
