/**
 * Affiche le formulaire de création
 */
export function showAdd() {
    const l = Ladda.create( document.querySelector('#btn-show-add-gauntlet') )
    l.start()

    const url = Routing.generate('app_gauntlet_add');
    console.log(url)
}

