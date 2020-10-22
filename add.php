<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_requests.php';
require_once 'validation.php';
require_once 'mail.php';
require_once 'vendor/autoload.php';

check_page_access();

$current_user = $_SESSION['user'];

$post_types = get_post_types($link);

$active_post_type_id = isset($_GET['post_type'])
    ? filter_input(INPUT_GET, 'post_type', FILTER_VALIDATE_INT)
    : filter_input(INPUT_POST, 'post_type_id', FILTER_VALIDATE_INT);

$active_post_type = get_active_post_type($link, (string)$active_post_type_id);

if (is_null($active_post_type)) {
    header("HTTP/1.0 404 Not Found");
    exit ();
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
        'post_type_id' => FILTER_DEFAULT,
        'tags' => FILTER_DEFAULT,
    ], true);

    foreach ($new_post as $key => $value) {
        $new_post[$key] = !empty($value) ? trim($value) : '';
    }
    $new_post['post_type'] = $active_post_type;
    $new_post['user_id'] = $_SESSION['user']['id'];
    $new_post['view_count'] = 0;
    $new_post['original_id'] = null;
    $rules = validate_post_rules($new_post['post_type'], $new_post['tags']);
    $errors = check_data_by_rules($new_post, $rules);
    if ($new_post['post_type'] === PHOTO) {
        if (empty($new_post['img']) && empty($_FILES['upload_photo']['name'])) {
            $errors['photo_post'] = 'Загрузите файл или заполните поле "ссылка из интернета"';
        } elseif (!empty($_FILES['upload_photo'])) {
            $new_post['photo'] = $_FILES['upload_photo'];
        }
    }

    if (!empty($errors)) {
        $error_titles = [
            'title' => 'Заголовок',
            'content' => $new_post['post_type'] === TEXT ? 'Текст поста' : 'Текст цитаты',
            'author_quote' => 'Автор',
            'img' => 'Ссылка из интернета',
            'video' => 'Ссылка на YOUTUBE',
            'link' => 'Ссылка',
            'tags' => 'Теги',
        ];
    }

    if (empty($errors)) {
        if ($new_post['post_type'] === PHOTO && !empty($new_post['photo']['name'])) {
            $new_post['img'] = upload_photo($new_post['photo']);
        } elseif ($new_post['post_type'] === PHOTO && !empty($new_post['img'])) {
            $data = file_get_contents($new_post['img']);
            $headers = get_headers($new_post['img'], 1);
            $type = $headers['Content-Type'];
            $extension = substr($type, strpos($type, '/') + 1);
            $path = 'uploads/'.uniqid().".{$extension}";
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

        $post_id = create_post_sql($link, $new_post);

        if (!empty($tags_id)) {
            create_post_tag_sql($link, $post_id, $tags_id);
        }

        $followers = get_followers($link, $current_user['id']);

        foreach ($followers as $follower) {
            $message = (new Swift_Message("Новая публикация от пользователя {$current_user['login']}"))
                ->setFrom(['keks@phpdemo.ru' => 'readme'])
                ->setTo([$follower['email'] => $follower['login']])
                ->setBody("Здравствуйте, {$follower['login']}. Пользователь {$current_user['login']} только что опубликовал новую запись: {$new_post['title']}. Посмотрите её на странице пользователя: http://576665-readme-12/profile.php?user_id={$current_user['id']}");
            $mailer->send($message);
        }
        header('Location: /post.php?post_id='.$post_id);
        exit();
    }
}

$adding_post_content = include_template("/adding-post/adding-post-{$active_post_type}.php", [
    'post_types' => $post_types,
    'new_post' => !empty($new_post) ? $new_post : '',
    'errors' => !empty($errors) ? $errors : '',
]);

$page_content = include_template('adding-post.php', [
    'post_types' => $post_types,
    'active_post_type' => $active_post_type,
    'active_post_type_id' => $active_post_type_id,
    'adding_post_content' => $adding_post_content,
    'new_post' => !empty($new_post) ? $new_post : '',
    'errors' => !empty($errors) ? $errors : '',
    'error_titles' => !empty($error_titles) ? $error_titles : '',
]);

$layout_content = include_template('layout.php', [
    'current_user' => $current_user,
    'content' => $page_content,
    'title' => 'readme: добавление публикации',
]);

print ($layout_content);

