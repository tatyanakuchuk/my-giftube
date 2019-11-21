<div class="content__main-col">
    <h2 class="visually-hidden">Смешные гифки</h2>

    <header class="content__header">
        <nav class="filter">
            <a class="filter__item filter__item--active" href="/">Топовые гифки</a>
            <a class="filter__item " href="/?tab=new">Свежачок</a>
        </nav>

        <a class="button button--transparent button--transparent-thick content__header-button" href="/add.php">Загрузить свою</a>
    </header>

    <ul class="gif-list">
        <?php foreach ($gifs as $gif): ?>
            <?= include_template('gif-item.php', ['gif' => $gif]); ?>
        <?php endforeach; ?>
    </ul>
    <?= $pagination; ?>
</div>
