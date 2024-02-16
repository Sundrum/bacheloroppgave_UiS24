<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id'; // Assuming payment_id is the primary key
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'payment_status',
        'customer_id_ref',
    ];
    public function getStatus()
    {
        switch ($this->subscription_status) {
            case 0:
                return 'Created';
            case 1:
                return 'Cancelled';
            case 2:
                return 'Failed';
            case 3:
                return 'Completed';
        }
    }
}