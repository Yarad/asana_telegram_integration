<?php
include_once "vendor/autoload.php";
include_once __DIR__ . '/php_classes/Constants.php';
include_once __DIR__ . '/DAO/DAOUser.php';
include_once __DIR__ . '/DAO/DAOTask.php';

$ret = DAOTask::getInstance()->deleteTaskByAsanaID('1157682065001988');

$client = Asana\Client::accessToken(Constants::get('ASANA_API'), ['headers' => ['asana-disable' => 'string_ids']]);
try {
    $task = $client->tasks->findById('1157331118523004');
} catch (Exception $exception) {
    return;
}

if (!$task->assignee) {
    return;
}

$user = DAOUser::getInstance()->getUserByAsanaID($task->assignee->id);
if (!$user) {
    return;
}

$taskObj = new stdClass();
$taskObj->ID_user = $user->ID;
$taskObj->ID_in_asana = $task->gid;
$taskObj->name = $task->name;
$taskObj->description = $task->notes;

if ($task->due_at) {
    //TIMEZONE - 0
    $taskObj->time_to_notify = new DateTime($task->due_at);
    $taskObj->time_to_notify->add(new DateInterval('PT3H'));
} else {
    $taskObj->time_to_notify = new DateTime($task->due_on);
    $taskObj->time_to_notify->setTime(12, 0);
}

$ret = DAOTask::getInstance()->setTask($taskObj);