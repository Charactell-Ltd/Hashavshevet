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
      //  $token =      "AREQBLAMKUKXLVJZPUOUPWKSIULXIWNSIULXOVPSIULXOVPSIUPXKVMZCULX";
        $token = "F9682B75DF384984B3819E22358134AF";  // ct2
     //  $token = "150BF00F361A64B54B11EAB33037FAB7";  // CTOrg

       $json = json_encode($moduleData, JSON_UNESCAPED_UNICODE);
       $signature = md5($json . $token);
  
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
       
            $messageArray = array(
                "hashavshevetError" => $response,
            );
           

            sendResponse(500,300 , 0,$messageArray);
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