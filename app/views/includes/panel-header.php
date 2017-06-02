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