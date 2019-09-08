<?php

require_once('config/db.php');
require_once('helpers.php');
require_once ('functions.php');

$link = mysqli_connect($db['host'], $db['login'],  $db['password'],  $db['dataBase']);
mysqli_set_charset($link, 'utf8');


$userName = 'Василий';
$userId = 1;
