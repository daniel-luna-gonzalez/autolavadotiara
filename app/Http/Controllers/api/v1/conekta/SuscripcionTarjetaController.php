<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\api\v1\conekta;

/**
 * Description of SuscripcionTarjeta
 *
 * @author Daniel Luna <dluna>
 */
use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\libraries\SubscriptionLibrary;

class SuscripcionTarjetaController extends Controller {

    protected $subscriptionLibrary;

    public function __construct(SubscriptionLibrary $subscriptionLibrary)
    {
        $this->subscriptionLibrary = $subscriptionLibrary;
    }

    public function create(SubscriptionRequest $request) {
       return $this->subscriptionLibrary->create($request);
    }
}
