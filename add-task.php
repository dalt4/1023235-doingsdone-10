<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке - добавление задачи';

if (!isset($_SESSION['user'])) {
    header('Location: /index.php');
}

$categories = get_categories($userId, $link);

// проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // валидация:
    if (empty($_POST['name'])) {
        $errors['name'] = "Это поле нужно заполнить";
    }

    if (!empty($_POST['date']) && !is_date_valid($_POST['date'])) {
        $errors['date'] = "Неверный формат даты";

    } elseif (!empty($_POST['date']) && strtotime($_POST['date']) < strtotime('today')) {
        $errors['date'] = "Задним числом не получится)";
    }

    if (empty($_POST['category'])) {
        $errors['category'] = "Это поле нужно заполнить";
    } elseif (!in_array($_POST['category'], array_column($categories, 'id'))) {
        $errors['category'] = "Несуществующий проект";
    }


    if (empty($errors)) {
// если не было ошибок обрабатываем файл
        if (!empty($_FILES['file']['name'])) {
            $fileName = uniqid() . $_FILES['file']['name'];
            $filePath = __DIR__ . '/uploads/';
            $fileUrl = '/uploads/' . $fileName;

            move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName);
        }
// получаем все данные
        $newTask = [
            'name' => $_POST['name'],
            'categoriesId' => $_POST['category'],
            'doneDate' => !empty($_POST['date']) ? $_POST['date'] : null,
            'fileUrl' => !empty($_FILES['file']['name']) ? $fileUrl : null
        ];

        $sql = "INSERT INTO tasks (name, user_file, done_date, user_id, categories_id)
                VALUES (?, ?, ?, '$userId', ?)";

        $stmt = db_get_prepare_stmt($link, $sql,
            [$newTask['name'], $newTask['fileUrl'], $newTask['doneDate'], $newTask['categoriesId']]);

        $res = mysqli_stmt_execute($stmt);

        if (!$res) {
            echo mysqli_error($link);
            exit();
        } else {
            header("Location: index.php");
        }
    }
}

$pageContent = include_template('form-task.php', [
    'errors' => $errors,
    'categories' => $categories
]);

print include_template('layout.php', [
    'pageContent' => $pageContent,
    'categories' => $categories,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);