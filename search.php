<?php

session_start();
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

    // запрос для получения списка гифок
    $gifs = [];
    $search = isset($_GET['q']) ? $_GET['q'] : '';
     if ($search) {

        $count_gifs = 'SELECT count(*) AS cnt FROM gifs WHERE MATCH(title, description) AGAINST(?)';
        $dbHelper->executeQuery($count_gifs, [$search]);

        if (!$dbHelper->getLastError()) {
            $items_count = $dbHelper->getResultAsArray()[0]['cnt'];
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }

        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $page_items = 3;
        $offset = ($current_page - 1) * $page_items;
        $pages_count = ceil($items_count / $page_items);
        $pages = range(1, $pages_count);

        $sql_gifs = 'SELECT g.id, g.dt_add, category_id, user_id, title, ' .
        'description, img_path, likes_count, u.name ' .
        'FROM gifs g ' .
        'JOIN users u ON g.user_id = u.id ' .
        'JOIN categories c ON g.category_id = c.id ' .
        'WHERE MATCH(title, description) AGAINST(?) ' .
        'ORDER BY g.dt_add DESC LIMIT ? OFFSET ?';
        $dbHelper->executeQuery($sql_gifs, [
            $search,
            $page_items,
            $offset
        ]);
        if (!$dbHelper->getLastError()) {
            $gifs = $dbHelper->getResultAsArray();
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }
    }
    $param = isset($_GET['q']) ? ('q=' . $_GET['q'] . '&') : '';
}

$pagination = include_template('pagination.php', [
    'param' => $param,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$page_content = include_template('main.php', [
    'gifs' => $gifs,
    'title' => 'Результаты поиска',
    'pagination' => $pagination
]);

if (isset($_SESSION['user'])) {

    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Результаты поиска',
        'search' => $search
    ]);
}
else {

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Результаты поиска',
        'search' => $search
    ]);
}

print($layout_content);
