<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customervariables extends Model
{
    protected $table = 'customer_variables';
    protected $primaryKey = 'customervariable_id';
    public $timestamps = false;
}
