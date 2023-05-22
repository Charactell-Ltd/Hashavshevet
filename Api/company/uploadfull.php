<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:  POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once '../objects/company.php';
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

// convert jason object to string 
$companyData = json_encode($data->companyData);


$company = new Company();
$company->setKeyColumn("companyid");
$company->setKeyValue($data->companyId);
$companyexists = $company->read_record();
if ($companyexists)
{
    $company->companyData = $companyData;
    $company->lastUpdate = $data->updateTime;
    $company->update();
}
else
{
    $company->companyId = $data->companyId;
    $company->companyData = $companyData;
    $company->lastUpdate = $data->updateTime;
    $company->create();
  
} 

sendResponse(200, 0, 0);
// $tableobj_arr = array(
      
//     "message" => "Ok",
// );
// print_r(json_encode($tableobj_arr));

//  $paramsArray = array(
//    "solutionid" => $solution->newKeyValue,
//    "solutionkey"  => $solution->solutionkey  
  
// );
// sendResponse(200, 0, 0);//, $paramsArray);

?>