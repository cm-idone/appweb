'use strict';

/* CLICK
------------------------------------------------------------------------------*/
var VK_CLICK = (!document.ontouchstart ? 'click' : 'touchend');

/* Acciones en el DOM
------------------------------------------------------------------------------*/
$( document ).ready(function ()
{
    var MyDocument = $( this );

    // Remueve todos los elementos abiertos como menus, dropdows con la clase "open"
    MyDocument.on(VK_CLICK, 'html,body', function () { $('*.open').removeClass('open'); });
    // Elimina la accion del click a elementos sin un link o con el atributo "disabled"
    MyDocument.on(VK_CLICK, '[href=""],[disabled]', function () { return false; });

    /*
    * Dropdown Menu
    */
    MyDocument.on(VK_CLICK, '.dropmenu > button', function ( event )
    {
        event.stopPropagation();
        $('.dropmenu > .dropdown.open').removeClass('open');

        var self = $( this );
        var dropmenu = self.parent();
        var dropdown = dropmenu.find('> .dropdown');

        dropdown.addClass('open');
        dropdown.find('a:not([href])').on(VK_CLICK, function ( event ) { event.stopPropagation(); });
    });
    /** END **/



    /*
    * Modal
    */
    MyDocument.on(VK_CLICK, '[data-button-modal]', function ()
    {
        var self = $( this );
        var target = self.data('button-modal');
        var modal = MyDocument.find('[data-modal="'+ target +'"]');

        $('body').addClass('noscroll');
        modal.addClass('view').animate({ scrollTop: 0 }, 300);
    });

    $('[data-modal]').each(function ()
    {
        var self = $( this );
        var content = self.find('> div.content');
        var closeOnOverlay = self.data('close-on-overlay');
        var buttonClose = self.find('[button-close]');

        content.on(VK_CLICK, function ( event ) { event.stopPropagation(); });

        if ( closeOnOverlay === true )
            self.on(VK_CLICK, function () { close(); });

        buttonClose.on(VK_CLICK, function () { close(); });

        function close ()
        {
            $('body').removeClass('noscroll');
            self.removeClass('view');
        }
    });
    /** END **/



    /*
    * SmoothScroll
    */
    MyDocument.on(VK_CLICK, '[smooth-scroll]', function ( event )
    {
        if ( location.pathname.replace(/^\//, "") === this.pathname.replace(/^\//, "") && location.hostname === this.hostname )
        {
            var element = $(this.hash);
                element = element.length ? element : $("[name=" + this.hash.slice(1) + "]");

            if ( element.length )
            {
                $("html,body").animate({scrollTop: element.offset().top}, 1000);
                return false;
            }
        }
    });
    /** END **/
});

/* FUNCIONES
------------------------------------------------------------------------------*/
/**
* @name Modal
* @description Ventanas modal
*
* @return var myModal
*   @description El propio elemento modal en jQuery
*
*   @return object
*
* @return function close()
*   @description accion para cerrar el modal
*
*   @return void
*
* @return function onCancel()
*   @description accion para cancelar el modal
*   @param function callback
*
*   @return void
*
* @return function onSuccess()
*   @description accion para cuando existe un exito
*   @param function callback
*
*   @return object
*/
$.fn.modal = function ()
{
    var self = $( this );
    var buttonCancel = self.find('[button-cancel]');
    var buttonSuccess = self.find('[button-success]');

    var modal = {
        myModal: self,
        open: function ()
        {
            $('body').addClass('noscroll');
            self.addClass('view').animate({ scrollTop: 0 }, 300);
        },
        close: function ()
        {
            $('body').removeClass('noscroll');
            self.removeClass('view');
        },
        onCancel: function ( callback )
        {
            buttonCancel.on(VK_CLICK, function ()
            {
                if ( callback != null )
                    callback();

                modal.close();
            });
        },
        onSuccess: function ( callback )
        {
            buttonSuccess.on(VK_CLICK, function ()
            {
                if ( callback != null )
                    callback( modal );
            });
        }
    };

    return modal;
};
/** END **/



/**
* @name Parallax
* @description Efecto parallax para un elemento en el DOM
* @param int velocidad de movimiento
* @param string (up|down) direccion de movimiento.
*
* @return void
*/
$.fn.parallax = function ( speed, direction )
{
    var self = $( this );
    var config = {};
    var MyDocument = $( document );
    var touchSupported = ( ( 'ontouchstart' in window ) || window.DocumentTouch && document instanceof DocumentTouch );

    config.parallax = self.find('[data-parallax]');
    config.pixelsTop = self.offset().top;
    config.startParallax = config.pixelsTop - $( window ).height();
    config.speed = speed ? speed : 5;
    config.direction = direction == 'up' ? '-' : '';

    MyDocument
        .each(function () { scroller(); })
        .on('scroll', function () { scroller(); });

    if ( touchSupported )
        MyDocument.on('touchmove', function () { scroller(); });

    function scroller()
    {
        if ( MyDocument.scrollTop() >= config.startParallax )
        {
            var scroll = ( ( MyDocument.scrollTop() - config.startParallax ) / config.speed );

            config.parallax.css({
                '-webkit-transform': 'translate(0px, '+ config.direction + scroll +'px)',
                'transform': 'translate(0px, '+ config.direction + scroll +'px)'
            });
        }
        else
        {
            config.parallax.css({
                '-webkit-transform': 'translate(0px, 0px)',
                'transform': 'translate(0px, 0px)'
            });
        }
    }
}
/** END **/



/**
* @name ScrollDown
* @description Agrega una clase al momento de hacer scrolldown en un elemento establecido, si no se establece usara el documento.
* @param $class string clase a colocar al momento de cumplir la distancia de scroll
* @param $distance int distancia que debe cumplir
* @param $container element|null elemento que tomara de referencia para el scroll, si no se coloca usara el documento
*
* @return void;
*/
$.fn.scrollDown = function ( $class, $distance, $container )
{
    var self = $(this);

    if ( !$container )
        $container = $( document );

    $container
        .each(function () { scroller() })
        .on("scroll", function () { scroller() });

    function scroller ()
    {
        if ( $container.scrollTop() > $distance )
            self.addClass($class);
        else
            self.removeClass($class);
    }
}
/** END **/



/**
* @name Toggles
* @description secciones en forma de acordion, para usar en FAQ'S
*
* @return void;
*/
$.fn.toggles = function ( accordion = false )
{
    var self        = $( this );
    var button      = self.find('section.toggle > h3');

    self.find('section.toggle.view > div').show();

    button.on(VK_CLICK, function ()
    {
        var toggle = $( this ).parents('section.toggle');

        if ( accordion == true )
        {
            if ( !toggle.hasClass('view') )
            {
                toggle.addClass('view');
                toggle.find('> div').slideDown(300);
            }
            else
            {
                toggle.removeClass('view');
                toggle.find('> div').slideUp(300);
            }
        }
        else
        {
            if ( !toggle.hasClass('view') )
            {
                self.find('section.toggle.view').removeClass('view');
                toggle.addClass('view');

                self.find('section.toggle > div').slideUp(300);
                toggle.find('> div').slideDown(300);
            }
        }
    });
}
/** END **/



/**
* @name Multi-Tabs
* @description Crea multiples pestañas en un contenedor.
*
* @return object;
*/
$.fn.multiTabs = function ()
{
    var self = $( this );
    var buttonTarget = self.find('[data-tab-target]:not([disabled])');
    var onChange = null;
    var multitab = {
        tabActive: null,
        onInit: function ( callback )
        {
            if ( callback != null )
                callback();
        },
        onChange: function ( callback )
        {
            if ( $.isFunction( callback ) )
                onChange = callback;
        },
        goTab: function ( tab = false )
        {
            openTab( tab );
        }
    };

    $('.multi-tabs').each(function ()
    {
        multitab.onInit();

        openTab($( this ).data('tab-active'));
    });

    buttonTarget.on(VK_CLICK, function ()
    {
        openTab($( this ).data('tab-target'));
    });

    function openTab( tab = false )
    {
        if ( tab == false )
            return false;

        if ( multitab.tabActive == tab )
            return false;

        if ( !self.find('[data-target="'+ tab +'"]').length )
            return false;

        multitab.tabActive = tab;

        if ( $.isFunction( onChange ) )
            onChange();

        self.find('[data-tab-target]').removeClass('active');
        self.find('[data-target]').slideUp(300);

        self.find('[data-tab-target="'+ tab +'"]').addClass('active');
        self.find('[data-target="'+ tab +'"]').slideDown(300);
    }

    return multitab;
}
/** END **/
