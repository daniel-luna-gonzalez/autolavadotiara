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
class VehicleInformationModel  extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "Nocajon",
        "color",
        "depto",
        "marca",
        "modelo",
        "nivelEstacionamiento",
        "placas",
        "vehicleType",
        "washDays"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
