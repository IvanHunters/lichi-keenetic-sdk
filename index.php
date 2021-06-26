<?php

include "vendor/autoload.php";

use Lichi\Keenetic\ApiProvider;
use GuzzleHttp\Client;
use Lichi\Keenetic\Sdk\About;
use Lichi\Keenetic\Sdk\LAN\DevicesList;
use Lichi\Keenetic\Sdk\Traffic;

$client = new Client([
    'base_uri' => "http://192.168.0.1",
    'verify' => false,
    'timeout'  => 30.0,
]);

$apiProvider = new ApiProvider($client, getenv('API_LOGIN'), getenv('API_PASS'));

$about = new About($apiProvider);
$aboutData = $about->get();

$deviceList = new DevicesList($apiProvider);
//$active = $deviceList->getActive(true);
//$unregistered = $deviceList->getRegistered(false);
//
//$deviceList->registration("Клиент", $unregistered[0]);
//$deviceList->unRegistration("cc:2d:21:6d:d7:99");
//
//$deviceList->disableInternet("cc:2d:21:6d:d7:99");
//$deviceList->enableInternet("cc:2d:21:6d:d7:99");
//
//$traffic = new Traffic($apiProvider);
//$trafficData = $traffic->limit(5);