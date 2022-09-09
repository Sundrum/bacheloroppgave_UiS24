<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Sensorunit;
use App\Models\Treespecies;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use DB, DateTime, DateTimeZone;
use App\Models\Api;
use App\Models\Irrigationrun;
use App\Models\Customer;
class DevelopmentController
{
    
    public function compass() {
        return view('admin.development.compass');
    }

    public function farmfield($serial) {
        $token = request()->token;
        if ($token == '2ablEKS3MT51') {
            $unit = Sensorunit::where('serialnumber', $serial)->first();
            $result = Unit::latestArrayData($serial);
            DashboardController::probeProcess($result);
            ksort($result);
            $unit['probe'] = $result;
            $api = DB::connection('sensordata')->select('SELECT * FROM status WHERE serialnumber = ?', [$serial]);
            if($api) {
                foreach ($api as $row) {
                    $unit[trim($row->variable)] = trim($row->value);
                    if(trim($row->variable) == 'gnss') {
                        if($row->value) {
                            $pieces = explode(",", $row->value);
                            $unit['lat'] = (float)$pieces[0];
                            $unit['lng'] = (float)$pieces[1];
                        }
                    }
                }
            }
            return $unit;
        } else {
            return 'missing critical parameter';
        }
    }

    public function woodMoisture(){
        $data = array();
        $data['treespecies'] = Treespecies::all();
        return view('admin.development.woodmoisture', compact('data'));
    }

    public function flowrate(){
        $sensorunit = Sensorunit::where('product_id_ref', 131)->get();
        return view('admin.development.flowrate', compact('sensorunit'));
    }

    public function devIrrigationRun($serial,$run) {
        $run_input =  $run;
        $run = Irrigationrun::where('serialnumber', $serial)->where('irrigation_run_id', $run)->first();
        $start = new DateTime($run->irrigation_starttime);
        $stop = new DateTime($run->irrigation_endtime);
        $interval = $start->diff($stop);
        $minutes = $interval->format('%i');
        $hours = $interval->format('%h');

        $A = 0.005410608;

        $readable =  $interval->format('%h hour(s) %i minutes(s)');

        $data = Api::getApi('sensorunits/data?serialnumber='.$serial.'&timestart='.substr($run->irrigation_starttime, 0, 19).'&timestop='.substr($run->irrigation_endtime, 0, 19).'&sortfield=timestamp');
        $total = 0;
        $count = 0;
        foreach ($data['result'] as $probe) {
            if($probe['probenumber'] == 18) {
                if($probe['value'] !== '0') {
                    if($probe['value'] > '1.7') {
                        $result[] = $probe['value'];
                        $total += $probe['value'];
                        $count++;
                    }
                }
            }
        }
        $min = min($result);
        $max = max($result);
        $flow_v = $total / $count;
        $flow = $A * ($flow_v*3600);
        $timespent = ($hours*60) + $minutes;
        $active = $timespent/60;
        $total_flow = $flow * $active;
        //dd($minutes, $readable, $total, $timespent, $flow_v, $flow, $active, $total_flow);

        $text = '<div class="rcorners3 bg-white"><div class="m-2"><b>Calculations of Flowrate m<sup>3</sup>/h - '.$serial.', Run: '.$run_input.' </b> <hr><div class="row"><div class="col-md-6">Min Flow Velocity ' .round($min, 3). ' m/s</div><div class="col-md-6">Max Flow Velocity: '.round($max, 3).' m/s </div></div><hr><div class="row"><div class="col-md-6">Flow Velocity ' .round($flow_v, 3). ' m/s</div><div class="col-md-6">Flowrate: '.round($flow, 2).' m<sup>3</sup>/h </div></div><hr><div class="row"><div class="col-md-6">Time ' .$readable. '</div></div><div class="row"><div class="col-md-6">Total amount of water from run ' .round($total_flow,2). ' m<sup>3</sup>/h*</div></div><hr><div class="row"><div class="col-md-12"><small>*The calculation removes any flow velocity measurement under 1.7m/s and the time used is raw.</small></div></div></div></div>';
        return json_encode($text);
    }

