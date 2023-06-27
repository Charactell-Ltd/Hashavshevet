<?php
include_once '../objects/boi.php';

$boi = new boi();
$boi->getExchangeRates();
// $data = json_decode(file_get_contents("php://input"));
// if ($data==null ||empty($data->companyId) || empty($data->stationId) || empty($data->netPassportId) || empty($data->pluginData) ) 
// {
//     sendResponse(400, 1, 0);
//     return;
// }

// $pluginData = $data->pluginData;



?>