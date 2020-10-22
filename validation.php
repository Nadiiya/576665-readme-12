<?php

/**
 * Returns error message if string is empty
 * @param  string  $value
 *
 * @return string Error message or empty string
 */
function check_emptiness(string $value)
{
    return empty($value) ? 'Это поле должно быть заполнено' : '';
}

/**
 * Checks whether a string length is allowed
 * @param  string  $string  The string
 * @param  int  $min  The required minimum number of characters
 * @param  int  $max  The required maximum number of characters
 *
 * @return bool True if length of a string is allowed or false otherwise
 */
function check_length(string $string, int $min = 1, int $max = INF)
{
    $length = mb_strlen($string);

    return $length >= $min && $length <= $max;
}

/**
 * Checks whether a string is a link to a Youtube video
 * @param  string  $value  video url
 *
 * @return string Error message or empty string if url is correct
 */
function check_youtube_domain(string $value)
{
    $domain = parse_url($value, PHP_URL_HOST);

    return strpos($domain, 'youtube.com') === false ? 'Введите ссылку на видео из YOUTUBE' : '';
}

/**
 * Checks whether a user data is exists in database or not
 * @param  mysqli  $link  The MySQL connection
 * @param  string  $value
 * @param  string  $param
 *
 * @return bool true if a value is exists in database or false if a value is unique
 */
function is_user_exist(mysqli $link, string $value, string $param)
{
    $sql = "SELECT * FROM users WHERE {$param} = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [$value]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result) > 0;
}

/**
 * Checks whether a string is valid email
 * @param  string  $email
 *
 * @return bool true if email is correct or false if email is not valid
 */
function is_valid_email(string $email)
{
    return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? true : false;
}

/**
 * Checks whether password_repeat value and the password do match
 * @param  string  $value  The password_repeat value
 * @param  string  $password  The password value
 *
 * @return string Error message or empty string if passwords do match
 */
function check_password_repeat(string $value, string $password)
{
    return ($value !== $password) ? 'Пароли не совпадают' : '';
}

/**
 * Checks whether the email value is correct
 * @param  string  $email  The string contains email
 *
 * @return string Error message or empty string if value is correct
 */
function email_validate(string $email)
{
    if (empty($email)) {
        return "Это поле должно быть заполнено";
    }
    if (!is_valid_email($email)) {
        return "Адрес электронной почты не корректен";
    }

    return '';
}

/**
 * Checks whether the login value is correct
 * @param  string  $value  The string contains login
 *
 * @return string Error message or empty string if value is correct
 */
function login_validate(string $value)
{
    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }
    if (!check_length($value, 1, 60)) {
        return "Колличество символов превышает допустимое значение";
    }

    return '';
}

/**
 * Checks whether the password value is correct
 * @param  string  $value  The string contains password
 *
 * @return string Error message or empty string if value is correct
 */
function password_validate(string $value)
{
    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }
    if (!check_length($value, MIN_LENGTH_PASSWORD, MAX_LENGTH_PASSWORD)) {
        return "Недопустимая длина пароля";
    }

    return '';
}

/**
 * Checks whether the text value is correct
 * @param  string  $value  The string contains text
 * @return string Error message or empty string if value is correct
 */
function text_field_validate(string $value)
{
    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }
    if (!check_length($value, 1, MAX_DATABASE_VARCHAR)) {
        return "Количество символов превышает допустимое значение";
    }

    return '';
}

/**
 * Checks whether a string is a correct link
 * @param  string  $link_value  The string contains link
 *
 * @return string Error message or empty string if link is correct
 */
function link_validate(string $link_value)
{
    if (empty($link_value)) {
        return "Это поле должно быть заполнено";
    }
    if (!check_length($link_value, 1, MAX_DATABASE_VARCHAR)) {
        return "Количество символов превышает допустимое значение";
    }
    if (!filter_var($link_value, FILTER_VALIDATE_URL)) {
        return "Значение не является ссылкой";
    }

    return '';
}

