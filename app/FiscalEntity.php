<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;
use \Illuminate\Database\Eloquent\Model;

/**
 * Description of FiscalEntity
 *
 * @author danielunag
 */
class FiscalEntity extends Model
{
    protected $table = "fiscalEntity";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "idDonor",
        "name",
        "tax_id",
        "street1",
        "street2",
        "street3",
        "state",
        "zip",
        "city"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
