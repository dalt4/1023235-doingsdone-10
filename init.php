<?php

require_once('config/db.php');

$link = mysqli_connect($db['host'], $db['login'],  $db['password'],  $db['dataBase']);
mysqli_set_charset($link, 'utf8');

$error = '';