'use strict';

$(document).ready(function()
{
    $('[name="age"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="test_1"]').on('keyup', function()
    {
        validate_string(['int','float'], $(this).val(), $(this));
    });

    $('[name="test_2"]').on('keyup', function()
    {
        validate_string(['int','float'], $(this).val(), $(this));
    });

    $('[name="test_3"]').on('keyup', function()
    {
        validate_string(['int','float'], $(this).val(), $(this));
    });

    var employee_signature = document.getElementById('employee_signature');

    if (employee_signature)
    {
        var employee_canvas = employee_signature.querySelector('canvas');
        var employee_pad = new SignaturePad(employee_canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        resize_canvas(employee_canvas);
    }

    $('[data-action="clean_employee_signature"]').on('click', function()
    {
        employee_pad.clear();
    });

    $('form[name="update_custody_chain"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        if (employee_signature)
        {
            employee_pad.fromData(employee_pad.toData());

            data.append('employee_signature', ((employee_pad.isEmpty()) ? '' : employee_pad.toDataURL('image/jpeg')));
        }

        data.append('action', 'update_custody_chain');

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
