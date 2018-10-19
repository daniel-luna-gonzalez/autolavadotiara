<?php

namespace App\Http\Controllers;

use App\libraries\SyncConekta;
use Illuminate\Http\Request;

class ConektaSyncController extends Controller
{
    protected $sync;
    public function __construct(SyncConekta $syncConekta)
    {
        $this->sync = $syncConekta;
    }

    public function sync(){
       return $this->sync->start();
    }
}
