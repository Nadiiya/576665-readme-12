<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_requests.php';

check_page_access();

$current_user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $posts = [];

    $search_query = trim($_GET['q']) ?? '';

    $sql = "SELECT posts.*,
            users.login,
            users.picture,
            users.registered,
            post_types.class,
       (SELECT COUNT(likes.id) FROM likes WHERE likes.post_id = posts.post_id) as likes_count,
        (SELECT COUNT(comments.id) FROM comments WHERE comments.post_id = posts.post_id) as comments_count
            FROM posts
            JOIN post_types ON posts.post_type_id = post_types.id
            JOIN users ON posts.user_id = users.id ";

    if (substr($search_query, 0, 1) === '#') {
        $search = substr($search_query, 1);
        $sql .= "JOIN post_tag ON posts.post_id = post_tag.post_id
            JOIN tags ON post_tag.tag_id = tags.id
            WHERE tags.tag = ?";
    } else {
        $search = $search_query;
        $sql .= "WHERE MATCH(posts.title, posts.content) AGAINST(?)";
    }
    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        exit ('error'.mysqli_error($link));
    }
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!empty($posts)) {
        $posts_id = array_column($posts, 'post_id');
        $posts_tags = get_posts_tags($link, $posts_id);

        foreach ($posts as $key => $post) {
            $posts[$key]['tags'] = $posts_tags[$posts[$key]['post_id']] ?? '';
            $posts[$key]['is_liked'] = is_liked($link, (int)$posts[$key]['post_id'], $current_user['id']);
        }
    }
}

$page_content = include_template('search-results.php', [
    'posts' => $posts ?? '',
    'search_query' => $search_query,
]);

$layout = include_template('layout.php', [
    'current_user' => $current_user,
    'content' => $page_content,
    'title' => 'readme: результаты поиска',
    'search_query' => $search_query,
]);

print $layout;
