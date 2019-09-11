<?php

require_once('config/init.php');

$pageTitle = 'Регистрация';

if (!$link) {
    $error = mysqli_connect_error();
    print include_template('error.php', ['error' => $error]);
}
else {
    $sql = "SELECT email FROM users";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $emails = mysqli_fetch_all($result, MYSQLI_NUM);
    }
    else {
        $error = "Ошибка запроса: " . mysqli_error($link);
        print include_template('error.php', ['error' => $error]);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $errors =[];

        if (empty($_POST['email'])) {
            $errors['email'] = "Это поле нужно заполнить";
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        } elseif (in_array(array($_POST['email']), $emails, true)) {
            $errors['email'] = 'E-mail уже зарегистрирован';
        }

        if (empty($_POST['password'])) {
            $errors['password'] = "Это поле нужно заполнить";
        }

        if (empty($_POST['name'])) {
            $errors['name'] = "Это поле нужно заполнить";
        }

        if (empty($errors)) {
            $sql = 'INSERT INTO users (email, name, password) VALUES (?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql, [$_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                header("Location: index.php");
                exit();
            }
        }
    }
}

print include_template('form-register.php', [
    'pageTitle' => $pageTitle,
    'errors' => $errors
]);
