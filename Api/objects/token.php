<?php

include_once '../config/core.php';
include_once '../config/messages.php';
include_once '../lib/response.php';
include_once '../lib/php-jwt-master/src/BeforeValidException.php';
include_once '../lib/php-jwt-master/src/ExpiredException.php';
include_once '../lib/php-jwt-master/src/SignatureInvalidException.php';
include_once '../lib/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

class token
{

    // variables used for jwt
    private $key = "cHar&clOud@iNv";
    private $iss = "http://charactell.com";
    private $aud = "http://charactell.com";
    private $iat = 1356999524;
    private $nbf = 1357000000;

    public $tokenid;
    public $tokenMessage;


    public $email;
    public $userid;
    public $accountid;
    public $integratorid;
    public $projectid;
    public $expirationtime;


    public function __construct()
    {
    }

    
    public function generateSiteToken($user)
    {

        $expirationtime = "2099-31-12 23:59:00";    //  no expiration
      // $expirationtime = time() + (24 * 60 * 60);    //  1 day from now
     
    
        $tokenArr = array(
            "iss" => $this->iss,
            "aud" => $this->aud,
            "iat" => $this->iat,
            "nbf" => $this->nbf,
            "data" => array(
                "email" => $user->email,
                "userid" => $user->userid,
                "accountid" => $user->accountid,
                "expirationtime" => $expirationtime,
            ),
        );
        // generate token
        $tokenid = JWT::encode($tokenArr, $this->key);
        $this->expirationtime = $expirationtime ;
        $this->tokenid = $tokenid;
        return ;
    }




    public function readtoken()
    {
        $this->tokenid = $this->getBearerToken();
        if (is_null($this->tokenid))
        {
            $data = json_decode(file_get_contents("php://input"));
            $this->tokenid = isset($data->tokenid) ? $data->tokenid : "";
        }
        return($this->validateToken());
    }

    public function validateToken()
    {
        // if jwt is not empty
        if ($this->tokenid) {

            // if decode succeed, show user details
            try {
                // decode jwt
                $decoded = JWT::decode($this->tokenid, $this->key, array('HS256'));
            }
            catch (Exception $e) {
                    // if decode fails, it means jwt is invalid
                    sendResponse(401, 105, 0);
                    return;
            }

            $this->email = $decoded->data->email;
            $this->userid = $decoded->data->userid;
            $this->accountid = $decoded->data->accountid;
            $this->integratorid = $decoded->data->integratorid;
            $this->projectid = $decoded->data->projectid;
            $this->expirationtime = $decoded->data->expirationtime;


         //    check if token  non expired
            if (time()> $this->expirationtime)
            {
                sendResponse(401, 106, 0);
                return false;   // token expired
            }
  
         //   $this->tokenMessage = "Token Valid";
            return true;
        }
      
        // show error message if jwt is empty
        else {
            sendResponse(401, 105, 0);
             return;
        }
     }

/**
 * Get header Authorization
 * */
    public function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
     /**
     * get access token from header
     * */
    public function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    // public function readsitetoken()
    // {
    //     $this->tokenid = $this->getBearerToken();
    //     if (is_null($this->tokenid))
    //     {
    //         $data = json_decode(file_get_contents("php://input"));
    //         $this->tokenid = isset($data->tokenid) ? $data->tokenid : "";
    //     }
    //     return($this->validateSiteToken());
    // }

    // public function validateSiteToken()
    // {
    //     // if jwt is not empty
    //     if ($this->tokenid) {

    //         // if decode succeed, show user details
    //         try {
    //             // decode jwt
    //             $decoded = JWT::decode($this->tokenid, $this->key, array('HS256'));
             
    //             $this->email = $decoded->data->email;
    //             $this->userid = $decoded->data->userid;
    //         //    $this->fullname = $decoded->data->fullname;

    //          //   $this->userid = $decoded->data->userid;
    //          //   $this->language = $decoded->data->language;

    //             $this->tokenMessage = "Token Valid";
    //             return true;
    //         }

    //         // if decode fails, it means jwt is invalid
    //         catch (Exception $e) {
    //             sendResponse(401, 112, 0);
    //             return;
    //         }
    //     }
    //     // show error message if jwt is empty
    //     else {
    //         sendResponse(401, 112, 0);
    //          return;
    //     }

    // }

}
