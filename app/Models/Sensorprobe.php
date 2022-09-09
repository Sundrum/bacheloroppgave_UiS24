<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensorprobe extends Model
{
    protected $table = 'sensorprobes';
    protected $primaryKey = 'sensorprobes_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sensorprobes_number', 'unittype_id_ref', 'product_id_ref', 
        'hidden', 'sensorprobes_alert_hidden',
    ];
}
