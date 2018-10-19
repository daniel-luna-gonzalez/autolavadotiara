<?php

namespace App\Http\Controllers\api\v1\paypal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\api\v1\paypal\Sample;
use App\Donors;

class Donate extends Main {

    public function __construct() {
        $this->arrayCreate = [
            "donorData.name" => 'string|required|min:3',
            'donorData.last_name' => 'string|required|min:3',
            'donorData.mother_last_name' => 'string',
            'donorData.birthday' => 'string|required|date',
            'donorData.email' => 'string|required|email'];

        parent::__construct();
    }

    public function create(Request $request) {
        if (($validate = $this->validate($request, $this->arrayCreate)) !== NULL)
            return $validate;

        if (($donor = Donors::where("email", $request->input("donorData.email"))->first()))
            return response()->json($donor);

        $donor = Donors::create($request->get('donorData'));

        return response()->json($donor);
    }

    public function createBillingPlan(Request $request) {
        
    }

    public function activaPayPalSample() {
        $sample = new Sample();
        return $sample->run();
    }

}
