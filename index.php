<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке';

if (isset($_SESSION['user'])) {

    if (!$link) {
        $error = mysqli_connect_error();
        $pageContent = include_template('error.php', ['error' => $error]);
    } //есть соединение с бд и сессия запрашиваем категории с подсчетом задач
    else {
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
        //проверка на наличие GET запросов
        if (isset($_GET['id'])) {
            $taskId = intval($_GET['id']);

            $sql = "SELECT t.id, t.name, done_date AS doneDate, status AS done, c.name AS category, user_file AS userFile FROM tasks t
            JOIN categories c ON c.id = t.categories_id
            WHERE t.user_id = '$userId' AND c.id = '$taskId'";

        } elseif (isset($_GET['ft_search'])) {

            $search = mysqli_real_escape_string($link, trim($_GET['ft_search']));

            $sql = "SELECT t.id, t.name, done_date AS doneDate, status AS done, c.name AS category, user_file AS userFile FROM tasks t
            JOIN categories c ON c.id = t.categories_id
            WHERE t.user_id = '$userId' AND MATCH(t.name) AGAINST('$search') ";

        } // GET запросов нет выводим все задачи пользователя
        else {
            $sql = "SELECT t.id, t.name, done_date AS doneDate, status AS done, c.name AS category, user_file AS userFile FROM tasks t
            JOIN categories c ON c.id = t.categories_id
            WHERE t.user_id = '$userId'";
        }

        if ($res = mysqli_query($link, $sql)) {
            $tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);
        } else {
            $error = "Ошибка запроса: " . mysqli_error($link);
            $pageContent = include_template('error.php', ['error' => $error]);
        };

        if (is_array($tasks) && empty($tasks)) {
            http_response_code(404);
            $pageContent = include_template('404.php', ['categories' => $categories]);

        } else {
            $pageContent = include_template('main.php', [
                'categories' => $categories,
                'tasks' => $tasks,
                'show_complete_tasks' => $show_complete_tasks,
            ]);
        }

    };
//нет сессии отображаем гостевую страницу
} else {
    $pageContent = include_template('guest.php', []);
}
print include_template('layout.php', [
    'pageContent' => $pageContent,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);