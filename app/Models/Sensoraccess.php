<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensoraccess extends Model
{
    protected $table = 'sensoraccess';
    protected $primaryKey = 'sensoraccess_id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'serialnumber', 'changeallowed',
    ];
}
