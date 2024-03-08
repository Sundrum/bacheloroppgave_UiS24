<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_payments';
    protected $primaryKey = ['subscription_id', 'payment_id'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['subscription_id', 'payment_id'];



    public static function Join($customer_id){
        $subscriptionpayment = Subscription::select('*')
            ->where('subscriptions.customer_id_ref', $customer_id)
            ->join('subscriptions_payments', 'subscriptions.subscription_id', '=', 'subscriptions_payments.subscription_id')
            ->join('payments', 'subscriptions_payments.payment_id', '=', 'payments.payment_id')
            ->distinct()
            ->get();
        return $subscriptionpayment;
    }
}

