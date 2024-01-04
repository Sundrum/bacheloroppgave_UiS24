<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Sensorunit;
use App\Models\Sensoraccess;
use App\Models\Messagereceiver;
use App\Models\Irrigationrun;

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

    public function deleteRun(Request $req) {
        $run = Irrigationrun::find($req->id);
        $response = $run->delete();
        return $response;
    }

    public function getNotification($serialnumber) {
        $response['unit'] = Sensorunit::where('serialnumber', $serialnumber)->first();
        $response['notifications'] = Messagereceiver::where('sensorunits_id_ref', $response['unit']->sensorunit_id)->first();
        $response['users'] = Sensoraccess::select('users.user_name as user_name', 'users.user_email as user_email', 'users.user_phone_work as user_phone_work', 'users.email_verified as email_verified', 'users.phone_verified as phone_verified')->where('serialnumber', $serialnumber)->join('users', 'sensoraccess.user_id', 'users.user_id')->get();
        return $response;
    }

    public function setNotification(Request $req) {
        return $req;
    }
}
