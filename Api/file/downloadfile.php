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


$data = json_decode(file_get_contents("php://input"));
if ($data == null || empty($data->companyId) || empty($data->fileId) || empty($data->processorId))
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
$file->setKeyColumn("fileid");
$file->setKeyValue($data->fileId);

$stmt = $file->read_single();

if ($stmt->rowCount() == 0) {
    sendResponse(404, 11, 0 );  // file not found
}


$row = $stmt->fetch(PDO::FETCH_ASSOC);

$status = $row["status"];
$processorId = $row["processorId"];

if ($status <> 1 || $processorId != $data->processorId)
{
    sendResponse(404, 14, 0); // File is not in process status or being processed by another processor
    return;
}

$fileContent = $row["fileContent"];
$fileType = $row["fileType"];


$dataArray = array(
    "fileContent" => $fileContent,
    "fileType" => $fileType
);

sendResponse(200, 0, 0, $dataArray ); // file found
 


?>