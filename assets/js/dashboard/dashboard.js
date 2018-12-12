/**
 * Affiche les statistiques du jour
 */
window.showTodayStats = function()
{
    const url = Routing.generate('app_dashboard', {
        link: 'today',
        'gauntletType': getCurrentGauntletType()
    })

    Utils.redirect(url)
}

/**
 * Affiche les statistiques de la semaine
 */
window.showWeekStats = function()
{
    const startDate = moment().startOf('isoWeek').format('YYYY-MM-DD')
    const endDate = moment().endOf('isoWeek').format(('YYYY-MM-DD'))

    const url = Routing.generate('app_dashboard', {
        'startDate': startDate,
        'endDate': endDate,
        'link': 'week',
        'gauntletType': getCurrentGauntletType()
    })

    Utils.redirect(url)
}

/**
 * Affiche les statistiques du mois
 */
window.showMonthStats = function()
{
    const startDate = moment().startOf('month').format('YYYY-MM-DD')
    const endDate = moment().endOf('month').format(('YYYY-MM-DD'))

    const url = Routing.generate('app_dashboard', {
        'startDate': startDate,
        'endDate': endDate,
        'link': 'month',
        'gauntletType': getCurrentGauntletType()
    })

    Utils.redirect(url)
}

/**
 * Affiche les statistiques selon une plage de dates
 */
window.showCustomDatesStats = function()
{
    const startDate = $('#txtStartDate').val();
    const endDate = $('#txtEndDate').val();

    if (startDate == '' || endDate == '') {
        return;
    }

    const url = Routing.generate('app_dashboard', {
        'startDate': startDate,
        'endDate': endDate,
        'link': 'customDates',
        'gauntletType': getCurrentGauntletType()
    })

    Utils.redirect(url)
}

/**
 * Retourne l'ID du type d'affrontement en paramètre de l'url
 * @returns {*}
 */
function getCurrentGauntletType()
{
    const currentUrl = window.location.href
    const values = currentUrl.split('?')

    if (values[1] === undefined) {
        return null;
    }

    const paramString = values[1]
    const params = paramString.split('&')

    let gauntletType = null;

    params.forEach(function(str) {
        const param = str.split('=');

        if (param[0] === 'gauntletType') {
            gauntletType = param[1]
        }
    })

    return gauntletType
}

/**
 * Affiche les stats pour un type d'affrontement
 *
 * @param e
 */
window.showGauntletTypeStats = function(e)
{
    const currentUrl = window.location.href
    const values = currentUrl.split('?')

    let startDate = null;
    let endDate = null;
    let link = null;

    if (values[1] !== undefined) {
        const paramString = values[1]
        const params = paramString.split('&')


        params.forEach(function(str) {
            const param = str.split('=');

            if (param[0] === 'startDate') {
                startDate = param[1]
            }

            if (param[0] === 'endDate') {
                endDate = param[1]
            }

            if (param[0] === 'link') {
                link = param[1]
            }
        })
    }

    const url = Routing.generate('app_dashboard', {
        'startDate': startDate,
        'endDate': endDate,
        'link': link,
        'gauntletType': $(e.currentTarget).val()
    })

    Utils.redirect(url)

}