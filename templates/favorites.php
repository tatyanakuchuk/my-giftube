<div class="content__main-col">
    <header class="content__header">
        <h2 class="content__header-text"><?= $title; ?></h2>
        <a class="button button--transparent content__header-button" href="/">Назад</a>
    </header>
    <ul class="gif-list">
        <?php foreach ($gifs as $gif): ?>
            <li class="gif gif-list__item">
                <div class="gif__picture">
                    <a href="/.php" class="gif__preview">
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
</div>
