<?php

require_once('data.php');
require_once('functions.php');

$page_content = include_template('signup.php', [
    'title' => 'Регистрация'
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Регистрация пользователя'
]);

print($layout_content);
