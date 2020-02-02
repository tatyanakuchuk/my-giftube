<?php

session_start();
$isGifPage = true;

require_once('bootstrap.php');

if ($dbHelper->getLastError()) {
    show_error('Ошибка MySQL: ', $dbHelper->getLastError());
}
else {
    // Получение списка категорий
    $dbHelper->executeQuery('SELECT * FROM categories');
    if (!$dbHelper->getLastError()) {
        $categories = $dbHelper->getResultAsArray();
    }
    else {
        show_error('Ошибка MySQL: ', $dbHelper->getLastError());
    }

    if (isset($_GET['id'])) {
        $gif_id = $_GET['id'];

        // Данные гифки по id
        $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, ' .
        'likes_count, favs_count, views_count, description ' .
        'FROM gifs g ' .
        'JOIN categories c ON g.category_id = c.id ' .
        'JOIN users u ON g.user_id = u.id ' .
        'WHERE g.id = ?';
        $dbHelper->executeQuery($sql_gif, [$gif_id]);
        if (!$dbHelper->getLastError()) {
            $gif = $dbHelper->getResultAsArray()[0];
            if(!isset($gif)) {
                header('Location: /error404.php');
                http_response_code(404);
                $is404error = true;
            }
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }
    }

    // если гифка добавлена в избранное
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];

        $isLiked = false;
        $isFav = false;

        $sql_fav = 'SELECT id FROM gifs_fav WHERE user_id = ? AND gif_id = ?';
        $dbHelper->executeQuery($sql_fav, [
            $user_id,
            $gif_id
        ]);
        if (!$dbHelper->getLastError()) {
            $fav = $dbHelper->getResultAsArray()[0];
            if(!empty($fav)) {
                $isFav = true;
            }
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }

        $sql_like = 'SELECT id FROM gifs_like WHERE user_id = ? AND gif_id = ?';
        $dbHelper->executeQuery($sql_like, [
            $user_id,
            $gif_id
        ]);
        if (!$dbHelper->getLastError()) {
            $like = $dbHelper->getResultAsArray()[0];
            if(!empty($like)) {
                $isLiked = true;
            }
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }
        // end если гифка добавлена в избранное

        // add comment
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $gif_id = $_POST['gif_id'];
            $comment = $_POST['comment'];

            $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, ' .
                'likes_count, favs_count, views_count, description ' .
                'FROM gifs g ' .
                'JOIN categories c ON g.category_id = c.id ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE g.id = ?';

            $dbHelper->executeQuery($sql_gif, [$gif_id]);

            if (!$dbHelper->getLastError()) {
                $gif = $dbHelper->getResultAsArray()[0];
            }
            else {
                show_error('Ошибка MySQL: ', $dbHelper->getLastError());
            }

            $required = ['comment'];
            $errors = [];

            foreach($required as $key) {
                if (empty($_POST[$key])) {
                    $errors[$key] = 'Это поле должно быть заполнено';
                }
            }

            if(!count($errors)) {
                $sql = 'INSERT INTO comments (dt_add, user_id, gif_id, comment_text) VALUES (NOW(), ?, ?, ?)';
                $dbHelper->executeQuery($sql, [
                    $user_id,
                    $gif_id,
                    $comment
                ]);
                if (!$dbHelper->getLastError()) {
                    $sql_update_views = 'UPDATE gifs SET views_count = views_count + 1 WHERE id = ?';
                    $dbHelper->executeQuery($sql_update_views, [$gif_id]);
                    $content = include_template('gif.php', [
                        'gif' => $gif,
                        'comments' => $comments,
                        'gifs' => $similar_gifs,
                        'isGifPage' => $isGifPage
                    ]);
                }
                else {
                    show_error('Ошибка MySQL: ', $dbHelper->getLastError());
                }
            }
        }
    }

    // all comments
    $sql_comments = 'SELECT c.dt_add, avatar_path, name, comment_text ' .
                'FROM comments c ' .
                'JOIN gifs g ON g.id = c.gif_id ' .
                'JOIN users u ON c.user_id = u.id ' .
                'WHERE g.id = ?';
    $dbHelper->executeQuery($sql_comments, [$gif_id]);
    if (!$dbHelper->getLastError()) {
        $comments = $dbHelper->getResultAsArray();
    }
    else {
        show_error('Ошибка MySQL: ', $dbHelper->getLastError());
    }

    // запрос для списка похожих гифок
    if(!$is404error) {
        $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count ' .
        'FROM gifs g ' .
        'JOIN categories c ON g.category_id = c.id ' .
        'JOIN users u ON g.user_id = u.id ' .
        'WHERE category_id = ? AND g.id NOT IN(?) ' .
        'LIMIT 6';

        $dbHelper->executeQuery($sql_similar, [
            $gif['category_id'],
            $gif_id
        ]);
        if (!$dbHelper->getLastError()) {
            $similar_gifs = $dbHelper->getResultAsArray();
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }
    }
}

if ($is404error) {

    $page_content = include_template('main.php', [
        'title' => '404 Страница не найдена',
        'is404error' => $is404error
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => '404 Страница не найдена'
    ]);

}
else {

    $page_content = include_template('gif.php', [
        'errors' => $errors,
        'gif' => $gif,
        'comments' => $comments,
        'gifs' => $similar_gifs,
        'isGifPage' => $isGifPage
    ]);

    if (isset($_SESSION['user'])) {

        $page_content = include_template('gif.php', [
            'errors' => $errors,
            'gif' => $gif,
            'comments' => $comments,
            'gifs' => $similar_gifs,
            'isGifPage' => $isGifPage,
            'isFav' => $isFav,
            'isLiked' => $isLiked
        ]);

        $layout_content = include_template('layout.php', [
            'username' => $_SESSION['user']['name'],
            'content' => $page_content,
            'categories' => $categories,
            'title' => $gif['title']
        ]);
    }
    else {
        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'categories' => $categories,
            'title' => $gif['title']
        ]);
    }

}

print($layout_content);
