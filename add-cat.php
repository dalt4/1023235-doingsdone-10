<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке - добавление проекта';

if (isset($_SESSION['user'])) {

    if (!$link) {
        $error = mysqli_connect_error();
        $pageContent = include_template('error.php', ['error' => $error]);
    } else {
        $sql = "SELECT c.id, c.name, COUNT(t.id) AS tasksCount FROM categories c
            LEFT  JOIN tasks t ON c.id = t.categories_id 
            WHERE c.user_id = '$userId' GROUP BY c.id";

        $result = mysqli_query($link, $sql);

        if (!$result) {
            $error = "Ошибка запроса: " . mysqli_error($link);
            $pageContent = include_template('error.php', ['error' => $error]);
        } else {
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

            $pageContent = include_template('form-category.php', [
                'categories' => $categories
            ]);

            // проверка отправки формы
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //валидация
                if (empty($_POST['name'])) {
                    $errors['name'] = "Это поле нужно заполнить";
                } elseif (in_array($_POST['name'], array_column($categories, 'name'))) {
                    $errors['name'] = "У вас уже есть такой проект";
                }
                //нет ошибок добавляем проект и выходим на главную
                if (empty($errors)) {

                    $nameCategories = mysqli_real_escape_string($link, $_POST['name']);

                    $sql = "INSERT INTO categories (name, user_id)
                            VALUES ('$nameCategories', '$userId')";

                    if ($res = mysqli_query($link, $sql)) {
                        header("Location: index.php");
                        exit();
                    }
                //есть ошибки выводим страницу добавления проекта с показом ошибок
                } else {
                    $pageContent = include_template('form-category.php', [
                        'categories' => $categories,
                        'errors' => $errors,
                    ]);
                }
            }
        }

    }
    print include_template('layout.php', [
        'pageContent' => $pageContent,
        'userName' => $userName,
        'pageTitle' => $pageTitle
    ]);

}else {
    header('Location: index.php');
}