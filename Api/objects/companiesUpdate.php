<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';

class CompaniesUpdate extends TableObject
{
    public $recordId;
    public $companyId;
    public $updateTime;
    public $companyData;
    public $updateType;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "CompaniesUpdates";
    }
}
