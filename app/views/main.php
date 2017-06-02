<div class="panel panel-primary main">
    <?php require_once 'includes/panel-header.php'?>
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
    <?php require_once 'includes/panel-footer.php'?>
</div>

<script src="/assets/js/main.js" type="application/javascript"></script>
