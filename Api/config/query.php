
<?php

class query
{

  
    public static function generateReadListQuery($table_name, $columnsList, $userFilterColumn, $userFilterValue, $filterColumns, $specialFilter, $sortField = "", $sortDescend = false)
    {
        $query = "SELECT ";
        foreach ($columnsList as $columnName) {
            
            $query .= "`" . $columnName . "`,";
        }
        if (strlen($query) > 0) {
            $query = rtrim($query, ",");
        }
        $query .= " FROM " . $table_name;

        $query .= " where true ";

        if (strlen($userFilterColumn) > 0) {
            $query .= " and " . $userFilterColumn . "='" . $userFilterValue . "' ";
        }
        foreach ($filterColumns as $key => $value) {
            if (in_array($key,$columnsList))
            {
               $query .= " and " . $key . "='" . $value . "' ";
            }
        }
        if (strlen($specialFilter)>0)
        {
            $query .= " and (" . $specialFilter .")";
        }
        // if (strlen($filterColumn) > 0) {
        //     $query .= " and " . $filterColumn . "='" . $filterValue . "' ";
        // }

        if (strlen($sortField) > 0) {
            $query .= " ORDER BY " . $sortField;
            if ($sortDescend) {
                $query .= " DESC";
            }
        }

        return $query;
    }

    public static function generateReadsingleRecordQuery($table_name, $columnsList, $userFilterColumn, $userFilterValue, $filterColumns,  $searchField, $searchValue)
    {
        $query = "SELECT ";
        foreach ($columnsList as $columnName) {
            $query .= "`" . $columnName . "`,";
        }
        if (strlen($query) > 0) {
            $query = rtrim($query, ",");
        }
        $query .= " FROM " . $table_name;
        $query .= " where  " ;
        if ($searchValue==null)
        {
             $query .=  " true " ;
        }
        else
        {
            $query .= $searchField . "= '" . $searchValue . "'";
         }        
        foreach ($filterColumns as $key => $value) {
            if (in_array($key,$columnsList))
            {
               $query .= " and " . $key . "='" . $value . "' ";
            }
        }

        if (strlen($userFilterColumn) > 0) {
            $query .= " and " . $userFilterColumn . "='" . $userFilterValue . "' ";
        }

        return $query;
    }

    public static function updateColumnValue($columnName,$columnValue)
    {

    }

    public static function generateCheckUniqueQuery($table_name, $uniqueColumn, $uniqueValue )
    {
        $query = "SELECT *
        FROM
            " . $table_name . "
        WHERE " .
             $uniqueColumn . "='" . $uniqueValue . "'";

        return $query;
     

    }


    public static function generateCreateRecordQuery($table_name, $columnsList, $userFilterColumn, $userFilterValue, $valuesList, $filterColumns, $idFieldLocation)
    {
        $querycolumnsNamesPart = "(";
        $queryValuesPart = "(";
        $query = "INSERT INTO " . $table_name;

        // add list of field names
        $counter = 1;
        foreach ($columnsList as $columnName) {
            if ($counter != $idFieldLocation && $columnName != "creationTime" && $columnName != "updateTime") {
                $querycolumnsNamesPart .= "`" . $columnName . "`,";
             
                $columnValue  = addslashes($valuesList[$counter - 1]);
                $queryValuesPart .= "'" . $columnValue . "',";

               // $queryValuesPart .= "'" . query::fixSingleQuote( $valuesList[$counter - 1]) . "',";
             
            }
            $counter++;
        }
        if (strlen($userFilterColumn) > 0) {
            $querycolumnsNamesPart .= "`" . $userFilterColumn . "`,";
            $queryValuesPart .= "'" . $userFilterValue . "',";
        }
     
        // foreach ($filterColumns as $key => $value) {
        //     $querycolumnsNamesPart .= "`" . $key . "`,";
        //     $queryValuesPart .= "'" . $value . "',";
          
        // }

        $querycolumnsNamesPart = rtrim($querycolumnsNamesPart, ",");
        $queryValuesPart = rtrim($queryValuesPart, ",");

        $querycolumnsNamesPart .= ")";
        $queryValuesPart .= ")";
        $query .= $querycolumnsNamesPart . " values " . $queryValuesPart;

        return $query;
    }

    public static function generateUpdateRecordQuery($table_name, $columnsList, $userFilterColumn, $userFilterValue,  $valuesList, $idFieldLocation, $searchField, $searchValue,$updateAll)
    {

        $query = "UPDATE " . $table_name . " SET ";

        // add list of field names/values
        $queryfieldListPart = "";
        $counter = 1;
        foreach ($columnsList as $columnName) {
         
            if ($counter != $idFieldLocation && $columnName != "creationTime" && $columnName != "updateTime") {
                $columnValue =  $valuesList[$counter - 1];
          
                $columnValue  = addslashes($columnValue);


                if ( $columnValue===true )
                {
                   $queryfieldListPart .= $columnName . "='1',";
                }
                else  if ( $columnValue===false )
                {
                   $queryfieldListPart .= $columnName . "='0',";
                }
                else if ($updateAll  || $columnValue!="")
                {
                   $queryfieldListPart .= $columnName . "='" . $columnValue  . "',";
                }
            }
            $counter++;
        }
        $queryfieldListPart = rtrim($queryfieldListPart, ",");
        $query .= $queryfieldListPart;

        $query .= " where  " . $searchField . "= '" . $searchValue . "'";
        if (strlen($userFilterColumn) > 0) {
            $query .= " and " . $userFilterColumn . "='" . $userFilterValue . "' ";
        }
        
        return $query;
    }

    public static function generateDeleteQuery($table_name,$userFilterColumn, $userFilterValue, $searchField, $searchValue)
    {
        $query = "DELETE FROM " . $table_name;
        $query .= " WHERE  " . $searchField . "= '" . $searchValue . "'";
        if (strlen($userFilterColumn) > 0) {
            $query .= " and " . $userFilterColumn . "='" . $userFilterValue . "' ";
        }
        return $query;
    }

    private static function fixSingleQuote($fieldValue)
    {
        if ($fieldValue==true || $fieldValue == false)
        {
            return $fieldValue;
        }
        else {         
            return str_replace("'","\'",$fieldValue);
        }
    }

}

?>