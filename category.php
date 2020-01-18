<?php

session_start();

require_once('functions.php');

$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
} else {

    if (isset($_GET['id'])) {
        $category_id = $_GET['id'];
    }

    $res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs WHERE category_id = ' . $category_id);
    $items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_items = 3;
    $offset = ($current_page - 1) * $page_items;
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    // 1. запрос для получения списка категорий;
    $sql_cat = 'SELECT * FROM categories';
    $res_cat = mysqli_query($connect, $sql_cat);
    if($res_cat) {
        $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 2. запрос для получения названия категории
    $sql_cat_name = 'SELECT name FROM categories WHERE id = ' . $category_id;
    $res_cat_name = mysqli_query($connect, $sql_cat_name);
    if($res_cat_name) {
        $category_name = mysqli_fetch_assoc($res_cat_name);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 3. запрос для получения списка гифок по категории
    $sql_gifs = 'SELECT g.id, name, title, img_path, likes_count ' .
                'FROM gifs g ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE g.category_id = ' . $category_id .
                ' ORDER BY g.dt_add DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $res_gifs = mysqli_query($connect, $sql_gifs);
    if($res_gifs) {
        $gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    $param = isset($_GET['id']) ? ('id=' . $_GET['id'] . '&') : '';
}

$pagination = include_template('pagination.php', [
    'param' => $param,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$page_content = include_template('main.php', [
    'gifs' => $gifs,
    'title' => $category_name['name'],
    'pagination' => $pagination
]);

if (isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Все гифки в категории «' . $category_name['name'] . '»'
    ]);
}
else {
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Все гифки в категории «' . $category_name['name'] . '»'
    ]);
}

print($layout_content);
