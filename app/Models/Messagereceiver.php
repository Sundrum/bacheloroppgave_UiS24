<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messagereceiver extends Model
{
    use HasFactory;
    protected $table = 'message_receivers';
    protected $primaryKey = 'message_user_id';

}
