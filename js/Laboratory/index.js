'use strict';

$(document).ready(function()
{
    $('[data-search="custody_chanins"]').focus();

    $('[data-search="custody_chanins"]').on('keyup', function()
    {
        search_in_table($(this).val(), $('[data-table="custody_chanins"]').find(' > tbody > tr'));
    });
});