/**
 * Validates a string contains tags
 * @param  string The string contains tags
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
            if (!check_length($tag, 1, MAX_LENGTH_TAG)) {
                $tag_error = "Тег привышает допустимое количество символов";
            }
            if (!empty($invalid_tags)) {
                $count_invalid_tags = count($invalid_tags);
                $tag_error = get_noun_plural_form($count_invalid_tags, 'Тег ', 'Теги ', 'Теги ').esc(implode(', ',
                        $invalid_tags)).get_noun_plural_form($count_invalid_tags, ' не корректен', ' не корректны',
                        ' не корректны');
            }
        }
    }

    return $tag_error;
}

/**
 * Validates link to a video
 * @param  string The string contains link to a video
 *
 * @return string Error message or empty string if link is correct
 */
function youtube_url_validation(string $url_value)
{
    if (!check_length($url_value, 1, MAX_DATABASE_VARCHAR)) {
        return "Недопустимое количество символов";
    }
    if (link_validate($url_value)) {
        return link_validate($url_value);
    } elseif (check_youtube_url($url_value) !== true) {
        return check_youtube_url($url_value);
    } elseif (check_youtube_domain($url_value)) {
        return check_youtube_domain($url_value);
    }

    return '';
}

/**
 * Checks image's MIME-type
 * @param  string  $file_type  File's MIME-type
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
 * @param  array Array $_FILE
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
 * @param  string The string contains link
 *The link to image files
 *
 * @return string Error massage or empty string if uploaded file is validate
 */
function photo_link_validate(string $photo_link)
{
    if (!empty($photo_link)) {
        if (!check_length($photo_link, 1, MAX_DATABASE_VARCHAR)) {
            return "Недопустимое количество символов";
        }
        if (!filter_var($photo_link, FILTER_VALIDATE_URL)) {
            return "Значение не является ссылкой";
        } else {
            $get_headers = get_headers($photo_link, 1);
            if (!strpos($get_headers[0], '200')) {
                return "Страница не отвечает";
            } elseif (check_img_type($get_headers['Content-Type']) !== true) {
                return "Ссылка на недопустимый тип файла";
            }
        }
    }

    return '';
}

/**
 * Checks whether a string is a correct comment
 * @param  string  $comment  The string contains comment
 *
 * @return string Error message or empty string if comment is correct
 */
function comment_validate(string $comment)
{
    if (check_emptiness($comment)) {
        return 'Поле должно быть заполнено';
    }
    if (!check_length($comment, MIN_COMMENT)) {
        return "Длина комментария должна быть не меньше ".MIN_COMMENT.get_noun_plural_form(MIN_COMMENT, ' символа',
                ' символов', ' символов');
    }

    return '';
}

/**
 * Prepares validation rules for the new post
 * @param  string  $post_type  The class-name of post type
 * @param  string  $tags  The string contains tags
 *
 * @return array The array of validation rules
 */
function validate_post_rules(string $post_type, string $tags)
{
    $rules = [];
    $rules['title'] = function ($value) {
        return text_field_validate($value);
    };
    if (!empty($tags)) {
        $rules['tags'] = function ($value) {
            return tags_validate($value);
        };
    }
    switch ($post_type) {
        case 'text':
            $rules['content'] = function ($value) {
                return check_emptiness($value);
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
                return check_emptiness($value);
            };
            $rules['author_quote'] = function ($value) {
                return text_field_validate($value);
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

/**
 * Checks data array by rules array and creates errors array
 * @param  array  $data_array  The data array
 * @param  array  $rules  The rules array
 *
 * @return array The errors array
 */
function check_data_by_rules(array $data_array, array $rules)
{
    $errors = [];
    foreach ($data_array as $key => $value) {
        if (isset($rules[$key])) {
            $errors[$key] = $rules[$key]($value);
        }
    }

    return array_filter($errors);
}

