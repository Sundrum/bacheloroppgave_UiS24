<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capturetransaction extends Model
{
    use HasFactory;

    protected $table = 'capture_transactions';
    public $timestamps = false;
    
}
