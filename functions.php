<?php

// вывод массива категорий с подсчетом задач
function get_categories($userId, $link) {
    $sql = "SELECT c.id, c.name, COUNT(t.id) AS tasksCount FROM categories c
            LEFT  JOIN tasks t ON c.id = t.categories_id 
            WHERE c.user_id = '$userId' GROUP BY c.id";

    $result = mysqli_query($link, $sql);

    if (!$result) {
        echo mysqli_error($link);
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}