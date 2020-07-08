<?php

require_once 'init.php';
/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}


/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

if (isset($_GET['post_id'])) {
    $param_id = filter_input(INPUT_GET, 'post_id');
}

if (empty($link)) {
    print ('error' . mysqli_connect_error());
} else {
    $sql = 'SELECT * , users.id, post_types.id FROM posts'
        . ' JOIN users ON posts.user_id = users.id'
        . ' JOIN post_types ON posts.post_type_id = post_types.id'
        . ' WHERE post_id =' . $param_id;

    $result = mysqli_query($link, $sql);

    if ($result) {
        $post = mysqli_fetch_assoc($result);
    }

    $sql = 'SELECT * FROM subscriptions'
        . ' WHERE subscriptions.author_id = ' . $post['user_id'];
    $result = mysqli_query($link, $sql);
    if ($result) {
        $subscriptions_count = mysqli_num_rows($result);
    }

    $sql = 'SELECT * FROM posts'
        . ' WHERE posts.user_id = ' . $post['user_id'];
    $result = mysqli_query($link, $sql);
    if($result) {
        $posts_count = mysqli_num_rows($result);
    }
}

if (empty($post)) {
    print ('error 404. Page not found');
}

$post_content = include_template("post-{$post['class']}.php", [
    'post' => $post
]);

$page_content = include_template('post.php', [
    'post' => $post,
    'post_content' => $post_content,
    'subscriptions_count' => $subscriptions_count,
    'posts_count' => $posts_count
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'readme: публикация'

]);

print ($layout_content);