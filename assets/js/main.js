function addHandlers() {
    $('.edit-file-name').on('click', function (e) {
        e.preventDefault();
        var t = $(this);
        var panel = t.closest('.panel');
        var panelTitle = panel.find('.panel-title');
        panelTitle.toggle();
        panel.find('.edit-file-name-form').toggle();
        panel.find('.file-name').toggle();
        panel.find('.save-file-name').toggle();
        panelTitle.fadeToggle();
    });

    $('.save-file-name').on('click', function (e) {
        e.preventDefault();
        var t = $(this);
        var form = t.closest('.panel').find('form.edit-file-name-form');
        var newName = form.find('input[name=name]').val();
        var extension = form.find('input[name=extension]').val();
        var path = form.find('input[name=path]').val();
        sendFileRenameAjax(t, newName, extension, path);
    });
}

function sendFileRenameAjax(t, newName, extension, path) {
    $.ajax({
        url: '/?path=' + path,
        type: 'POST',
        data: {
            command: 'file-rename',
            newName: newName,
        },
        success: function (data) {
            console.log(data);
            var panel = t.closest('.panel');
            var panelTitle = panel.find('.panel-title');
            panelTitle.toggle();
            panel.find('.edit-file-name-form').toggle();
            panel.find('.file-name').empty().append(newName + '.' + extension).toggle();
            panel.find('.save-file-name').toggle();
            panelTitle.fadeToggle();
        }
    });
}

$(function () {
    addHandlers();
});