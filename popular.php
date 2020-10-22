<?php

require_once 'init.php';
require_once 'helpers.php';
require_once 'functions.php';
require_once 'db_requests.php';

check_page_access();

$current_user = $_SESSION['user'];

$post_types = get_post_types($link);
$popular_posts = [];

$post_type = !empty($_GET['post_type']) ? $_GET['post_type'] : '';
$sorting = !empty($_GET['sorting']) ? $_GET['sorting'] : 'view_count';
$order = !empty($_GET['order']) ? $_GET['order'] : 'DESC';

$params = [
    'post_type_id' => $post_type,
];
$posts_counts = get_posts_count($link, $params);
if ($posts_counts) {
    $current_page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?? 1;
    $pages_count = ceil($posts_counts / POST_PER_PAGE);
    $prev_page = ($current_page - 1) > 0 ? $current_page - 1 : '';
    $next_page = ($current_page + 1) <= $pages_count ? $current_page + 1 : '';
    $offset = ($current_page - 1) * POST_PER_PAGE;
    $limit = POST_PER_PAGE;
    $popular_posts = get_posts_by_parameters($link, $params, $current_user['id'], $sorting, $order, $limit, $offset);
}

$page_content = include_template('popular.php', [
    'popular_posts' => $popular_posts,
    'post_types' => $post_types,
    'active_post_type' => $post_type,
    'sorting' => $sorting,
    'order' => $order,
    'pages_count' => $pages_count ?? '',
    'prev_page' => $prev_page ?? '',
    'next_page' => $next_page ?? '',
]);

$layout_content = include_template('layout.php', [
    'current_user' => $current_user,
    'content' => $page_content,
    'title' => 'readme: популярное',
]);

print ($layout_content);

