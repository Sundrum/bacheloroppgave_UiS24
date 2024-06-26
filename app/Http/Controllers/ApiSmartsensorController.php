<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime, DateTimeZone;

class ApiSmartsensorController extends AdminController
{
    public function getBilling($customer, $quarter, $year) 
    {
        if ($quarter == 1) {
            $time1 = $year.'-01-01';
            $time2 = $year.'-03-31';
        } else if ($quarter == 2) {
            $time1 = $year.'-04-01';
            $time2 = $year.'-06-30';
        } else if ($quarter == 3) {
            $time1 = $year.'-07-01';
            $time2 = $year.'-09-30';
        } else {
            $time1 = $year.'-10-01';
            $time2 = $year.'-12-31';
        }
        $data = DB::connection('sensordata')->select('SELECT serialnumber, MAX(log_time), MIN(log_time), SUM(connectcounter) AS connectcounter FROM connect_log WHERE customernumber = ? AND log_time BETWEEN ? AND ? GROUP BY serialnumber', [$customer, $time1, $time2]);

        if (isset($data)){
            $i = 0;
            foreach ($data as $row) {
                $jsondata[$i][0] = $row->serialnumber;
                $jsondata[$i][1] = $row->connectcounter;
                $first_connection = new DateTime($row->min);
                $first_connection->setTimezone(new DateTimeZone('Europe/Oslo'));
                $first = $first_connection->format('Y-m-d H:i:s');
                $jsondata[$i][2] = $first;
                $latest_connection = new DateTime($row->max);
                $latest_connection->setTimezone(new DateTimeZone('Europe/Oslo'));
                $latest = $latest_connection->format('Y-m-d H:i:s');
                $jsondata[$i][3] = $latest;
                $i++;
            }
        } else {
            $jsondata = [];
        }
        
        return json_encode($jsondata);
    }

    public function getSummary($customer, $quarter, $year) 
    {
        if ($quarter == 1) {
            $time1 = $year.'-01-01';
            $time2 = $year.'-03-31';
        } else if ($quarter == 2) {
            $time1 = $year.'-04-01';
            $time2 = $year.'-06-30';
        } else if ($quarter == 3) {
            $time1 = $year.'-07-01';
            $time2 = $year.'-09-30';
        } else {
            $time1 = $year.'-10-01';
            $time2 = $year.'-12-31';
        }
        $data = DB::connection('sensordata')->select('SELECT serialnumber, SUM(connectcounter) AS connectcounter FROM connect_log WHERE customernumber = ? AND log_time BETWEEN ? AND ? GROUP BY serialnumber', [$customer, $time1, $time2]);
        
        $units = 0;
        $units_10 = 0;
        $connections = 0;
        $connections_10 = 0;
        $total_units = 0;
        $total_connenections = 0;

        foreach ($data as $item) {
            $total_connenections += $item->connectcounter;
            if($item->connectcounter >= 5) {
                $units++;
                $connections += $item->connectcounter;
            }
            if($item->connectcounter >= 10) {
                $units_10++;
                $connections_10 += $item->connectcounter;
            }
        }

        $total_units = count($data);
        $units = number_format($units, 0, ',', ' ');
        $connections = number_format($connections, 0, ',',' ');
        $connections_10 = number_format($connections_10, 0, ',',' ');
        $total_connenections = number_format($total_connenections, 0, ',',' ');
        $total_units = number_format($total_units, 0, ',',' ');
        

        $text = '<b>Summary of Q'.$quarter.' - '.$year.'</b> <hr><div class="row"><div class="col-md-6">Active Units ( > 5<sup>*</sup>): ' .$units. '</div><div class="col-md-6">Active Connections : ' .$connections.' </div></div><hr><div class="row"><div class="col-md-6">Active Units ( > 10<sup>*</sup>): ' .$units_10. '</div><div class="col-md-6">Active Connections : ' .$connections_10.' </div></div><hr><div class="row"><div class="col-md-6">Total Units ( >1<sup>*</sup> ): ' .$total_units. '</div><div class="col-md-6">Total Connections: ' .$total_connenections.' </div></div> <hr> <div class="row mt-3 mb-0"><div class="col-12"> <p class="muted"><sup>*</sup> Connections in calculation</p></div></div>';
        
        return json_encode($text);
    }

