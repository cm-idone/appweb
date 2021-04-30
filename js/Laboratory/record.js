'use strict';

$(document).ready(function()
{
    $('[name="sex"]').on('change', function()
    {
        $('[name="sf_pregnant"][value="not"]').prop('checked', true);

        if ($(this).val() == 'male')
            $('[data-hidden="sf_pregnant"]').addClass('hidden');
        else if ($(this).val() == 'female')
            $('[data-hidden="sf_pregnant"]').removeClass('hidden');
    });

    $('[name="age"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="sf_symptoms[]"]').on('change', function()
    {
        if ($('[name="sf_symptoms[]"]:checked').prop('checked'))
            $('[data-hidden="sf_symptoms_time"]').removeClass('hidden');
        else
        {
            $('[name="sf_symptoms_time"]').val('');
            $('[data-hidden="sf_symptoms_time"]').addClass('hidden');
        }
    });

    $('[name="sf_travel"]').on('change', function()
    {
        if ($(this).val() == 'not')
        {
            $('[name="sf_travel_countries"]').val('');
            $('[data-hidden="sf_travel_countries"]').addClass('hidden');
        }
        else if ($(this).val() == 'yeah')
            $('[data-hidden="sf_travel_countries"]').removeClass('hidden');
    });

    $('[name="sf_covid"]').on('change', function()
    {
        if ($(this).val() == 'not')
        {
            $('[name="sf_covid_time"]').val('');
            $('[data-hidden="sf_covid_time"]').addClass('hidden');
        }
        else if ($(this).val() == 'yeah')
            $('[data-hidden="sf_covid_time"]').removeClass('hidden');
    });

    var signature = document.getElementById('signature');

    if (signature)
    {
        var signature_canvas = signature.querySelector('canvas');
        var signature_pad = new SignaturePad(signature_canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });
    
        resize_canvas(signature_canvas);
    }

    $('[data-action="clean_signature"]').on('click', function()
    {
        signature_pad.clear();
    });

    $('form[name="create_record"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        data.append('signature', ((signature_pad.isEmpty()) ? '' : signature_pad.toDataURL('image/jpeg')));
        data.append('action','create_record');

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

    $('[data-action="restore_record"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'action=restore_record',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    location.reload();
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });
});
