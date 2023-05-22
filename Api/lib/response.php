<?php

function sendResponse($responseCode,$messageCode,$language,$outparameters = array())
{
    http_response_code($responseCode);
    $message = getmessage($messageCode, $language);
    $messageArray = array(
            "messageCode" => $messageCode,
            "message" => $message);

   $responseArray = $messageArray+ $outparameters;
   echo json_encode($responseArray );
   return;
}


?>