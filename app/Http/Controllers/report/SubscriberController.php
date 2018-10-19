<?php

namespace App\Http\Controllers\report;

use App\Donors;
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
use App\libraries\SyncConekta;

class SubscriberController extends Controller
{
    protected $SyncConekta;
    public function __construct(SyncConekta $syncConekta)
    {
        $this->SyncConekta = $syncConekta;
    }

    public function index(Request $route){
        $from = $route->input("from");
        $until = $route->input("until");

        $query = $this->getDataBetwenDates($from, $until);

        return view('report.subscriber', ["subscribers" => $query] );
    }

    public function download(Request $route){
        $from = $route->input("from");
        $until = $route->input("until");

        $subscribers = $this->getDataBetwenDates($from, $until);

        Excel::create('Suscriptores', function($excel) use ($subscribers) {
            $excel->sheet('Suscriptores', function($sheet) use ($subscribers) {

                $rows = array();
                $total = 0;

                if (count($subscribers) > 0) {
                    foreach ($subscribers as $subscriber) {
                        $row = array();
                        $row["id"] = $subscriber["id"];
                        $row["Nombre"] = $subscriber["fullname"];
                        $row["Monto"] = $subscriber["amount"];
                        $row["Fecha de registro"] = $subscriber["created"];
                        $row["Antigüedad"] = ($subscriber["status"]) ? $subscriber["antiquity"] : $subscriber["billing_period"];
                        $row["Activo"] = $subscriber["status"] ? "Si" : "No";

                        $rows[] = $row;

                        $total+= (float) $subscriber["amount"];
                    }

                    $rows[] = array("id" => "", "Nombre" => "", "Monto" => $total, "Fecha de registro" => "", "Antigüedad" => "", "Activo" => "");
                } else
                    $rows = array("No existen suscriptores");

                $sheet->fromArray($rows);
            });
        })
            ->download('xlsx');
    }

    private function getDataBetwenDates($from = null, $until = null){
        $query = Donors::select("*",
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created'),
            DB::raw("(TIMESTAMPDIFF(MONTH, suscription_created_at, now())+1) as antiquity"),
            DB::raw("(TIMESTAMPDIFF(MONTH, suscription_created_at, canceled_at)+1) as billing_period"),
            DB::raw("CONCAT(name,' ',last_name, ' ', mother_last_name) as fullname")
        );

        if(Utils::validateDate($from, "Y/m/d") === true and !Utils::validateDate($until, "Y/m/d"))
            $query->whereDate("created_at", ">=", $from);

        if(Utils::validateDate($from, "Y/m/d") and Utils::validateDate($until, "Y/m/d"))
            $query->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $until);

        if(!Utils::validateDate($from, "Y/m/d") and Utils::validateDate($until, "Y/m/d"))
            $query->whereDate("created_at", "<=", $until);

        $donorsEloquent = $query->get();

        $donors = $this->removeSubscribersWithoutCauses($donorsEloquent);

        return $donors;
    }

    public function removeSubscribersWithoutCauses($donorsEloquent){
        $donors = array();

        foreach ($donorsEloquent as $donor){
            if(count($donor->causes) == 0)
                continue;

            $donors[] = $donor;
        }
        return $donors;
    }

    public function sync(Request $request){
        $customersConekta = Customer::where(["limit" => 200]);

        foreach ($customersConekta as $customerCustomer){
            $customer = Donors::where("email", $customerConekta->email)->first();
            $customerConekta = $this->getUserFromConekta($customer->idCustomer);

            if(is_null($customerConekta))
                continue;

            $customer->suscription_created_at = (isset($customerConekta->subscription->created_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->created_at) : null;
            $customer->canceled_at = (isset($customerConekta->subscription->canceled_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->canceled_at) : null;
            $customer->paused_at = (isset($customerConekta->subscription->paused_at)) ? $this->getDateFromUnifFormat($customerConekta->subscription->paused_at) : null;
            $customer->billing_cycle_start = (isset($customerConekta->subscription->billing_cycle_start)) ? $this->getDateFromUnifFormat($customerConekta->subscription->billing_cycle_start) : null;

            if(!isset($customerConekta->subscription->plan_id) )
                continue;

            $plan = Plan::find($customerConekta->subscription->plan_id);

            if(!is_null($plan))
                $customer->amount = ((float)$plan->amount > 0) ? $plan->amount / 100 : 0;

            if($customerConekta->subscription->status != "active"){
                $customer->status = 0;
                $customer->billing_cycle_end = (isset($customerConekta->subscription->billing_cycle_end)) ? $this->getDateFromUnifFormat($customerConekta->subscription->billing_cycle_end) : null;
            }
            else{
                $customer->status = 1;
            }

            $customer->save();
        }

        return redirect()->back();
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

    /**
     * Upgrade
     * @param Request $request
     */
    public function updateAmounts(Request $request){
        $ccp = new CreditCardPayment();
        $customers = Donors::all();

        foreach ($customers as $cust){
            $plan = $ccp->getCustomerPlan($cust->idCustomer);

            if(is_object($plan)){
                $localCust = Donors::where("id", $cust->id)->first();

                if(is_object($localCust)){
                    $localCust->amount = (float) $plan->amount / 100;
                    $localCust->status = 1;
                    $localCust->save();
                }
            }
        }
    }

    public function getall(Request $request){
        Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
        Conekta::setApiVersion("2.0.0");

        $customers = Customer::all();

        foreach ($customers as $customer){
            if(!isset($customer->subscription->plan_id) or $customer->subscription != "active")
                echo "<br>no valido: $customer->email";
            else
                echo "<br>valido $customer->email ";
        }
    }

    public function conekta()
    {
        try {
            return $this->SyncConekta->start();

            Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
            Conekta::setApiVersion("2.0.0");
            $customers = Customer::where(["limit" => 200]);

            foreach ($customers as $customerConekta) {
                if (!isset($customerConekta->subscription->plan_id)){
                    echo "<br>$customerConekta->email not has idPlan";
                    continue;
                }

                if(!strcasecmp($customerConekta->subscription->status, "active") == 0){
                    echo "<br>$customerConekta->email no active";
                    continue;
                }

                $plan = $this->getPlan($customerConekta->subscription->plan_id);

                if (is_null($plan))
                    continue;

                $dbCustomer = Donors::where("email", $customerConekta->email)->first();

                if (is_null($dbCustomer)){
                    $dbCustomerData = $this->createUserDb($plan, $customerConekta);
                    $dbCustomer = Donors::create($dbCustomerData);

                    if (!is_null($dbCustomer))
                        echo "creado: $dbCustomer->email <br>";
                    else
                        echo $dbCustomerData["email"] . " no creado<br>";
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

                return redirect()->back();
            }
        }
        catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
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
