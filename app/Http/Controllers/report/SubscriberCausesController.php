<?php

namespace App\Http\Controllers\report;

use App\Causes;
use App\CustomerModel;
use Conekta\Conekta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\libraries\Utils;
use Excel;

class SubscriberCausesController extends Controller
{

    public function __construct()
    {
        Conekta::setApiKey(env("CONEKTA_API_PRIVATE_KEY"));
        Conekta::setApiVersion("2.0.0");
    }

    public function index(Request $request){
        try {
            $idCause = (int)$request->input("idCause");

            if(!(int)$idCause > 0)
                return view("report.customercauses", ["customers" => [], "causes" => Causes::all(), "cause" => null]);

            $cause = Causes::where("id", $idCause)->first();

            $customers = $this->getDataBetwenDates($idCause, $request->input("from"), $request->input("until"));

            $customers = $customers->get()->toArray();

            return view("report.customercauses", ["customers" => $customers, "causes" => Causes::all(), "cause" => $cause]);
        }
        catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }

    }

    private function getDataBetwenDates($idCause, $from = null, $until = null){
        $query = CustomerModel::select("*",
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

        $query->whereHas("causes", function($subquery) use($idCause){
            $subquery->where("idCause", $idCause);
        })
            ->withCount('causes')
        ->where("status", 1);

        return $query;
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

    public function download(Request $route){
        $from = $route->input("from");
        $until = $route->input("until");
        $idCause = $route->input("idCause");

        $subscribers = $this->getDataBetwenDates($idCause, $from, $until);
        $subscribers = $subscribers->get()->toArray();

        Excel::create('Suscriptor Causa', function($excel) use ($subscribers) {
            $excel->sheet('Suscriptor Causa', function($sheet) use ($subscribers) {

                $rows = array();
                $total = 0;

                if (count($subscribers) > 0) {
                    foreach ($subscribers as $subscriber) {
                        $row = array();
                        $row["id"] = $subscriber["id"];
                        $row["Nombre"] = $subscriber["fullname"];
                        $row["Monto"] = round((float)$subscriber["amount"] / $subscriber["causes_count"]);
                        $row["Fecha de registro"] = $subscriber["created"];
                        $row["Antigüedad"] = ($subscriber["status"]) ? $subscriber["antiquity"] : $subscriber["billing_period"];
                        $row["Activo"] = $subscriber["status"] ? "Si" : "No";

                        $rows[] = $row;

                        $total+= round((float)$subscriber["amount"] / $subscriber["causes_count"]);
                    }

                    $rows[] = array("id" => "", "Nombre" => "", "Monto" => $total, "Fecha de registro" => "", "Antigüedad" => "", "Activo" => "");
                } else
                    $rows = array("Seleccionar una causa");

                $sheet->fromArray($rows);
            });
        })
            ->download('xlsx');
    }

}
