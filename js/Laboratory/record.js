'use strict';

$(document).ready(function()
{
    $('[name="sex"]').on('change', function()
    {
        $('[name="pregnant"][value="not"]').prop('checked', true);

        if ($(this).val() == 'male')
            $('[data-hidden="pregnant"]').addClass('hidden');
        else if ($(this).val() == 'female')
            $('[data-hidden="pregnant"]').removeClass('hidden');
    });

    $('[name="age"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="phone_number"]').on('keyup', function()
    {
        validate_string('int', $(this).val(), $(this));
    });

    $('[name="symptoms[]"]').on('change', function()
    {
        $('[name="symptoms_time"]').val('');

        if ($('[type="checkbox"][name="symptoms[]"]:checked').prop('checked'))
        {
            $('[type="radio"][name="symptoms[]"]').prop('checked', false);
            $('[data-hidden="symptoms_time"]').removeClass('hidden');
        }
        else
        {
            $('[type="radio"][name="symptoms[]"]').prop('checked', true);
            $('[data-hidden="symptoms_time"]').addClass('hidden');
        }
    });

    $('[name="previous_travel"]').on('change', function()
    {
        $('[name="previous_travel_countries"]').val('');

        if ($(this).val() == 'not')
            $('[data-hidden="previous_travel_countries"]').addClass('hidden');
        else if ($(this).val() == 'yeah')
            $('[data-hidden="previous_travel_countries"]').removeClass('hidden');
    });

    $('[name="covid_infection"]').on('change', function()
    {
        $('[name="covid_infection_time"]').val('');

        if ($(this).val() == 'not')
            $('[data-hidden="covid_infection_time"]').addClass('hidden');
        else if ($(this).val() == 'yeah')
            $('[data-hidden="covid_infection_time"]').removeClass('hidden');
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

    $('form[name="create_record"]').on('submit', function(event)
    {
        event.preventDefault();

        var form = $(this);
        var data = new FormData(form[0]);

        data.append('signature', ((signature_pad.isEmpty()) ? '' : signature_pad.toDataURL('image/jpeg')));
        data.append('action', 'create_record');

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
