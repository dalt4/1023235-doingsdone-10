<?php

session_start();

require_once('config/db.php');
require_once('helpers.php');
require_once ('functions.php');

$link = mysqli_connect($db['host'], $db['login'],  $db['password'],  $db['dataBase']);
mysqli_set_charset($link, 'utf8');

$errors =[];
$userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
$userName = isset($_SESSION['user']) ? $_SESSION['user']['name'] : null;