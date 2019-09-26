<?php

require_once('config/init.php');
require_once('vendor/autoload.php');

$transport = (new Swift_SmtpTransport("smtp.mailtrap.io", 2525))
    ->setUsername("9c8582ca746e54")
    ->setPassword("3f1b3363bbc3d0")
;

$mailer = new Swift_Mailer($transport);

$sql_tasks = "SELECT  t.name AS task_name, done_date, u.name AS user_name, email
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

$user_name = '';
$task_name = '';
$done_date = '';
$user_email = '';
$text = '';

$emails = array_unique(array_column($tasks, 'email'));

foreach ($emails as $email) {
    $user_email = $email;
    $task_text = '';
    $count_tasks = 1;
    foreach ($tasks as $task) {
        if ($task['email'] ===  $user_email) {
            $user_name = $task['user_name'];
            $done_date = $task['done_date'];
            $correct_textform = get_noun_plural_form($count_tasks, 'запланирована задача</h4>', 'запланированы задачи: </h4>' , 'запланированы задачи: </h4>');
            $task_text .= '&emsp;' . $task['task_name'] . '  на ' .  $task['done_date'] . ' <br>';
            $text = '<h3> Уважаемый (ая),  ' . $task['user_name'] . '.</h3><h4> У вас ' . $correct_textform . $task_text;
            $count_tasks++;
        }
    }

    $message = (new Swift_Message("Уведомление от сервиса «Дела в порядке»"))
        ->setFrom(['keks@phpdemo.ru' => '«Дела в порядке»'])
        ->setTo([$user_email => $user_name])
        ->setBody($text, 'text/html')
    ;

    $result = $mailer->send($message);

    if ($result) {
        print("Уведомление отправлено &#x1F44D;<br>");
    } else {
        print("Не удалось отправить уведомление &#128078;<br>");
    }
}