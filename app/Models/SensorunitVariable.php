<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorunitVariable extends Model
{
    protected $table = 'sensorunit_variables';
    protected $primaryKey = 'variable_id';
    public $timestamps = false;
}
