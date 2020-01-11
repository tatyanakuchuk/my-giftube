<?php

session_start();

$isGifPage = true;

require_once('functions.php');

//подключение к MySQL
$connect = mysqli_connect("localhost", "root", "", "giftube");
mysqli_set_charset($connect, "utf8");

if(!$connect) {
    print('Ошибка подключения: ' . mysqli_connect_error());
}
else {

    // 1. запрос для получения списка категорий;
    $sql_cat = 'SELECT * FROM categories';
    $res_cat = mysqli_query($connect, $sql_cat);
    if($res_cat) {
        $categories = mysqli_fetch_all($res_cat, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    if (isset($_GET['id'])) {
        $gif_id = $_GET['id'];
    }

    // 2. запрос для получения данных гифки по id
    $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, ' .
                'likes_count, favs_count, views_count, description ' .
                'FROM gifs g ' .
                'JOIN categories c ON g.category_id = c.id ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE g.id = ' . $gif_id;

    $res_gif = mysqli_query($connect, $sql_gif);

    if($res_gif) {
        $gif = mysqli_fetch_assoc($res_gif);
        if(!isset($gif)) {
            header('Location: /error404.php');
            http_response_code(404);
            $is404error = true;
        }
    }
    else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // если гифка добавлена в избранное
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        $gif_id = $_GET['id'];

        $isLiked = false;
        $isFav = false;

        $sql_fav = 'SELECT id FROM gifs_fav WHERE user_id = ' . $user_id .
        ' AND gif_id = ' . $gif_id;
        $res_fav = mysqli_query($connect, $sql_fav);

        if($res_fav) {
            $fav = mysqli_fetch_assoc($res_fav);
            if(!empty($fav)) {
            $isFav = true;

                // header('Location: /gif.php?id=' . $gif_id . "");
            }
        }

        $sql_like = 'SELECT id FROM gifs_like WHERE user_id = ' . $user_id .
        ' AND gif_id = ' . $gif_id;
        $res_like = mysqli_query($connect, $sql_like);

        if($res_like) {
            $like = mysqli_fetch_assoc($res_like);
            if(!empty($like)) {
                $isLiked = true;
            }
        }
    }
    // end если гифка добавлена в избранное

    // 3. add comment
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];

        // add comment
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $gif_id = $_POST['gif_id'];
            $comment = $_POST['comment'];

            $sql_gif = 'SELECT g.id, category_id, u.name, title, img_path, ' .
                'likes_count, favs_count, views_count, description ' .
                'FROM gifs g ' .
                'JOIN categories c ON g.category_id = c.id ' .
                'JOIN users u ON g.user_id = u.id ' .
                'WHERE g.id = ' . $gif_id;

            $res_gif = mysqli_query($connect, $sql_gif);
            if($res_gif) {
                $gif = mysqli_fetch_assoc($res_gif);
            }
            else {
                $error = mysqli_error($connect);
                print('Ошибка MySQL: ' . $error);
            }

            $required = ['comment'];
            $errors = [];

            foreach($required as $key) {
                if (empty($_POST[$key])) {
                    $errors[$key] = 'Это поле должно быть заполнено';
                }
            }

            if(!count($errors)) {
                $sql = "INSERT INTO comments (dt_add, user_id, gif_id, comment_text) VALUES (NOW(), ?, ?, ?)";
                $stmt = db_get_prepare_stmt($connect, $sql, [$user_id, $gif_id, $comment]);
                $res = mysqli_stmt_execute($stmt);
                if ($res) {
                    $content = include_template('gif.php', [
                        'gif' => $gif,
                        'comments' => $comments,
                        'gifs' => $similar_gifs,
                        'isGifPage' => $isGifPage
                    ]);
                } else {
                    $error = mysqli_error($connect);
                    print($error);
                }
            }
        }
    }

    // 4. all comments
    $sql_comments = 'SELECT c.dt_add, avatar_path, name, comment_text ' .
                'FROM comments c ' .
                'JOIN gifs g ON g.id = c.gif_id ' .
                'JOIN users u ON c.user_id = u.id ' .
                'WHERE g.id = ' . $gif_id;

    $res_comments = mysqli_query($connect, $sql_comments);

    if($res_comments) {
        $comments = mysqli_fetch_all($res_comments, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($connect);
        print('Ошибка MySQL: ' . $error);
    }

    // 5. запрос для списка похожих гифок
    if(!$is404error) {
        $sql_similar = 'SELECT g.id, category_id, u.name, title, img_path, likes_count ' .
                    'FROM gifs g ' .
                    'JOIN categories c ON g.category_id = c.id ' .
                    'JOIN users u ON g.user_id = u.id ' .
                    'WHERE category_id = ' . $gif['category_id'] .
                    ' AND g.id NOT IN(' . $gif_id . ') ' .
                    ' LIMIT 6';
        $res_similar = mysqli_query($connect, $sql_similar);
        if($res_similar) {
            $similar_gifs = mysqli_fetch_all($res_similar, MYSQLI_ASSOC);
        } else {
            $error = mysqli_error($connect);
            print('Ошибка MySQL: ' . $error);
        }
    }
}

if ($is404error) {

    $page_content = include_template('main.php', [
        'title' => '404 Страница не найдена',
        'is404error' => $is404error
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'categories' => $categories,
        'title' => '404 Страница не найдена'
    ]);

}
else {

    $page_content = include_template('gif.php', [
        'errors' => $errors,
        'gif' => $gif,
        'comments' => $comments,
        'gifs' => $similar_gifs,
        'count_likes' => $count_likes,
        'isGifPage' => $isGifPage
    ]);

    if (isset($_SESSION['user'])) {

        $page_content = include_template('gif.php', [
            'errors' => $errors,
            'gif' => $gif,
            'comments' => $comments,
            'gifs' => $similar_gifs,
            'isGifPage' => $isGifPage,
            'isFav' => $isFav,
            'isLiked' => $isLiked
        ]);

        $layout_content = include_template('layout.php', [
            'username' => $_SESSION['user']['name'],
            'content' => $page_content,
            'categories' => $categories,
            'title' => $gif['title']
        ]);
    }
    else {
        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'categories' => $categories,
            'title' => $gif['title']
        ]);
    }

}

print($layout_content);
