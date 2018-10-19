<?php

namespace App\Http\Controllers\api\v1;

use App\Donors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DonorsController extends Controller
{
    public function index(Request $request){
        try{
            return response()->json(["status" => true, "donors" =>
                Donors::select(["*", DB::raw("CONCAT(name, ' ', last_name) as fullname")])
                ->where("status" , 1)
                    ->where("anonymous", 0)->get()
            ]);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }
}