    public static function convertWM($value, $temperature, $a, $b) {
        if (($value != -10) && ($value > - 100)) {
            $woodtemp = $temperature;
            $M_ohm = $value / 1000000.0;
            $temporary = (log10(log10($M_ohm) + 1) - $b) / $a;
            $woodmoisture = ($temporary + 0.567 - 0.026 * ($woodtemp + 2.8) + 0.000051 * (pow(($woodtemp + 2.8),2))) / (0.881 * (pow((1.0056),($woodtemp  + 2.8))));
        } else {
            $woodmoisture = 0;
        }
        // LIMIT 101000 for 90
        if ($woodmoisture > 90) {
            $woodmoisture = 90;
        } else if ($woodmoisture < 6) {
            $woodmoisture = 6;
        }
        round($woodmoisture,2);
        return $woodmoisture;
    }

    public function developmentWoodMoisture($id, $temperature, $ohm){
        $treespecie = Treespecies::find($id);
        $mohm = $ohm / 1000000.0;
        $result = self::convertWM($ohm, $temperature, $treespecie->specie_value_a, $treespecie->specie_value_b);
        $text = '<div class="rcorners3 bg-white"><div class="m-2"><b>Calculations of '.$treespecie->specie_name.'</b> <hr><div class="row"><div class="col-md-6">Ohm ' .$ohm. '</div><div class="col-md-6">M Ohm ' .$mohm.' </div></div><hr><div class="row"><div class="col-md-6">Temperature ' .$temperature. '</div></div><hr><div class="row"><div class="col-md-6 mb-1">Wood Moisture ' .round($result,4). ' %</div></div></div></div>';
        
        return json_encode($text);
    }

    public function logIrrigationRun($serial, $run) {
        $run_input =  $run;
        $run = Irrigationrun::where('serialnumber', $serial)->where('irrigation_run_id', $run)->first();
        $start = new DateTime($run->irrigation_starttime);
        $stop = new DateTime($run->irrigation_endtime);
        $interval = $start->diff($stop);
        $minutes = $interval->format('%i');
        $hours = $interval->format('%h');

        $A = 0.005410608;

        $readable =  $interval->format('%h hour(s) %i minutes(s)');

        $data = Api::getApi('sensorunits/data?serialnumber='.$serial.'&timestart='.substr($run->irrigation_starttime, 0, 19).'&timestop='.substr($run->irrigation_endtime, 0, 19).'&sortfield=timestamp');
        $result = array();
        $temp = array();
        $i = 0;
        foreach ($data['result'] as $item) {
            $temp[$item['timestamp']]['timestamp'] = $item['timestamp'];
            if($item['probenumber'] == '2') $temp[$item['timestamp']]['lat'] = $item['value'];
            if($item['probenumber'] == '3') $temp[$item['timestamp']]['lng'] = $item['value'];
            if($item['probenumber'] == '18') {
                $temp[$item['timestamp']]['velocity'] = $item['value'];
                if($item['value'] > 0) {
                    $flow = $A * ($item['value']*3600);
                    $temp[$item['timestamp']]['flow'] = $flow;
                }
            }
        }
        foreach ($temp as $record) {
            $result[$i][0] = $serial;
            $result[$i][1] = $record['timestamp'];

            if(isset($record['lat'])) {
                $result[$i][2] = $record['lat'];
            } else {
                $result[$i][2] = 0;
            }

            if(isset($record['lng'])) {
                $result[$i][3] = $record['lng'];
            } else {
                $result[$i][3] = 0; 
            }

            if(isset($record['velocity'])) {
                $result[$i][4] = $record['velocity'];
            } else {
                $result[$i][4] = 0; 
            }

            if(isset($record['flow'])) {
                $result[$i][5] = $record['flow'];
            } else {
                $result[$i][5] = 0; 
            }
            $i++;

        }
        return json_encode($temp);
    }
}
