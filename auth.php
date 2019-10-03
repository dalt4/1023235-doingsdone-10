<?php
require_once('config/init.php');

$pageTitle = 'Дела в порядке - вход на сайт';

if (isset($_SESSION['user'])) {
    header('Location: /index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $sql = "SELECT email FROM users";
    $result = mysqli_query($link, $sql);

    if (!$result) {
        echo mysqli_error($link);
        exit();
    }

    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (isset($_POST['email']) && empty($_POST['email'])) {
        $errors['email'] = "Это поле нужно заполнить";
        $errors['password'] = "Вы ввели неверный email/пароль";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'E-mail введён некорректно';
        $errors['password'] = "Вы ввели неверный email/пароль";
    } elseif (!in_array($_POST['email'], array_column($users, 'email'))) {
        $errors['email'] = 'Вы ввели неверный email/пароль';
        $errors['password'] = "Вы ввели неверный email/пароль";
    } else {

        $email = mysqli_real_escape_string($link, $_POST['email']);

        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

        if (!$res) {
            echo mysqli_error($link);
            exit();
        }

        $user = mysqli_fetch_array($res, MYSQLI_ASSOC);
    }

    if (isset($_POST['password']) && empty($_POST['password'])) {
        $errors['password'] = "Это поле нужно заполнить";
    }

    if (isset($user) && !password_verify($_POST['password'], $user['password'])) {
        $errors['password'] = "Вы ввели неверный email/пароль";
        $errors['email'] = 'Вы ввели неверный email/пароль';
    }


    if (empty($errors)) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
    } else {
        $pageContent = include_template('form-auth.php', [
            'errors' => $errors,
        ]);
    }

} else {
    $pageContent = include_template('form-auth.php', []);
}

print include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => $pageTitle,
    'userName' => $userName,
    'auth' => 1
]);