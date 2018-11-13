<?php
/**
 * Created by PhpStorm.
 * User: ulises.luna
 * Date: 11/6/2018
 * Time: 11:12 AM
 */

namespace App\libraries;


use Illuminate\Http\Request;
use Psr\Log\LoggerInterface;

class PaymentsLibrary
{
    protected $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function register(Request $request){
        try{
            $this->log->info(__CLASS__." ".__FUNCTION__." ".json_encode($request->all()));
        }catch(\Exception $e){
            $this->log->info(__CLASS__." ".__METHOD__." Exception ".$e->getMessage()." ".$e->getTraceAsString());
        }
    }
}