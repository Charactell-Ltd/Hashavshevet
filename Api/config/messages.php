<?php

function getMessage($code,$language)
{

      $engMessages = array (
        0   => "OK",
        1 => "Missing parameter/s",
        2 => "Update failed",

        
        210 => "Company not found",
        211 => "File not found",
        212 => "File already is in process by another processor",
        213 => "File already processed",
        214 => "File is not in process status or being processed by another processor",
        215 => "No Files to process",

    );
    $hebMessages = array (
      0   => "OK"
    );

    $message= $engMessages[$code];
    if ($message == NULL)
    {
        $message= $engMessages[$code];
    }

    return  $message;

}

?>