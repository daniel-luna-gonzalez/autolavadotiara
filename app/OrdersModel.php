<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdersModel extends Model
{
    protected $table = "orders";
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
        'cardToken',
        'plan_id',
        'subscription_id',
        'credit_card_id',
        'anonymous',
        'subscription_status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
