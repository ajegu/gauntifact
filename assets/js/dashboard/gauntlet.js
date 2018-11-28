
/**
 * Affiche le formulaire de création
 */
window.showAddGauntlet = function()
{
    Utils.showFormModal({
        btnSelector: '#btn-show-add-gauntlet',
        route: 'app_gauntlet_add',
        modalSelector: '#modal-add-gauntlet'
    });
}

/**
 * Création d'un affrontement
 * @param EventTarget e
 */
window.addGauntlet = function(e)
{
    e.preventDefault()


    const l = Ladda.create( document.querySelector('button[type="submit"]') )
    l.start()

    const $form = $(e.currentTarget)

    const url = Routing.generate('app_gauntlet_add')

    $.ajax(url, {
        method: 'post',
        data: $form.serialize(),
        success: function(data) {
            l.stop()
            if (data.success) {
                $('#modal-add-gauntlet').modal('hide')

                Utils.notify({
                    type: 'success',
                    message: data.message
                });

                const url = Routing.generate('app_gauntlet_show', {id: data.gauntletId})
                Utils.redirect(url)

            } else {
               $('#modal-add-gauntlet .modal-body').replaceWith($('.modal-body', data))
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