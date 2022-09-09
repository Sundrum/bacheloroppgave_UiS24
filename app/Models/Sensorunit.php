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

    public static function getUnit($id) {
        $sensorunit = DB::table('sensorunits')
                    ->where('sensorunits.sensorunit_id', $id)
                    ->join('customer', 'customer_id_ref', '=', 'customer_id')
                    ->join('products', 'product_id_ref', '=', 'product_id')
                    ->first();
        return $sensorunit;
    }
}
