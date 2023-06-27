<?php

include_once '../../vendor/autoload.php';
include_once '../config/messages.php';
include_once '../lib/response.php';

use GuzzleHttp\Client;

class boi
{
    
    public function __construct()
    {
    }

    public function getExchangeRates()
    {
      

        $client = new Client();

        try
        {
            $url = "http://boi.org.il/PublicApi/GetExchangeRates";
            $response = $client->post($url, [
                'headers' => [
                'Content-Type' => 'application/json',
            ]
          //  'json' => $body
            ]);
        }
        catch (Exception $e)
        {
            $response = $e->getMessage();
       
            // $messageArray = array(
            //     "hashavshevetError" => $response,
            // );
           



            sendResponse(500,300 , 0);
            return false;  
        }

        $data = json_decode($response->getBody(), true);
       
        http_response_code(200);
      
      
    
       $responseArray = $data;
       echo json_encode($responseArray );

       // sendResponse(200, 0, 0);
        return true;

    }
}