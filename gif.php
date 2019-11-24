<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('gif.php', [
    'gifs' => $gifs
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Название гифки',
    'isGifPage' => true
]);


print($layout_content);
