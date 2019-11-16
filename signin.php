<?php

require_once('functions.php');
require_once('data.php');

$page_content = include_template('signin.php', [
    'title' => 'Вход для своих'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Вход на сайт'
]);

print($layout_content);
