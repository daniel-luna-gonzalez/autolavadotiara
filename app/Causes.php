<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Causes extends Model
{
    protected $table = "causes";

    protected $fillable = ["name", "description"];
}