    public function proxyApi() {
        if(isset(request()->product)) {
            $product = request()->product;
            if(strlen($product) == 7) {
                $status = DB::connection('sensordata')->select("SELECT status.serialnumber, 
                                                                max(case when(status.variable='rssi') then status.value else NULL end) as rssi, 
                                                                max(case when(status.variable='swversion') then status.value else NULL end) as swversion,
                                                                max(case when(status.variable='lastconnect') then status.value else NULL end) as lastconnect,
                                                                max(case when(status.variable='fota_137') then status.value else NULL end) as fota_137, 
                                                                max(case when(status.variable='imei') then status.value else NULL end) as imei,
                                                                max(case when(status.variable='iccid') then status.value else NULL end) as iccid,
                                                                max(case when(status.variable='imsi') then status.value else NULL end) as imsi,
                                                                max(case when(status.variable='mccmnc') then status.value else NULL end) as mccmnc,
                                                                max(case when(status.variable='sequencenumber') then status.value else NULL end) as sequencenumber,
                                                                max(case when(status.variable='rebootcounter') then status.value else NULL end) as rebootcounter,
                                                                max(case when(status.variable='rebootcounter') then status.dateupdated else NULL end) as reboot_at, 
                                                                max(case when(status.variable='resetcode') then status.value else NULL end) as resetcode,
                                                                max(case when(status.variable='mdmhwver') then status.value else NULL end) as mdmhwver,
                                                                max(case when(status.variable='connectmode') then status.value else NULL end) as connectmode,
                                                                max(case when(status.variable='axxe_fix') then status.value else NULL end) as axxe_fix,
                                                                queue.statusid as fota_in_queue,
                                                                queue.dateupdated as queue_updated_at
                                                                FROM status
                                                                LEFT JOIN queue ON (status.serialnumber = queue.serialnumber AND queue.typeid='2')
                                                                WHERE status.serialnumber LIKE  '%$product%'
                                                                GROUP BY status.serialnumber, queue.statusid, queue.dateupdated");
                $data = json_encode($status);
                return view('admin.apismartsensor.proxy', compact('data'));
            } else {
                $status = array();
                $data = json_encode($status);
                return view('admin.apismartsensor.proxy', compact('data'))->with('errormessage', 'Product type should be set to 7 chars');
            }
        } else {
            $status = array();
            $data = json_encode($status);
            return view('admin.apismartsensor.proxy', compact('data'))->with('errormessage', 'No product type is selected for this view');
        }

    }

    public function fotaQueue(Request $req) {
    //     if ($req->swversion == 'V1.3.8') {
    //         return response()->json('V1.3.8');
    //     }

    //     if ($req->swversion == 'V1.3.7') {
    //          return response()->json('V1.3.7');
    //     }

        // if ($req->swversion == 'V1.3.9') {
        //     return response()->json('V1.3.9');
        // }

        $aa_cmd = "105,0,0,21-9030/app_update.bin";
        $ab_cmd = "105,0,0,21-9030/app_update_9030_AB_v148.bin";
        $farmfield = "105,0,0,21-1065-AC/farmfield-ac_1.0.6";
        $irrigation = "105,http://firmware.smartsensor.no/21-1020-AC/irrigation-ac_1.2.3";

        //$irrigation = "105,21-1020-AC/irrigation-ac_1.2.3";
        $productnumber = substr($req->serialnumber, 0,10);
        $comment = 'FOTA to V1.3.8';

        $in_queue = DB::connection('sensordata')->select("SELECT * FROM queue WHERE serialnumber='$req->serialnumber' AND typeid='2' ");
        if ($in_queue) {
            DB::connection('sensordata')->select("DELETE FROM queue WHERE serialnumber='$req->serialnumber' AND typeid='2' ");
        }

        if($productnumber == '21-9030-AA') {
            $response = DB::connection('sensordata')->select("INSERT INTO queue (serialnumber,statusid,typeid,data,dateadded,comment) VALUES ('$req->serialnumber','1','2','$aa_cmd',now(),'$comment')");
            $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE variable='fota_137' AND serialnumber='$req->serialnumber'");
            if(isset($check[0]->value)) {
                $timestamp = now();
                $response_2 = DB::connection('sensordata')->update("UPDATE status set dateupdated='$timestamp' WHERE variable='fota_137' AND serialnumber='$req->serialnumber'");
            } else {
                $response_2 = DB::connection('sensordata')->select("INSERT INTO status (serialnumber,variable,value,dateupdated) VALUES ('$req->serialnumber','fota_137','IN QUEUE',now())");
            }
        }
        if($productnumber == '21-9030-AB') {
            $response = DB::connection('sensordata')->select("INSERT INTO queue (serialnumber,statusid,typeid,data,dateadded,comment) VALUES ('$req->serialnumber','1','2','$ab_cmd',now(),'$comment')");
            $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE variable='fota_137' AND serialnumber='$req->serialnumber'");
            if(isset($check[0]->value)) {
                $timestamp = now();
                $response_2 = DB::connection('sensordata')->update("UPDATE status set dateupdated='$timestamp' WHERE variable='fota_137' AND serialnumber='$req->serialnumber'");
            } else {
                $response_2 = DB::connection('sensordata')->select("INSERT INTO status (serialnumber,variable,value,dateupdated) VALUES ('$req->serialnumber','fota_137','IN QUEUE',now())");
            }
        }  

        if($productnumber == '21-1065-AC') {
            $response = DB::connection('sensordata')->select("INSERT INTO queue (serialnumber,statusid,typeid,data,dateadded,comment) VALUES ('$req->serialnumber','1','2','$farmfield',now(),'$comment')");
            $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE variable='fota_106' AND serialnumber='$req->serialnumber'");
            if(isset($check[0]->value)) {
                $timestamp = now();
                $response_2 = DB::connection('sensordata')->update("UPDATE status set dateupdated='$timestamp' WHERE variable='fota_106' AND serialnumber='$req->serialnumber'");
            } else {
                $response_2 = DB::connection('sensordata')->select("INSERT INTO status (serialnumber,variable,value,dateupdated) VALUES ('$req->serialnumber','fota_106','IN QUEUE',now())");
            }
        }  

        if($productnumber == '21-1020-AC') {
            $response = DB::connection('sensordata')->select("INSERT INTO queue (serialnumber,statusid,typeid,data,dateadded,comment) VALUES ('$req->serialnumber','1','2','$irrigation',now(),'$comment')");
            $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE variable='fota_123' AND serialnumber='$req->serialnumber'");
            if(isset($check[0]->value)) {
                $timestamp = now();
                $response_2 = DB::connection('sensordata')->update("UPDATE status set dateupdated='$timestamp' WHERE variable='fota_123' AND serialnumber='$req->serialnumber'");
            } else {
                $response_2 = DB::connection('sensordata')->select("INSERT INTO status (serialnumber,variable,value,dateupdated) VALUES ('$req->serialnumber','fota_123','IN QUEUE',now())");
            }
        }  

        
        return response()->json('DONE');
    }

    public function deleteQueue(Request $req) {
        $in_queue = DB::connection('sensordata')->select("DELETE FROM queue WHERE serialnumber='$req->serialnumber' AND typeid='2' ");
        return response()->json($in_queue);
    }

    public function getProxyViewVariables() {
        return view('admin.apismartsensor.variables');
    }

    public function getProxyVariables(Request $req) {
        $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE serialnumber='$req->serialnumber' ORDER BY variable ASC");
        return $check;
    }

    public function setProxyVariables(Request $req) {
        $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE serialnumber='$req->serialnumber' AND variable='$req->variable'");
        if(isset($check) && count($check) > 0) {
            $timestamp = now();
            $response = DB::connection('sensordata')->update("UPDATE status SET dateupdated='$timestamp', value='$req->value'  WHERE variable='$req->variable' AND serialnumber='$req->serialnumber'");
        } else {
            $response = DB::connection('sensordata')->select("INSERT INTO status (serialnumber,variable,value,dateupdated) VALUES ('$req->serialnumber','$req->variable','$req->value',now())");
        }
        return $response;
    }
}
