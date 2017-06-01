function addHandlers() {
    $('input[name=name]').keyup(function (e) {
        var parent = $(this).parent();
        if (parent.hasClass('has-error'))
            parent.removeClass('has-error');
        var helpBlock = $(this).next('span.help-block');
        if (helpBlock.length !== 0)
            helpBlock.remove();
        var regexp = /[а-яА-ЯёЁ]/g;
        if (e.key.search(regexp) !== -1) {
            var text = $(this).val().replace(regexp, '');
            $(this).val(text);
            parent.addClass('has-error');
            $(this).after('<span class="help-block"><small>Допускается только латиница</small></span>');
        }
    });

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
        var id = form.find('input[name=id]').val();
        var newName = form.find('input[name=name]').val();
        var extension = form.find('input[name=extension]').val();
        if (extension) {
            extension = '.' + extension;
        }
        var path = form.find('input[name=path]').val();
        sendFileRenameAjax(t, id, newName, extension, path);
    });

    $('.delete-file').on('click', function (e) {
        e.preventDefault();
        var t = $(this);
        var form = t.closest('.panel').find('form.edit-file-name-form');
        var id = form.find('input[name=id]').val();
        var path = form.find('input[name=path]').val();
        var name = form.find('input[name=name]').val();
        if (confirm('Вы действительно хотите удалить «' + name + '»?')) {
            sendFileDeleteAjax(t, id, path);
        }
    });
}

function sendFileRenameAjax(t, id, newName, extension, path) {
    $.ajax({
        url: '/?path=' + path,
        type: 'POST',
        data: {
            command: 'file-rename',
            id: id,
            newName: newName
        },
        success: function (data) {
            var panel = t.closest('.panel');
            var panelTitle = panel.find('.panel-title');
            panelTitle.toggle();
            panel.find('.edit-file-name-form').toggle();
            panel.find('.file-name').empty().append(newName + extension).toggle();
            panel.find('.save-file-name').toggle();
            panelTitle.fadeToggle();
        }
    });
}

function sendFileDeleteAjax(t, id, path) {
    $.ajax({
        url: '/?path=' + path,
        type: 'POST',
        data: {
            command: 'file-delete',
            id: id
        },
        success: function (data) {
            t.closest('.panel').fadeOut();
        }
    });
}

$(function () {
    addHandlers();
});