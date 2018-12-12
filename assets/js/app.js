/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss')

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery')
global.$ = global.jQuery = $


require('bootstrap')
require('popper.js')
require('@fortawesome/fontawesome-free/js/all')
require('bootstrap4-notify')
require('datatables.net-bs4')
require('pc-bootstrap4-datetimepicker')

$.extend(true, $.fn.datetimepicker.defaults, {
    icons: {
        time: 'far fa-clock',
        date: 'far fa-calendar',
        up: 'fas fa-arrow-up',
        down: 'fas fa-arrow-down',
        previous: 'fas fa-chevron-left',
        next: 'fas fa-chevron-right',
        today: 'fas fa-calendar-check',
        clear: 'far fa-trash-alt',
        close: 'far fa-times-circle'
    }
});

const Ladda = require('ladda')
global.Ladda = Ladda

require('chart.js')

import moment from 'moment'
global.moment = moment

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js'

Routing.setRoutingData(routes)
global.Routing = Routing

const Utils = require('./utils');
global.Utils = Utils

require('./dashboard/gauntlet')
require('./dashboard/game')
require('./dashboard/dashboard')

global.spinner = `<div class="lds-facebook d-block mx-auto"><div></div><div></div><div></div></div>`

$('a').click(function() {
    $('.body-content').html(spinner)
})