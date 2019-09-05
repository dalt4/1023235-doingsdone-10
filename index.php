<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке';

if (!$link) {
    $error = mysqli_connect_error();
    $pageContent = include_template('error.php', ['error' => $error]);
}
else {
    $sql = "SELECT c.id, c.name, COUNT(t.id) AS tasksCount FROM categories c
            LEFT  JOIN tasks t ON c.id = t.categories_id 
            WHERE c.user_id = $userId GROUP BY c.id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = "Ошибка запроса: " . mysqli_error($link);
        $pageContent = include_template('error.php', ['error' => $error]);
    }
    if (!isset($_GET['id'])) {

        $sql = "SELECT  t.name, done_date AS doneDate, status AS done, c.name AS category, user_file AS userFile FROM tasks t
            JOIN categories c ON c.id = t.categories_id
            WHERE t.user_id = $userId";
    }
    else {
        $id = $_GET['id'];
        $sql = "SELECT  t.name, done_date AS doneDate, status AS done, c.name AS category, user_file AS userFile FROM tasks t
            JOIN categories c ON c.id = t.categories_id
            WHERE t.user_id = $userId AND c.id = $id";
    }

    if ($res = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $error = "Ошибка запроса: " . mysqli_error($link);
        $pageContent = include_template('error.php', ['error' => $error]);
    };

    if (count($tasks) === 0) {
        http_response_code(404);
        exit;
    }

    elseif ($result && $res) {
        $pageContent = include_template('main.php', [
            'categories' => $categories,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
        ]);
    }

};
print include_template('layout.php', [
    'pageContent' => $pageContent,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);
