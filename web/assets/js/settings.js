var cvEditor = null;
var isDeep = false;

var toggleDeepEditor = function() {
    if($('input[name="deep"]').val() == 'true' || $('input[name="deep"]').val() == 1) {
        cvEditor = ace.edit("c-value-editor");
        cvEditor.setTheme("ace/theme/github");
        cvEditor.getSession().setMode("ace/mode/json");

        isDeep = true;

        $('#fg-deep').show();
        $('#fg-nodeep').hide();
    } else {
        cvEditor = null;
        isDeep = false;

        $('#fg-deep').hide();
        $('#fg-nodeep').show();
    }
};

var isJson = function(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
};

$(document).ready(function() {

    var currentMode = $('#current-mode').html();
    var currentId = $('#current-id').html();

    $('#cb-deep').change(function() {
        //set deep value on form for submitting
        $('input[name="deep"]').val($(this).prop('checked'));

        //change input for deep value
        toggleDeepEditor();
    });

    toggleDeepEditor();

    $('#form-setting').submit(function(e) {

        e.preventDefault();

        if(isDeep == true && cvEditor.getValue() != '' && !isJson(cvEditor.getValue())) {
            swal('Please enter valid JSON as a Complex Value!', '', 'error');

            return;
        }

        var data = {
            setting_key: $('#input-key').val(),
            setting_value: (isDeep) ? cvEditor.getValue() : $('#input-value').val(),
            deep: isDeep,
            sent: 1
        };

        var url = (currentMode == 'create')
            ? Routing.generate('backend_settings_create')
            : Routing.generate('backend_settings_update', {settingId: currentId})
        ;

        $.post(url, data).success(function(_data) {
            swal({
                title: 'Setting saved!',
                text : 'You will be redirected',
                type: 'success',
                showConfirmButton: false
            });
            window.location.href = Routing.generate('backend_settings');
        }).fail(function(error) {
            console.log(error);
            swal('An Error happened', 'Please try again!', 'error');
        });

    });

});
