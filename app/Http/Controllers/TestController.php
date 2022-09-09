<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTimeZone;
use DateTime;
use date;
    

class TestController extends Controller
{

    public function dbtest() {
        for ($i = 10; $i < 100; $i++) {            
            $custom_id = 1;
            $status = 3;
            $sensorunit_installdate = now();
            $sensorunit_lastconnect = now();
            $customer_id_ref = 18;
            // $customernumber = '10-1250-AA';
            // $dbname = 'sensordata_1250';
            $customernumber = '10-0000-AA';
            $dbname = 'sensordata_onstock';
            $customer_help = 1;
            $product_id_ref = 61;
            $serialnumber = '21-1020-AB-002'.$i;
            $sensounit_position = '';
            $bool = TRUE;
            //DB::insert('insert into sensoraccess (user_id, serialnumber, changeallowed) values (?, ?, ?)', [$custom_id, $serialnumber, $bool]);

            DB::insert('insert into sensorunits (customernumber, dbname, customer_id_ref, custom_id, helpdesk_id_ref, product_id_ref, serialnumber, sensorunit_installdate, sensorunit_position, sensorunit_lastconnect, sensorunit_status) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$customernumber, $dbname, $customer_id_ref, $custom_id, $customer_help, $product_id_ref, $serialnumber, $sensorunit_installdate, $sensounit_position, $sensorunit_lastconnect, $status]);
        }
        return 'Finifshed';
    }

    public function sensoraccess() {
        for ($i = 1; $i < 7; $i++) {            
            $serialnumber = '21-1059-AA-0000'.$i;
            $bool = TRUE;
            $custom_id = 311;
            DB::insert('insert into sensoraccess (user_id, serialnumber, changeallowed) values (?, ?, ?)', [$custom_id, $serialnumber, $bool]);
        }
        return 'finished';
    }

}
