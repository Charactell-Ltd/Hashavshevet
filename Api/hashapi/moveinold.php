<?php

include_once '../../vendor/autoload.php';

use GuzzleHttp\Client;

$moduleData = [
    [
        "TransType" => "9",
        "reference" => "85",
        // "DueDate" => "17/07/2023",
        "AccountKeyDeb1" => "9009",
        "SufDeb1" => "5.00",
        "AccountKeyCred1" => "3576",
        "SufCred1" => "5.00",
        "Details" => "API"
    ]
];


$moduleDataJson = json_encode($moduleData);

$signature = md5(json_encode($moduleData) . "F9682B75DF384984B3819E22358134AF");

$body = array(
    "station" => "9afa864d-36cb-462c-9e69-b8a6d258c0f4",
    "plugin" => "movein",
    "company" => "ct2",
    "message" => array(
        "netPassportID" => "250081",
        "pluginData" => $moduleData
    ),
    "signature" => $signature
);

$client = new Client();

try
{
  $response = $client->post('https://ws.wizground.com/api', [
    'headers' => [
        'Content-Type' => 'application/json',
    ],
    'json' => $body
]);
}
catch (Exception $e)
{
    echo $e->getMessage();
    return;
}

$data = json_decode($response->getBody(), true);
echo json_encode($data);
?>