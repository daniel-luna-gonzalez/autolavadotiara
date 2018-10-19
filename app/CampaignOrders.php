<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignOrders extends Model
{
    protected $table = "campaing_orders";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idDonor',
        'amount',
        'idCampaign',
        'idConekta',
        'cardToken'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
