<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/php_classes/Constants.php';

$client = Asana\Client::accessToken('', ['headers' => ['asana-disable' => 'string_ids']]);
$workspaces = $client->workspaces->findAll();
foreach ($workspaces as $workspace) {
    $events = $client->events->getNext(['resource' => $workspace->gid]);
    foreach ($events as $value) {
        var_dump($value);
    }
}
