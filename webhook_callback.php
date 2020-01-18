<?php
include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/php_classes/Constants.php';
include_once __DIR__ . '/DAO/DAOUser.php';
include_once __DIR__ . '/DAO/DAOTask.php';

$headers = apache_request_headers();

// if headers contain X-Hook-Secret, we need to answer with 200
if (array_key_exists('X-Hook-Secret', $headers)) {
    $xHookSecret = $headers['X-Hook-Secret'];

    // header needs to be resent
    header('X-Hook-Secret: ' . $xHookSecret);
    header("HTTP/1.1 200 OK");
    exit;
}

$client = Asana\Client::accessToken(Constants::get('ASANA_API'), ['headers' => ['asana-disable' => 'string_ids']]);
$entries = json_decode(file_get_contents('php://input'), true);
$events = $entries['events'];

foreach ($events as $event) {
    if ($event['resource']['resource_type'] !== 'task') {
        continue;
    }

    if (!$event['resource']['gid']) {
        continue;
    }

    try {
        $task = $client->tasks->findById($event['resource']['gid']);
    } catch (Exception $exception) {
        if($exception->status == 404){
            //TODO: delete task
        }
        continue;
    }

    //отправляем только владельцу
    if (!$task->assignee) {
        continue;
    }

    $user = DAOUser::getInstance()->getUserByAsanaID($task->assignee->id);
    if (!$user) {
        Constants::Log(__LINE__ . $task->assignee->id);
        continue;
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
    if (!$ret) {
        Constants::Log($taskObj);
    }
}