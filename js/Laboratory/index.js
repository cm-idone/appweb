'use strict';

$(document).ready(function()
{
    $('[data-search="custody_chains"]').focus();

    $('[data-search="custody_chains"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="custody_chains"]').find(' > tbody > tr'));
    });

    var filter_action = 'filter_custody_chains';

    $(document).on('click', '[data-action="' + filter_action + '"]', function()
    {
        action = filter_action;
        id = null;

        transform_form_modal('filter', $('[data-modal="' + filter_action + '"]'));
        open_form_modal('filter', $('[data-modal="' + filter_action + '"]'));
    });

    $('[data-modal="' + filter_action + '"]').find('[button-cancel]').on('click', function()
    {
        filter = false;

        $('[data-modal="' + filter_action + '"]').find('form').submit();
    });

    $('[data-modal="' + filter_action + '"]').find('form').on('submit', function(event)
    {
        action = filter_action;
        id = null;

        send_form_modal('filter', $(this), event);
    });
});
