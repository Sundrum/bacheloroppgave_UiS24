<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentsProducts extends Model
{

    protected $table = 'paymentsProducts';
    protected $primaryKey = ['payment_id', 'product_id'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['payment_id', 'product_id'];

    public static function Join($payment_id){
        $paymentsproducts = Payment::select('*')
            ->where('payments.payment_id', $payment_id)
            ->join('paymentsproducts', 'payments.payment_id', '=', 'paymentsproducts.payment_id')
            ->distinct()
            ->get();
        return $paymentsproducts;
    }
}