<?php

require_once('config/init.php');
require_once('vendor/autoload.php');

//создаем mailer
$transport = (new Swift_SmtpTransport("smtp.mailtrap.io", 2525))
    ->setUsername("9c8582ca746e54")
    ->setPassword("3f1b3363bbc3d0");

$mailer = new Swift_Mailer($transport);

//запрашиваем все задачи на сегодня
$sql_tasks = "SELECT  t.name AS task_name, DATE_FORMAT(done_date, '%d.%m.%Y') AS done_date, u.name AS user_name, email
    FROM tasks t LEFT JOIN users u ON u.id = t.user_id WHERE done_date = CURDATE() AND status = 0";

$res = mysqli_query($link, $sql_tasks);

if (!$res) {
    echo mysqli_error($link);
    exit();
}

$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($tasks)) {
    exit('Нет срочных задач');
}

// получаем массив уникальных email
$emails = array_unique(array_column($tasks, 'email'));

//обьявляем все переменные для отправки
$user_name = '';
$task_name = '';
$done_date = '';
$user_email = '';
$text = '';

//в цикле находим все необходимые данные для отправки
foreach ($emails as $email) {
    $user_email = $email;
    $task_text = '';
    $count_tasks = 1;
    foreach ($tasks as $task) {
        if ($task['email'] === $user_email) {
            $user_name = $task['user_name'];
            $done_date = $task['done_date'];
//приводим часть сообщения(запланирована задача) к правильной форме в зависимости от количества задач
            $correct_textform = get_noun_plural_form($count_tasks, 'запланирована задача</h4>',
                'запланированы задачи: </h4>', 'запланированы задачи: </h4>');
            $task_text .= '&emsp;&laquo;' . $task['task_name'] . '&raquo;  на ' . $task['done_date'] . ' <br>';
            $text = '<h3> Уважаемый (ая),  ' . $task['user_name'] . '.</h3><h4> У вас ' . $correct_textform . $task_text;
            $count_tasks++;
        }
    }

//формируем и отправляем сообщение для каждого пользователя
    $message = (new Swift_Message("Уведомление от сервиса «Дела в порядке»"))
        ->setFrom(['keks@phpdemo.ru' => '«Дела в порядке»'])
        ->setTo([$user_email => $user_name])
        ->setBody($text, 'text/html');

    $result = $mailer->send($message);

    if ($result) {
        print("Уведомление отправлено &#x1F44D;<br>");
    } else {
        print("Не удалось отправить уведомление &#128078;<br>");
    }
}