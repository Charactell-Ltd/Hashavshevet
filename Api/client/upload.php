<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:  POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../objects/client.php';
include_once '../objects/token.php';

$requestmethod = trim($_SERVER["REQUEST_METHOD"]);
if ($requestmethod == "OPTIONS")
{
    return;
}


$database = new Database();
$db = $database->getConnection();


$data = json_decode(file_get_contents("php://input"));
if ($data==null || empty($data->uniqueid) ||  empty($data->updatetime) ||  empty($data->clientdata))
{
    sendResponse(400, 1, 0);
    return;
}

// convert jason object to string 
$clientdata = json_encode($data->clientdata);



$client = new Client();
$client->setKeyColumn("uniqueid");
$client->setKeyValue($data->uniqueid);
$clientexists = $client->read_record();
if ($clientexists)
{
    $client->clientdata = $clientdata;
    $client->lastupdate = $data->updatetime;
    $client->update();

    // sendResponse(200, 0, 0);
}
else
{
    $client->uniqueid = $data->uniqueid;
    $client->clientdata = $clientdata;
    $client->lastupdate = $data->updatetime;
    $client->create();
  
} 


$tableobj_arr = array(
      
    "message" => "Ok",
);
print_r(json_encode($tableobj_arr));

//  $paramsArray = array(
//    "solutionid" => $solution->newKeyValue,
//    "solutionkey"  => $solution->solutionkey  
  
// );
// sendResponse(200, 0, 0);//, $paramsArray);

?>