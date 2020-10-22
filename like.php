<?php


require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_requests.php';

$current_user_id = $_SESSION['user']['id'];

$post_id = filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT);

if (!is_post_exist($link, (int)$post_id)) {
    header("HTTP/1.0 404 Not Found");
    exit ();
}

if (is_liked($link, $post_id, $current_user_id)) {
    $sql = "DELETE FROM likes WHERE likes.post_id = ? AND likes.user_id = ?";
} else {
    $sql = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
}
$stmt = db_get_prepare_stmt($link, $sql, [$post_id, $current_user_id]);
mysqli_stmt_execute($stmt);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit();

