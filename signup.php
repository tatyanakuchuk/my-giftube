<?php

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
        $sign_up = $_POST;

        $required = ['email', 'password', 'name'];
        $errors = [];
        $dict = [
            'email' => 'E-mail',
            'password' => 'Пароль',
            'name' => 'Имя',
            'avatar' => 'Фото'
        ];

        foreach($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле должно быть заполнено';
            }
        }

        $email = $sign_up['email'];

        //проверка email на корректность
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email должен быть корректным';
            }
        }

        // проверка на существование пользователя с таким же email
        if (!empty($email)) {
            $sql = 'SELECT id FROM users WHERE email = ?';

            $dbHelper->executeQuery($sql, [$email]);

            if (!$dbHelper->getLastError()) {
                $emails = $dbHelper->getResultAsArray();
                if(!empty($emails)) {
                    $errors['email'] = 'Введённый вами email уже зарегистрирован. Введите другой email.';
                }
            }
            else {
                show_error('Ошибка MySQL: ', $dbHelper->getLastError());
            }
        }

        // load avatar
        if (isset($_FILES['avatar']['name'])) {
            if(!empty($_FILES['avatar']['name'])) {
                $tmp_name = $_FILES['avatar']['tmp_name'];
                $file = $_FILES['avatar']['name'];

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file_type = finfo_file($finfo, $tmp_name);

                // Получаем расширение загруженного файла
                $extension = strtolower(substr(strrchr($file, '.'), 1));
                //Генерируем новое имя файла
                $file = uniqid() . '.' .  $extension;
                //Папка назначения
                $dest = 'uploads/avatar/';

                if (($file_type == "image/gif") || ($file_type == "image/jpeg") || ($file_type == "image/png") || ($file_type == "image/pjepg")) {
                    move_uploaded_file($tmp_name, $dest . $file);
                    $sign_up['avatar_path'] = $dest . $file;
                }
                else {
                    $errors['avatar'] = 'Файл с таким расширением невозможно загрузить';
                }
            }
        }

        if (count($errors)) {
            $signup_form = include_template('signup-form.php', [
                'sign_up' => $sign_up,
                'errors' => $errors,
                'dict' => $dict
            ]);
        }
        else {
            // хэш от пароля
            $password = password_hash($sign_up['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (dt_add, name, email, password, avatar_path) ' .
                    'VALUES (NOW(), ?, ?, ?, ?)';

            $dbHelper->executeQuery($sql_top_gifs, [
                $sign_up['name'],
                $sign_up['email'],
                $password,
                $sign_up['avatar_path']
            ]);

            if (!$dbHelper->getLastError()) {
                $user_id = $dbHelper->getLastId();
                header('Location: /signin.php');
            }
            else {
                show_error('Ошибка MySQL: ', $dbHelper->getLastError());
            }
        }
    }
    else {
        $signup_form = include_template('signup-form.php');
    }
}

$page_content = include_template('main.php', [
    'form' => $signup_form,
    'title' => 'Регистрация',
    'isFormPage' => $isFormPage
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Регистрация пользователя'
]);

print($layout_content);
