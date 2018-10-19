<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 27/08/17
 * Time: 2:51 PM
 */

namespace App\Http\Controllers\api\v1\campaign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Campaign;
use Illuminate\Support\Facades\DB;
use App\CampaignDonor;
use App\CampaignOrders;

class Todosxuno extends Controller
{
    public function index(){
        try{
            $campaign = Campaign::where("id",1)->select("*", DB::raw("TIMEDIFF(end_date,now()) AS timeleft"))->first();

            $expectedAmount = $campaign->expected_amount;
            $currentAmount = $campaign->current_amount;
            $timeLeft = $campaign->timeleft;

            return view('includes/campaigns/todosxuno/index',  [
                "expectedAmount" => $expectedAmount,
                "timeLeft" => $timeLeft,
                "endDate" => $campaign->end_date,
                "currentAmount" => $currentAmount,
                "APP_HOST" => env("APP_HOST"),
                "APP_PORT" => env("APP_PORT"),
                "CONEKTA_API_PUBLIC_KEY" => env("CONEKTA_API_PUBLIC_KEY")
            ] );
        }
        catch (\Exception $e){
            return response()->json(["status" => 0, "message" => "Ha ocurrido un error. ".$e->getMessage()]);
        }
    }

    /**
     * Donation did from capture url
     */
    public function captureAdd(Request $request){
        try{
            $idCampaign = (int)$request->input("campaign.id");
            $amount = (float) $request->input("order.amount");

            $donor = CampaignDonor::create($request->get("donor"));

            $orderParams = $request->get("order");
            $orderParams["idDonor"] = $donor->id;
            $orderParams["idCampaign"] = $idCampaign;
            $order = CampaignOrders::create($orderParams);

            Campaign::where('id', $idCampaign)->increment('current_amount', $amount);

            return response()->json(["status" => true, "message" => "Donación registrada"]);
        }catch (\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Donation did from capture url
     */
    public function captureSubs(Request $request){
        try{
            $idCampaign = (int)$request->input("campaign.id");
            $amount = (float) $request->input("order.amount");
            Campaign::where('id', $idCampaign)->decrement('current_amount', $amount);

            return response()->json(["status" => true, "message" => "Decremento realizado"]);
        }catch (\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function counter(){
        try {
            $campaign = Campaign::where("id", 1)->select("*", DB::raw("TIMEDIFF(end_date,now()) AS timeleft"))->first();

            $expectedAmount = $campaign->expected_amount;
            $currentAmount = $campaign->current_amount;
            $timeLeft = $campaign->timeleft;

            return response()->json([
                "status" => true,
                "counter" =>
                    [
                        "expectedAmount" => $expectedAmount,
                        "timeLeft" => $timeLeft,
                        "endDate" => $campaign->end_date,
                        "currentAmount" => $currentAmount,
                        "APP_HOST" => env("APP_HOST"),
                        "APP_PORT" => env("APP_PORT"),
                        "CONEKTA_API_PUBLIC_KEY" => env("CONEKTA_API_PUBLIC_KEY")
                    ]
            ]);
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function storeimageFb(Request $request){
        try{
            $imagedata = base64_decode($_POST['imgdata']);
            $filename = md5(uniqid(rand(), true));
            //path where you want to upload image
            $file = public_path().'/uploads/'.$filename.'.png';
            $imageurl  ='/uploads/'.$filename.'.png';

            file_put_contents($file,$imagedata);

            return response()->json(["status" => true, "url" => $imageurl]);
        } catch (\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }

    }
}