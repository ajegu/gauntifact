/**
 *
 * @param config
 * {
 *     type,
 *     message,
 * }
 */
export function notify(config)
{
    let width = 200;

    if (config.message.length * 100 > width) {
        width = 300;
    }

    let icon;
    if (config.type === 'danger') {
        icon = 'fa fa-exclamation-triangle'
    }

    $.notify({
        // options
        message: config.message,
        icon: icon
    },{
        // settings
        type: config.type,
        placement: {
            from: 'bottom',
            align: 'right'
        },
        delay: 1000,
        timer: 1000,
        offset: {
            x: 10,
            y: 0
        },
        animate: {
            enter: 'animated bounceInUp',
            exit: 'animated bounceOutDown'
        },
        width: width,
        z_index: 2000
    });
}

/**
 *
 * @param config
 * {
 *     btnSelector,
 *     btnElement,
 *     modalSelector,
 *     url
 * }
 */
export function showFormModal(config)
{
    let l;
    if (config.btnElement !== undefined) {
        l = Ladda.create( config.btnElement )
    } else if (config.btnSelector !== undefined) {
        l = Ladda.create( document.querySelector(config.btnSelector) )
    }

    l.start()

    $.ajax(config.url, {
        success: function(data) {
            l.stop()

            // Gestion du DOM
            $('body').append(data)

            // Affichage de la modale
            $(config.modalSelector).modal('show')

            // Si un champ de formulaire à l'attribut autofocus, on le repositionne
            $(config.modalSelector).on('shown.bs.modal', function () {
                $('input[autofocus]').trigger('focus')
            })

            $(config.modalSelector).on('hidden.bs.modal', function () {
                $(config.modalSelector).remove()
            })
        },
        error: function() {
            l.stop()
            Utils.notify({
                type: 'danger',
                message: 'Server error'
            })
        }
    })
}

/**
 *
 * @param config
 * {
 *     url,
 *     currentTarget,
 *     modalSelector,
 *     callback
 * }
 */
export function submitFormModal(config)
{
    const l = Ladda.create( document.querySelector('button[type="submit"]') )
    l.start()

    const $form = $(config.currentTarget)

    $.ajax(config.url, {
        method: 'post',
        data: $form.serialize(),
        success: function(data) {
            l.stop()
            if (data.success) {
                $(config.modalSelector).modal('hide')

                $(config.modalSelector).on('hidden.bs.modal', function () {
                    $(config.modalSelector).remove()
                })

                config.callback(data)

            } else {
                $(config.modalSelector + ' .modal-body').replaceWith($('.modal-body', data))
            }
        },
        error: function() {
            l.stop()

            Utils.notify({
                type: 'danger',
                message: 'Server error'
            })
        }
    })
}

export function redirect (url) {
    const ua        = navigator.userAgent.toLowerCase(),
        isIE      = ua.indexOf('msie') !== -1,
        version   = parseInt(ua.substr(4, 2), 10);

    // Internet Explorer 8 and lower
    if (isIE && version < 9) {
        const link = document.createElement('a');
        link.href = url;
        document.body.appendChild(link);
        link.click();
    }

    // All other browsers can use the standard window.location.href (they don't lose HTTP_REFERER like Internet Explorer 8 & lower does)
    else {
        window.location.href = url;
    }
}