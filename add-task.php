<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке - добавление задачи';

if (isset($_SESSION['user'])) {

    if (!$link) {
        $error = mysqli_connect_error();
        $pageContent = include_template('error.php', ['error' => $error]);
    } else {
        $sql = "SELECT c.id, c.name, COUNT(t.id) AS tasksCount FROM categories c
            LEFT  JOIN tasks t ON c.id = t.categories_id 
            WHERE c.user_id = '$userId' GROUP BY c.id";
        $result = mysqli_query($link, $sql);

        if ($result) {
            $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            $error = "Ошибка запроса: " . mysqli_error($link);
            $pageContent = include_template('error.php', ['error' => $error]);
        }
// проверка отправки формы
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// валидация:
            $errors = [];
            if (empty($_POST['name'])) {
                $errors['name'] = "Это поле нужно заполнить";
            }

            if (!empty($_POST['date']) && !is_date_valid($_POST['date'])) {
                $errors['date'] = "Неверный формат даты";
            } elseif (!empty($_POST['date']) && strtotime($_POST['date']) < time()) {
                $errors['date'] = "Задним числом не получится)";
            }

            if (empty($_POST['category'])) {
                $errors['category'] = "Это поле нужно заполнить";
            } else {
                $returnCategory = ''; // переменная для возвращения названия категории в форму
                $coincidence = 0;
                foreach ($categories as $category) {
                    if ($category['id'] === $_POST['category']) {
                        $returnCategory = $category['name'];
                        $coincidence++;
                    }
                }
                if ($coincidence === 0) {
                    $errors['category'] = "Несуществующий проект";
                }
            }

            if (empty($errors)) {
// если не было ошибок обрабатываем файл
                if (isset($_FILES['file'])) {
                    $fileName = uniqid() . $_FILES['file']['name'];
                    $filePath = __DIR__ . '/uploads/';
                    $fileUrl = '/uploads/' . $fileName;

                    move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName);
                }
// получаем все данные
                $newTask = [
                    'name' => $_POST['name'],
                    'categoriesId' => $_POST['category'],
                    'doneDate' => empty($_POST['date']) ? null : $_POST['date'],
                    'fileUrl' => $fileUrl === '/uploads/' ? null : $fileUrl
                ];

                $sql = "INSERT INTO tasks (status, name, user_file, done_date, user_id, categories_id)
                VALUES (0, ?, ?, ?, '$userId', ?)";
                $stmt = db_get_prepare_stmt($link, $sql,
                    [$newTask['name'], $newTask['fileUrl'], $newTask['doneDate'], $newTask['categoriesId']]);
                $res = mysqli_stmt_execute($stmt);
                if ($res) {
                    header("Location: index.php");
                }
            }
        };

        if ($result) {
            $pageContent = include_template('form-task.php', [
                'categories' => $categories,
                'errors' => $errors,
                'returnCategory' => $returnCategory
            ]);
        }
    }

    print include_template('layout.php', [
        'pageContent' => $pageContent,
        'userName' => $userName,
        'pageTitle' => $pageTitle
    ]);
}else {
    header('Location: /index.php');
}