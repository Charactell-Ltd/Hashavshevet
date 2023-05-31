<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:  POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/user.php';
include_once '../objects/token.php';

$requestmethod = trim($_SERVER["REQUEST_METHOD"]);
if ($requestmethod == "OPTIONS")
{
    return;
}


$data = json_decode(file_get_contents("php://input"));
if ($data==null || empty($data->adminPass) )
{
    sendResponse(400, 1, 0);
    return;
}


$user = new User();
$user->email = $data->email;
$user->accountId = $data->accountId;
$user->fullName = $data->fullName;
$user->create();

$user->userId = $user->newKeyValue;

$token = new Token();
$token->generateSiteToken($user);

$user->token = $token->token;
$user->tokenExpiration = $token->expirationtime;
$user->update();

        
sendResponse(200, 0, 0 ); 
    
?>

