<?php
include_once "vendor/autoload.php";
include_once __DIR__ . '/php_classes/Constants.php';

$client = Asana\Client::accessToken(Constants::get('ASANA_API'), ['headers' => ['asana-disable' => 'string_ids']]);
$webhook = $client->webhooks->create(array(
    'resource' => 1157457146869705,
    'target' => 'https://blagoosip.ru/_egg/asana_telegram_integration/webhook_callback.php'
));