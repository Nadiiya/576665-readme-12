<?php

require_once('init.php');
require_once('helpers.php');
require_once('functions.php');
require_once('db_requests.php');
require_once('validation.php');

if (isset($_SESSION['user'])) {
    header('Location: /feed.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authorization_data = [];
    $errors = [];

    $authorization_data = filter_input_array(INPUT_POST, [
        'login' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
    ], true);

    foreach ($authorization_data as $key => $value) {
        $authorization[$key] = !empty($value) ? trim($value) : '';
    }

    $rules = [
        'login' => function ($value) {
            return check_emptiness($value);
        },
        'password' => function ($value) {
            return check_emptiness($value);
        },
    ];

    $errors = check_data_by_rules($authorization_data, $rules);

    if (empty($errors)) {
        $sql = 'SELECT * from users WHERE login = ?';
        $stmt = db_get_prepare_stmt($link, $sql, [$authorization_data['login']]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            exit('error'.mysqli_error($link));
        }

        $current_user = mysqli_fetch_array($result, MYSQLI_ASSOC);
        if (!$current_user) {
            $errors['login'] = 'Неверный логин';
        } else {
            $password_verify = password_verify($authorization_data['password'], $current_user['password']);
        }

        if ($current_user && $password_verify) {
            $_SESSION['user'] = $current_user;
            header('Location: /feed.php');
            exit();
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    }
}

$layout_content = include_template('index.php', [
    'authorization_data' => !empty($authorization_data) ? $authorization_data : '',
    'errors' => !empty($errors) ? $errors : '',
]);

print ($layout_content);

