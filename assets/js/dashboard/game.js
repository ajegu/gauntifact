/**
 * Affiche le formulaire de création
 */
window.showAddGame = function()
{
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_game_add', {id: gauntletId});

    Utils.showFormModal({
        btnSelector: '#btn-add-game',
        url: url,
        modalSelector: '#modal-add-game'
    });
}

/**
 * Création d'une game
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

/**
 * Affiche la modal de modification d'une game
 * @param EventTarget e
 */
window.showEditGame = function(e)
{
    const gameId = $(e.currentTarget).attr('data-game-id');
    const url = Routing.generate('app_game_edit', {id: gameId});

    Utils.showFormModal({
        btnElement: e.currentTarget,
        url: url,
        modalSelector: '#modal-edit-game'
    });
}

/**
 * Modification d'une game
 * @param EventTarget e
 */
window.editGame = function(e) {
    e.preventDefault()

    const gameId = $('.game').attr('data-game-id');
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');
    const url = Routing.generate('app_game_edit', {id: gameId});

    Utils.submitFormModal({
        url: url,
        currentTarget: e.currentTarget,
        modalSelector: '#modal-edit-game',
        callback: function() {
            const url = Routing.generate('app_gauntlet_show', {id: gauntletId})
            Utils.redirect(url)
        }
    })
}

/**
 * Affiche la modal de confirmation de suppression d'une game
 * @param EventTarget e
 */
window.showDeleteGame = function(e)
{
    const gameId = $(e.currentTarget).attr('data-game-id');
    const url = Routing.generate('app_game_delete', {id: gameId});

    Utils.showFormModal({
        btnElement: e.currentTarget,
        url: url,
        modalSelector: '#modal-delete-game'
    });
}

/**
 * Suppression d'une game
 * @param EventTarget e
 */
window.deleteGame = function(e) {
    e.preventDefault()

    const gameId = $('.game').attr('data-game-id');
    const gauntletId = $('.gauntlet').attr('data-gauntlet-id');

    const url = Routing.generate('app_game_delete', {id: gameId});

    Utils.submitFormModal({
        url: url,
        currentTarget: e.currentTarget,
        modalSelector: '#modal-delete-game',
        callback: function() {
            const url = Routing.generate('app_gauntlet_show', {id: gauntletId})
            Utils.redirect(url)
        }
    })
}