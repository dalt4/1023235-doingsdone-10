<?php
//Вычиление количества задач в каждой категории.
$taskCounter = function (array $array, $value) {
    $count = 0;
    foreach ($array as $item) {
        if ($item['category'] === $value) {
            $count += 1;
        }
    }
    return $count;
};

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);