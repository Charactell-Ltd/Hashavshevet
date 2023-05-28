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
if ($data == null || empty($data->companyId)  || empty($data->processorId))
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
    sendResponse(404, 210, 0 );  // company not found
    return;
}

$fileId = 0;
if (!empty($data->fileId))
{
    $fileId  =$data->fileId;
}
else {
    // search for the first file in the queue
    $file = new File();
    $file->companyId = $data->companyId;
    $file->status = 0;
    $file->processorId = 0;
    $file->setSortColumn("uploadTime");
  
    $file->setFilter("status","0");
    $stmt = $file->read();
    if ($stmt->rowCount() == 0) {
        sendResponse(404, 15, 0 ); // no file in queue
        return;
    }   
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $fileId = $row["fileId"];

    
}

$file = new File();
$file->setKeyColumn("fileId");
$file->setKeyValue($fileId);

$stmt = $file->read_single();

if ($stmt->rowCount() == 0) {
    sendResponse(404, 211, 0 );  // file not found
}


$row = $stmt->fetch(PDO::FETCH_ASSOC);

$status = $row["status"];
$processorId = $row["processorId"];

if ($status == 1 && $processorId != $data->processorId)
{
    sendResponse(501, 212, 0); // file is already in process by another processor
    return;
}
if ($status > 1)
{
    sendResponse(501, 213, 0); // file is already processed
    return;
}

$file->processorId = $data->processorId;
$file->status = 1;  // file is in process
$file->processStartTime = date("Y-m-d H:i:s");
$fileUpdated = $file->update();


if ($fileUpdated)
{
    
    $dataArray = array(
        "fileId" => $fileId,
     
    );
   sendResponse(200, 0, 0, $dataArray);
}
else
{
   sendResponse(501, 2, 0);
}


?>