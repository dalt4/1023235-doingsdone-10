<?php

require_once('helpers.php');
require_once ('init.php');
require_once ('functions.php');

$pageTitle = 'Дела в порядке';
$userName = 'Василий';
$user_id = 1;


if (!$link) {
    $error = mysqli_connect_error();
    $pageContent = include_template('error.php', ['error' => $error]);
}
else {
    $sql = "SELECT id, name FROM categories WHERE user_id = $user_id";
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        $error = "Ошибка запроса: " . mysqli_error($link);
        $pageContent = include_template('error.php', ['error' => $error]);
    }


    $sql = "SELECT  t.name, done_date AS doneDate, status AS done, c.name AS category FROM tasks t
            JOIN categories c ON c.id = t.categories_id
            WHERE t.user_id = $user_id";


    if ($res = mysqli_query($link, $sql)) {
        $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    else {
        $error = "Ошибка запроса: " . mysqli_error($link);
        $pageContent = include_template('error.php', ['error' => $error]);
    };

    if ($result && $res) {
        $pageContent = include_template('main.php', [
            'categories' => $categories,
            'tasks' => $tasks,
            'show_complete_tasks' => $show_complete_tasks,
            'taskCounter' => $taskCounter
        ]);
    }
};
print include_template('layout.php', [
    'pageContent' => $pageContent,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);
