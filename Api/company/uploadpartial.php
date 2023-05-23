<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:  POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../objects/company.php';
include_once '../objects/Companiesupdate.php';
include_once '../objects/token.php';

$requestmethod = trim($_SERVER["REQUEST_METHOD"]);
if ($requestmethod == "OPTIONS")
{
    return;
}

$database = new Database();
$db = $database->getConnection();


$data = json_decode(file_get_contents("php://input"));
if ($data == null || empty($data->companyId) || empty($data->updateTime) || empty($data->companyData))
{
    sendResponse(400, 1, 0);
    return;
}

// convert json object to string 
$companyData = json_encode($data->companyData);

$companyupdateStatus=true;
$newCompany = false;

$company = new Company();
$company->setKeyColumn("companyid");
$company->setKeyValue($data->companyId);
$companyexists = $company->read_record();

if (!$companyexists)
{
    sendResponse(404, 10, 0, $companyData );  // company not found
    return;
}


$companiesUpdate = new CompaniesUpdate();
$companiesUpdate->companyId = $data->companyId;
$companiesUpdate->updateTime1 = $data->updateTime;
$companiesUpdate->companyData = $companyData;   
$companiesUpdate->updateType =2 ; // partial update
$companiesUpdateStatus = $companiesUpdate->create();


if ($companyupdateStatus)
{
   sendResponse(200, 0, 0);
}
else
{
   sendResponse(501, 2, 0);
}

?>