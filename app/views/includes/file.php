<?php /** @var \app\classes\AFile $file */ ?>
<div class="panel panel-warning panel-file">
    <div class="panel-heading">
        <span class="panel-title"><?= $file->getName() ?></span>
    </div>
    <div class="panel-body">
        <a href="?file=<?= htmlspecialchars($file->getPath()) ?>"><span
                class="glyphicon <?= htmlspecialchars($file->getIcon()) ?>"></span></a>
    </div>
    <div class="panel-footer"><?= $file->getSize('mb') . ' Мб' ?></div>
</div>
