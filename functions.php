<?php

/**
 * Converts special characters to HTML entities
 * @param string $str
 *
 * @return string The converted string
 */
function esc(string $str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * Сut string to a character length, the default is 80,
 * adds "..." at the end of excerpt
 * @param string $text
 * @param int $excerpt_length Maximum allowed length
 *
 * @return string The original string if its length is less than
 * maximum allowed length or excerpt
 */
function cut_text(string $text, int $excerpt_length = 80)
{
    $text_length = mb_strlen($text);
    if ($text_length > $excerpt_length) {
        $text = mb_substr($text, 0, $excerpt_length);
        $text = mb_substr($text, 0, mb_strrpos($text, ' ')) . '...';
    }
    return $text;
}

/**
 * Returns a single number of months, days, weeks, hours, minutes
 * between the current date and the post publication date
 * @param string $post_date Date in 'Y-m-d H:i:s' format
 *
 * @return string The date in relative terms or string 'меньше минуты назад'
 * if post was created less then 60 seconds ago
 */
function relative_date(string $post_date)
{
    $publish_date = date_create($post_date);
    $cur_date = date_create('now');
    $diff = date_diff($cur_date, $publish_date);
    if ($diff->m >= 1) {
        $diff_months = $diff->m;
        return "$diff_months " . get_noun_plural_form($diff_months, 'месяц', 'месяца', 'месяцев') . ' назад';
    }
    if ($diff->d >= 7) {
        $diff_weeks = floor(($diff->d) / 7);
        return "$diff_weeks " . get_noun_plural_form($diff_weeks, 'неделя', 'недели', 'недель') . ' назад';
    }
    if ($diff->d < 7 && $diff->d >= 1) {
        $diff_days = $diff->d;
        return "$diff_days " . get_noun_plural_form($diff_days, 'день', 'дня', 'дней') . ' назад';
    }
    if ($diff->h >= 1) {
        $diff_hours = $diff->h;
        return "$diff_hours " . get_noun_plural_form($diff_hours, 'час', 'часа', 'часов') . ' назад';
    }
    if ($diff->i >= 1) {
        $diff_minutes = $diff->i;
        return "$diff_minutes " . get_noun_plural_form($diff_minutes, 'минута', 'минуты', 'минут') . ' назад';
    }
    return 'меньше минуты назад';
}

/**
 * Returns class-name of post type by id
 * @param array $array  Existing posts types array
 * @param string $id Post type ID
 *
 * @return string | null The post type class-name or null if ID is not exist
 */
function get_active_post_type(array $array, string $id)
{
    foreach ($array as $key => $value) {
        if ($value['id'] === $id) {
            return $value['class'];
        }
    }
    return null;
}

/**
 * Returns error message if string is empty
 * @param string $value
 *
 * @return string Error message or empty string
 */
function check_text(string $value)
{
    return empty($value) ? 'Это поле должно быть заполнено' : '';
}

/**
 * Checks whether a string is a link to a Youtube video
 * @param string $value video url
 *
 * @return string Error message or empty string if url is correct
 */
function check_youtube_domain(string $value)
{
    $domain = parse_url($value, PHP_URL_HOST);
    return strpos($domain, 'youtube.com') === false ? 'Введите ссылку на видео из YOUTUBE' : '';
}

function is_new_user(string $email, $link)
{
    $sql = 'SELECT id FROM users '
        . 'WHERE email =' . '?';

    $stmt = db_get_prepare_stmt($link, $sql, [
        $email
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        return false;
    }
    return true;
}

function is_valid_email(string $email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function check_password_repeat(string $value)
{
    if ($value === $_POST['password']) {
        return true;
    }
    return false;
}

function password_repeat_validate (string $value)
{
    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }
    if (check_password_repeat($value) !== true) {
        return "Пароли не совпадаеют";
    }
    return '';
}

function email_validate (string $email)
{
    if (empty($email)) {
        return "Это поле должно быть заполнено";
    }
    if (is_valid_email($email) !== true) {
        return "Адресс электронной почты не корректен";
    }
    if (is_new_user($email) !== true) {
        return "Указанный email уже используется другим пользователем";
    }
    return '';
}

/**
 * Checks whether a string is a correct link
 * @param string $link_value
 *
 * @return string Error message or empty string if link is correct
 */
function link_validate(string $link_value)
{
    if (empty($link_value)) {
        return "Это поле должно быть заполнено";
    } elseif (!filter_var($link_value, FILTER_VALIDATE_URL)) {
        return "Значение не является ссылкой";
    }
}

/**
 * Validates a string contains tags
 * @param string $tags_value
 *
 * @return string Error massage or empty string if string contains correct tags
 */
function tags_validate(string $tags_value)
{
    $invalid_tags = [];
    $tag_error = '';
    $tags_array = explode(' ', $tags_value);
    if (!empty($tags_value)) {
        foreach ($tags_array as $tag) {

            if (!preg_match('/^[a-zA-Zа-яёА-ЯЁ0-9]+$/u', $tag)) {
                $invalid_tags[] = $tag;
            }

            if (!empty($invalid_tags)) {
                $count_invalid_tags = count($invalid_tags);
                $tag_error = get_noun_plural_form($count_invalid_tags, 'Тег ', 'Теги ', 'Теги ') . implode(', ',
                        $invalid_tags) . get_noun_plural_form($count_invalid_tags, ' не корректен', ' не корректны',
                        ' не корректны');
            }

        }
    }
    return $tag_error;
}

/**
 * Validates link to a video
 * @param string $url_value
 *
 * @return string Error message or empty string if link is correct
 */
function youtube_url_validation(string $url_value)
{
    $error = '';
    if (link_validate($url_value)) {
        $error = link_validate($url_value);
    } elseif (check_youtube_url($url_value) !== true) {
        $error = check_youtube_url($url_value);
    } elseif (check_youtube_domain($url_value)) {
        $error = check_youtube_domain($url_value);
    }
    return $error;
}

/**
 * Checks image's MIME-type
 * @param string $file_type File's MIME-type
 *
 * @return bool|string Error message or true if MIME-type is correct
 */
function check_img_type(string $file_type)
{
    $required_types = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file_type, $required_types)) {
        return 'Загрузите картинку в одном из допустимых форматов: JPG PNG GIF';
    }
    return true;
}

