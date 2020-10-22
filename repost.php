<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_requests.php';

check_page_access();

$current_user = $_SESSION['user'];
$post_id = isset($_GET['post_id']) ? filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT) : '';

if (!is_post_exist($link, (int)$post_id)) {
    header("HTTP/1.0 404 Not Found");
    exit ();
}


$sql = 'SELECT posts.* FROM posts WHERE post_id = ?';
$stmt = db_get_prepare_stmt($link, $sql, [$post_id]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    exit('error'.mysqli_error($link));
}
$repost_data = mysqli_fetch_assoc($result);
if (!empty($repost_data['original_id']) || $repost_data['user_id'] === $current_user['id']) {
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit();
}

$repost_data['user_id'] = $current_user['id'];
$repost_data['original_id'] = $repost_data['post_id'];

create_post_sql($link, $repost_data);

header("Location: /profile.php?user_id=".$current_user['id']);
exit();

