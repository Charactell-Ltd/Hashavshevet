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
if ($data==null || empty($data->adminpass) )
{
    sendResponse(400, 1, 0);
    return;
}


$company = new Company();
$company->setKeyColumn("companyId");
$company->setKeyValue($data->companyId);
$stmt = $company->read_single();

if ($stmt->rowCount() > 0) {

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $dataArray = array(
        "companyData" => json_decode( $row["companyData"]),
        "ocrInfo" => $row["ocrInfo"],
    );
        
    sendResponse(200, 0, 0, $dataArray ); // company found
    
} else {
  
    sendResponse(404, 10, 0 );  // company not found
 
}
?>