/**
 * Validates uploaded file
 * @param array $upload_photo Array $_FILE
 *
 * @return bool|string Error message or true if file is correct
 */
function photo_validate(array $upload_photo)
{
    $tmp_name = $upload_photo['tmp_name'];
    $file_info = finfo_open(FILEINFO_MIME_TYPE);
    $file_type = finfo_file($file_info, $tmp_name);
    return check_img_type($file_type) !== true ? check_img_type($file_type) : '';
}

/**
 * Validates link to uploaded file
 * @param string $photo_link The link to image files
 *
 * @return string Error massage or empty string if uploaded file is validate
 */
function photo_link_validate(string $photo_link)
{
    $error = '';
    if (!empty($photo_link)) {
        if (!filter_var($photo_link, FILTER_VALIDATE_URL)) {
            $error = "Значение не является ссылкой";
        } else {
            $get_headers = get_headers($photo_link, 1);
            if (!strpos($get_headers[0], '200')) {
                $error = "Страница не отвечает";
            } elseif (check_img_type($get_headers['Content-Type']) !== true) {
                $error = "Ссылка на недопустимый тип файла";
            }
        }
    }
    return $error;
}

/**
 * Uploads file
 * @param array $upload_photo Array $_FILE
 *
 * @return string Path to uploaded file
 */
function upload_photo(array $upload_photo)
{
    $tmp_name = $upload_photo['tmp_name'];
    $path = $upload_photo['name'];
    $filename = 'uploads/' . uniqid() . '.' . pathinfo($path, PATHINFO_EXTENSION);
    move_uploaded_file($tmp_name, $filename);
    return $filename;
}

/**
 * Returns tag's ID
 * @param mysqli $link The MySQL connection.
 * @param string $tag The string contains tags
 * @param array $tags The array of exist tags
 *
 * @return bool|int false if ID is not exist or tag's ID
 */
function get_tag_id($link, string $tag, array $tags)
{
    foreach ($tags as $item) {
        if ($item['tag'] === $tag) {
            return $item['id'];
        }
    }
    $sql = 'INSERT INTO tags (tag) VALUE (?)';
    $stmt = db_get_prepare_stmt($link, $sql, [$tag]);
    $result = mysqli_stmt_execute($stmt);
    return $result ? mysqli_insert_id($link) : false;
}

/**
 * Inserts a new rows post_tag into the database
 * @param mysqli $link The MySQL connection.
 * @param int $post_id Current post ID
 * @param array $tags_id Tags ID array for current post
 *
 * @return bool|string true if tags added or error massage
 */
function create_post_tag_sql($link, int $post_id, array $tags_id)
{
    foreach ($tags_id as $item) {
        $request_values[] = "({$post_id}, {$item})";
    }
    $request_string = implode(', ', $request_values);

    $sql = 'INSERT INTO post_tag (post_id, tag_id) VALUES'
        . $request_string;
    $result = mysqli_query($link, $sql);

    return $result ? true : ('Не удалось добавить новый тег' . mysqli_error($link));
}

/**
 * Prepares validation rules for the new post
 * @param string $post_type The class-name of post type
 * @param string $tags The string contains tags
 *
 * @return array The array of validation rules
 */
function validate_post_rules($post_type, $tags)
{
    $rules = [];
    $rules['title'] = function ($value) {
        return check_text($value);
    };
    if (!empty($tags)) {
        $rules['tags'] = function ($value) {
            return tags_validate($value);
        };
    }
    switch ($post_type) {
        case 'text':
            $rules['content'] = function ($value) {
                return check_text($value);
            };
            break;
        case 'link':
            $rules['link'] = function ($value) {
                return link_validate($value);
            };
            break;
        case 'video':
            $rules['video'] = function ($value) {
                return youtube_url_validation($value);
            };
            break;
        case 'quote':
            $rules['content'] = function ($value) {
                return check_text($value);
            };
            $rules['author_quote'] = function ($value) {
                return check_text($value);
            };
            break;
        case 'photo' :
            if (!empty($_FILES['upload_photo']['name'])) {
                $rules['photo'] = function ($value) {
                    return photo_validate($value);
                };
            } else {
                $rules['img'] = function ($value) {
                    return photo_link_validate($value);
                };
            }
            break;
    }
    return $rules;
}
