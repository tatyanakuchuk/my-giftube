<?php

session_start();
$isMainPage = true;

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

    // Получение количества гифок
    $dbHelper->executeQuery('SELECT COUNT(*) AS cnt FROM gifs');
    if (!$dbHelper->getLastError()) {
        $items_count = $dbHelper->getResultAsArray()[0]['cnt'];
    }
    else {
        show_error('Ошибка MySQL: ', $dbHelper->getLastError());
    }

    $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $page_items = 9;

    $offset = ($current_page - 1) * $page_items;
    $pages_count = ceil($items_count / $page_items);
    $pages = range(1, $pages_count);

    // Запрос: получение списка новых гифок
    $sql_new_gifs = 'SELECT g.id, name, title, img_path, likes_count ' .
    'FROM gifs g ' .
    'JOIN users u ON g.user_id = u.id ' .
    'ORDER BY g.dt_add DESC LIMIT ? OFFSET ?';

    // Запрос: получение списка топовых гифок
    $sql_top_gifs = 'SELECT g.id, name, title, img_path, likes_count ' .
    'FROM gifs g ' .
    'JOIN users u ON g.user_id = u.id ' .
    'ORDER BY g.views_count DESC LIMIT ? OFFSET ?';

    $dbHelper->executeQuery($sql_top_gifs, [
        $page_items,
        $offset
    ]);

    if (!$dbHelper->getLastError()) {
        $gifs = $dbHelper->getResultAsArray();
    }
    else {
        show_error('Ошибка MySQL: ', $dbHelper->getLastError());
    }

    if (isset($_GET['tab']) && $_GET['tab'] == 'new') {
        $dbHelper->executeQuery($sql_new_gifs, [$page_items, $offset]);
        if (!$dbHelper->getLastError()) {
            $gifs = $dbHelper->getResultAsArray();
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }
    }

    $param = isset($_GET['tab']) && $_GET['tab'] == 'new' ? ('tab=' . $_GET['tab'] . '&') : '';
}

$pagination = include_template('pagination.php', [
    'param' => $param,
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
