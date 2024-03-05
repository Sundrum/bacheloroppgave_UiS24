<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PaymentsUnits extends Model
{
   use HasFactory;


   protected $table = 'paymentsUnits';
   protected $primaryKey = ['serialnumber', 'payment_id'];


   public static function Join($payment_id){
       $paymentsunits = Subscription::select('*')
           ->where('payments.payment_id', $payment_id)
           ->join('paymentsunits', 'payments.payment_id', '=', 'paymentsunits.payment_id')
           ->join('sensorunits', 'paymentsunits.serialnumber', '=', 'sensorunits.serialnumber')
           ->distinct()
           ->get();
       return $paymentsunits;
   }
}

