<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignDonor extends Model
{
    protected $table = "campaign_donor";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idConekta',
        'name',
        'last_name',
        'mother_last_name',
        'email',
        'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];
}
