<?php

session_start();

require_once('config/db.php');
require_once('helpers.php');
require_once('functions.php');

$link = mysqli_connect($db['host'], $db['login'], $db['password'], $db['dataBase']);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    $error = mysqli_connect_error();
    $pageContent = include_template('error.php', ['error' => $error]);
    print include_template('layout.php', [
        'pageContent' => $pageContent,
        'pageTitle' => 'Ошибка'
        ]);
    exit();
}

$errors = [];
$userId = $_SESSION['user']['id'] ?? null;
$userName = $_SESSION['user']['name'] ?? null;