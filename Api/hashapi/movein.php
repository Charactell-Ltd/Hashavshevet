<?php
include_once '../objects/hashapi.php';



$data = json_decode(file_get_contents("php://input"));
if ($data==null ||empty($data->companyId) || empty($data->stationId) || empty($data->netPassportId) || empty($data->pluginData) ) 
{
    // sendResponse(400, 1, 0);
    return;
}

// $pluginData = $data->pluginData;

$hashapi = new hashapi();
$hashapi->movein($data->stationId, $data->netPassportId, $data->companyId, $data->pluginData);


?>