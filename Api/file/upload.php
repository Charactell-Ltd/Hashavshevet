<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:  POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../objects/company.php';
include_once '../objects/file.php';
include_once '../objects/token.php';

$requestmethod = trim($_SERVER["REQUEST_METHOD"]);
if ($requestmethod == "OPTIONS")
{
    return;
}

// $database = new Database();
// $db = $database->getConnection();


$data = json_decode(file_get_contents("php://input"));
if ($data == null || empty($data->companyId) || empty($data->fileContent) || empty($data->fileType))
{
    sendResponse(400, 1, 0);
    return;
}


$company = new Company();
$company->setKeyColumn("companyid");
$company->setKeyValue($data->companyId);
$companyexists = $company->read_record();

if (!$companyexists)
{
    sendResponse(404, 10, 0 );  // company not found
    return;
}


$file = new File();

$file->companyId = $data->companyId;
$file->fileContent = $data->fileContent;
$file->fileType = $data->fileType;
$file->fileName = $data->fileName;
$file->uploadTime = date("Y-m-d H:i:s");
$file->instanceId = 0;
$file->status = 0;
$file->numberOfDocuments = 0;
$fileCreated = $file->create();


if ($fileCreated)
{
   sendResponse(201, 0, 0);
}
else
{
   sendResponse(501, 2, 0);
}

?>