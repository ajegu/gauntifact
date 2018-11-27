/**
 * Affiche le formulaire de création
 */
export function showAdd()
{
    const l = Ladda.create( document.querySelector('#btn-show-add-gauntlet') )
    l.start()

    const url = Routing.generate('app_gauntlet_add');
    $.ajax(url, {
        success: function(data) {
            l.stop()
            $('#modal-add-gauntlet').remove()
            $('body').append(data)
            $('#modal-add-gauntlet').modal('show')
            $('#modal-add-gauntlet').on('shown.bs.modal', function () {
                $('input[autofocus]').trigger('focus')
            })
        },
        error: function() {
            l.stop()
            $.notify({
                // options
                message: 'Server error',
            },{
                // settings
                type: 'danger',
                placement: {
                    from: 'bottom',
                    align: 'right'
                },
                delay: 5000,
                timer: 1000,
                offset: {
                    x: 10,
                    y: 0
                },
                animate: {
                    // enter: 'animated fadeInDown',
                    // exit: 'animated fadeOutUp'
                    enter: 'animated bounceInUp',
                    exit: 'animated bounceOutDown'
                }
            });
        }
    })
}

/**
 * Création d'un affrontement
 * @param EventTarget e
 */
export function add(e)
{
    e.preventDefault();

    console.log('soumission du formulaire')
}