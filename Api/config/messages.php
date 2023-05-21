<?php



function getMessage($code,$language)
{

      $engMessages = array (
        0   => "OK",
        1 => "Missing parameter/s",

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