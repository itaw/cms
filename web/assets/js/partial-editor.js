$(document).ready(function() {

    $('#app-container').removeClass('container').addClass('container-fluid');

    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/github");
    editor.getSession().setMode("ace/mode/twig");
    editor.getSession().setTabSize(4);
    document.getElementById('editor').style.fontSize='14px';

    var constraints = {
        title: {
            presence: true
        }
    };

    //submit
    $('#btn-submit').click(function(e) {

        e.preventDefault();

        var $btn = $(this).button('loading');

        //collect data
        var data = {
            title: $('#input-title').val(),
            content: editor.getSession().getValue(),
            active: $('#cb-active').prop('checked'),
            sent: 1
        };

        var errors = validate(data, constraints);

        if(errors != undefined && Object.keys(errors).length > 0) {
            for(var prop in errors) {
                swal('Error', errors[prop], 'error');
            }

            $btn.button('reset');

            return;
        }

        //send
        var url = ($('#editor-type').html() == 'create')
            ? Routing.generate('backend_partials_create')
            : Routing.generate('backend_partials_update', {
                partialId: $('#editor-current-id').html()
            })
        ;

        $.post(url, data).success(function(_data) {
            swal({
                title: 'Partial saved!',
                text : 'You will be redirected',
                type: 'success',
                showConfirmButton: false
            });
            window.location.href = Routing.generate('backend_partials');
        }).fail(function(error) {
            console.log(error);
            swal('An Error happened', 'Please try again!', 'error');

            $btn.button('reset');
        });

    });

});
