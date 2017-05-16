<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">FileManager</h3>
    </div>
    <div class="panel-body">
        <?php $files = array_chunk($this->files, 12) ?>
        <?php foreach ($files as $filesGroup): ?>
            <div class="row">
                <?php foreach ($filesGroup as $file): ?>
                    <div class="col-sm-1">
                        <?php require 'includes/file.php'?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="panel-footer">Panel footer</div>
</div>
