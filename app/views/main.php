<div class="panel panel-primary main">
    <div class="panel-heading">
        <h3 class="panel-title">FileManager</h3>
        <div class="icons">
            <?php if (!empty($this->backLink)): ?>
                <div class="top-icon back">
                    <a href="?path=<?= htmlspecialchars($this->backLink) ?>" class="btn btn-default btn-xs">
                        <span class="glyphicon glyphicon-share-alt"></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="panel-body">
        <?php $files = array_chunk($this->files, 6, true) ?>
        <?php if (isset($files) && is_array($files)): ?>
            <?php foreach ($files as $filesGroup): ?>
                <div class="row">
                    <?php foreach ($filesGroup as $id => $file): ?>
                        <div class="col-sm-2">
                            <?php require 'includes/file.php' ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Файлы отсутствуют</p>
        <?php endif; ?>
    </div>
    <div class="panel-footer">
        <form method="post" enctype="multipart/form-data" class="form-inline">
            <div class="form-group">
                <input type="hidden" name="command" value="file-upload">
                <input type="file" name="files[]" class="form-control" multiple>
                <input type="submit" class="btn btn-default form-control" value="Загрузить">
            </div>
        </form>
    </div>
</div>

<script src="/assets/js/main.js" type="application/javascript"></script>
