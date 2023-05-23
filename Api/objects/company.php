<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';


class Company extends TableObject
{
    public $recordId;
    public $companyId;
    public $lastUpdate;
    public $companyData;
    public $ocrInfo;
  
       
    public function __construct()
    {
        parent::__construct();
        $this->table_name = "Companies";
    }
}

