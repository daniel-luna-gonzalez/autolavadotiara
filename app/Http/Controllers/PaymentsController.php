<?php

namespace App\Http\Controllers;

use App\libraries\PaymentsLibrary;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    protected $paymentLibrary;
    public function __construct(PaymentsLibrary $paymentLibrary)
    {
        $this->paymentLibrary = $paymentLibrary;
    }

    public function register(Request $request){
        return $this->paymentLibrary->register($request);
    }
}
