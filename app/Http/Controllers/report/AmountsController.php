<?php

namespace App\Http\Controllers\report;

use App\Causes;
use App\CustomerModel;
use App\libraries\Utils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class AmountsController extends Controller
{
    public function index(Request $request){
        $data = $this->getCausesTotals($request);

        return view('report.amounts', ["data" => $data, "total" => $data["total"]] );
    }

    private function getCausesTotals($request){
        $from = $request->input("from");
        $until = $request->input("until");

        $amountCauses = array();
        $total = 0;
        $donors = $this->getDataBetwenDates($from, $until);

        $causes = Causes::all();

        foreach ($causes as $cause){
            $amountCauses[$cause->id] = $cause->toArray();
            $amountCauses[$cause->id]["total"] = 0;
        }

        $causesGrandTotal = 0;

        foreach ($donors as $donor){
            if(count($donor->causes->toArray()) === 0){
//                echo "<pre>"; var_dump($donor->toArray()); die();
                continue;
            }
//            if($donor->status != 1)
//                continue;

            $total+= (float)$donor->amount;
            $causeSegmented = (count($donor->causes->toArray()) > 0 ) ? count($donor->causes->toArray()) : 1;
            $causeTotal = (float)$donor->amount / $causeSegmented;

            foreach ($donor->causes as $cause){
                $amountCauses[$cause->id]["total"]+= $causeTotal;
                $causesGrandTotal+= $causeTotal;
            }
        }

        $grandTotal = 0;

        foreach ($amountCauses as $key => $cause){
            $amountCauses[$key]["percent"] = ((float)$cause["total"] * 100) / $total;
            $grandTotal+=(float)$cause["total"];
        }

        return ["causes" => $amountCauses, "total" => $total];
    }

    private function getDataBetwenDates($from = null, $until = null){
        $donors = CustomerModel::with(["causes"]);

        if(Utils::validateDate($from, "Y/m/d") === true and !Utils::validateDate($until, "Y/m/d"))
            $donors->whereDate("created_at", ">=", $from);

        if(Utils::validateDate($from, "Y/m/d") and Utils::validateDate($until, "Y/m/d"))
            $donors->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $until);

        if(!Utils::validateDate($from, "Y/m/d") and Utils::validateDate($until, "Y/m/d"))
            $donors->whereDate("created_at", "<=", $until);

        return $donors->get();
    }

    public function download(Request $request){
        $from = $request->input("from");
        $until = $request->input("until");

        $subscribers = $this->getCausesTotals($request);

        Excel::create('Montos', function($excel) use ($subscribers) {
            $excel->sheet('Montos', function($sheet) use ($subscribers) {
                $rows = array();
                $total = 0;

                if (count($subscribers["causes"]) > 0) {
                    foreach ($subscribers["causes"] as $subscriber) {
                        $row = array();
                        $row["Nombre"] = $subscriber["name"];
                        $row["Monto"] = $subscriber["total"];
                        $row["Descripción"] = $subscriber["description"];

                        $rows[] = $row;

                        $total+= (float) $subscriber["total"];
                    }

                    $rows[] = array("Nombre" => "", "Monto" => $total, "Descripción" => "");
                } else
                    $rows = array("No existen registros");

                $sheet->fromArray($rows);
            });
        })
            ->download('xlsx');
    }
}
