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

    $sql = 'SELECT count(*) AS cnt FROM gifs WHERE category_id = ?';
    $dbHelper->executeQuery($sql, [
        $_GET['id']
    ]);
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

    // Получение названия категории
    $dbHelper->executeQuery('SELECT name FROM categories WHERE id = ?', [
        $_GET['id']
    ]);
    if (!$dbHelper->getLastError()) {
        $category_name = $dbHelper->getResultAsArray()[0];
    }
    else {
        show_error('Ошибка MySQL: ', $dbHelper->getLastError());
    }

    // Список гифок по категории
    $sql_gifs = 'SELECT g.id, name, title, img_path, likes_count ' .
    'FROM gifs g ' .
    'JOIN users u ON g.user_id = u.id ' .
    'WHERE g.category_id = ? ' .
    'ORDER BY g.dt_add DESC LIMIT ? OFFSET ?';

    $dbHelper->executeQuery($sql_gifs, [
        $_GET['id'],
        $page_items,
        $offset
    ]);

    if (!$dbHelper->getLastError()) {
        $gifs = $dbHelper->getResultAsArray();
    }
    else {
        show_error('Ошибка MySQL: ', $dbHelper->getLastError());
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
