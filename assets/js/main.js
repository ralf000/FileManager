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
        var newName = form.find('input[name=name]');
        var filePath = form.find('input[name=path]');
        sendFileRenameAjax(t, newName, filePath);
    });
}

function sendFileRenameAjax(t, newName, filePath) {
    $.ajax({
        url: '/',
        type: 'POST',
        data: {
            command: 'file-rename',
            newName: newName.val(),
            filePath: filePath.val()
        },
        success: function () {
            var panel = t.closest('.panel');
            var panelTitle = panel.find('.panel-title');
            panelTitle.toggle();
            panel.find('.edit-file-name-form').toggle();
            panel.find('.file-name').empty().append(newName.val()).toggle();
            panel.find('.save-file-name').toggle();
            panelTitle.fadeToggle();
        }
    });
}

$(function () {
    addHandlers();
});