<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignFiscalEntity extends Model
{
    protected $table = "campaign_fiscalEntity";
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
