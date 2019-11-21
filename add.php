<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('add.php', [
    'categories' => $categories,
    'title' => 'Добавить гифку',
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Добавление новой гифки'
]);

print($layout_content);
