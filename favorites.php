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

    if (isset($_SESSION['user'])) {
        $dbHelper->executeQuery('SELECT count(*) AS cnt FROM gifs_fav WHERE user_id = ?', [
            $_SESSION['user']['id']
        ]);
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

        // Список избранных гифок у пользователя
        $sql_favs = 'SELECT g.id, title, img_path, likes_count, u.name ' .
        'FROM gifs g ' .
        'JOIN users u ON g.user_id = u.id ' .
        'JOIN gifs_fav gf ON gf.gif_id = g.id ' .
        'AND gf.user_id = ? LIMIT ? OFFSET ?';

        $dbHelper->executeQuery($sql_favs, [
            $_SESSION['user']['id'],
            $page_items,
            $offset
        ]);

        if (!$dbHelper->getLastError()) {
            $favs = $dbHelper->getResultAsArray();
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }
    }
    else {
        http_response_code(403);
    }
}

$pagination = include_template('pagination.php', [
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$page_content = include_template('main.php', [
    'gifs' => $favs,
    'title' => 'Избранное',
    'pagination' => $pagination
]);

if (isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Моё избранное'
    ]);
}
else {
    http_response_code(403);
}

print($layout_content);
