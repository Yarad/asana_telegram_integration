<?php

include_once "vendor/autoload.php";
include_once __DIR__ . '/php_classes/Constants.php';

$client = Asana\Client::accessToken(Constants::get('ASANA_API'), ['headers' => ['asana-disable' => 'string_ids']]);
$webhook = $client->webhooks->getById(1157629344884596);
var_dump($webhook);