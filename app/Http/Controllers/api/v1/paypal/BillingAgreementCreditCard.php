<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\api\v1\paypal;

/**
 * Description of BillingAgreementCreditCard
 *
 * @author Daniel Luna <dluna>
 */
use Illuminate\Http\Request;
use PayPal\Api\Agreement;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\PaymentCard;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;

class BillingAgreementCreditCard {

    public function create(Request $httpRequest, $apiContext, $idPlan) {
        //create new agreement
        $agreement = new Agreement();
        $agreement->setName('Base Agreement')
                ->setDescription('Basic Agreement')
                ->setStartDate('2019-06-17T9:45:04Z');

// Set plan id
        $plan = new Plan();
        $plan->setId($idPlan);
        $agreement->setPlan($plan);

// Create credit card object and set funding instrument
        $card = new CreditCard();
        $card->setType("visa")
                ->setNumber("4669424246660779")
                ->setExpireMonth("11")
                ->setExpireYear("2019")
                ->setCvv2("012")
                ->setFirstName("Joe")
                ->setLastName("Shopper");

        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

// Set payer to process credit card
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
                ->setFundingInstruments(array($fi));
        $agreement->setPayer($payer);

// Adding shipping details
        $shippingAddress = new ShippingAddress();
        $shippingAddress->setLine1('111 First Street')
                ->setCity('Saratoga')
                ->setState('CA')
                ->setPostalCode('95070')
                ->setCountryCode('MX');
        $agreement->setShippingAddress($shippingAddress);

        try {
            $res = $agreement->create($apiContext);
            
            return response()->json(["idBillingAgreement" => $res->getId()]);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json(["code" => $ex->getCode(), "data" => $ex->getData(), "error" => ($ex)]);
        } catch (Exception $ex) {
            return response()->json(["error" => ($ex)]);
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()->json(["code" => $ex->getCode(), "data" => $ex->getData(), "error" => ($ex)]);
        } catch (Exception $ex) {
            return response()->json(["error" => ($ex)]);
        }
    }

}
