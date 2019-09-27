<?php

require_once('config/init.php');

$pageTitle = 'Дела в порядке';

if (!isset($_SESSION['user'])) {
    $pageContent = include_template('guest.php', []);

    print include_template('layout.php', [
        'pageContent' => $pageContent,
        'pageTitle' => $pageTitle,
        'guest' => 1
    ]);
}

// переменная для показа/скрытия выполненных задач
$show_complete_tasks = $_GET['show_completed'] ?? $_SESSION['show_completed'] ?? 0;
$_SESSION['show_completed'] = $show_complete_tasks;

$categories = get_categories($userId, $link);

$sql_tasks = "SELECT t.id, t.name, done_date AS doneDate, status AS done, c.name AS category, user_file AS userFile,  t.categories_id
    FROM tasks t JOIN categories c ON c.id = t.categories_id WHERE t.user_id = '$userId'";

//проверка на наличие GET запросов
if (isset($_GET['check']) && isset($_GET["task_id"])) {
    $id = intval($_GET["task_id"]);
    $check = intval($_GET["check"]);
    $sql_status = "UPDATE tasks SET status = '$check' WHERE id = '$id'";
    $result = mysqli_query($link, $sql_status);
    $cat = $_GET['id'] ?? '';
    header("Location: /index.php?id=$cat");
}

if (isset($_GET['choice'])) {
    switch ($_GET['choice']) {
        case 'today' :
            $sql_tasks .= "AND done_date = CURDATE()";
            break;
        case 'tomorrow' :
            $sql_tasks .= "AND done_date = DATE_ADD(CURDATE() , INTERVAL 1 DAY)";
            break;
        case 'yesterday' :
            $sql_tasks .= "AND done_date < CURDATE() AND status = 0";
            break;
    }
}

if (isset($_GET['id'])) {
    $catId = intval($_GET['id']);
    $sql_tasks .= "AND c.id = '$catId'";
}

if (isset($_GET['ft_search'])) {
    $search = mysqli_real_escape_string($link, trim($_GET['ft_search']));
    $sql_tasks .= "AND MATCH(t.name) AGAINST('$search')";
}

$res = mysqli_query($link, $sql_tasks);

if (!$res) {
    echo mysqli_error($link);
    exit();
}

$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($categories)) {
    $new_message = 'Для начала работы добавьте проект';
} else {
    if (isset($_GET['ft_search']) && empty($tasks)) {
        http_response_code(404);
        $message = 'По вашему запросу ничего не найдено';
    }

    if (isset($_GET['id']) && empty($tasks)) {
        $message = 'Задач нет';
    }
}

if (isset($message) || isset($new_message)) {
    $pageContent = include_template('404.php', [
        'message' => $message ?? null,
        'new_message' => $new_message ?? null
    ]);
} else {
    $pageContent = include_template('main.php', [
        'categories' => $categories,
        'tasks' => $tasks,
        'show_complete_tasks' => $show_complete_tasks,
    ]);
}

print include_template('layout.php', [
    'pageContent' => $pageContent,
    'categories' => $categories,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);