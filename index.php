<?php

include "vendor/autoload.php";

use Lichi\Keenetic\ApiProvider;
use GuzzleHttp\Client;
use Lichi\Keenetic\Sdk\LAN\DevicesList;

$client = new Client([
    'base_uri' => "http://192.168.0.1",
    'verify' => false,
    'timeout'  => 30.0,
]);

$apiProvider = new ApiProvider($client, getenv('API_LOGIN'), getenv('API_PASS'));
$deviceList = new DevicesList($apiProvider);

$active = $deviceList->getActive(true);
$unregistered = $deviceList->getRegistered(false);

$deviceList->registration("Клиент", $unregistered[0]);
$deviceList->unRegistration("cc:2d:21:6d:d7:99");

$deviceList->disableInternet("cc:2d:21:6d:d7:99");
$deviceList->enableInternet("cc:2d:21:6d:d7:99");