<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';

class Document extends TableObject
{
    public $documentId;
    public $companyId;
    public $fileId;
    public $documentType;
    public $documentDate;
    public $status;
    public $documentImage;
    public $imageType;
    public $checkSum;
    public $duplicationId;
    public $misparMana;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "Documents";
    }
}
