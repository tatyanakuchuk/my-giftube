<?php

require_once('functions.php');
require_once('data.php');

$pagination = include_template('pagination.php');

$page_content = include_template('index.php', [
    'gifs' => $gifs,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная страница'
]);

print($layout_content);
