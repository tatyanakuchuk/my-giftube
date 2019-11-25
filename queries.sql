INSERT INTO categories (name)
VALUES  ('Видеоигры'),
        ('Животные'),
        ('Люди'),
        ('Наука'),
        ('Приколы'),
        ('Спорт'),
        ('Фейлы'),
        ('Фильмы и анимация');

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 4,
    user_id = 5,
    title = 'Название гифки',
    description = 'Описание гифки',
    img_path = 'uploads/venus.gif',
    likes_count = 65,
    favs_count = 77,
    views_count = 523;

INSERT INTO users
SET dt_add = NOW(),
    name = 'Имя пользователя',
    email = 'email@mail.ru',
    password = 'secret',
    avatar_path = 'uploads/avatar/avatar5a7d69e385bf6.jpg';

INSERT INTO gifs_like
SET user_id = 5,
    gif_id = 56;

INSERT INTO gifs_fav
SET user_id = 1,
    gif_id = 36;

INSERT INTO comments
SET dt_add = NOW(),
    user_id = 5,
    gif_id = 7,
    comment_text = 'Текст комментария';
