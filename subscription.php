<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'validation.php';
require_once 'db_requests.php';
require_once 'mail.php';

$current_user = $_SESSION['user'];

$author_id = filter_input(INPUT_GET, 'author_id', FILTER_VALIDATE_INT);

if (!is_user_exist($link, (string)$author_id, 'id')) {
    header("HTTP/1.0 404 Not Found");
    exit ();
}
$author = get_user_data($link, $author_id);

if (is_following($link, $current_user['id'], $author_id)) {
    $sql = "DELETE FROM subscriptions WHERE subscriptions.author_id = ? AND subscriptions.follower_id = ?";
} else {
    $sql = "INSERT INTO subscriptions (author_id, follower_id) VALUES (?, ?)";
    $message = (new Swift_Message('У вас новый подписчик'))
        ->setFrom(['keks@phpdemo.ru' => 'readme'])
        ->setTo([$author['email'] => $author['login']])
        ->setBody('Здравствуйте, '.$author['login'].'. На вас подписался новый пользователь '.$current_user['login'].'. Вот ссылка на его профиль: http://576665-readme-12/profile.php?user_id='.$current_user['id']);
    $result = $mailer->send($message);
}
$stmt = db_get_prepare_stmt($link, $sql, [$author_id, $current_user['id']]);
mysqli_stmt_execute($stmt);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit();

