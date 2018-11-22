<?php

namespace App\Http\Controllers\report;

use App\CustomerModel;
use App\SubscriptionModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use App\libraries\Utils;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Create and download an excel report of subscription.
     * @param Request $request
     */
    public function download(Request $request){
        $from = $request->input("from");
        $until = $request->input("until");

        $subscriptions = $this->getDataBetwenDates($from, $until);

        Excel::create('Suscripciones', function($excel) use ($subscriptions) {
            $excel->sheet('Hoja 1', function($sheet) use ($subscriptions) {

                $rows = array();
                $total = 0;

                if (count($subscriptions) > 0) {
                    foreach ($subscriptions as $subscription) {
                        $customer =  $subscription->customer;
                        $package = $subscription->package;
                        $vehicle = $subscription->vehicle;

                        $row = array();
                        $row["id"] = $subscription->id;
                        $row["Nombre"] = $customer->fullname;
                        $row["Monto"] = (float)$subscription->amount;
                        $row["Fecha de registro"] = $subscription->created_at;
                        $row["Paquete"] = $package->name;
                        $row["Dias Lavado"] = $vehicle->washDays;
//                        $row["No cajon"] = $vehicle->Nocajon;
                        $row["Tipo Vehiculo"] = $vehicle->vehicleType;
                        $row["marca"] = $vehicle->marca;
                        $row["modelo"] = $vehicle->modelo;
                        $row["color"] = $vehicle->color;
                        $row["placas"] = $vehicle->placas;
                        $row["Departamento"] = $vehicle->depto;
                        $row["Nivel Estacionamiento"] = $vehicle->nivelEstacionamiento;
                        $row["Tipo Servicio"] = $vehicle->serviceType;
                        $row["Horario"] = $vehicle->horario;

                        //$row["Antigüedad"] = ($subscription["status"]) ? $subscription["antiquity"] : $subscription["billing_period"];
                        //$row["Activo"] = $subscriber["status"] ? "Si" : "No";

                        $rows[] = $row;

                        $total+= (float)$subscription->amount;
                    }

                    $rows[] = array(
                        "id" => "",
                        "Nombre" => "Total",
                        "Monto" => $total,
                        "Fecha de registro" => "",
                        "Paquete" => "",
                        );
                } else
                    $rows = array("No existen suscriptores");

                $sheet->fromArray($rows);
            });
        })
            ->download('xlsx');
    }

    private function getDataBetwenDates($from = null, $until = null){
        $query = SubscriptionModel::select("*",
            DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as created')
            //DB::raw("(TIMESTAMPDIFF(MONTH, suscription_created_at, now())+1) as antiquity"),
            //DB::raw("(TIMESTAMPDIFF(MONTH, suscription_created_at, canceled_at)+1) as billing_period"),
        )->with(["customer" => function($subquery){
            $subquery->select('*', DB::raw("CONCAT(name,' ',last_name, ' ', mother_last_name) as fullname"));
        }])
        ->with("vehicle")
        ->with("package");

        if(Utils::validateDate($from, "Y/m/d") === true and !Utils::validateDate($until, "Y/m/d"))
            $query->whereDate("created_at", ">=", $from);

        if(Utils::validateDate($from, "Y/m/d") and Utils::validateDate($until, "Y/m/d"))
            $query->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $until);

        if(!Utils::validateDate($from, "Y/m/d") and Utils::validateDate($until, "Y/m/d"))
            $query->whereDate("created_at", "<=", $until);

        return $query->get();
    }
}
