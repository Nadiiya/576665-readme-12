<?php

if (file_exists('config.php')) {
    $config = require 'config.php';
    $link = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'],
        $config['db']['database']);
    mysqli_set_charset($link, 'utf8');
} else {
    exit('The file config.php does not exist. Use the sample file named config.sample.php, create a config.php file and editing it as required');
}

if (!$link) {
    exit('error' . mysqli_connect_error());
}

/*set the default timezone*/
date_default_timezone_set('Europe/Moscow');

define("PHOTO", 'photo');
define("VIDEO", 'video');
define("TEXT", 'text');
define("QUOTE", 'quote');
define("LINK", 'link');
