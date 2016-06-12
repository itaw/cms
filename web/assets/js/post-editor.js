$(document).ready(function() {

    $('#app-container').removeClass('container').addClass('container-fluid');

    // var editor = ace.edit("editor");
    // editor.setTheme("ace/theme/github");
    // editor.getSession().setMode("ace/mode/twig");
    // editor.getSession().setTabSize(4);
    // document.getElementById('editor').style.fontSize='14px';

    var excerptEditor = ace.edit("excerpt-editor");
    excerptEditor.setTheme("ace/theme/github");
    excerptEditor.getSession().setMode("ace/mode/twig");
    excerptEditor.getSession().setTabSize(4);
    document.getElementById('excerpt-editor').style.fontSize='14px';

    $('#sel-tags').selectize({
        plugins: ['remove_button', 'optgroup_columns'],
        delimiter: ',',
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input
            }
        }
    });

    $('#input-image').change(function() {
        $('#main-image-preview').attr('src', $(this).val());
    });

    var constraints = {
        title: {
            presence: true
        },
        text: {
            presence: true
        }
    }

    //submit
    $('#btn-submit').click(function(e) {

        e.preventDefault();

        var $btn = $(this).button('loading');

        //collect data
        var data = {
            title: $('#input-title').val(),
            text: CKEDITOR.instances.ckeditor.getData(),
            excerpt: excerptEditor.getSession().getValue(),
            main_image_url: $('#input-image').val(),
            published: $('#cb-published').prop('checked'),
            tags: JSON.stringify($('#sel-tags').val()),
            sent: 1
        };

        if($('#editor-type').html() != 'create') {
            data.publish_date = $('#input-date').val();
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
            ? Routing.generate('backend_blogs_posts_create', {
                blogId: $('#editor-blog-id').html()
            })
            : Routing.generate('backend_blogs_posts_update', {
                blogId: $('#editor-blog-id').html(),
                postId: $('#editor-current-id').html()
            })
        ;

        $.post(url, data).success(function(_data) {
            swal({
                title: 'Post saved!',
                text : 'You will be redirected',
                type: 'success',
                showConfirmButton: false
            });
            window.location.href = Routing.generate('backend_blogs_posts', {
                blogId: $('#editor-blog-id').html()
            });
        }).fail(function(error) {
            console.log(error);
            swal('An Error happened', 'Please try again!', 'error');

            $btn.button('reset');
        });

    });

});
