<?php /** @var \app\classes\AFile $file */ ?>
<div class="panel panel-warning panel-file">
    <div class="panel-heading">
        <span class="panel-title">
            <span class="file-name">
                <?= $file->getBasename() ?>
            </span>
            <form class="edit-file-name-form" action="/" method="post" style="display: none;">
                <span class="file-name-input">
                    <input type="hidden" name="path" value="<?= $file->getPathname() ?>">
                    <input type="hidden" name="extension" value="<?= $file->getExtension() ?>">
                    <input type="text" name="name" value="<?= $file->getFileNameWithoutExt() ?>">
                    .<?= $file->getExtension() ?>
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
            <a class="center-block" href="?path=<?= htmlspecialchars($file->getPathname()) ?>"><span
                    class="glyphicon <?= htmlspecialchars($file->getIcon()) ?> center-block"></span></a>
        </div>
    </div>
    <div class="panel-footer"><?= $file->getSize() . ' б' ?></div>
</div>
