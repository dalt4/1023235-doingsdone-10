<?php
require_once('config/init.php');

$pageTitle = 'Дела в порядке - вход на сайт';

if (!$link) {
    $error = mysqli_connect_error();
    print include_template('error.php', ['error' => $error]);
} else {
    $sql = "SELECT email FROM users";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = "Ошибка запроса: " . mysqli_error($link);
        print include_template('error.php', ['error' => $error]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (empty($_POST['email'])) {
            $errors['email'] = "Это поле нужно заполнить";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        } elseif (!in_array($_POST['email'], array_column($users, 'email'))) {
            $errors['email'] = 'E-mail не найден';
        } else {

            $email = $_POST['email'];

            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($link, $sql);

            if ($result) {
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                if (!password_verify($_POST['password'], $user['password'])) {
                    $errors['password'] = "Неверный пароль";
                } else {
                    $_SESSION['user'] = $user;
                    header("Location: index.php");
                    exit();
                }
            } else {
                $error = "Ошибка запроса: " . mysqli_error($link);
                print include_template('error.php', ['error' => $error]);
            }
        }
        if (empty($_POST['password'])) {
            $errors['password'] = "Это поле нужно заполнить";
        }
    };

    print include_template('form-auth.php', [
        'errors' => $errors,
        'pageTitle' => $pageTitle
    ]);
}