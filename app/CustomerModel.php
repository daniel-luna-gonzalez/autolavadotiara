<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;
use \Illuminate\Database\Eloquent\Model;
/**
 * Description of Donors
 *
 * @author Daniel Luna <dluna>
 */
class CustomerModel  extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idPlan', 
        'idSubscription',
        'idCustomer',
        'token_card',
        'name', 
        'last_name', 
        'mother_last_name' , 
        'email', 
        'birthday',
        'phone',
        "created_at"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public function causes(){
        return $this->belongsToMany("App\Causes", "causes_donor", "idDonor", "idCause");
    }
}
