<?php



function getMessage($code,$language)
{

      $engMessages = array (
        0   => "OK",
        1 => "Missing parameter/s",
        2 => "update failed",
        10 => "Company not found",
        11 => "File not found",
        12 => "File already is in process by another processor",
        13 => "File already processed",
        14 => "File is not in process status or being processed by another processor",
        15 => "No Files to process",

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