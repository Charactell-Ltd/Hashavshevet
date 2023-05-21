
<?php

class utils
{
     public static function convertToBool(&$row, $columnName)
     {
       
        if ($row[$columnName]==="0")
        {
            $row[$columnName] = false;
        }
        if ($row[$columnName]==="1")
        {
            $row[$columnName]= true;
        }
    }

}
?>
