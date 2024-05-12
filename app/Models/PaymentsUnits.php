<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentsUnits extends Model
{
   use HasFactory;


   protected $table = 'paymentsUnits';
   protected $primaryKey = ['serialnumber', 'payment_id'];
   public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['payment_id', 'product_id'];


   public static function Join($payment_id){
       $paymentsunits = Subscription::select('*')
           ->where('payments.payment_id', $payment_id)
           ->join('paymentsunits', 'payments.payment_id', '=', 'paymentsunits.payment_id')
           ->join('sensorunits', 'paymentsunits.serialnumber', '=', 'sensorunits.serialnumber')
           ->distinct()
           ->get();
       return $paymentsunits;
   }
   public static function firstDistinctPaymentUnit($paymentId){
        $paymentUnit = self::where('payment_id', $paymentId)->first();
        return $paymentUnit;
    }
}

