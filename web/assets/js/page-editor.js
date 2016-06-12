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
        },
        slug: {
            presence: false
        },
        content: {
            presence: false
        },
        meta_title: {
            presence: true
        },
        meta_description: {
            presence: false
        }
    }

    //submit
    $('#btn-submit').click(function(e) {

        e.preventDefault();

        var $btn = $(this).button('loading');

        //collect data
        var data = {
            title: $('#input-title').val(),
            slug: ($('#input-slug').val()),
            content: editor.getSession().getValue(),
            meta_title: $('#input-meta-title').val(),
            meta_description: $('#input-meta-description').val(),
            active: $('#cb-active').prop('checked'),
            hidden: $('#cb-hidden').prop('checked'),
            blog: parseInt($('#sel-blog').val()),
            tag: parseInt($('#sel-tag').val()),
            sent: 1
        };

        if($('#editor-type').html() == 'create') {
            data.parent = parseInt($('#sel-parent').val());
        }

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
            ? Routing.generate('backend_pages_create')
            : Routing.generate('backend_pages_update', {
                pageId: $('#editor-current-id').html()
            })
        ;

        $.post(url, data).success(function(_data) {
            swal({
                title: 'Page saved!',
                text : 'You will be redirected',
                type: 'success',
                showConfirmButton: false
            });
            window.location.href = Routing.generate('backend_pages');
        }).fail(function(error) {
            console.log(error);
            swal('An Error happened', 'Please try again!', 'error');

            $btn.button('reset');
        });

    });

});
