'use strict';

$(document).ready(function()
{

});

/* ------------------------------------------------------------- */
/* Tab's rewrite
/* ------------------------------------------------------------- */

!function ( $ )
{
    const Valkyrie = function () {}

    Valkyrie.prototype.tabs = function ()
    {
        let tabs = document.querySelectorAll(".tabs")

        for ( const tab of tabs )
        {
            let buttons = tab.querySelectorAll("ul > [data-tab-target]:not([disabled])")

            $(tab).vkye_multitabs().goto( $(tab).data('tab-active') )

            for ( const button of buttons )
            {
                button.addEventListener('click', function ()
                {
                    $(tab).vkye_multitabs().goto( $(this).data('tab-target') )
                })
            }
        }
    },

    Valkyrie.prototype.init = function ()
    {
        this.tabs()
    }

    $.Valkyrie = new Valkyrie
    $.Valkyrie.Constructor = Valkyrie

}( window.jQuery ),

function ( $ )
{
    $.fn.vkye_multitabs = function ()
    {
        let self = $(this)

        return {
            goto: function ( target = false )
            {
                if ( target == false )
                    return false

                if ( !self.find('[data-target="'+ target +'"]').length )
                    return false

                self.find('[data-tab-target="'+ target +'"]').addClass("view").siblings().removeClass("view")
                self.find('[data-target]').slideUp(300)
                self.find('[data-target="'+ target +'"]').slideDown(300)

                self[0].dispatchEvent( new CustomEvent('change', {bubbles: true, detail: {tab: target}}) )
            }
        }
    }

    $.Valkyrie.init()

}( window.jQuery )
