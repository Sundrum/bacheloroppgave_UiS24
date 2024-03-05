<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Sensorunit extends Model
{
    protected $table = 'sensorunits';
    protected $primaryKey = 'sensorunit_id';
    public $timestamps = false;

    public static function getSensorunits() {
        $sensorunit = DB::table('sensorunits')
                    ->join('customer', 'customer_id_ref', '=', 'customer_id')
                    ->join('products', 'product_id_ref', '=', 'product_id')
                    ->orderby('public.sensorunits.serialnumber', 'ASC')
                    ->get();
        return $sensorunit;
    }

    public static function getSensorunitsForCustomer($customerId) {
        $sensorunits = DB::table('sensorunits')
            ->where('customer.customer_id', '=', $customerId)
            ->join('customer', 'customer_id_ref', '=', 'customer_id')
            ->join('products', 'product_id_ref', '=', 'product_id')
            ->orderBy('public.sensorunits.serialnumber', 'ASC')
            ->get();
    
        return $sensorunits;
    }

    public static function getSensorUnitsWithSubscriptionForCustomer($customerId)
    {
        $sensorUnits = DB::table('sensorunits')
            ->join('products', 'sensorunits.product_id_ref', '=', 'products.product_id')
            ->where('sensorunits.customer_id_ref', $customerId)
            ->whereNotNull('products.subscription_price')
            ->orderBy('public.sensorunits.serialnumber', 'ASC')
            ->get();

        return $sensorUnits;
    }
    

    public static function getUnit($id) {
        $sensorunit = DB::table('sensorunits')
                    ->where('sensorunits.sensorunit_id', $id)
                    ->join('customer', 'customer_id_ref', '=', 'customer_id')
                    ->join('products', 'product_id_ref', '=', 'product_id')
                    ->first();
        return $sensorunit;
    }
}
