<?php

use Longman\TelegramBot\Request;

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/php_classes/Constants.php';
include_once __DIR__ . '/DAO/DAOTask.php';

//$client = Asana\Client::accessToken('', ['headers' => ['asana-disable' => 'string_ids']]);

$tasks = DAOTask::getInstance()->getTasksToNotifyAbout();
if ($tasks) {
    $telegram = new Longman\TelegramBot\Telegram(Constants::get('TELEGRAM_BOT_API'), 'ASANA');
    $messageTemplate = file_get_contents('templates/messages/simple_message.twig');

    foreach ($tasks as $task) {
        try {
            //TODO: подключить Twig при более сложных обработках
            $params = [
                '{{ link }}' => 'https://app.asana.com/0/0/' . $task->ID_in_asana,
                '{{ name }}' => $task->name,
                '{{ notify_time }}' => $task->time_to_notify->format('d.m.Y h:i'),
                '{{ description }}' => $task->description
            ];

            $response = Request::sendMessage(
                [
                    'chat_id' => $task->ID_telegram,
                    'text' => str_replace(array_keys($params), array_values($params), $messageTemplate),
                    'timeout' => 4,
                    'parse_mode' => 'HTML'
                ]
            );
            if ($response->isOk()) {
                $task->sent = 1;
                DAOTask::getInstance()->setTask($task);
            }
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            continue;
        }
    }
}
