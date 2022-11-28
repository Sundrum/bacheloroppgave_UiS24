<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicepersons extends Model
{
    use HasFactory;
    protected $table = 'service_persons';
    protected $primaryKey = 'service_person_id';
    public $timestamps = false;

}
