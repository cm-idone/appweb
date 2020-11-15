'use strict';

$(document).ready(function()
{
    var employee_signature = document.getElementById('employee_signature');
    var employee_canvas = employee_signature.querySelector('canvas');
    var employee_pad = new SignaturePad(employee_canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });

    resize_canvas(employee_canvas);

    var collector_signature = document.getElementById('collector_signature');
    var collector_canvas = collector_signature.querySelector('canvas');
    var collector_pad = new SignaturePad(collector_canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });

    resize_canvas(collector_canvas);

    $('form[name="create_custody_chain"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        employee_pad.fromData(employee_pad.toData());
        collector_pad.fromData(collector_pad.toData());

        data.append('employee_signature', ((employee_pad.isEmpty()) ? '' : employee_pad.toDataURL('image/jpeg')));
        data.append('collector_signature', ((collector_pad.isEmpty()) ? '' : collector_pad.toDataURL('image/jpeg')));
        data.append('action', 'create_custody_chain');

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
                    open_notification_modal('success', response.message, response.path);
                });
            }
        });
    });
});
