<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Sensorunit;
use App\Models\Treespecies;
use App\Models\Api;
use App\Models\Irrigationrun;
use App\Models\Customer;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use DB, DateTime, DateTimeZone, Log, Session;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Process\Process;

class DevelopmentController extends Controller
{

    public function gateway() {
        $serial = request()->gateway;
        $date = date('M');
        $day = date('j');
        if ($day < 9) {
            $today = $date."  ".$day;
        } else {
            $today = $date." ".$day;
        }
        $result = Process::fromShellCommandline("ls -la /home/sensorbackup/$serial.* | grep '$today'");
        $result->run();
        $response = $result->getOutput();
        Log::info($response);
        return $response;
    }

    public function logGateway(Request $req) {
        if($req->path) {
            $result = Process::fromShellCommandline("cat $req->path");
            $result->run();
            $response = $result->getOutput();
            return $response;
        } else {
            return 1;
        }
    }
    
    public function compass() {
        return view('admin.development.compass');
    }

    public function graph() {
        $serialnumber = '21-1020-AC-00301';
        $variable = DB::connection('sensordata')->select("SELECT serialnumber, 
                                                            max(case when(variable='sequencenumber') then value else NULL end) as sequencenumber, 
                                                            max(case when(variable='resetcode') then value else NULL end) as resetcode,
                                                            max(case when(variable='rebootcounter') then value else NULL end) as rebootcounter    
                                                            FROM status 
                                                            WHERE serialnumber LIKE  '%21-1020-AC-%' 
                                                            GROUP BY  serialnumber LIMIT 10");
        // dd($variable);
        //$variable = DB::connection('sensordata')->select("SELECT * FROM status WHERE serialnumber = '$serialnumber' ORDER BY variable ASC");
        return view('admin.development.graph');
    }


    public function productionLog() {
        return view('admin.development.productionlog');
    }

    public function processLog(Request $req) {

        $prod = array();
        $production_log = trim($req->production_log);
        $production_log_temp = explode("\n", $production_log);
        $production_log_temp = array_filter($production_log_temp, 'trim');
        
        foreach($production_log_temp as $line) {
            if(strlen($line) > 60) {
                $pieces = explode(";", $line);
                $main_pieces = explode(",", $pieces[4]);
                $prod[trim($main_pieces[1])]['imei'] = trim($main_pieces[1]);
                $prod[trim($main_pieces[1])]['imsi'] = $main_pieces[0];
                $prod[trim($main_pieces[1])]['iccid'] = $main_pieces[4];
                $prod[trim($main_pieces[1])]['serial'] = $pieces[0];
                $prod[trim($main_pieces[1])]['checked'] = 0;
            }
        }

        $prod = array_values($prod);
        usort($prod, function($a, $b) {
            return $a['serial'] <=> $b['serial'];
        });


        $serial_imei = trim($req->serial_imei);
        $serial_imei_temp = explode("\n", $serial_imei);
        $serial_imei_temp = array_filter($serial_imei_temp, 'trim');
        $not_found = array();
        $duplicates_imeilist = array();

        foreach($serial_imei_temp as $line) {
            $imei = trim($line);
            $counter = 0;
            foreach ($serial_imei_temp as $line_check) {
                $imei_check = trim($line_check);
                if($imei == $imei_check) {
                    $counter++;
                    if($counter > 1) {
                        if(!in_array($imei, $duplicates_imeilist)) {
                            $duplicates_imeilist[] = $imei;
                        }
                    }
                }
            }
            $found = false;
            foreach ($prod as &$unit) {
                if($unit['imei'] == $imei) {
                    $unit['serial'] = $unit['serial'];
                    $unit['checked'] += 1;
                    $found = true;
                }
            }

            if(!$found) {
                $not_found[] = $imei;
            }
        }
        
        $response['duplicates_imeilist'] = $duplicates_imeilist;
        $response['not_found'] = $not_found;
        $response['result'] = $prod;
        return $response;
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
        $A = 0.0059032;
        $readable =  $interval->format('%h hour(s) %i minutes(s)');

        $data = Api::getApi('sensorunits/data?serialnumber='.$serial.'&timestart='.substr($run->irrigation_starttime, 0, 19).'&timestop='.substr($run->irrigation_endtime, 0, 19).'&sortfield=timestamp');
        $total = 0;
        $count = 0;
        foreach ($data['result'] as $probe) {
            if($probe['probenumber'] == 22) {
                if($probe['value'] !== '0') {
                    if($probe['value'] > '1.3') {
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

        $text = '<div class="rcorners3 bg-white"><div class="m-2"><b>Calculations of Flowrate m<sup>3</sup>/h - '.$serial.', Run: '.$run_input.' </b> <hr><div class="row"><div class="col-md-6">Min Flow Velocity ' .round($min, 3). ' m/s</div><div class="col-md-6">Max Flow Velocity: '.round($max, 3).' m/s </div></div><hr><div class="row"><div class="col-md-6">Flow Velocity ' .round($flow_v, 3). ' m/s</div><div class="col-md-6">Flowrate: '.round($flow, 2).' m<sup>3</sup>/h* </div></div><hr><div class="row"><div class="col-md-6">Time ' .$readable. '</div></div><div class="row"><div class="col-md-6">Total amount of water from run ' .round($total_flow,2). ' m<sup>3</sup>*</div></div><hr><div class="row"><div class="col-md-12"><small>*The calculation removes any flow velocity measurement under 1.3m/s and the time used is raw.</small></div></div></div></div>';
        return json_encode($text);
    }

    public static function convertWM($value, $temperature, $a, $b) {
        if (($value != -10) && ($value > - 100)) {
            $woodtemp = $temperature;
            if($value < 100300) {
                $woodmoisture = 101;
            }
            $M_ohm = $value / 1000000.0;
            $temporary = (log10(log10($M_ohm) + 1) - $b) / $a;
            $woodmoisture = ($temporary + 0.567 - 0.026 * ($woodtemp + 2.8) + 0.000051 * (pow(($woodtemp + 2.8),2))) / (0.881 * (pow((1.0056),($woodtemp  + 2.8))));
            
        } else {
            $woodmoisture = 0;
        }
        // LIMIT 101000 for 90
        if ($woodmoisture > 100) {
            return '> 100';
        } else if ($woodmoisture < 6) {
            return '< 6';
        }
        Log::info('Woodmoisture content: ' .round($woodmoisture,2). ' By user: '.Auth::user()->user_name);
        return round($woodmoisture,2);
    }

    public function developmentWoodMoisture($id, $temperature, $ohm){
        $treespecie = Treespecies::find($id);
        $mohm = $ohm / 1000000.0;
        $result = self::convertWM($ohm, $temperature, $treespecie->specie_value_a, $treespecie->specie_value_b);
        $result_2 = self::convertWM($ohm, ($temperature+10), $treespecie->specie_value_a, $treespecie->specie_value_b);
        $result_3 = self::convertWM($ohm, ($temperature-10), $treespecie->specie_value_a, $treespecie->specie_value_b);

        $text = '<div class="rcorners3 bg-white"><div class="m-2"><b>Calculations of WMC: </b>'.$treespecie->specie_name.' at ' .$mohm. ' MΩ <hr><div class="row"><div class="col-md-4 text-center">' .($temperature-10). '°C</div><div class="col-md-4 text-center">' .$temperature.'°C </div><div class="col-md-4 text-center">' .($temperature+10).'°C </div></div><hr><div class="row"><div class="col-md-4 mb-1 text-center">' .$result_3. '%</div><div class="col-md-4 mb-1 text-center">' .$result. '%</div><div class="col-md-4 mb-1 text-center">' .$result_2. '%</div></div></div></div>';
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
