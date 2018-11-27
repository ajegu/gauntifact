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

const Ladda = require('ladda')
global.Ladda = Ladda

const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);
global.Routing = Routing

require('./dashboard/gauntlet.events')

// console.log('Hello Webpack Encore! Edit me in assets/js/app.js')
