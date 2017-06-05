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

function createThumbnails() {
    var canvases = $('.thumbnail');
    $.each(canvases, function (id, canvas) {
        var ctx = canvas.getContext("2d");

        var img = new Image();
        img.onload = function () {

            canvas.height = canvas.width * (img.height / img.width);

            var oc = document.createElement('canvas'),
                octx = oc.getContext('2d');

            oc.width = img.width * 0.2;
            oc.height = img.height * 0.2;
            octx.drawImage(img, 0, 0, oc.width, oc.height);

            octx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5);

            ctx.drawImage(oc, 0, 0, oc.width * 0.5, oc.height * 0.5,
                0, 0, canvas.width, canvas.height);
        };
        img.src = $(canvas).data('src');
    });
}

$(function () {
    addHandlers();
    createThumbnails();
});