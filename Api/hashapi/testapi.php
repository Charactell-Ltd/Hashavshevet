<?php

 include_once '../../vendor/autoload.php';

use GuzzleHttp\Client;


$moduleData = [ [
       "ItemKey" => "A2000"
    ] 
];

$moduleDataJson = json_encode($moduleData);


$signature = md5(json_encode($moduleData) . "F9682B75DF384984B3819E22358134AF");

$body = array(
    "station" => "9afa864d-36cb-462c-9e69-b8a6d258c0f4",
    "plugin" => "itemin",
    "company" => "demo",
    "message" => array(
        "netPassportID" => "250081",
        "pluginData" => $moduleData
    ),
    "signature" => $signature
);

$client = new Client();
$response = $client->post('https://ws.wizground.com/api', [
    'headers' => [
        'Content-Type' => 'application/json',
    ],
    'json' => $body
]);

$data = json_decode($response->getBody(), true);
echo json_encode($data);
?>