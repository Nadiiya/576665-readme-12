<?php

require_once 'vendor/autoload.php';
// Create the Transport
$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

