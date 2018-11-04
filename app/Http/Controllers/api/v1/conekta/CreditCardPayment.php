<?php

namespace App\Http\Controllers\api\v1\conekta;

/***
 * @author Daniel Luna <dluna>
 */
use App\CampaignOrders;
use Illuminate\Http\Request;
use Conekta\Conekta;
use Conekta\Customer;
use Conekta\ErrorList;
use Conekta\Error;
use Conekta\Plan;
use App\CustomerModel;
use App\CampaignDonor;
use App\CampaignFiscalEntity;
use App\Campaign;
use \Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class CreditCardPayment extends Main{
    public function __construct() {
        $this->arrayCreate = [
            "donor.name" => 'string|required|min:3',
            'donor.last_name' => 'string|required|min:3',
            'donor.mother_last_name' => 'string',
            'donor.birthday' => '|required|string',
            'donor.email' => 'string|required|email',
            'card.name' => 'string|required',
            'card.amount' => 'numeric|required'
        ];

        parent::__construct();
    }

    public function create(Request $request) {
        Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
        Conekta::setApiVersion("2.0.0");

        if (($validate = $this->validate($request, $this->arrayCreate)) !== NULL)
            return $validate;

        try {
                $idCampaign = (int)$request->input("campaign.id");
                $amount = ((float) $request->input("card.amount") > 0) ? (float) $request->input("card.amount") * 100 : 0;

                if(!(float)$amount > 0)
                    return response()->json(["status" => false, "message" => "No se ha ingresado un monto válido"]);

                $customerName =  $request->input("donor.name");

                $customer = Customer::create(array(
                    "name" => $customerName,
                    "email" => $request->input("donor.email"),
    //                "phone" => "5525056592",
                    "payment_sources" => array(
                        array(
                            "type" => "card",
                            "token_id" => $request->input("tokenCard.id")
//                            "token_id" => "tok_test_visa_4242"
                        )
                    )
                ));

                $orderParams = array(
                    'line_items' => array(
                        array(
                            'name' => 'Todosxuno',
                            'description' => 'Donación campaña Todosxuno',
                            'unit_price' => $amount,
                            'quantity' => 1,
                            'category'    => 'Donación',
                            'tags' => array("Tiara Autolavado")
                        )
                    ),
                    'currency' => 'mxn',
        //                    'metadata'    => array('test' => ''),
                    'charges' => array(
                        array(
                            'payment_method' => array(
                                'type' => 'card',
                                "payment_source_id" => $customer["default_payment_source_id"]
                            ),
                            'amount' => (int)$amount
                        )
                    ),
                    'currency' => 'mxn',
                    'customer_info' => array(
                        "customer_id" => $customer["id"],
                                'name'  => $customerName,
//                                'phone' => '5555969966',
                                'email' => $request->input("donor.email")
                    )
                );

                $order = \Conekta\Order::create($orderParams);

                $response =  array(
                     "ID" => $order->id,
                     "Status" => $order->payment_status,
                     "$" => $order->amount / 100 . $order->currency,
                     "Order" => array(
                                 $order->line_items[0]->quantity . "-". $order->line_items[0]->name . "- $". $order->line_items[0]->unit_price / 100,
                                 "Payment info" => array(
                                 "CODE:" => $order->charges[0]->payment_method->auth_code,
                                 "Card info:" =>
                                 "- ". $order->charges[0]->payment_method->name .
                                 "- <strong><strong>". $order->charges[0]->payment_method->last4 .
                                 "- ". $order->charges[0]->payment_method->brand .
                                 "- ". $order->charges[0]->payment_method->type
                             )
                    )
                );

                Campaign::where('id', $idCampaign)->increment('current_amount', $amount/100);

                $donorData = $request->get("donor");

                $donorData["idConekta"] = $customer["id"];
                $donorData["amount"] = (float)$amount / 100;

                $donor = CampaignDonor::create($donorData);

                $orderData = [
                    "idConekta" => $order->id,
                    "idDonor" => $donor->id,
                    "amount" => (float)$amount / 100,
                    "idCampaign" => $idCampaign,
                    "cardToken" => $request->input("tokenCard.id")
                ];

                $orderObject = CampaignOrders::create($orderData);

                $this->insertFiscalEntity($donor, $request);

            Mail::send('email.campaign.todosxuno.order', ["amount" => "$" . $amount/100, "name" => $customerName], function($message) use ($request) {
                $message->to($request->input("donor.email"), $request->input("donor.name"))->subject('!Todos somos uno!');
            });

            return response()->json(["status" => true, $response]);

        } catch (ErrorList $errorList) {
            foreach ($errorList->details as &$errorDetail) {
                return response()->json(["status" => false, "message" => $errorDetail->getMessage()]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        } catch (Conekta_Error $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
        catch (\Conekta\ProccessingError $error){
            return response()->json(["status" => false, "message" => $error->getMessage()]);
        } catch (\Conekta\ParameterValidationError $error){
            return response()->json(["status" => false, "message" => $error->getMessage()]);
        } catch (\Conekta\Handler $error){
            return response()->json(["status" => false, "message" => $error->getMessage()]);
        }
    }

    private function insertFiscalEntity($donor, Request $request) {
        if ($request->input("donor.fiscalEntity") != 1)
            return 0;

        $fiscalEntityData = $request->input("fiscalEntity");
        $fiscalEntityData["idDonor"] = $donor->id;

        CampaignFiscalEntity::create($fiscalEntityData);
    }

    public function getCustomerPlan($id){
        try{
            Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
            Conekta::setApiVersion("2.0.0");

            $customer =   \Conekta\Customer::find($id);
            $planId = $customer->subscription->plan_id;
            $plan = \Conekta\Plan::find($planId);
            return $plan;
        } catch (ErrorList $errorList) {
           return null;
        } catch (\Exception $e) {
            return null;
        } catch (Conekta_Error $e) {
            return null;
        }
        catch (\Conekta\ProccessingError $error){
            return null;
        } catch (\Conekta\ParameterValidationError $error){
            return null;
        } catch (\Conekta\Handler $error){
            return null;
        }
    }

}
