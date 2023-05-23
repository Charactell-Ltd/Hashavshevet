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
if ($data==null || empty($data->uniqueid) ||  empty($data->updatetime) ||  empty($data->clientdata))
{
    sendResponse(400, 1, 0);
    return;
}

// convert jason object to string 
$clientdata = json_encode($data->clientdata);


$newCompany = false;

$company = new Company();
$company->setKeyColumn("companyid");
$company->setKeyValue($data->uniqueid);
$companyexists = $company->read_record();

if ($companyexists)
{
    $company->companyData = $clientdata;
    $company->lastUpdate = $data->updatetime;
    $companyupdateStatus  = $company->update();
}
else
{
    $newCompany = true;
    $company->companyId = $data->uniqueid;
    $company->companyData = $clientdata;
    $company->lastUpdate = $data->updatetime;
    $companyupdateStatus  = $company->create();
  
} 

if (!$companyupdateStatus)
{
    sethttpstatus(501);
    $tableobj_arr = array(
        "message" => "Failed",
    );
    print_r(json_encode($tableobj_arr));
    return;
}


$companiesUpdate = new CompaniesUpdate();
$companiesUpdate->companyId = $data->uniqueid;
$companiesUpdate->updateTime1 = $data->updatetime;
$companiesUpdate->companyData = $clientdata;   
$companiesUpdate->updateType =1; // full update
$companiesUpdateStatus = $companiesUpdate->create();


if ($companyupdateStatus)
{
    $tableobj_arr = array(
        "message" => "Ok",
    );
    print_r(json_encode($tableobj_arr));
}
else {
    sethttpstatus(501);
    $tableobj_arr = array(
        "message" => "Failed",
    );
    print_r(json_encode($tableobj_arr));
}





// $client = new Client();
// $client->setKeyColumn("uniqueid");
// $client->setKeyValue($data->uniqueid);
// $clientexists = $client->read_record();
// if ($clientexists)
// {
//     $client->clientdata = $clientdata;
//     $client->lastupdate = $data->updatetime;
//     $client->update();
   
// }
// else
// {
//     $client->uniqueid = $data->uniqueid;
//     $client->clientdata = $clientdata;
//     $client->lastupdate = $data->updatetime;
//     $client->create();
  
// } 

// $tableobj_arr = array(
      
//     "message" => "Ok",
// );
// print_r(json_encode($tableobj_arr));


?>