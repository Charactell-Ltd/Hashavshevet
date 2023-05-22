<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';

class File extends TableObject
{
    public $fileId;
    public $companyId;
    public $uploadTime;
    public $processStartTime;
    public $processFinishTime;
    public $processorId;
    public $numberOfDocuments;
    public $status;
    public $fileContent;
    public $fileType;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "files";
    }
}
