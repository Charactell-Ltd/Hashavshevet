<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';

class SuppliersDoc extends TableObject
{
    public $supplierDocId;
    public $companyId;
    public $documentId;
    public $docType;
    public $docDate;
    public $dueDate;
    public $docNumber;
    public $paymentDate;
    public $supplierNumber;
    public $supplierName;
    public $vatNumber;
    public $docDescription;
    public $subTotal;
    public $tax;
    public $total;
    public $discount;
    public $clientAccountNumber;
    public $servicePeriod;
    public $creditCard;
    public $template;
    public $transactionNo1;
    public $sugTnuaa1;
    public $heshbonZchut1;
    public $heshbonHova1;
    public $amount1;
    public $description1;
    public $dueDate1;
    public $transactionNo2;
    public $sugTnuaa2;
    public $heshbonZchut2;
    public $heshbonHova2;
    public $amount2;
    public $description2;
    public $dueDate2;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "SuppliersDocs";
    }
}
