<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Irrigationrun extends Model
{
    protected $table = 'irrigation_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
}
