<?php

include_once '../config/database.php';
include_once '../config/query.php';

abstract class TableObject
{

    private $database;
    private $db;

    public  $newKeyValue;
    protected $conn;
    protected $table_name;
    protected $columnsList;
    protected $valuesList;
    protected $keyColumn;
    protected $keyValue;
   
    protected $sortColumn;
    protected $filterColumn;
    protected $filterValue;
    protected $filterColumns;
    protected $specialFilter;
  
    protected $userFilterColumn;
    protected $userFilterValue;
  
    protected $uniqueColumn;

    

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->conn = $db;
        $this->sortColumn = "";
        $this->filterColumn = "";
        $this->filterValue = "";

        $this->userFilterColumn = ""; // default user filter column
        $this->userFilterValue = 0;
        $this->filterColumns = [];
        $this->specialFilter ="";

        $this->uniqueColumn = "";    // dedault check unique value column
        $this->uniqueValue = "";

        $this->columnsList = [];
        // $this->excludeFromQuery = [];
        $classname = get_class($this);
        $reflect = new ReflectionClass($classname);
        $props = $reflect->getProperties();
        
        foreach ($props as $object) {
            if ($object->class == $classname) {
                array_push($this->columnsList, $object->name);
            }
        }
        $this->keyColumn = $this->columnsList[0];

    }

    public function setValueList()
    {
        $this->valuesList = [];
        $classname = get_class($this);
        $reflect = new ReflectionClass($classname);
        $props = $reflect->getProperties();

        foreach ($props as $object) {
            if ($object->class == $classname) {
             

                // $escaped_value =  $this->conn->quote($object->getValue());
                // $object->setValue($escaped_value);

                $objname = $object->name;

                array_push($this->valuesList, $this->$objname);

                if ($this->uniqueColumn!="")
                {
                   if ($objname == $this->uniqueColumn)
                   {
                      $this->uniqueValue  = $this->$objname;
                   }
                }

            }
        }
    }

    public function setKeyColumn($columnName)
    {
        $this->keyColumn = $columnName;
    }
    public function setKeyValue($id)
    {
        $this->keyValue = $id;
    }

    public function setuserFilter($value)
    {
        $this->userFilterValue = $value;
    }

    public function setAccountFilter($value)
    {
        if  (in_array("accountid", $this->columnsList))
        {
            $this->setFilter("accountid",$value );
        }
    }

    public function setAccountValue($value)
    {
        if  (in_array("accountid", $this->columnsList))
        {
            $this->accountid = $value;
        }
    }

    public function clearuserFilter()
    {
        $this->userFilterColumn ="";
    }

    public function setUniqueColumn($value)
    {
        $this->uniqueColumn =$value;
    }

    // public function addToExcludeList($columnName)
    // {
    //     array_push($this->excludeFromQuery, $columnName);
       
    // }

    public function removeColumnFromList($columnName)
    {
        $this->columnsList = array_diff($this->columnsList, array($columnName));
    //    unset( $this->columnsList[$columnName]);
    }
   

    // public function setFilter($column, $value)
    // {
    //     $this->filterColumn = $column;
    //     $this->filterValue = $value;
    // }
    public function setFilter($column, $value)
    {
        $this->filterColumns[$column] = $value;
    }

    public function clearFilter()
    {
        $this->filterColumns = [];
    }



    public function setSpecialFilter($filter)
    {
        if ($this->specialFilter<>"" )
        {
            $this->specialFilter =  $this->specialFilter . " or " ;
        }
        $this->specialFilter = $this->specialFilter . $filter;
    }

    public function getTableName()
    {
        return $this->table_name;
    }

    public function getColumnList()
    {
        return $this->columnsList;
    }

    public function getValuesFromInput($data)
    {
        foreach (get_object_vars($data) as $key => $value) {
            $this->$key = $value;
        }
    }
    public function getValuesFromArray($arr)
    {
        foreach (($arr) as $key => $value) {
            $this->$key = $value;
            if ($key == $this->keyColumn)
            {
                $this->keyValue = $value;
            }
        }
    }
    // read  object records
    public function read()
    {
   
        // if ($this->table_name == "Project")
        // {
        //      $temp = $this->table_name;
        // }
 
        $query = query::generateReadListQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue, $this->filterColumns , $this->specialFilter, $this->sortColumn, false);

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

 
        return $stmt;
    }

    // get single object record
    public function read_single()
    {
        $query = query::generateReadsingleRecordQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue, $this->filterColumns , $this->keyColumn, $this->keyValue);

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

  // get single object record - new 
  public function read_record()
  {
      $query = query::generateReadsingleRecordQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue,$this->filterColumns, $this->keyColumn, $this->keyValue);

      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->postRetrieve($row);
      
        foreach ($row as $key => $value)
        {
            if (in_array( $key, $this->columnsList ))
            {
                $this->$key = $value;
            }
        }

        return true;
      }

      return false;
  }

  public function read_records()
  {

      $query = query::generateReadListQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue, $this->filterColumns , $this->specialFilter, $this->sortColumn, false);

      $stmt = $this->conn->prepare($query);
      $stmt->execute();

      $num = $stmt->rowCount();

    //  if ($num > 0) {
          $table = array();
          $table["Rows"] = array();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //   $tableobj->postRetrieve($row);
              array_push($table["Rows"], $row);
          }
      
    //  }   
       

      return $table;
  }


    // create object record
    public function create()
    {
        if ($this->uniqueColumn!="")
        {
           if ($this->isAlreadyExist()) {
              return false;
           }
        }
        $this->setValueList();

        $query = query::generateCreateRecordQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue,  $this->valuesList, $this->filterColumns , 1);
        $lquery = strlen($query);
        $stmt = $this->conn->prepare($query);

        file_put_contents("c:/temp/query.txt",$stmt->queryString);
        // execute query
        if ($stmt->execute()) {
            $this->newKeyValue=  $this->conn->lastInsertId();
           
            return true;
        }

        return false;
    }

    // update all object  column
    public function updateAll()
    {
        $this->setValueList();
        $query = query::generateUpdateRecordQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue, $this->valuesList, 1, $this->keyColumn, $this->keyValue,true);

       // $escaped_query =  $this->conn->quote($query);
    

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // update non empty columns
    public function update()
    {
        $this->setValueList();
        $query = query::generateUpdateRecordQuery($this->table_name, $this->columnsList, $this->userFilterColumn, $this->userFilterValue, $this->valuesList, 1, $this->keyColumn, $this->keyValue,false);

        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function postRetrieve(&$row)
    {

    }

    // delete object
    public function delete()
    {

        $this->setValueList();
        $query = query::generateDeleteQuery($this->table_name, $this->userFilterColumn, $this->userFilterValue, $this->keyColumn, $this->keyValue);

        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


    // check if records with key value exist
    public function isKeyExist()
    {

        $query = query::generateCheckUniqueQuery($this->table_name, $this->keyColumn, $this->keyValue);
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // check if record with certain column value already exist
    public function isAlreadyExist()
    {

        $query = query::generateCheckUniqueQuery($this->table_name,$this->uniqueColumn, $this->uniqueValue);
      
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
