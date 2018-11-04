<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\api\v1\conekta;

/**
 * Description of SuscripcionTarjeta
 *
 * @author Daniel Luna <dluna>
 */
use App\Http\Requests\SubscriptionRequest;
use Illuminate\Http\Request;
use Conekta\Conekta;
use Conekta\Customer;
use Conekta\ErrorList;
use Conekta\Error;
use Conekta\Plan;
use App\CustomerModel;
use \Illuminate\Support\Facades\Mail;

class SuscripcionTarjeta {

    public function __construct()
    {
    }

    public function create(SubscriptionRequest $request) {
        try {

            Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
            Conekta::setApiVersion("2.0.0");

            echo "<pre>"; var_dump($request->all()); die();

            $amount = $request->input("card.amount");

            $customer = Customer::create(array(
//                'id' => $request->input("donor.email"),
                        "name" => $request->input("customer.name"),
                        "email" => $request->input("customer.email"),
                        "phone" => $request->input("customer.phone"),
                        "payment_sources" => array(
                            array(
                                "type" => "card",
                                "token_id" => $request->input("tokenCard.id")
                            )
                        )
            ));


            $plan = $this->getPlan("Tiaraautolavado".(int)$amount);

            if(is_null($plan))
                $plan = $this->createPlan($request);

            $subscription = $customer->createSubscription(
                    array(
                        'plan' => $plan['id']
                    )
            );

            $insert = $request->get("donor");
            $insert['idPlan'] = $plan['id'];
            $insert['token_card'] = $request->input("tokenCard.id");
            $insert['idCustomer'] = $customer['id'];
            $insert['idSubscription'] = $subscription['id'];

            Mail::send('email.subscription', ["amount" => "$" . $amount], function($message) use ($request) {
                $message->to($request->input("donor.email"), $request->input("donor.name"))->subject('Gracias por hacerlo realidad');
            });

            if (CustomerModel::where("email", $request->input("donor.email"))->count() == 0) {
                $donor = CustomerModel::create($insert);
                $this->insertCauses($donor, $request->input("donor.causas"));
                $this->insertFiscalEntity($donor, $request);
            }
//
            return response()->json(["status" => true, "message" => "Subscripción realizada con éxito"]);
        } catch (ErrorList $errorList) {
            foreach ($errorList->details as &$errorDetail) {
                return response()->json(["status" => false, "message" => $errorDetail->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        } catch (Conekta_Error $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    private function insertFiscalEntity($donor, Request $request) {
        if ($request->input("donor.fiscalEntity") != 1)
            return 0;

        $fiscalEntityData = $request->input("fiscalEntity");
        $fiscalEntityData["idDonor"] = $donor->id;

        FiscalEntity::create($fiscalEntityData);
    }

    private function insertCauses($donor, $causes) {
        foreach ($causes as $cause) {
            CausesDonor::create(array("idCause" => $cause['id'], "idDonor" => $donor->id));
        }
    }

    private function getPlan($id) {
        try {
            return Plan::find($id);
        } catch (\Exception $e) {
            return null;
        }
        catch (Conekta_Error $e){
            return null;
        }
    }

    private function createPlan(Request $request) {
        try {
            $amount = $request->input("card.amount");

            $plan = Plan::create(array(
                "id" => "Tiaraautolavado".(int)$amount,
                "name" => "Tiaraautolavado $ $amount",
                "amount" => (float) $amount * 100,
                "currency" => "MXN",
                "interval" => "month"
                    )
            );
            return $plan;
        } catch (ErrorList $errorList) {
            return null;
        }
    }

    private function getCausesString($causes) {
        $string = "";

        if (!count($causes) > 0)
            return "";

        foreach ($causes as $cause) {
            $string .= " * " . $cause["name"];
        }

        return $string;
    }

}
