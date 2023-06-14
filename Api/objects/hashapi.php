<?php


include_once '../../vendor/autoload.php';
include_once '../config/messages.php';
include_once '../lib/response.php';

use GuzzleHttp\Client;

class hashapi
{
    
    public function __construct()
    {
    }

    public function movein($station, $netPassportID, $company, $moduleData)   
    {
        

       // $moduleDataJson = json_encode($moduleData);
    //   $moduledata = json_decode($moduleDataJson, true);

       $signature = md5(json_encode($moduleData) . "F9682B75DF384984B3819E22358134AF");

       $body = array(
            "station" => $station,
            "plugin" => "movein",
            "company" => $company,
            "message" => array(
            "netPassportID" => $netPassportID,
            "pluginData" => $moduleData
              ),
        "signature" => $signature
        );

        $client = new Client();

        try
        {
            $response = $client->post('https://ws.wizground.com/api', [
                'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $body
            ]);
        }
        catch (Exception $e)
        {
            $response = $e->getMessage();
           // $json = $this->extractReponse($response);
          //  $data = json_encode($json, true);

            // $dataArray = array(
            //     "error" => json_decode( $data["err"], true),
              
            // );

            sendResponse(500,300 , 0);
            return false;  
        }

        $data = json_decode($response->getBody(), true);
       
        sendResponse(200, 0, 0);
        return true;

    }

    private function extractReponse($response)
    {

        $jsonStartPos = strpos($response, '{');

            // Check if a valid JSON start position was found
            if ($jsonStartPos !== false) {
                // Extract the JSON part from the response
                $jsonPart = substr($response, $jsonStartPos);
            
                return $jsonPart;
                // Decode the JSON string into a PHP associative array
               // $data = json_decode($jsonPart, true);
               // return $data;
            }
            else
            {
                return "";
            }

    
     
    }


}
    

?>