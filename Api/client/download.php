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
if ($data==null || empty($data->uniqueid) )
{
    $tableobj_arr = array(
      
        "message" => "Missing Parameter",
    );
    print_r(json_encode($tableobj_arr));
  
    return;
}

$client = new Client();
$client->setKeyColumn("uniqueid");



$client->setKeyValue($data->uniqueid);
$stmt = $client->read_single();

if ($stmt->rowCount() > 0) {

    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    $messageArray = array(
     
        "message" => "ok",
        "clientdata" => json_decode( $row["clientdata"]));


    print_r( json_encode($messageArray ));

//    print_r($row["clientdata"]);
} else {
    // send http record not found
    http_response_code(404);
    $tableobj_arr = array(
      
        "message" => "Client not exists!",
    );
    print_r(json_encode($tableobj_arr));
}


