<div class="content__main-col">
    <header class="content__header">
        <h2 class="content__header-text"><?= $title; ?></h2>
        <a class="button button--transparent content__header-button" href="/">Назад</a>
    </header>

    <ul class="gif-list">
        <?php foreach ($gifs as $gif): ?>
            <?= include_template('gif-item.php', ['gif' => $gif]); ?>
        <?php endforeach; ?>
    </ul>
</div>
