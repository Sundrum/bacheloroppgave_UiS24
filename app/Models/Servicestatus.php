<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicestatus extends Model
{
    use HasFactory;
    protected $table = 'service_status';
    protected $primaryKey = 'service_status_id';
    public $timestamps = false;
}
