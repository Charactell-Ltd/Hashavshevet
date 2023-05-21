<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';


class Client extends TableObject
{
    public $clientid; 
    public $uniqueid;
    public $lastupdate;
    public $clientdata;
  
       
    public function __construct()
    {
        parent::__construct();
        $this->table_name = "Clients";
    }
}

