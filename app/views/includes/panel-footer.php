<div class="panel-footer">
        <span id="create-folder"
              class="footer-icon glyphicon glyphicon-folder-open pull-left"
              title="Создать новую директорию"></span>
    <form id="create-folder-form" method="post" class="form-inline pull-left">
        <div class="form-group">
            <input type="hidden" name="command" value="folder-create">
            <input type="text" class="form-control" name="name" placeholder="Имя директории">
            <input type="submit"
                   class="btn btn-default form-control"
                   title="Создать новую директорию"
                   value="Создать">
        </div>
    </form>
    <form method="post" enctype="multipart/form-data" class="form-inline">
        <div class="form-group">
            <input type="hidden" name="command" value="file-upload">
            <input type="file" name="files[]" class="form-control" multiple>
            <input type="submit" class="btn btn-default form-control" value="Загрузить">
        </div>
    </form>
</div>