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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $gif = $_POST;
        $required = ['category', 'gif-title', 'gif-description'];
        $errors = [];
        $dict = [
            'gif-img' => 'Гифка',
            'category' => 'Категория',
            'gif-title' => 'Название',
            'gif-description' => 'Описание'
        ];

        foreach($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле должно быть заполнено';
            }
        }

        if (isset($_FILES['gif-img']['name'])) {
            if (empty($_FILES['gif-img']['name'])) {
                $errors['gif-img'] = 'Вы не загрузили файл';
            }
            else {
                $tmp_name = $_FILES['gif-img']['tmp_name'];
                $file = $_FILES['gif-img']['name'];

                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file_type = finfo_file($finfo, $tmp_name);

                // Получаем расширение загруженного файла
                $extension = strtolower(substr(strrchr($file, '.'), 1));
                //Генерируем новое имя файла
                $file = uniqid() . '.' .  $extension;
                //Папка назначения
                $dest = 'uploads/';

                if ($file_type !== "image/gif") {
                    $errors['gif-img'] = 'Загрузите гифку в формате GIF';
                }
                else {
                    move_uploaded_file($tmp_name, $dest . $file);
                    $gif['img_path'] = $dest . $file;
                }
            }
        }

        if (isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];
        }

        if (count($errors)) {
            $add_form = include_template('add-form.php', [
                'gif' => $gif,
                'categories' => $categories,
                'errors' => $errors,
                'dict' => $dict
            ]);
        }
        else {
            // Запрос: загрузка гифки
            $sql_add = 'INSERT INTO gifs (dt_add, category_id, user_id, title, description, ' .
            'img_path, likes_count, favs_count, views_count) ' .
            'VALUES (NOW(), ?, ?, ?, ?, ?, 0, 0, 0)';

            $dbHelper->executeQuery($sql_add, [
                $gif['category'],
                $user_id,
                $gif['gif-title'],
                $gif['gif-description'],
                $gif['img_path']
            ]);

            if (!$dbHelper->getLastError()) {
                $gif_id = $dbHelper->getLastId();
                header('Location: /gif.php?id=' . $gif_id);
            }
            else {
                show_error('Ошибка MySQL: ', $dbHelper->getLastError());
            }
        }

    }
    else {
        $add_form = include_template('add-form.php', [
            'gif' => $gif,
            'categories' => $categories
        ]);
    }

}

$page_content = include_template('main.php', [
        'form' => $add_form,
        'title' => 'Добавить гифку',
        'isFormPage' => $isFormPage
    ]);

if (isset($_SESSION['user'])) {
    $layout_content = include_template('layout.php', [
        'username' => $_SESSION['user']['name'],
        'content' => $page_content,
        'categories' => $categories,
        'title' => 'Добавление новой гифки'
    ]);
}
else {
    http_response_code(403);
}

print($layout_content);
