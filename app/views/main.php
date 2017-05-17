<div class="panel panel-primary main">
    <div class="panel-heading">
        <h3 class="panel-title">FileManager</h3>
        <div class="icons">
            <?php if (isset($this->backLink)): ?>
                <div class="top-icon back">
                    <a href="?file=<?= htmlspecialchars($this->backLink) ?>" class="btn btn-default btn-xs">
                        <span class="glyphicon glyphicon-share-alt"></span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="panel-body">
        <?php $files = array_chunk($this->files, 12) ?>
        <?php if (isset($files) && is_array($files)): ?>
            <?php foreach ($files as $filesGroup): ?>
                <div class="row">
                    <?php foreach ($filesGroup as $file): ?>
                        <div class="col-sm-1">
                            <?php require 'includes/file.php' ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Файлы отсутствуют</p>
        <?php endif; ?>
    </div>
    <div class="panel-footer">Panel footer</div>
</div>
