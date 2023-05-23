<?php



function getMessage($code,$language)
{

      $engMessages = array (
        0   => "OK",
        1 => "Missing parameter/s",
        2 => "update failed",
        10 => "Company not found",

    );
    $hebMessages = array (
      0   => "OK"
    );


    // $message="";

    $message= $engMessages[$code];
    if ($message == NULL)
    {
        $message= $engMessages[$code];
    }

    return  $message;

}

?>