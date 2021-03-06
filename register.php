<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке - регистрация';

if (isset($_SESSION['user'])) {
    header('Location: /index.php');
}

$sql = "SELECT email FROM users";
$result = mysqli_query($link, $sql);

if (!$result) {
    echo mysqli_error($link);
    exit();
}

$emails = mysqli_fetch_all($result, MYSQLI_NUM);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['email'])) {
        $errors['email'] = "Это поле нужно заполнить";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'E-mail введён некорректно';
    } elseif (in_array(array($_POST['email']), $emails, true)) {
        $errors['email'] = 'E-mail уже зарегистрирован';
    }

    if (strlen($_POST['email']) > 50) {
        $errors['email'] = "Недопустимая длина";
    }

    if (empty($_POST['password'])) {
        $errors['password'] = "Это поле нужно заполнить";
    }

    if (strlen($_POST['password']) > 50) {
        $errors['password'] = "Недопустимая длина";
    }

    if (empty($_POST['name'])) {
        $errors['name'] = "Это поле нужно заполнить";
    }

    if (strlen($_POST['name']) > 50) {
        $errors['name'] = "Недопустимая длина";
    }

    if (empty($errors)) {
        $sql = 'INSERT INTO users (email, name, password) VALUES (?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql,
            [$_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            echo mysqli_error($link);
            exit();
        } else {
            header("Location: index.php");
        }


    } else {
        $pageContent = include_template('form-register.php', [
            'errors' => $errors
        ]);
    }

} else {
    $pageContent = include_template('form-register.php', []);
}

print include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => $pageTitle,
    'userName' => $userName,
    'register' => 1
]);