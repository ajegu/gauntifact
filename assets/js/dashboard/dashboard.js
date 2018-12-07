function removeActive(e)
{
    $(e.currentTarget).parent().children('button').removeClass('active');
    $(e.currentTarget).addClass('active')
}

/**
 * Affiche les statistiques du jour
 */
window.showTodayStats = function(e)
{
    removeActive(e)

    const l = Ladda.create( e.currentTarget )
    l.start()

    $('.dashboard-content').html(spinner)

    const url = Routing.generate('app_dashboard_stats')

    $.ajax(url, {
        success: function(data) {
            l.stop()
            $('.dashboard-content').html(data)
        },
        error: function() {
            Utils.errorNotify(l)
        }
    })
}

/**
 * Affiche les statistiques de la semaine
 */
window.showWeekStats = function(e)
{
    removeActive(e)

    const l = Ladda.create( e.currentTarget )
    l.start()

    $('.dashboard-content').html(spinner)

    const startDate = moment().startOf('isoWeek').format('YYYY-MM-DD')
    const endDate = moment().endOf('isoWeek').format(('YYYY-MM-DD'))

    const url = Routing.generate('app_dashboard_stats', {
        'startDate': startDate,
        'endDate': endDate
    })

    $.ajax(url, {
        success: function(data) {
            l.stop()
            $('.dashboard-content').html(data)
        },
        error: function() {
            Utils.errorNotify(l)
        }
    })
}

/**
 * Affiche les statistiques du mois
 */
window.showMonthStats = function(e)
{
    removeActive(e)

    const l = Ladda.create( e.currentTarget )
    l.start()

    $('.dashboard-content').html(spinner)

    const startDate = moment().startOf('month').format('YYYY-MM-DD')
    const endDate = moment().endOf('month').format(('YYYY-MM-DD'))

    const url = Routing.generate('app_dashboard_stats', {
        'startDate': startDate,
        'endDate': endDate
    })

    $.ajax(url, {
        success: function(data) {
            l.stop()
            $('.dashboard-content').html(data)
        },
        error: function() {
            Utils.errorNotify(l)
        }
    })
}

/**
 * Affiche les statistiques selon une plage de dates
 */
window.showCustomDatesStats = function(e)
{
    const startDate = $('#txtStartDate').val();
    const endDate = $('#txtEndDate').val();

    if (startDate == '' || endDate == '') {
        return;
    }

    $('#btn-show-custom-stats').parent().children('button').removeClass('active');
    $('#btn-show-custom-stats').addClass('active')

    const l = Ladda.create( e.currentTarget )
    l.start()

    $('.dashboard-content').html(spinner)

    const url = Routing.generate('app_dashboard_stats', {
        'startDate': startDate,
        'endDate': endDate
    })

    $.ajax(url, {
        success: function(data) {
            l.stop()
            $('.dashboard-content').html(data)
        },
        error: function() {
            Utils.errorNotify(l)
        }
    })
}