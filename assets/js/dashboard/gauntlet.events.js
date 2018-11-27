
import { showAdd, add } from './gauntlet.actions'

$(document).ready(function() {

    $('#btn-show-add-gauntlet').click(showAdd)
    $('form[name="gauntlet"]').submit(add)

})