<?php

session_start();

$isMainPage = true;

require_once('functions.php');

//подключение к MySQL
$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

//проверка подключения
if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
} else {

    $res_count_gifs = mysqli_query($connect, 'SELECT count(*) AS cnt FROM gifs');
    $items_count = mysqli_fetch_assoc($res_count_gifs)['cnt'];
    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_items = 9;
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

    // 2. создаем запрос для получения списка топовых гифок
    $sql_gifs = 'SELECT g.id, name, title, img_path, likes_count ' .
                'FROM gifs g ' .
                'JOIN users u ON g.user_id = u.id ' .
                'ORDER BY likes_count DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

    //отправляем запрос и получаем результат
    $res_gifs = mysqli_query($connect, $sql_gifs);
    //запрос выполнен успешно
    if($res_gifs) {
        //получаем гифки в виде двумерного массива
        $gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
    } else {
        //получаем текст последней ошибки
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    if (isset($_GET['tab'])) {
        // 3. создаем запрос для получения списка свежих гифок
        $sql_gifs = 'SELECT g.id, name, title, img_path, likes_count ' .
                    'FROM gifs g ' .
                    'JOIN users u ON g.user_id = u.id ' .
                    'ORDER BY g.dt_add DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;

        $res_gifs = mysqli_query($connect, $sql_gifs);

        if($res_gifs) {
            $gifs = mysqli_fetch_all($res_gifs, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        }
    }

}

$pagination = include_template('pagination.php', [
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$page_content = include_template('main.php', [
    'gifs' => $gifs,
    'pagination' => $pagination,
    'title' => 'Смешные гифки',
    'isMainPage' => $isMainPage
]);

if (isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Главная страница',
        'isMainPage' => $isMainPage
    ]);
}
else {
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Главная страница',
        'isMainPage' => $isMainPage
    ]);
}

print($layout_content);
