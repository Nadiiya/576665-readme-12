<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';

if (!isset($_SESSION['user'])) {
    header('Location: /index.php');
    exit();
}

$result = mysqli_query($link, "SELECT * FROM post_types");
if (!$result) {
    exit ('error' . mysqli_error($link));
}
$post_types = mysqli_fetch_all($result, MYSQLI_ASSOC);

$active_post_type_id = isset($_GET['post_type'])
    ? filter_input(INPUT_GET, 'post_type', FILTER_DEFAULT)
    : filter_input(INPUT_POST, 'post_type_id', FILTER_DEFAULT);

$active_post_type = get_active_post_type($link, $active_post_type_id);

if (is_null($active_post_type)) {
    header("HTTP/1.0 404 Not Found");
    exit ('PAGE NOT FOUND');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $new_post = filter_input_array(INPUT_POST, [
        'title' => FILTER_DEFAULT,
        'content' => FILTER_DEFAULT,
        'author_quote' => FILTER_DEFAULT,
        'img' => FILTER_DEFAULT,
        'video' => FILTER_DEFAULT,
        'link' => FILTER_DEFAULT,
        'post_type_id' =>FILTER_DEFAULT,
        'tags' => FILTER_DEFAULT
    ], true);

    foreach ($new_post as $key => $value) {
        $new_post[$key] = !empty($value) ? trim($value) : '';
    }
    $new_post['post_type'] = $active_post_type;
    $new_post['user_id'] = $_SESSION['user']['id'];
    $new_post['view_count'] = 0;

    if ($new_post['post_type'] === PHOTO) {
        if (empty($new_post['img']) && empty($_FILES['upload_photo']['name'])) {
            $errors['photo_post'] = 'Загрузите файл или заполните поле "ссылка из интернета"';
        } elseif (!empty($_FILES['upload_photo'])) {
            $new_post['photo'] = $_FILES['upload_photo'];
        }
    }

    $rules = validate_post_rules($new_post['post_type'], $new_post['tags']);

    $errors = check_data_by_rules($new_post, $rules);

    if (!empty($errors)) {
        $error_titles = [
            'title' => 'Заголовок',
            'content' => $new_post['post_type'] === TEXT ? 'Текст поста' : 'Текст цитаты',
            'author_quote' => 'Автор',
            'img' => 'Ссылка из интернета',
            'video' => 'Ссылка на YOUTUBE',
            'link' => 'Ссылка',
            'tags' => 'Теги'
        ];
    }

    if (!count($errors)) {
        if ($new_post['post_type'] === PHOTO && !empty($new_post['photo']['name'])) {
            $new_post['img'] = upload_photo($new_post['photo']);
        } elseif ($new_post['post_type'] === PHOTO && !empty($new_post['img'])) {
            $data = file_get_contents($new_post['img']);
            $headers = get_headers($new_post['img'], 1);
            $type = $headers['Content-Type'];
            $extension = substr($type, strpos($type, '/') + 1);
            $path = 'uploads/' . uniqid() . ".{$extension}";
            $new_post['img'] = $path;
            file_put_contents($path, $data);
        }

        if (!empty($new_post['tags'])) {
            $exists_tags = [];
            $new_tags = array_unique(explode(' ', $new_post['tags']));

            $result = mysqli_query($link, 'SELECT id, tag from tags');
            if (!$result) {
                exit ('error'.mysqli_error($link));
            }
            $exists_tags = mysqli_fetch_all($result, MYSQLI_ASSOC);
            if (!empty($new_tags)) {
                foreach ($new_tags as $tag) {
                    $tags_id[] = get_tag_id($link, $tag, $exists_tags);
                }
            }

        }

    $sql = 'INSERT INTO posts (title, content, author_quote, img, video, link, view_count, user_id, post_type_id)
    VALUE (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [
            $new_post['title'],
            $new_post ['content'],
            $new_post['author_quote'],
            $new_post['img'],
            $new_post['video'],
            $new_post['link'],
            $new_post['view_count'],
            $new_post['user_id'],
            $new_post['post_type_id']
        ]);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            exit ('error' . mysqli_error($link));
        }
        $post_id = mysqli_insert_id($link);

        if (!empty($tags_id)) {
            create_post_tag_sql($link, $post_id, $tags_id);
        }
        header('Location: /posts.php?post_id=' . $post_id);
        exit();
    }
}

$adding_post_content = include_template("/adding-post/adding-post-{$active_post_type}.php", [
    'post_types' => $post_types,
    'new_post' => !empty($new_post) ? $new_post : '',
    'errors' => !empty($errors) ? $errors : ''
]);


$page_content = include_template('adding-post.php', [
    'post_types' => $post_types,
    'active_post_type' => $active_post_type,
    'active_post_type_id' => $active_post_type_id,
    'adding_post_content' => $adding_post_content,
    'new_post' => !empty($new_post) ? $new_post : '',
    'errors' => !empty($errors) ? $errors : '',
    'error_titles' => !empty($error_titles) ? $error_titles : ''
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'readme: добавление публикации'

]);

print ($layout_content);
