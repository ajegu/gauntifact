/**
 * Affiche le formulaire de création
 */
window.showAddGame = function()
{
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_game_add', {id: gauntletId});

    Utils.showFormModal({
        btnSelector: '#btn-show-add-game',
        url: url,
        modalSelector: '#modal-add-game'
    });
}

/**
 * Création d'un affrontement
 * @param EventTarget e
 */
window.addGame = function(e) {
    e.preventDefault()

    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_game_add', {id: gauntletId});

    Utils.submitFormModal({
        url: url,
        currentTarget: e.currentTarget,
        modalSelector: '#modal-add-game',
        callback: function() {
            const url = Routing.generate('app_gauntlet_show', {id: gauntletId})
            Utils.redirect(url)
        }
    })
}