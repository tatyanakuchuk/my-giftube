<?php

session_start();
$isFormPage = true;

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

    // send form
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sign_in = $_POST;

        $required = ['email', 'password'];
        $errors = [];
        $dict = [
            'email' => 'E-mail',
            'password' => 'Пароль'
        ];

        foreach($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле должно быть заполнено';
            }
        }

        //проверка email на корректность
        if (!empty($sign_in['email'])) {
            if (!filter_var($sign_in['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email должен быть корректным';
            }
        }

        // проверка на существование пользователя с таким же email
        $sql = 'SELECT * FROM users WHERE email = ?';
        $pass = $dbHelper->executeQuery($sql, [
            $sign_in['email']
        ]);
        if (!$dbHelper->getLastError()) {
            $user = $pass ? $dbHelper->getResultAsArray() : null;
            if($user) {
                if (password_verify($sign_in['password'], $user[0]['password'])) {
                    $_SESSION['user'] = $user[0];
                }
                else {
                    $errors['password'] = 'Вы ввели неверный пароль';
                }
            }
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }

        if (count($errors)) {
            $signin_form = include_template('signin-form.php', [
                'sign_in' => $sign_in,
                'errors' => $errors,
                'dict' => $dict
            ]);
        }
        else {
            header('Location: /');
            exit();
        }
    }
    else {
        $signin_form = include_template('signin-form.php');
    }
}

$page_content = include_template('main.php', [
    'form' => $signin_form,
    'title' => 'Вход для своих',
    'isFormPage' => $isFormPage
]);

if (isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Вход на сайт'
    ]);
}
else {
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Вход на сайт'
    ]);
}

print($layout_content);
