<?php

session_start();

require_once('bootstrap.php');

if ($dbHelper->getLastError()) {
    show_error('Ошибка MySQL: ', $dbHelper->getLastError());
}
else {
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        $gif_id = $_GET['id'];

        if (!isset($_GET['rem'])) {

            $sql = "INSERT INTO gifs_fav (user_id, gif_id) VALUES (?, ?)";
            $dbHelper->executeQuery($sql, [
                $user_id,
                $gif_id
            ]);
            if (!$dbHelper->getLastError()) {
                // обновляем количество добавлений в избранное
                $sql_update_favs = 'UPDATE gifs SET favs_count = favs_count + 1 WHERE id = ?';
                $dbHelper->executeQuery($sql_update_favs, [$gif_id]);
            }
            else {
                show_error('Ошибка MySQL: ', $dbHelper->getLastError());
            }
        }
        else {
            $sql = 'DELETE FROM gifs_fav WHERE user_id = ? AND gif_id = ?';
            $dbHelper->executeQuery($sql, [
                $user_id,
                $gif_id
            ]);
            if (!$dbHelper->getLastError()) {
                // обновляем количество добавлений в избранное
                $sql_update_favs = 'UPDATE gifs SET favs_count = favs_count - 1 WHERE id = ?';
                $dbHelper->executeQuery($sql_update_favs, [$gif_id]);
            }
            else {
                show_error('Ошибка MySQL: ', $dbHelper->getLastError());
            }
        }
        // обновляем количество просмотров в БД
        $sql_update_views = 'UPDATE gifs SET views_count = views_count + 1 WHERE id = ?';
        $dbHelper->executeQuery($sql_update_views, [$gif_id]);;

        header('Location: /gif.php?id=' . $gif_id);
    }
}
