
/**
 * Affiche le formulaire de création
 */
window.showAddGauntlet = function()
{
    const url = Routing.generate('app_gauntlet_add');

    Utils.showFormModal({
        btnSelector: '#btn-show-add-gauntlet',
        url: url,
        modalSelector: '#modal-add-gauntlet'
    })
}

/**
 * Création d'un affrontement
 * @param EventTarget e
 */
window.addGauntlet = function(e)
{
    e.preventDefault()

    Utils.submitFormModal({
        url: Routing.generate('app_gauntlet_add'),
        currentTarget: e.currentTarget,
        modalSelector: '#modal-add-gauntlet',
        callback: function(data) {
            const url = Routing.generate('app_gauntlet_show', {id: data.gauntletId})
            Utils.redirect(url)
        }
    })
}

/**
 * Dévérouiller un affrontement
 */
window.unlockGauntlet = function()
{
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_gauntlet_unlock', {id: gauntletId})

    const l = Ladda.create( document.querySelector('#btn-unlock-gauntlet') )

    l.start()

    $.ajax(url, {
        success: function (data) {
            l.stop()

            if (data.success) {

                const url = Routing.generate('app_gauntlet_show', {id: gauntletId})
                Utils.redirect(url)

            } else {
                // Gestion du DOM
                $('body').append(data)

                // Affichage de la modale
                $('#modal-unlock-gauntlet').modal('show')

                $('#modal-unlock-gauntlet').on('hidden.bs.modal', function () {
                    $('#modal-unlock-gauntlet').remove()
                })
            }


        },
        error: function () {
            Utils.errorNotify(l)
        }
    })
}

/**
 * Vérouiller un affrontement
 */
window.lockGauntlet = function()
{
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_gauntlet_lock', {id: gauntletId})

    const l = Ladda.create( document.querySelector('#btn-lock-gauntlet') )

    l.start()

    $.ajax(url, {
        success: function (data) {
            l.stop()

            if (data.success) {
                const url = Routing.generate('app_gauntlet_show', {id: gauntletId})
                Utils.redirect(url)
            }

        },
        error: function () {
            Utils.errorNotify(l)
        }
    })
}

/**
 * Concéder un affrontement
 */
window.concedeGauntlet = function()
{
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_gauntlet_concede', {id: gauntletId})

    const l = Ladda.create( document.querySelector('#btn-concede-gauntlet') )

    l.start()

    $.ajax(url, {
        success: function (data) {
            l.stop()

            if (data.success) {
                const url = Routing.generate('app_gauntlet_show', {id: gauntletId})
                Utils.redirect(url)
            }

        },
        error: function () {
            Utils.errorNotify(l)
        }
    })
}