<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_requests.php';
require_once 'validation.php';

check_page_access();

$current_user = $_SESSION['user'];

if (isset($_GET['post_id'])) {
    $post_id = filter_input(INPUT_GET, 'post_id');
    $post = get_posts_by_parameters($link, [
        'post_id' => $post_id,
    ],
        $current_user['id']);

    if (empty($post)) {
        header("HTTP/1.0 404 Not Found");
        exit ();
    }
    $post = call_user_func_array('array_merge', $post);
    $original_id = $post['original_id'] ?? $post['post_id'];
    $post['reposts_count'] = get_posts_count($link, ['original_id' => $original_id]);

    $view_count = ++$post['view_count'];
    mysqli_query($link, "UPDATE posts SET posts.view_count = {$view_count} WHERE posts.post_id = {$post_id}");

    $author_data = get_user_data($link, $post['user_id']);
    $author_data['is_following'] = is_following($link, $current_user['id'], $author_data['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $new_comment = filter_input_array(INPUT_POST, [
        'comment' => FILTER_DEFAULT,
        'post_id' => FILTER_VALIDATE_INT,
    ], true);

    if (!is_post_exist($link, $new_comment['post_id'])) {
        header("HTTP/1.0 404 Not Found");
        exit ();
    }

    $new_comment['comment'] = trim($new_comment['comment']);

    $rules = [
        'comment' => function ($value) {
            return comment_validate($value);
        },
    ];
    $errors = check_data_by_rules($new_comment, $rules);

    if (empty($errors)) {
        $sql = 'INSERT INTO comments (content, user_id, post_id) VALUE (?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [
            $new_comment['comment'],
            $current_user['id'],
            $post_id,
        ]);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            exit ('error'.mysqli_error($link));
        }
        header('Location: /profile.php?user_id='.$author_data['id']);
        exit();
    }
}

$comments = get_comments($link, $post_id);
$post['comments_count'] = count($comments);

$post_content = include_template("post/post-{$post['class']}.php", [
    'post' => $post,
]);

$page_content = include_template('post.php', [
    'post' => $post,
    'post_content' => $post_content,
    'current_user' => $current_user,
    'new_comment' => $new_comment ?? '',
    'errors' => $errors ?? '',
    'comments' => $comments ?? '',
    'author_data' => $author_data,
]);

$layout_content = include_template('layout.php', [
    'current_user' => $current_user,
    'content' => $page_content,
    'title' => 'readme: публикация',
]);

print ($layout_content);

