'use strict';

$(document).ready(function()
{
    $('[data-search="custody_chains"]').focus();

    $('[data-search="custody_chains"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="custody_chains"]').find(' > tbody > tr'));
    });

    var filter_action = 'filter_custody_chains';
    var restore_action = 'restore_custody_chain';
    var delete_action = 'delete_custody_chain';

    $(document).on('click', '[data-action="' + filter_action + '"]', function()
    {
        action = filter_action;
        id = null;

        transform_form_modal('filter', $('[data-modal="' + filter_action + '"]'));
        open_form_modal('filter', $('[data-modal="' + filter_action + '"]'));
    });

    $('[name="deleted_status"]').on('change', function()
    {
        if ($(this).val() == 'deleted')
            $('[name="sended_status"]').val('all');
    });

    $('[data-modal="' + filter_action + '"]').modal().onCancel(function()
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

    $(document).on('click', '[data-action="' + restore_action + '"]', function()
    {
        action = restore_action;
        id = $(this).data('id');

        send_form_modal('restore');
    });

    $(document).on('click', '[data-action="' + delete_action + '"]', function()
    {
        action = delete_action;
        id = $(this).data('id');

        open_form_modal('delete', $('[data-modal="' + delete_action + '"]'));
    });

    $('[data-modal="' + delete_action + '"]').modal().onSuccess(function()
    {
        send_form_modal('delete');
    });
});
