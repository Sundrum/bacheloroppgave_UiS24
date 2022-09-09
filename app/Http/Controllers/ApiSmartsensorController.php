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
        $connections = 0;
        $total_units = 0;
        $total_connenections = 0;

        foreach ($data as $item) {
            $total_connenections += $item->connectcounter;
            if($item->connectcounter >= 5) {
                $units++;
                $connections += $item->connectcounter;
            }
        }

        $total_units = count($data);
        $units = number_format($units, 0, ',', ' ');
        $connections = number_format($connections, 0, ',',' ');
        $total_connenections = number_format($total_connenections, 0, ',',' ');
        $total_units = number_format($total_units, 0, ',',' ');
        

        $text = '<b>Summary of Q'.$quarter.' - '.$year.'</b> <hr><div class="row"><div class="col-md-6">Active Units ( > 5<sup>*</sup>): ' .$units. '</div><div class="col-md-6">Active Connections : ' .$connections.' </div></div><hr><div class="row"><div class="col-md-6">Total Units ( >1<sup>*</sup> ): ' .$total_units. '</div><div class="col-md-6">Total Connections: ' .$total_connenections.' </div></div> <hr> <div class="row mt-3 mb-0"><div class="col-12"> <p class="muted"><sup>*</sup> Connections in calculation</p></div></div>';
        
        return json_encode($text);
    }
}
