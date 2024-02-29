<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
    ];
    public function getStatus()
    {
        switch ($this->subscription_status) {
            case 0:
                return 'Inactive';
            case 1:
                return 'Not Paid';
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
}



