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
        $data = DB::connection('sensordata')->select("SELECT * FROM status WHERE serialnumber LIKE '%21-9030%' AND  variable IN ('rssi','swversion','lastconnect','fota_137', 'imei', 'imsi', 'iccid', 'mccmnc','sequencenumber', 'rebootcounter', 'mdmhwver') ORDER BY serialnumber ASC");
        $result = array();
        foreach ($data as $row) {
            // dd(substr(trim($row->serialnumber), 11,17));
            // if(substr($row->serialnumber, 11,17) > 1173 && substr($row->serialnumber, 11,17) < 1580) {
                if(strcmp(trim($row->variable),'fota_137') == 0) {
                    $result[trim($row->serialnumber)]['queue_at'] = substr($row->dateupdated,0,16). ' / ' . $row->value ?? null;
                } if(strcmp(trim($row->variable),'lastconnect') == 0) {
                    $result[trim($row->serialnumber)]['lastconnect'] = substr($row->value,0,16) ?? null;
                } else {
                    $result[trim($row->serialnumber)][trim($row->variable)] = trim($row->value) ?? null;
                }
                $result[trim($row->serialnumber)]['serialnumber'] = trim($row->serialnumber);

            // }
        }
        $result = array_values($result);

        foreach ($result as &$row) {
            $serial = $row['serialnumber'];
            $queue_data = DB::connection('sensordata')->select("SELECT statusid, dateupdated from queue WHERE serialnumber='$serial' AND typeid='2'");

            if($queue_data) {
                $row['fota_in_queue'] = $queue_data[0]->statusid ?? null;
                $row['queue_updated_at'] = substr($queue_data[0]->dateupdated,0,16) ?? null;
                $row['fota_in_queue_count'] = count($queue_data) ?? null;
            } else {
                $row['fota_in_queue'] = null;
                $row['queue_updated_at'] = null;
                $row['fota_in_queue_count'] = null;
            }
        }
        $data = json_encode($result);
        return view('admin.apismartsensor.proxy', compact('data'));
        // return view('admin.apismartsensor.proxy_2', compact('data'));

    }

    public function fotaQueue(Request $req) {
        if ($req->swversion == 'V1.3.8') {
            return response()->json('V1.3.8');
        }

        // if ($req->swversion == 'V1.3.7') {
        //     return response()->json('V1.3.7');
        // }

        $aa_cmd = "105,0,0,21-9030/app_update.bin";
        $ab_cmd = "105,0,0,21-9030/app_update_9030_AB_v138.bin";
        $productnumber = substr($req->serialnumber, 0,10);
        $comment = 'FOTA to V1.3.7';

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
        $check = DB::connection('sensordata')->select("SELECT * FROM status WHERE serialnumber='$req->serialnumber'");
        return $check;
    }
}
