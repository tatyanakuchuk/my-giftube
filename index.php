<?php

$isMainPage = true;

require_once('functions.php');
require_once('data.php');

$pagination = include_template('pagination.php');

$main_content = include_template('main.php', [
    'gifs' => $gifs,
    'pagination' => $pagination,
    'title' => 'Смешные гифки',
    'isMainPage' => $isMainPage
]);

$layout_content = include_template('layout.php', [
    'content' => $main_content,
    'categories' => $categories,
    'title' => 'Главная страница',
    'isMainPage' => $isMainPage
]);

print($layout_content);
