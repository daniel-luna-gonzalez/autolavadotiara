<?php
namespace App\libraries;

use App\Causes;
use App\CausesDonor;
use App\CustomerModel;
use App\Http\Controllers\api\v1\conekta\CreditCardPayment;
use Conekta\ApiError;
use Conekta\Conekta;
use Conekta\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Excel;
use App\libraries\Utils;
use Conekta\Customer;

class SyncConekta
{
    public function start(){
        try{
            logger("Sync conekta started");
            $this->conekta();
            logger("Sync conekta finished");
        }catch(\Exception $e) {
            logger("failed sync conekta: " . $e->getMessage());
            echo "SyncConekta:: Exception:: ".$e->getMessage();

        }
    }

    private function getDateFromUnifFormat($date){
        return gmdate("Y-m-d H:i:s", $date);
    }

    private function getUserFromConekta($customer){
        try{
            Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
            Conekta::setApiVersion("2.0.0");
            $customerConekta = Customer::find($customer);
            return $customerConekta;
        }catch (\Exception $e){
            return null;
        }
    }

    public function conekta()
    {
        try {
            Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
            Conekta::setApiVersion("2.0.0");

            $customers = Customer::where(["limit" => 200]);
            $this->processCustomers($customers);

            $amount = true;

            while ($amount){
                $customersConekta = $customers->next(["limit" => 200]);
                if(count($customersConekta) > 0){
                    $this->processCustomers($customersConekta);
                }
                else{
                    $amount = false;
                }
            }

        }
        catch (\Exception $e) {
            logger("SyncConekta::Exception ".$e->getMessage());
            return false;
        }

        return true;
    }

    private function processCustomers($customers){
        foreach ($customers as $customerConekta) {
            if (!isset($customerConekta->subscription->plan_id)){
                echo "<br>$customerConekta->email not has idPlan";
                logger("$customerConekta->email not has idPlan");
                continue;
            }

            if(!strcasecmp($customerConekta->subscription->status, "active") == 0){
                echo "<br>$customerConekta->email no active";
                logger("$customerConekta->email no active");
                continue;
            }

            $plan = $this->getPlan($customerConekta->subscription->plan_id);

            if (is_null($plan)){
                echo "<br>$customerConekta->email no plan";
                continue;
            }

            $dbCustomer = CustomerModel::where("email", $customerConekta->email)->first();


            if (is_null($dbCustomer)){
                $dbCustomerData = $this->createUserDb($plan, $customerConekta);
                $dbCustomer = CustomerModel::create($dbCustomerData);

                if (is_null($dbCustomer)){
                    echo $dbCustomerData["email"] . " no creado";
                    continue;
                }

                $cause = Causes::firstOrCreate(["name" => "No asignado", "description" => "Causa no asignada"])->first();

                CausesDonor::create(array("idCause" => $cause['id'], "idDonor" => $donor->id));
                echo "causa asignada a ".$dbCustomerData["email"];
            }

            $dbCustomer->suscription_created_at = (isset($customerConekta->subscription->created_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->created_at) : null;
            $dbCustomer->canceled_at = (isset($customerConekta->subscription->canceled_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->canceled_at) : null;
            $dbCustomer->paused_at = (isset($customerConekta->subscription->paused_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->paused_at) : null;
            $dbCustomer->billing_cycle_start = (isset($customerConekta->subscription->billing_cycle_start)) ? $this->getDateFromUnifFormat($customerConekta->subscription->billing_cycle_start) : null;


            if(!is_null($plan))
                $dbCustomer->amount = ((float)$plan->amount > 0) ? $plan->amount / 100 : 0;

            if($customerConekta->subscription->status != "active"){
                $dbCustomer->status = 0;
                $dbCustomer->billing_cycle_end = (isset($customerConekta->subscription->billing_cycle_end)) ? $this->getDateFromUnifFormat($customerConekta->subscription->billing_cycle_end) : null;
            }
            else{
                $dbCustomer->status = 1;
            }

            $dbCustomer->save();
        }
    }

    private function createUserDb($plan, $customerConekta){
        return [
            "idCustomer" => $customerConekta->id,
            "idPlan" => $plan->id,
            "idSubscription" => $customerConekta->subscription->id,
            "email" => $customerConekta->email,
            "name" => $customerConekta->name,
            "last_name" => "",
            "birthday" => "",
            "mother_last_name" => "",
            "phone" => (isset($customerConekta->phone)) ? $customerConekta->phone : null,
            "amount" => ((float)$plan->amount > 0) ? $plan->amount / 100 : 0,
            "created_at" => $this->getDateFromUnifFormat($customerConekta->subscription->created_at),
            "suscription_created_at" => (isset($customerConekta->subscription->created_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->created_at) : null,
            "canceled_at" => (isset($customerConekta->subscription->canceled_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->canceled_at) : null,
            "paused_at" => (isset($customerConekta->subscription->paused_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->paused_at) : null,
            "billing_cycle_start" => (isset($customerConekta->subscription->billing_cycle_start)) ? $this->getDateFromUnifFormat($customerConekta->subscription->billing_cycle_start) : null,
        ];
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
}