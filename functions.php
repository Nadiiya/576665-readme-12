<?php

/**
 * Converts special characters to HTML entities
 * @param  string  $str
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
 * @param  string  $text
 * @param  int  $excerpt_length  Maximum allowed length
 *
 * @return string The original string if its length is less than
 * maximum allowed length or excerpt
 */
function cut_text(string $text, int $excerpt_length = 80)
{
    $text_length = mb_strlen($text);
    if ($text_length > $excerpt_length) {
        $text = mb_substr($text, 0, $excerpt_length);
        $text = mb_substr($text, 0, mb_strrpos($text, ' ')).'...';
    }

    return $text;
}

/**
 * Returns a single number of months, days, weeks, hours, minutes
 * between the current date and the post publication date
 * @param  string  $post_date  Date in 'Y-m-d H:i:s' format
 *
 * @return string The date in relative terms or string 'меньше минуты назад'
 * if post was created less then 60 seconds ago
 */
function get_relative_date(string $post_date)
{
    $publish_date = date_create($post_date);
    $cur_date = date_create('now');
    $diff = date_diff($cur_date, $publish_date);
    if ($diff->m >= 1) {
        $diff_months = $diff->m;

        return "$diff_months ".get_noun_plural_form($diff_months, 'месяц', 'месяца', 'месяцев');
    }
    if ($diff->d >= 7) {
        $diff_weeks = floor(($diff->d) / 7);

        return "$diff_weeks ".get_noun_plural_form($diff_weeks, 'неделя', 'недели', 'недель');
    }
    if ($diff->d < 7 && $diff->d >= 1) {
        $diff_days = $diff->d;

        return "$diff_days ".get_noun_plural_form($diff_days, 'день', 'дня', 'дней');
    }
    if ($diff->h >= 1) {
        $diff_hours = $diff->h;

        return "$diff_hours ".get_noun_plural_form($diff_hours, 'час', 'часа', 'часов');
    }
    if ($diff->i >= 1) {
        $diff_minutes = $diff->i;

        return "$diff_minutes ".get_noun_plural_form($diff_minutes, 'минута', 'минуты', 'минут');
    }

    return 'меньше минуты';
}

/**
 * Uploads file
 * @param  array  $upload_photo  Array $_FILE
 *
 * @return string Path to uploaded file
 */
function upload_photo(array $upload_photo)
{
    $tmp_name = $upload_photo['tmp_name'];
    $path = $upload_photo['name'];
    $filename = 'uploads/'.uniqid().'.'.pathinfo($path, PATHINFO_EXTENSION);
    move_uploaded_file($tmp_name, $filename);

    return $filename;
}

/**
 * Adds query parameters to current query string
 * @param  array  $current_query  Current query string
 * @param  array  $query_data  The array of parameters which must be adding
 *
 * @return string The query string
 */
function get_query_string(array $current_query, array $query_data)
{
    return http_build_query(array_merge($current_query, $query_data));
}

/**
 * Checks if the user has access to current page or not
 * Redirects to the login page, if user has no access
 */
function check_page_access()
{
    if (!isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit();
    }
}

