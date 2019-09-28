<?php

/**
 * Получает id пользователя и ресурс соединения, возвращает массив с проектами пользователя
 * @param string $userId строка с id пользователя
 * @param object(mysqli) $link ресурс соединения
 * @return array ассоциативный массив с проектами выбранного пользователя
 */
function get_categories($userId, $link)
{
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