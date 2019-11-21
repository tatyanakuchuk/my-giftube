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
