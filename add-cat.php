<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке - добавление проекта';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$categories = get_categories($userId, $link);

// проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //валидация
    if (empty($_POST['name'])) {
        $errors['name'] = "Это поле нужно заполнить";
    } elseif (in_array($_POST['name'], array_column($categories, 'name'))) {
        $errors['name'] = "У вас уже есть такой проект";
    }

    if (strlen($_POST['name']) > 50) {
        $errors['name'] = "Недопустимая длина";
    }

    //нет ошибок добавляем проект и выходим на главную
    if (empty($errors)) {

        $nameCategories = mysqli_real_escape_string($link, $_POST['name']);

        $sql = "INSERT INTO categories (name, user_id)
                            VALUES ('$nameCategories', '$userId')";

        $res = mysqli_query($link, $sql);

        if (!$res) {
            echo mysqli_error($link);
            exit();
        }

        header("Location: index.php");

    } else {
        $pageContent = include_template('form-category.php', [
            'errors' => $errors,
        ]);
    }

} else {
    $pageContent = include_template('form-category.php', []);
}
print include_template('layout.php', [
    'pageContent' => $pageContent,
    'categories' => $categories,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);

