<?php /** @var \app\classes\AFile $file */ ?>
<div class="panel panel-warning panel-file">
    <div class="panel-heading">
        <span class="panel-title">
            <span class="file-name">
                <?= $file->getBasename() ?>
            </span>
            <form class="edit-file-name-form" action="/" method="post" style="display: none;">
                <span class="file-name-input">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="hidden" name="path" value="<?= \app\Request::get('path') ?>">
                        <input type="hidden" name="extension" value="<?= $file->getExtension() ?>">
                    <div class="form-group">
                        <input type="text" name="name" value="<?= $file->getFileNameWithoutExt() ?>">
                    </div>
                    <?= $file->getExtension() ? '.' . $file->getExtension() : '' ?>
                </span>
            </form>
            <span class="file-icons">
                <a href="#" class="save-file-name"><span class="glyphicon glyphicon-ok"></span></a>
                <a href="#" class="edit-file-name"><span class="glyphicon glyphicon-edit"></span></a>
            </span>
        </span>
    </div>
    <div class="panel-body">
        <div class="icon">
            <?php $link = ($file->isDir()) ? '?path='.htmlspecialchars($file->getPathname()) : htmlspecialchars($file->getPathname()) ?>
        <a class="center-block" href="<?= $link ?>"><span
                    class="glyphicon <?= htmlspecialchars($file->getIcon()) ?> center-block"></span></a>
        </div>
    </div>
    <div class="panel-footer">
        <?= $file->getSize() . ' б' ?>
        <span class="glyphicon glyphicon-remove pull-right delete-file"></span>
    </div>
</div>
