<?php

include_once 'tableobject.php';
include_once '../lib/utils.php';

class User extends TableObject
{
    public $userId;
    public $email;
    public $accountId;
    public $fullName;
    public $token;
    public $tokenExpiration;
    public $userBlocked;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = "Users";
    }
}
