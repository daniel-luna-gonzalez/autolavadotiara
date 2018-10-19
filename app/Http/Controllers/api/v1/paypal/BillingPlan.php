<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\api\v1\paypal;

/**
 * Description of BillingPlan
 *
 * @author danielunag
 */
use Illuminate\Http\Request;
use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use App\Http\Controllers\api\v1\paypal\BillingAgreementCreditCard;

class BillingPlan extends Main {
    public function index(Request $httpRequest) {
        
        try {
            $params = array('page_size' => '2');
            $planList = Plan::all($params, $this->apiContext);
        } catch (Exception $ex) {

            return response()->json($ex);
        }
        return response()->json($planList);
    }

    public function create(Request $httpRequest) {        
        // Create a new billing plan
        $plan = new Plan();
        $plan->setName('T-Shirt of the Month Club Plan')
                ->setDescription('Template creation.')
                ->setType('INFINITE');

// Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Donación Hazlo Realidad')
                ->setType('REGULAR')
                ->setFrequency('Month')
                ->setFrequencyInterval('1')
                ->setCycles('0')
                ->setAmount(new Currency(array('value' => 100, 'currency' => 'USD')));

// Set charge models
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
                ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));
        $paymentDefinition->setChargeModels(array($chargeModel));

// Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl('http://localhost:3000/processagreement')
                ->setCancelUrl('http://localhost:3000/cancel')
                ->setAutoBillAmount('yes')
                ->setInitialFailAmountAction('CONTINUE')
                ->setMaxFailAttempts('0')
                ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);

        /* Create and activate billing plan */

        try {
            $createdPlan = $plan->create($this->apiContext);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                        ->setPath('/')
                        ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);

                // Output plan id
                $billingAgreement = new BillingAgreementCreditCard();
                
                return $billingAgreement->create($httpRequest, $this->apiContext ,$plan->getId());
                
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                return response()->json(["code" => $ex->getCode(), "data"=>$ex->getData(), "error" => ($ex)]);
            } catch (Exception $ex) {
                return response()->json(["error" => ($ex)]);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json(["code" => $ex->getCode(),"data"=>$ex->getData(),"error" => ($ex)]);
        } catch (Exception $ex) {
            return response()->json(["error" => ($ex)]);
        }
    }

    

}
