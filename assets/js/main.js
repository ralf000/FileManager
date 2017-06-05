function checkInput(t, e) {
    var parent = t.parent();
    if (parent.hasClass('has-error'))
        parent.removeClass('has-error');
    var helpBlock = t.next('span.help-block');
    if (helpBlock.length !== 0)
        helpBlock.remove();
    var regexp = /[а-яА-ЯёЁ]/g;
    if (e.key.search(regexp) !== -1) {
        var text = t.val().replace(regexp, '');
        t.val(text);
        parent.addClass('has-error');
        t.popover('show');
        //t.after('<span class="help-block"><small>Допускается только латиница</small></span>');
    } else {
        t.popover('destroy');
        parent.removeClass('has-error');
    }
}

function addHandlers() {
    $('input[name=name]').keyup(function (e) {
        checkInput($(this), e);
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

    $('#create-folder').on('click', function (e) {
        e.preventDefault();
        var t = $(this);
        t.next('#create-folder-form').fadeToggle();
    });
}

function sendFileRenameAjax(t, id, newName, extension, path) {
    $.ajax({
        url: '/?path=' + path,
        type: 'POST',
        data: {
            command: 'file-rename',
            id: id,
            extension: extension,
            newName: newName
        },
        success: function (newName) {
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