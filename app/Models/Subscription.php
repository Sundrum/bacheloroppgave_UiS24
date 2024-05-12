<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $primaryKey = 'subscription_id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'interval',
        'serialnumber',
        'subscription_status',
        'customer_id_ref',
        'next_payment'
    ];
    public function getStatus()
    {
        switch ($this->subscription_status) {
            case 0:
                return 'Inactive';
            case 1:
                return 'Canceled';
            case 2:
                return 'Active';
            default:
                return 'Unknown';
        }
    }
    public static function getPaymentDate($lastPaymentDate, $interval)
    {
        $lastPaymentDate = Carbon::parse($lastPaymentDate);
        list($hours, $minutes, $seconds) = explode(':', $interval);
        $nextPaymentDate = $lastPaymentDate->addHours($hours)->addMinutes($minutes)->addSeconds($seconds);
        $formatted = $nextPaymentDate->format('F j, Y');
        return $formatted;
    }

    public static function getSubscriptionsForCustomer($customerId) {
        $subscriptions = Subscription::select('*')
            ->where('subscriptions.customer_id_ref', '=', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $subscriptions;
    }
    public static function getByCustomerIdAndSerialNumber($customerId, $serialNumber)
    {
        return self::where('customer_id_ref', $customerId)
                   ->where('serialnumber', $serialNumber)
                   ->get();
    }

    public static function getSubscriptionsForCustomerJoinAll($customerId) {
        $subscriptions = Subscription::where('subscriptions.customer_id_ref', '=', $customerId)
            ->orderBy('subscriptions.created_at', 'desc')
            ->join('customer', 'subscriptions.customer_id_ref', '=', 'customer.customer_id')
            ->leftJoin('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->leftJoin('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->leftJoin('subscriptions_payments', 'subscriptions.subscription_id', '=', 'subscriptions_payments.subscription_id')
            ->leftJoin('paymentsProducts', 'subscriptions_payments.payment_id', '=', 'paymentsProducts.payment_id')
            ->leftJoin('products AS payment_products', 'paymentsProducts.product_id', '=', 'payment_products.product_id')
            ->select('subscriptions.*', 'payment_products.*')
            ->get();
        return $subscriptions;
    }
    public static function getSubscriptionsForCustomerJoinAllDistinctSN($customerId) {
        //Sensors with serialnumber
        $subscriptions1 = Subscription::where('subscriptions.customer_id_ref', '=', $customerId)
            ->orderBy('subscriptions.created_at', 'desc')
            ->join('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->join('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->get();
        //Ordered sensors (No serialnumber)
        $subscriptions2 = Subscription::where('subscriptions.customer_id_ref', '=', $customerId)
            ->orderBy('subscriptions.created_at', 'desc')
            ->join('subscriptions_payments', 'subscriptions.subscription_id', '=', 'subscriptions_payments.subscription_id')
            ->join('paymentsProducts', 'subscriptions_payments.payment_id', '=', 'paymentsProducts.payment_id')
            ->join('products', 'paymentsProducts.product_id', '=', 'products.product_id')
            ->whereNull('subscriptions.serialnumber')
            ->get();
        $subscriptions = $subscriptions1->unique('serialnumber')->merge($subscriptions2);
        return $subscriptions;
    }
    
    public static function getSubscriptionsForCustomerJoinAllDistinctSNfunkerikke($customerId) {
        $subscriptions = Subscription::where('subscriptions.customer_id_ref', '=', $customerId)
            ->select('subscriptions.*', 'customer.*', 'sensorunits.*', 'products.product_name', 'payment_products.product_name AS payment_product_name', 'paymentsProducts.*')
            ->leftJoin('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->leftJoin('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->leftJoin('subscriptions_payments', 'subscriptions.subscription_id', '=', 'subscriptions_payments.subscription_id')
            ->leftJoin('paymentsProducts', 'subscriptions_payments.payment_id', '=', 'paymentsProducts.payment_id')
            ->leftJoin('products AS payment_products', 'paymentsProducts.product_id', '=', 'payment_products.product_id')
            ->leftJoin('customer', 'subscriptions.customer_id_ref', '=', 'customer.customer_id')
            ->leftJoin('subscriptions AS s2', function ($join) {
                $join->on('sensorunits.serialnumber', '=', 's2.serialnumber')
                    ->whereRaw('s2.created_at > subscriptions.created_at');
            })
            ->whereNull('s2.subscription_id')
            ->orderBy('subscriptions.created_at', 'desc')
            ->get();
        // Only store the first entry of all duplicate subscription_ids
        $subscriptions = $subscriptions->unique('subscription_id');
        // Return the subscriptions
        return $subscriptions;
    }

    public static function chargeSubscriptionData($date){
        $subscriptions = Subscription::where('subscription_status', 2)
            ->whereDate('next_payment', $date)
            ->select('subscriptions.*', 'products.*') // Select specific columns from subscriptions and products tables
            ->Join('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->Join('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->get();
        return $subscriptions;
    }

    public static function cancelSubscriptionData($date){
        $subscriptions = Subscription::where('subscription_status', 1)
            ->whereDate('next_payment', $date)
            ->select('subscriptions.*', 'products.*') // Select specific columns from subscriptions and products tables
            ->Join('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->Join('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->get();
        return $subscriptions;
    }

    public static function outdatedSubscriptionData($date) {
        $subscriptions = Subscription::where('subscription_status', '!=', 0)
            ->whereRaw('next_payment < ?', [$date])
            ->select('subscriptions.*', 'products.*')
            ->join('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->join('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->get();
    
        return $subscriptions;
    }
    
    
}



