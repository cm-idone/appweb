'use strict';

$(document).ready(function()
{
    $('[data-action="preview_doc"]').on('click', function()
    {
        $.ajax({
            type: 'POST',
            data: 'doc=' + $(this).data('doc') + '&action=preview_doc',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                {
                    $('[data-modal="preview_doc"]').find('div.preview-docs').html(response.html);
                    $('[data-modal="preview_doc"]').addClass('view');
                }
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    });

    // $('[data-action="load_custody_chanin"]').on('click', function()
    // {
    //     $.ajax({
    //         type: 'POST',
    //         data: 'type=' + $(this).data('type') + '&key=' + $(this).data('key') + '&action=load_custody_chanin',
    //         processData: false,
    //         cache: false,
    //         dataType: 'json',
    //         success: function(response)
    //         {
    //             if (response.status == 'success')
    //             {
    //                 $('[data-modal="load_custody_chanin"]').find('article.scanner-4').html(response.html);
    //                 $('[data-modal="load_custody_chanin"]').addClass('view');
    //             }
    //             else if (response.status == 'error')
    //                 open_notification_modal('alert', response.message);
    //         }
    //     });
    // });
});
