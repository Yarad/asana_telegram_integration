<?php

use Longman\TelegramBot\Request;

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/php_classes/Constants.php';
include_once __DIR__ . '/DAO/DAOTask.php';

//$client = Asana\Client::accessToken('', ['headers' => ['asana-disable' => 'string_ids']]);

$tasksPDO = DAOTask::getInstance()->getTasksToNotifyAbout();
if ($tasksPDO) {
    $telegram = new Longman\TelegramBot\Telegram(Constants::get('TELEGRAM_BOT_API'), 'ASANA');

    while ($task = $tasksPDO->fetchObject()) {
        try {
            $response = Request::sendMessage(
                [
                    'chat_id' => $task->ID_telegram,
                    'text' => 'NOTIFY',
                    'timeout' => null,
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
