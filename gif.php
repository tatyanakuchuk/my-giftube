<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('gif.php', [
    'gifs' => $gifs
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная страница'
]);

print($layout_content);
