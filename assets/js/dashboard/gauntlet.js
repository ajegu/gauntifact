
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
    });
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