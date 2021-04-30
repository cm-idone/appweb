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

    var signature = document.getElementById('signature');
    var signature_canvas = signature.querySelector('canvas');
    var signature_pad = new SignaturePad(signature_canvas, {
        backgroundColor: 'rgb(255, 255, 255)'
    });

    resize_canvas(signature_canvas);

    $('[data-action="clean_signature"]').on('click', function()
    {
        signature_pad.clear();
    });

    var save;

    $('button[type="submit"]').on('click', function()
    {
        save = $(this).data('save');
    });

    $('form[name="update_custody_chain"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        signature_pad.fromData(signature_pad.toData());

        data.append('signature', ((signature_pad.isEmpty()) ? '' : signature_pad.toDataURL('image/jpeg')));
        data.append('save', save);
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
