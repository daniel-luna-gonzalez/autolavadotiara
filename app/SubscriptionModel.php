<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    protected $table = "subscription";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "customer_id",
        "package_id",
        "idPlan",
        "idSubscription",
        "token_card",
        "idCustomer",
        "suscription_created_at",
        "canceled_at",
        "paused_at",
        "billing_cycle_start",
        "billing_cycle_end",
        "amount",
        "subscribed",
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function customer(){
        return $this->hasOne(CustomerModel::class, "id", "customer_id");
    }

    public function package(){
        return $this->hasOne(PackageModel::class, "id", "package_id");
    }

    public function vehicle(){
        return $this->hasOne(VehicleInformationModel::class, "subscription_id", "id");
    }
}
