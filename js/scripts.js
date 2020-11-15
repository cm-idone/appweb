'use strict';

/**
* @package valkyrie.js
*
* @summary Stock de funciones.
*
* @author Gersón Aarón Gómez Macías <ggomez@codemonkey.com.mx>
* <@create> 01 de enero, 2019.
*
* @version 1.0.0.
*
* @copyright Code Monkey <contacto@codemonkey.com.mx>
*/

/**
* @summary Ejecuta el Data Loader al ejecutarse una acción Ajax.
*/
$(window).on('beforeunload ajaxStart', function()
{
    $('body').prepend('<div data-ajax-loader><div class="loader"></div></div>');
});

/**
* @summary Detiene el Data Loader al terminar de ejecutarse una acción Ajax.
*/
$(window).on('ajaxStop', function()
{
    $('body').find('[data-ajax-loader]').remove();
});

$(document).ready(function()
{
    /**
    * @summary Abre y cierra el menú derecho del dashboard.
    */
    $('[data-action="open_rightbar"]').on('click', function(e)
    {
        e.stopPropagation();

        $('header.rightbar').toggleClass('open');
        $('body').toggleClass('open');
    });

    /**
    * @summary Solicita cambiar de cuenta en linea en la sesión del usuario logueado.
    */
    $('[data-action="switch_account"]').on('click', function()
    {
        $.ajax({
            url: '/system',
            type: 'POST',
            data: 'action=switch_account&id=' + $(this).data('id'),
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

    /**
    * @summary Solicita destruir la sesión del usuario logueado.
    */
    $('[data-action="logout"]').on('click', function()
    {
        $.ajax({
            url: '/system',
            type: 'POST',
            data: 'action=logout',
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                window.location.href = response.path;
            }
        });
    });

    /**
    * @summary Redirecciona al usuario a la página anterior.
    */
    $('[data-action="go_back"]').on('click', function()
    {
        window.history.back();
    });

    /**
    * @summary Ejecuta la función uploader de tipo low.
    */
    $('[data-low-uploader]').each(function()
    {
        uploader('low', $(this));
    });

    /**
    * @summary Ejecuta la función uploader de tipo fast.
    */
    $('[data-fast-uploader]').each(function()
    {
        uploader('fast', $(this));
    });

    /**
    * @summary Agrega una imagen como background.
    */
    $('[data-image-src]').each(function()
    {
        $(this).css('background-image', 'url("' + $(this).data('image-src') + '")');
    });

    /**
    * @summary Checkbox st-1
    */
    $('.checkbox.st-1').find('input[type="checkbox"]').on('change', function()
    {
        $(this).parents('fieldset').find('[data-search]').val('');
        $(this).parents('fieldset').find('[data-search]').focus();
    });

    /**
    * @summary Checkbox st-4
    */
    $('.checkbox.st-4').find('input[type="checkbox"]').on('change', function()
    {
        $(this).parents('fieldset').find('[data-search]').val('');

        var self = $(this);
        var targets = self.parents('[data-table]').find('[data-target]');

        $.each(targets, function(key, value)
        {
            var name = self.val() + '[]';
            var checkbox = value.getElementsByTagName('input')[0];
            var input = value.getElementsByTagName('input')[1];
            var select = value.getElementsByTagName('select')[0];
            var target = value.getAttribute('data-target');

            if (input.getAttribute('name') == name)
            {
                input.value = '';
                select.value = '';

                if (checkbox.checked == true)
                    input.focus();
                else
                    self.parents('fieldset').find('[data-search]').focus();
            }
            else
            {
                if (target == 'required')
                {
                    if (input.value == '' || input.value <= '0' || select.value == '')
                    {
                        checkbox.checked = false;
                        input.value = '';
                        select.value = '';
                    }
                }
            }
        });
    });

    $('.checkbox.st-4').find('input[type="text"]').on('keyup', function()
    {
        validate_string('float', $(this).val(), $(this));

        if ($(this).val().length > 0)
            $(this).parent().find('input[type="checkbox"]').prop('checked', true);
        else
        {
            $(this).parent().find('input[type="checkbox"]').prop('checked', false);
            $(this).parent().find('select').val('');
        }
    });

    $('.checkbox.st-4').find('select').on('change', function()
    {
        $(this).parent().find('input[type="checkbox"]').prop('checked', true);

        if ($(this).parent().find('input[type="text"]').val() <= '0')
            $(this).parent().find('input[type="text"]').val('');

        $(this).parent().find('input[type="text"]').focus();
    });

    /**
    * @summary Compound st-5
    */
    $('.compound.st-5-double').find('input[type="radio"]').on('click', function()
    {
        $(this).parents('.compound.st-5-double').find('span').removeClass('checked');
        $(this).parents('label').find('span').addClass('checked');
    });

    $('.compound.st-5-triple').find('input[type="radio"]').on('click', function()
    {
        $(this).parents('.compound.st-5-triple').find('span').removeClass('checked');
        $(this).parents('label').find('span').addClass('checked');
    });

    /**
    * @summary Compound st-6
    */
    $('.compound.st-6').find('[data-search] > input').on('keyup', function()
    {
        search_in_table($(this).val(), $(this).parents('.compound.st-6').find('[data-list]'), 'hidden');
    });

    $('.compound.st-6').find('[data-list] > [data-success]').on('click', function()
    {
        $(this).parents('.compound.st-6').find('[data-preview] > input').val($(this).data('value'));
        $(this).parents('.compound.st-6').find('[data-preview] > div').html($(this).parent().find('div').html());
        $(this).parents('.compound.st-6').find('[data-preview]').addClass('open');
        $(this).parents('.compound.st-6').find('[data-search] > input').val('');
        $(this).parents('.compound.st-6').find('[data-search]').addClass('close');
        $(this).parents('.compound.st-6').find('[data-list]').addClass('hidden');
    });

    $('.compound.st-6').find('[data-preview] > [data-cancel]').on('click', function()
    {
        $(this).parents('.compound.st-6').find('[data-preview] > input').val('');
        $(this).parents('.compound.st-6').find('[data-preview] > div').html('');
        $(this).parents('.compound.st-6').find('[data-preview]').removeClass('open');
        $(this).parents('.compound.st-6').find('[data-search] > input').val('');
        $(this).parents('.compound.st-6').find('[data-search]').removeClass('close');
        $(this).parents('.compound.st-6').find('[data-list]').addClass('hidden');

        $(this).parents('.compound.st-6').find('[data-search] > input').focus();
    });

    /**
    * @summary Escucha los signatures canvas en el on resize.
    */
    window.onresize = resize_canvas;
});

/**
* @summary Busca una cadena de texto en una tabla.
*
* @var string data: Cadena de texto que se va a buscar.
* @var <HTML Tag> target: Etiqueta HTML en la que se va a realizar la búsqueda.
* @var string style: (tbl, cbx) Estilo de busqueda.
* @var string type: (normal, hidden) Tipo de busqueda.
*/
function search_in_table(data, target, type)
{
    type = (type == undefined) ? 'normal' : 'hidden';

    $.each(target, function(key, value)
    {
        var inputs = value.getElementsByTagName('input');

        if (data.length > 0)
        {
            var string_1 = data.toLowerCase();
            var string_2 = value.innerHTML.toLowerCase();
            var result = string_2.indexOf(string_1);

            if (result > 0)
                value.className = '';
            else if (result <= 0)
                value.className = (type == 'hidden') ? ((inputs.length == 0) ? 'hidden' : ((inputs.length > 0 && inputs[0].checked == false) ? 'hidden' : '')) : 'hidden';
        }
        else if (data.length <= 0 && type == 'normal')
            value.className = '';
        else if (data.length <= 0 && type == 'hidden')
            value.className = (inputs.length == 0) ? 'hidden' : ((inputs.length > 0 && inputs[0].checked == false) ? 'hidden' : '');
    });
}

/**
* @summary Valida los valores de una cadena de texto en una etiqueta HTML <input>.
*
* @param string option: (empty, uppercase, lowercase, int, float) Tipo de cadena de texto permitida.
* @param string data: Cadena de texto a validar.
* @param <input> target: Etiqueta HTML donde retornará la validación.
*/
function validate_string(option, data, target)
{
    if (option == 'empty')
        return (data == undefined || data == null || data == '') ? true : false;
    else if (option == 'uppercase' || option == 'lowercase' || option == 'int' || option == 'float')
    {
        var filter = '';
        var uppercase = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
        var lowercase = 'abcdefghijklmnñopqrstuvwxyz';
        var numbet_int = '0123456789';
        var number_float = '.';

        if (Array.isArray(option))
        {
            $.each(option, function(key, value)
            {
                if (value == 'uppercase')
                    filter = filter + uppercase;
                else if (value == 'lowercase')
                    filter = filter + lowercase;
                else if (value == 'int')
                    filter = filter + numbet_int;
                else if (value == 'float')
                    filter = filter + number_float + numbet_int;
            });
        }
        else if (option == 'uppercase')
            filter = uppercase;
        else if (option == 'lowercase')
            filter = lowercase;
        else if (option == 'int')
            filter = numbet_int;
        else if (option == 'float')
            filter = number_float + numbet_int;

        var out = '';

        for (var i = 0; i < data.length; i++)
        {
            if (filter.indexOf(data.charAt(i)) != -1)
                out += data.charAt(i);
        }

        target.val(out);
    }
}

/**
* @summary Genera una cadena de texto random.
*
* @param string option: (uppercase, lowercase, int, float) Tipo de cadena de texto que se va a generar.
* @param string length: Tamaño de la cadena de texto que se va a generar.
* @param <input> target: Etiqueta HTML donde retornará la cadena de texto generada.
*/
function generate_string(option, length, target)
{
    var filter = '';
    var uppercase = 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ';
    var lowercase = 'abcdefghijklmnñopqrstuvwxyz';
    var numbet_int = '0123456789';
    var number_float = '.';

    if (Array.isArray(option))
    {
        $.each(option, function(key, value)
        {
            if (value == 'uppercase')
                filter = filter + uppercase;
            else if (value == 'lowercase')
                filter = filter + lowercase;
            else if (value == 'int')
                filter = filter + numbet_int;
            else if (value == 'float')
                filter = filter + number_float + numbet_int;
        });
    }
    else if (option == 'uppercase')
        filter = uppercase;
    else if (option == 'lowercase')
        filter = lowercase;
    else if (option == 'int')
        filter = numbet_int;
    else if (option == 'float')
        filter = number_float + numbet_int;

    var out  = '';

    for (var x = 0; x < length; x++)
    {
        var math = Math.floor(Math.random() * filter.length);
        out += filter.substr(math, 1);
    }

    target.val(out);
}

/**
* @summary Variables para trabajar en el CRUD.
*
* @var string action: Almacena la acción que ejecutará el CRUD.
* @var int id: Almacena el id del registro en la base de datos con el que se trabajará en el CRUD.
*/
var action = null;
var id = null;

/**
* @summary Abre el modal para trabajar en el CRUD.
*
* @var string option: (create, update, delete) Tipo de modal que se abrirá.
* @var <[data-modal]> target: Modal que se abrirá.
* @var function callback: Acciones que se ejecutarán al terminar de abrí el modal.
*/
function open_form_modal(option, target, callback)
{
    if (option == 'create' || option == 'read' || option == 'update' || option == 'filter')
    {
        if (option == 'create' || option == 'update')
            reset_form(target.find('form'));

        if (option == 'read' || option == 'update')
        {
            $.ajax({
                type: 'POST',
                data: 'action=' + action + '&id=' + id,
                processData: false,
                cache: false,
                dataType: 'json',
                success: function(response)
                {
                    if (response.status == 'success')
                        callback(response.data);
                    else if (response.status == 'error')
                        open_notification_modal('alert', response.message);
                }
            });
        }
    }

    target.addClass('view');
}

/**
* @summary Envía el modal con el que se está trabajando en el CRUD al controlador.
*
* @var string option: (create, update, block, unblock, delete) Tipo de envío.
* @var <form> target: Formulario que se enviará.
* @var Event event: Evento de formulario.
*/
function send_form_modal(option, target, event)
{
    if (option == 'create' || option == 'update' || option == 'filter')
    {
        event.preventDefault();

        var data = new FormData(target[0]);

        data.append('action', action);
        data.append('id', id);

        $.ajax({
            type: 'POST',
            data: data,
            contentType: false,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                check_form_errors(target, response, function()
                {
                    open_notification_modal('success', response.message);
                });
            }
        });
    }
    else if (option == 'block' || option == 'unblock')
    {
        $.ajax({
            type: 'POST',
            data: 'action=' + action + '&id=' + id,
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
    }
    else if (option == 'delete')
    {
        $.ajax({
            type: 'POST',
            data: 'action=' + action + '&id=' + id,
            processData: false,
            cache: false,
            dataType: 'json',
            success: function(response)
            {
                if (response.status == 'success')
                    open_notification_modal('success', response.message);
                else if (response.status == 'error')
                    open_notification_modal('alert', response.message);
            }
        });
    }
}

/**
* @summary Transforma el modal para trabaja en el CRUD.
*
* @var string option: (create, update) Tipo de modal que se abrirá.
* @var HTML Object target: Modal donde se aplicará la transformación.
*/
function transform_form_modal(option, target)
{
    if (option == 'create')
    {
        target.find('form').find('button[type="submit"]').html('<i class="fas fa-plus"></i>');
        target.find('form').find('button[type="submit"]').removeClass('warning');
        target.find('form').find('button[type="submit"]').addClass('success');
    }
    else if (option == 'update')
    {
        target.find('form').find('button[type="submit"]').html('<i class="fas fa-pen"></i>');
        target.find('form').find('button[type="submit"]').removeClass('success');
        target.find('form').find('button[type="submit"]').addClass('warning');
    }
}

/**
* @summary Restablece los datos de un formulario.
*
* @param <form> target: Formulario a restablecer.
*/
function reset_form(target)
{
    target[0].reset();
    target.find('.uploader').find('img').attr('src', '../images/empty.png');
    target.find('p.error').remove();
    target.find('.error').removeClass('error');
}

/**
* @summary Abre el modal de notificación.
*
* @var string option: (success, alert) Tipo de notificación.
* @var string message: Mensaje que mostrará el modal
* @var string path: Ruta de recarga o redirección.
* @var string timeout: Tiempo en que se ejecura la recarga o redirección.
*/
function open_notification_modal(option, message, path, timeout)
{
    message = (message == undefined) ? '' : message;
    path = (path == undefined) ? ((option == 'success') ? 'reload' : false) : path;
    timeout = (timeout == undefined) ? '500' : timeout;

    if (option == 'success')
    {
        $('[data-modal="success"]').addClass('view');
        $('[data-modal="success"]').find('main > p').html(message);
    }
    else if (option == 'alert')
    {
        $('[data-modal="alert"]').addClass('view');
        $('[data-modal="alert"]').find('main > p').html(message);
    }

    if (path != false)
    {
        setTimeout(function()
        {
            if (path == 'reload')
                location.reload();
            else
                window.location.href = path;
        }, timeout);
    }
}

/**
* @summary Revisa los errores que retornó el controlador y los aplica visualmente.
*
* @param <form> target: Formulario a revisar.
* @param Ajax response response: Respuesta del controlador.
* @param Function callback: Acciones que se ejecutarán en caso que no haya errores.
*/
function check_form_errors(target, response, callback)
{
    target.find('[name]').parents('.error').find('p.error').remove();
    target.find('[name]').parents('.error').removeClass('error');

    if (response.status == 'success')
        callback();
    else if (response.status == 'error')
    {
        if (Array.isArray(response.errors))
        {
            $.each(response.errors, function (key, value)
            {
                target.find('[name="' + value[0] + '"]').parent().addClass('error');
                target.find('[name="' + value[0] + '"]').parent().append('<p class="error">'+ value[1] +'</p>');
            });

            target.find('input[name="'+ response.errors[0][0] +'"]').focus();
        }
        else
            open_notification_modal('alert', response.message);
    }
}

/**
* @summary Envia archivos al controlador para que se suban al almacenamiento.
*
* @param string option: (low, fast) Tipo de subida.
* @param <[data-uploader]> target: Etiqueta HTML del uploader.
*/
function uploader(option, target)
{
    target.find('a[data-select]').on('click', function()
    {
        target.find('input[data-select]').click();
    });

    target.find('input[data-select]').on('change', function()
    {
        var accept = false;
        var accepts = $(this).attr('accept').split(',');
        var files = $(this)[0].files[0];

        $.each(accepts, function (key, value)
        {
            if (files.type.match(value))
                accept = true;
        });

        if (accept == true)
        {
            if (option == 'low')
            {
                var reader = new FileReader();

                reader.onload = function(e)
                {
                    var extension = e.target.result.split(';');
                    extension = extension[0].split('/');

                    if (extension[1] == 'pdf')
                        target.find('[data-preview] > img').attr('src', '/../images/pdf.png');
                    else
                        target.find('[data-preview] > img').attr('src', e.target.result);
                }

                reader.readAsDataURL(files);
            }
            else if (option == 'fast')
            {
                var data = new FormData();

                data.append('action', action);
                data.append('file', files);

                $.ajax({
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: 'json',
                    success: function(response)
                    {
                        if (response.status == 'success')
                            open_notification_modal('success', response.message, true);
                        else if (response.status == 'error')
                            open_notification_modal('alert', response.message);
                    }
                });
            }
        }
        else
            open_notification_modal('alert', 'ERROR');
    });
}

/**
* @summary Agrega un clase de css a una etiqueta al detectar un scroll down.
*
* @param string target: Etiqueta a la que se agregará la clase.
* @param string style: Clase de css que se agregará.
* @param string height: Medida en la cual se agregará la clase a la etiqueta dentro del scroll down.
*
* @return object
*/
function nav_scroll_down(target, style, height, lt_target, lt_img_1, lt_img_2)
{
    var nav = {
        initialize: function()
        {
            $(document).each(function()
            {
                nav.scroller()
            });

            $(document).on('scroll', function()
            {
                nav.scroller()
            });
        },
        scroller: function()
        {
            if ($(document).scrollTop() > height)
            {
                if (lt_target || lt_img_2)
                    $(lt_target).attr('src', lt_img_2);

                $(target).addClass(style);
            }
            else
            {
                if (lt_target || lt_img_1)
                    $(lt_target).attr('src', lt_img_1);

                $(target).removeClass(style);
            }
        }
    }

    nav.initialize();
}

/**
* @summary Renderiza el canvas de una firma digital.
*
* @param <canvas> target: Etiqueta HTML del canvas.
*/
function resize_canvas(target)
{
    var ratio = Math.max(window.devicePixelRatio || 1, 1);

    target.width = target.offsetWidth * ratio;
    target.height = target.offsetHeight * ratio;
    target.getContext('2d').scale(ratio, ratio);
}
