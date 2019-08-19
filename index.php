<?php

require_once('helpers.php');

$pageTitle = 'Дела в порядке';
$userName = 'Василий';

// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$categories = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    [
        'name' => 'Собеседование в IT компании',
        'doneDate' => '01.12.2019',
        'category' => $categories[2],
        'done' => false
    ],
    [
        'name' => 'Выполнить тестовое задание',
        'doneDate' => '25.12.2019',
        'category' => $categories[2],
        'done' => false
    ],
    [
        'name' => 'Сделать задание первого раздела',
        'doneDate' => '21.12.2019',
        'category' => $categories[1],
        'done' => true
    ],
    [
        'name' => 'Встреча с другом',
        'doneDate' => '22.12.2019',
        'category' => $categories[0],
        'done' => false
    ],
    [
        'name' => 'Купить корм для кота',
        'doneDate' => null,
        'category' => $categories[3],
        'done' => false
    ],
    [
        'name' => 'Заказать пиццу',
        'doneDate' => null,
        'category' => $categories[3],
        'done' => false
    ],
];

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

$pageContent = include_template('main.php', [
    'categories' => $categories,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks,
    'taskCounter' => $taskCounter
]);

print include_template('layout.php', [
    'pageContent' => $pageContent,
    'userName' => $userName,
    'pageTitle' => $pageTitle
]);

