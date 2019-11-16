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
            <li class="gif gif-list__item">
                <div class="gif__picture">
                    <a href="/gif.php" class="gif__preview">
                        <img src="<?= $gif['gif_img']; ?>" alt="" width="260" height="260">
                    </a>
                </div>
                <div class="gif__desctiption">
                    <h3 class="gif__desctiption-title">
                        <a href="/gif.php"><?= $gif['gif_title']; ?></a>
                    </h3>
                    <div class="gif__description-data">
                        <span class="gif__username"><?= $gif['gif_username']; ?></span>
                        <span class="gif__likes"><?= $gif['gif_likes']; ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="pagination">
        <ul class="pagination__control">
            <li class="pagination__item pagination__item--active">
                <a href="?page=1">1</a>
            </li>
            <li class="pagination__item ">
                <a href="?page=2">2</a>
            </li>
            <li class="pagination__item ">
                <a href="?page=3">3</a>
            </li>
            <li class="pagination__item ">
                <a href="?page=4">4</a>
            </li>
        </ul>

        <ul class="pagination__control">
            <li class="pagination__item">
                <a href="#">◀</a>
            </li>
            <li class="pagination__item">
                <a href="?page=2">▶</a>
            </li>
        </ul>
    </div>
</div>
