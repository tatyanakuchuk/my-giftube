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
    category_id = 2,
    user_id = 5,
    title = 'Песик',
    description = 'Кусь!',
    img_path = 'uploads/97a52867.gif',
    likes_count = 6,
    favs_count = 7,
    views_count = 5;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 2,
    user_id = 1,
    title = 'Кот-боксер',
    description = 'Тренировка усатого :)',
    img_path = 'uploads/ani.gif',
    likes_count = 10,
    favs_count = 12,
    views_count = 21;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 8,
    user_id = 2,
    title = 'Гомерчик',
    description = 'Тренировка усатого :)',
    img_path = 'uploads/ani.gif',
    likes_count = 10,
    favs_count = 12,
    views_count = 21;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 8,
    user_id = 4,
    title = 'Миньоны',
    description = 'Восторг',
    img_path = 'uploads/orig.gif',
    likes_count = 15,
    favs_count = 9,
    views_count = 32;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 4,
    user_id = 4,
    title = 'Спектр как он есть',
    description = '...',
    img_path = 'uploads/light.gif',
    likes_count = 17,
    favs_count = 19,
    views_count = 22;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 7,
    user_id = 1,
    title = 'Почти попал...',
    description = 'Нужно больше тренироваться!',
    img_path = 'uploads/giphy.gif',
    likes_count = 11,
    favs_count = 10,
    views_count = 27;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 8,
    user_id = 3,
    title = 'Белка',
    description = 'Увидела свой желудь :)',
    img_path = 'uploads/belk.gif',
    likes_count = 21,
    favs_count = 19,
    views_count = 37;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 4,
    user_id = 3,
    title = 'ДНК',
    description = 'Цепочка ДНК',
    img_path = 'uploads/dna.gif',
    likes_count = 11,
    favs_count = 5,
    views_count = 7;

INSERT INTO gifs
SET dt_add = NOW(),
    category_id = 4,
    user_id = 3,
    title = 'Пакмэн',
    description = 'Игра',
    img_path = 'uploads/pacman.gif',
    likes_count = 25,
    favs_count = 21,
    views_count = 37;

INSERT INTO users
SET dt_add = NOW(),
    name = 'Nelli1',
    email = 'nelli@mail.ru',
    password = 'secret1',
    avatar_path = 'uploads/avatar/avatar5a7d69e385bf6.jpg';

INSERT INTO users
SET dt_add = NOW(),
    name = 'Lalla2',
    email = 'lalla@mail.ru',
    password = 'secret2',
    avatar_path = 'uploads/avatar/avatar5a7d69e385bf6.jpg';

INSERT INTO users
SET dt_add = NOW(),
    name = 'freddy3',
    email = 'freddy@gmail.com',
    password = 'secret3',
    avatar_path = 'uploads/avatar/avatar5a7d69e385bf6.jpg';

INSERT INTO users
SET dt_add = NOW(),
    name = 'flymen4',
    email = 'flymen@gmail.com',
    password = 'secret4',
    avatar_path = 'uploads/avatar/avatar5a7d69e385bf6.jpg';

INSERT INTO users
SET dt_add = NOW(),
    name = 'hellboy5',
    email = 'hellboy@gmail.com',
    password = 'secret5',
    avatar_path = 'uploads/avatar/avatar5a7d69e385bf6.jpg';

INSERT INTO gifs_like
SET user_id = 5,
    gif_id = 6;

INSERT INTO gifs_fav
SET user_id = 1,
    gif_id = 3;

INSERT INTO comments
SET dt_add = NOW(),
    user_id = 5,
    gif_id = 7,
    comment_text = 'Текст комментария1';

INSERT INTO comments
SET dt_add = NOW(),
    user_id = 2,
    gif_id = 5,
    comment_text = 'Текст комментария2';

INSERT INTO comments
SET dt_add = NOW(),
    user_id = 1,
    gif_id = 1,
    comment_text = 'Текст комментария3';

INSERT INTO comments
SET dt_add = NOW(),
    user_id = 3,
    gif_id = 5,
    comment_text = 'Текст комментария4';

INSERT INTO comments
SET dt_add = NOW(),
    user_id = 5,
    gif_id = 4,
    comment_text = 'Текст комментария5';

-- получить все категории:
SELECT * FROM categories;

-- обновить название гифки по её идентификатору:
UPDATE gifs SET title='Новое название' WHERE id = '1';

-- показать гифку по её id. Получите также название категории, к которой принадлежит гифка:
SELECT g.id, c.name, u.name, title, description, img_path, likes_count, views_count
FROM gifs g
JOIN categories c ON g.category_id = c.id
JOIN users u ON g.user_id = u.id
WHERE g.id = '1';

-- получить список избранных гифок у пользователя:
SELECT g.id, name, title, img_path, likes_count
FROM gifs g
JOIN gifs_fav gf ON gf.gif_id = g.id
JOIN users u ON gf.user_id = u.id;

-- получить список комментариев к гифке по её идентификатору:
SELECT g.id, c.dt_add, avatar_path, name, comment_text
FROM comments c
JOIN gifs g ON g.id = c.gif_id
JOIN users u ON c.user_id = u.id
WHERE g.id = '2';

-- получить список самых свежих гифок по их идентификатору:
SELECT g.id, name, title, img_path, likes_count
FROM gifs g
JOIN users u ON g.user_id = u.id
ORDER BY g.dt_add DESC;

-- получить количество лайков у гифки:
SELECT count(id) AS likes_count, gif_id
FROM gifs_like
GROUP BY gif_id;

-- получить список самых популярных гифок по их идентификатору:
SELECT g.id, name, title, img_path, likes_count
FROM gifs g
JOIN gifs_like gl ON gl.gif_id = g.id
JOIN users u ON g.user_id = u.id
ORDER BY likes_count DESC;
