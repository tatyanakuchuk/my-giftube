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

            $sql = "INSERT INTO gifs_like (user_id, gif_id) VALUES (?, ?)";
            $dbHelper->executeQuery($sql, [
                $user_id,
                $gif_id
            ]);
            if (!$dbHelper->getLastError()) {

                // обновляем количество просмотров в БД
                $sql_update_views = 'UPDATE gifs SET views_count = views_count + 1 WHERE id = ?';
                $dbHelper->executeQuery($sql_update_views, [$gif_id]);

            }
        }
        else {
            $sql = 'DELETE FROM gifs_like WHERE user_id = ? AND gif_id = ?';
            $dbHelper->executeQuery($sql, [
                $user_id,
                $gif_id
            ]);
            if (!$dbHelper->getLastError()) {
                // обновляем количество просмотров в БД
                $sql_update_views = 'UPDATE gifs SET views_count = views_count + 1 WHERE id = ?';
                $dbHelper->executeQuery($sql_update_views, [$gif_id]);
            }
        }
        // подсчет лайков
        $sql_count_likes = 'SELECT count(id) FROM gifs_like WHERE gif_id = ?';
        $dbHelper->executeQuery($sql_count_likes, [$gif_id]);
        if (!$dbHelper->getLastError()) {
            $count_likes = $dbHelper->getResultAsArray()[0];
        }
        else {
            show_error('Ошибка MySQL: ', $dbHelper->getLastError());
        }

        // Обновление лайков в таблице с гифками
        $sql_update_likes = 'UPDATE gifs SET likes_count = ? WHERE id = ?';
        $dbHelper->executeQuery($sql_update_likes, [
            $count_likes['count(id)'],
            $gif_id
        ]);
        header('Location: /gif.php?id=' . $gif_id);
    }
}
