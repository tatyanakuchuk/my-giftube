<?php

$isGifPage = true;

require_once('functions.php');
// require_once('data.php');

//подключение к MySQL
$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
} else {

    // 1. запрос для получения списка категорий;
    $sql_cat = 'SELECT * FROM categories';

    $res_cat = mysqli_query($connect, $sql_cat);

    if($res_cat) {
        $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 2. запрос для получения данных гифки по id
    $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, ' .
                'likes_count, favs_count, views_count, description ' .
                'FROM gifs g ' .
                'JOIN categories c ON g.category_id = c.id ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE g.id = 5';

    $res_gif = mysqli_query($connect, $sql_gif);

    if($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 3. запрос для списка комментариев к гифке
    $sql_comments = 'SELECT c.dt_add, avatar_path, name, comment_text ' .
                'FROM comments c ' .
                'JOIN gifs g ON g.id = c.gif_id ' .
                'JOIN users u ON c.user_id = u.id ' .
                'WHERE g.id = 5';

    $res_comments = mysqli_query($connect, $sql_comments);

    if($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 4. запрос для списка похожих гифок
    $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count ' .
                'FROM gifs g ' .
                'JOIN categories c ON g.category_id = c.id ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE category_id = 4 LIMIT 6';

    $res_similar = mysqli_query($connect, $sql_similar);

    if($res_similar) {
        $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }
}

$page_content = include_template('gif.php', [
    'gif' => $gif,
    'comments' => $comments,
    'similar_gifs' => $similar_gifs,
    'isGifPage' => $isGifPage
]);

$layout_content = include_template('layout.php', [
    'gif' => $gif,
    'content' => $page_content,
    'categories' => $categories,
    'title' => $gif['title']
]);

print($layout_content);
