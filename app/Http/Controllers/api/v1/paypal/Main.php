<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Main
 *
 * @author danielunag
 */

namespace App\Http\Controllers\api\v1\paypal;

use App\Http\Controllers\Controller;

class Main extends Controller {

    protected $apiContext;
    protected $arrayCreate = array();
    protected $arrayUpdate = array();
    protected $arrayDelete = array();
    
    public function __construct() {
        $this->apiContext = $this->getApiContext();
    }

    public function getApiContext() {
        $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(
                env("PAYPAL_API_CLIENT_ID"), // ClientID
                env("PAYPAL_API_CLIENT_SECRET")      // ClientSecret
                )
        );

        // Step 2.1 : Between Step 2 and Step 3
        $apiContext->setConfig(
                array(
                    'log.LogEnabled' => true,
                    'log.FileName' => 'PayPal.log',
                    'log.LogLevel' => 'DEBUG'
                )
        );
        return $apiContext;
    }

}
