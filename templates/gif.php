<div class="content__main-col">
    <header class="content__header">
        <h2 class="content__header-text">Премии на моей бывшей работе</h2>
        <label for="gifControl">click</label>
    </header>

    <div class="gif gif--large">
        <div class="gif__picture">
            <input type="checkbox" name="" id="gifControl" value="1" class="hide">
            <label for="gifControl">Проиграть</label>
            <img src="uploads/dna.gif" alt="" class="gif_img main hide">
            <img src="uploads/preview_gif58dbdf3251fcf.gif" alt="" class="gif_img preview">
        </div>

        <div class="gif__desctiption">
            <div class="gif__description-data">
                <span class="gif__username">@frexin</span>
                <span class="gif__views">2807</span>
                <span class="gif__likes">3</span>
            </div>
            <div class="gif__description">
                <p>Горькая правда</p>
            </div>
        </div>

        <!-- Для зарегистрированных пользователей -->
        <div class="gif__controls">
            <a class="button gif__control" href="/gif/like?id=11&amp;rem=1">Мне нравится</a>
            <a class="button gif__control" href="/gif/fav?id=11&amp;rem=1">В избранное</a>
        </div>
        <!-- end Для зарегистрированных пользователей -->
    </div>

    <div class="comment-list">
        <h3 class="comment-list__title">Комментарии:</h3>
        <article class="comment">
            <img class="comment__picture" src="uploads/avatar5ba064590735c." alt="" width="100" height="100">
            <div class="comment__data">
                <div class="comment__author">@pestovpvl</div>
                <p class="comment__text">Teahsbhx</p>
            </div>
        </article>
    </div>

    <!-- Для зарегистрированных пользователей -->
    <form class="comment-form" action="" method="post">
        <label class="comment-form__label" for="comment">Добавить комментарий:</label>
        <textarea class="comment-form__text " name="comment[content]" id="comment" rows="8" cols="80" placeholder="Помните о правилах и этикете. "></textarea>
        <input type="hidden" name="comment[gif_id]" value="11">
        <input class="button comment-form__button" type="submit" name="" value="Отправить">
    </form>
    <!-- end Для зарегистрированных пользователей -->
</div>

<aside class="content__additional-col">
    <h3 class="content__additional-title">Похожие гифки:</h3>

    <ul class="gif-list gif-list--vertical">
        <?php foreach ($gifs as $gif): ?>
            <?= include_template('gif-item.php', ['gif' => $gif]); ?>
        <?php endforeach; ?>
    </ul>
</aside>
