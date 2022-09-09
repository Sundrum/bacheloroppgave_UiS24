<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;

class SensorunitController extends Controller
{
    public function connect($id) {
        return $id;
    }

    public static function getSensorunit($id) {
        return view('admin.sensorunit.edit');
    }
    
    public static function getSensorunits($action) {
        $data = Unit::getSensorunits();
        $count_data = count($data);
        $sorted = array();
        for ($i = 0; $i < $count_data; $i++) {

            if (isset($data[$i]['serialnumber'])) {
                $sorted[$i][0] = trim($data[$i]['serialnumber']);
            } else {
                $sorted[$i][0] = '-';
            }

            if (isset($data[$i]['sensorunit_location'])) {
                $sorted[$i][1] = trim($data[$i]['sensorunit_location']);
            } else {
                $sorted[$i][1] = '-';
            }

            if (isset($data[$i]['customer_name'])) {
                $sorted[$i][2] = trim($data[$i]['customer_name']);
            } else {
                $sorted[$i][2] = '-';
            }

            if (isset($data[$i]['product_name'])) {
                $sorted[$i][3] = trim($data[$i]['product_name']);
            } else {
                $sorted[$i][3] = '-';
            }

            if (isset($data[$i]['sensorunit_id'])) {
                $sensorunitid = trim($data[$i]['sensorunit_id']);
            } else {
                $sensorunitid= '-';
            }


            if ($action == 1) {
                $sorted[$i][4] = '<button class="btn btn-primary"><a href="/admin/sensorunit/'.$sensorunitid.'" style="color:#FFFFFF;">Edit</a></button>';
            }
        }
        return $sorted;
    }
}
