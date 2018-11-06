<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 11/4/18
 * Time: 9:18 AM
 */

namespace App\libraries;

use App\Http\Requests\SubscriptionRequest;
use App\PackageModel;
use App\SubscriptionModel;
use App\VehicleInformationModel;
use Illuminate\Http\Request;
use Conekta\Conekta;
use Conekta\Customer as ConektaCustomer;
use Conekta\ErrorList;
use Conekta\Error;
use Conekta\Plan;
use App\CustomerModel;
use \Illuminate\Support\Facades\Mail;
use Psr\Log\LoggerInterface;

class SubscriptionLibrary
{
    protected $log;

    public function __construct(LoggerInterface $logger)
    {
        Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
        Conekta::setApiVersion("2.0.0");
        $this->log = $logger;
    }

    public function create(SubscriptionRequest $request){
        try {
            $this->log->info(__CLASS__." create ".json_encode($request->all()));

            $customerEmail = $request->input("customer.email");
            $vehicleType = $request->input("vehicle.vehicleType");
            $packageCode = $request->input("vehicle.packageCode");

            $localCustomerParams = $request->get("customer");

            $localCustomer = CustomerModel::firstOrCreate(
                ["email" => $customerEmail],
                $localCustomerParams
            );


            $package = PackageModel::where("vehicleType", $vehicleType)
                ->where("code", $packageCode)->first();

            if(is_null($package))
                throw new \Exception("Package not found");

            $amount = $package->price;

            if(!is_null($localCustomer->conekta_customer_id)){
                $conektaCustomer = ConektaCustomer::find($localCustomer->conekta_customer_id);
            }else {

//            if($conektaCustomer->total === 0)
                $conektaCustomer = ConektaCustomer::create(array(
                    //                'id' => $request->input("donor.email"),
                    "name" => $request->input("customer.name"),
                    "email" => $customerEmail,
                    "phone" => $request->input("customer.phone"),
                    "payment_sources" => array(
                        array(
                            "type" => "card",
                            "token_id" => $request->input("tokenCard.id")
                        )
                    )
                ));

                $localCustomer->conekta_customer_id = $conektaCustomer["id"];
                $localCustomer->save();
            }

            $plan = $this->getPlan("Tiaraautolavado".$package->code);

            if(is_null($plan))
                $plan = $this->createConektaPlan($request, $package->code, $package->price);

            $conektaSubscription = $conektaCustomer->createSubscription(
                array(
                    'plan' => $plan['id']
                )
            );

            $localSubscriptionParams['idPlan'] = $plan['id'];
            $localSubscriptionParams['token_card'] = $request->input("tokenCard.id");
            $localSubscriptionParams['idCustomer'] = $conektaCustomer['id'];
            $localSubscriptionParams['idSubscription'] = $conektaSubscription['id'];
            $localSubscriptionParams['customer_id'] = $localCustomer->id;
            $localSubscriptionParams['suscription_created_at'] = $this->getDateFromUnifFormat($conektaSubscription['subscription_start']);
            $localSubscriptionParams['billing_cycle_start'] = $this->getDateFromUnifFormat($conektaSubscription['subscription_start']);
            $localSubscriptionParams['billing_cycle_end'] = $this->getDateFromUnifFormat($conektaSubscription['subscription_end']);

            $localSubscriptionParams["subscribed"] = 0;
            $subscriptionSuccess = false;

            $this->log->info("conektaStatusSubscription: ".$conektaSubscription["status"]);

            if(strcasecmp($conektaSubscription["status"], "active") == 0){
                $localSubscriptionParams["subscribed"] = 1;
                $subscriptionSuccess = true;
            }

            $localSubscription = SubscriptionModel::create($localSubscriptionParams);

            $vehicleParams = $request->input("vehicle");

            $vehicleParams["subscription_id"] = $localSubscription->id;
            $vehicleParams["washDays"] = implode(",", $vehicleParams["washDays"]);

            $vehicleInfo = VehicleInformationModel::create($vehicleParams);

            if($subscriptionSuccess){
                Mail::send('email.subscription',
                    [
                        "amount" => "$" . $amount,
                        "packageDescription" => $package->description,
                        "package" => $package->name,
                    ],
                    function($message) use ($request, $customerEmail) {
                        $message->to($customerEmail, $request->input("customer.name"))->subject('Suscripción Tiara Autolavado');
                    }
                );

                Mail::send('email.subscriptionNotify',
                    [
                        "messageContent" => "Hay un nuevo suscriptor para el $package->name ",
                        "customer" => $localCustomer,
                        "vehicle" => $vehicleInfo
                    ],
                    function($message) use ($request, $customerEmail, $package) {
                        $message->to(env('MAIL_SUBSCRIPTION_NOTIFICATION_TO'), 'Tiara Autolavado')
                            ->subject("Nueva suscripción $package->name");
                    }
                );
            }else{
                Mail::send('email.subscriptionNotify',
                    [
                        "messageContent" => "Hubo un intento de suscripción para el $package->name en la cual la tarjeta fue declinada.",
                        "customer" => $localCustomer,
                        "vehicle" => $vehicleInfo
                    ],
                    function($message) use ($request, $customerEmail, $package) {
                        $message->to(env('MAIL_SUBSCRIPTION_NOTIFICATION_TO'), 'Tiara Autolavado')
                            ->subject("Nueva suscripción $package->name");
                    }
                );
            }

            return response()->json(["status" => true, "message" => "Subscripción realizada con éxito"]);
        } catch (ErrorList $errorList) {
            foreach ($errorList->details as & $errorDetail) {
                return response()->json(["status" => false, "message" => $errorDetail->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        } catch (Conekta_Error $e) {
            $this->log->info("Subscription Exception ConektaError".$e->getMessage(). " ".$e->getTraceAsString());
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }catch(\Exception $e){
            $this->log->info("Subscription Exception ".$e->getMessage(). " ".$e->getTraceAsString());
            return response()->json(["status" => false, "message" => "internal error"]);
        }
    }

    private function getDateFromUnifFormat($date){
        return (!is_null($date)) ? gmdate("Y-m-d H:i:s", $date): NULL;
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

    private function createConektaPlan(Request $request, $packageCode, $amount) {
        try {
            $plan = Plan::create(array(
                    "id" => "Tiaraautolavado".$packageCode,
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