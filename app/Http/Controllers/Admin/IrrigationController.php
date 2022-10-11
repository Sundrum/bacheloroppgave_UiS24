<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensorunit;
use App\Models\Sensorlatestvalues;
use App\Models\Unit;
use App\Models\Status;
use App\Models\Api;
use App\Models\Irrigationrun;
use App\Models\SensorunitVariable;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CommandController;

class IrrigationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    // admin/irrigationstatus/+id
    public function get($serial){
        $data = Status::where('serialnumber',$serial)->get();
        $latest_data = Sensorlatestvalues::where('serialnumber',$serial)->latest('timestamp')->get();
        $variable = array();
        $variable['unit'] = Sensorunit::where('serialnumber', $serial)->first();


        $variable['unit']['last_time'] = self::convertToSortableDate($variable['unit']['sensorunit_lastconnect']);

        foreach ($data as $row) {
            $name = trim($row->variable);
            $variable[$name]['value'] = $row->value;
            $variable[$name]['time'] = $row->dateupdated;
        }
        $queue = CommandController::queueList($serial);
        $variable['firmware'] = CommandController::firmwareList($serial);
        $variable['runtable'] = self::runtable($serial);
        return view('admin.sensorunit.irrigationstatus',compact('variable', 'queue', 'latest_data')); // admin/irrigationstatus/+id
    }

    public static function runtable($serial) {
        $runtable = Irrigationrun::where('serialnumber', $serial)->get();
        $result = array();
        $i = 0;
        foreach($runtable as $run) {
            $result[$i][0] = $run->log_id;
            $result[$i][1] = $run->irrigation_run_id;
            $result[$i][2] = $run->irrigation_startpoint;
            $result[$i][3] = $run->irrigation_endpoint;
            $result[$i][4] = self::convertToSortableDate($run->irrigation_starttime);
            $result[$i][5] = self::convertToSortableDate($run->irrigation_endtime);
            $i++;
        }
        return json_encode($result);
    }

    public static function getRun($id) {
        $run = Irrigationrun::find($id);
        $url_1 = 'sensorunits/data?serialnumber='.trim($run->serialnumber).'&timestart='.substr($run->irrigation_starttime, 0, 19).'&timestop='.substr($run->irrigation_endtime, 0, 19).'&sortfield=timestamp';
        $data = Api::getApi($url_1);
        $i = 0;
        $temp = array();
        if (isset($data) && is_array($data['result'])) {
            foreach ($data['result'] as $row) {
                $temp[$row['timestamp']][0] =$row['timestamp'];
                $temp[$row['timestamp']][$row['probenumber']] = (float)$row['value'];
            }
        }
        $log = json_encode(array_values($temp));
        return view('admin.sensorunit.runtable', compact('run', 'log'));
    }

    public static function getIrrigationRun($id) {
        $run = Irrigationrun::find($id);
        $result = array();
        $result = $run;
        $url_1 = 'sensorunits/data?serialnumber='.trim($run->serialnumber).'&timestart='.substr($run->irrigation_starttime, 0, 19).'&timestop='.substr($run->irrigation_endtime, 0, 19).'&sortfield=timestamp';
        $data = Api::getApi($url_1);
        $i = 0;
        $temp = array();
        if (isset($data) && is_array($data['result'])) {
            foreach ($data['result'] as $row) {
                $temp[$row['timestamp']][0] = self::convertToSortableDate($row['timestamp']);
                $temp[$row['timestamp']][$row['probenumber']] = (float)$row['value'];
            }
        }
        $result['data'] = array_values($temp);
        return json_encode($result);
    }

    public static function updateRun(Request $req) {
        $run = Irrigationrun::find($req->log_id); // Get the log_id in runtable
        $latlng = $req->lat.','.$req->lng; // Combind lat and lng before storing the value.
        if($req->point_id == 1) {
            // Update Startmarker
            $run->irrigation_startpoint = $latlng;
        } else if ($req->point_id == 2) {
            // Update Endmarker
            $run->irrigation_endpoint = $latlng;
        }
        $result = $run->save(); // Save the data
        return json_encode($result);
    }


    public function updateStatusPage(){
        $data = Sensorunit::where('serialnumber', 'LIKE', '%21-1020%' )->get();
        // dd($data);
        $sorted = array();
        $result = array();

        foreach ($data as &$unit) {
            if (isset($unit['serialnumber'])) {
                $serial = trim($unit['serialnumber']);
                if (isset($unit['sensorunit_lastconnect'])) {
                    if(!in_array(trim($unit['serialnumber']),$sorted)){
                        $variables = Unit::getVariables($serial);
                        if(is_array($variables['result'])) {
                            foreach ($variables['result'] as $variable) {
                                if (trim($variable['variable']) == 'irrigation_state') {
                                    array_push($sorted, trim($unit['serialnumber']));
                                    $unit['irrigation_state'] = trim($variable['value']);
                                    $result[] = $unit;
                                }
                            }
                            if(!isset($unit['irrigation_state'])) {
                                array_push($sorted, trim($unit['serialnumber']));
                                $unit['irrigation_state'] = -1;
                                $result[] = $unit;
                            }
                        } else {
                            array_push($sorted, trim($unit['serialnumber']));
                            $unit['irrigation_state'] = -1;
                            $result[] = $unit;
                        }
                    }
                }
            }
        }

        $allirrigation = AdminController::processIrrigationArray($result);
        $variable = array();
        $variable['notused'] = 0;
        $variable['idle'] = 0;
        $variable['idle_green'] = 0;
        $variable['settling'] = 0;
        $variable['irrigation'] = 0;
        foreach ($result as $row) {
            if ($row['sortstate'] == 'state1') {
                $variable['notused'] += 1;
            } else if ($row['sortstate'] == 'state2') {
                $variable['idle'] += 1;
            } else if ($row['sortstate'] == 'state3') {
                $variable['idle_green'] += 1;
            } else if ($row['sortstate'] == 'state4') {
                $variable['settling'] += 1;
            } else if ($row['sortstate'] == 'state5') {
                $variable['irrigation'] += 1;
            }
        }
        $data = json_encode($variable);
        return $data;
    }

    public function map(){;
        $latlngs = array();
        $units = SensorunitVariable::where('variable','irrigation_state')->where('value', '>=', '1')->get();
        $i = 0;
        
        foreach ($units as $unit) {
            $data = Api::getApi('sensorunits/data/latest?serialnumber='.trim($unit->serialnumber));
            // dd($unit);
            if (isset($data['result']) && count($data['result']) > 0) {
                foreach ($data['result'] as $row) {
                    if(strtotime($row['timestamp']) > strtotime("-3 days")) {
                        if(trim($row['probenumber'])==2 && $row['value'] != '0') {
                            $latlngs[$i]['lat'] = $row['value'];
                        }
                        if(trim($row['probenumber'])==3 && $row['value'] != '0') {
                            $latlngs[$i]['lng'] = $row['value'];
                        }
                    }
                }
                $i++;
            }
        }
        $latlngs = array_values($latlngs);

        return view('admin.sensorunit.irrigationmap', compact('latlngs'));
    }

    public function debug($serial){
        $api = Api::getApi("sensorunits/data?serialnumber=$serial&timestart=2021-05-12&sortfield=timestamp");
        // dd($api);
        $result = array();
        foreach($api['result'] as $row) {
            $result[$row['timestamp']][1] = '';
            $result[$row['timestamp']][2] = '';
            $result[$row['timestamp']][3] = '';
            $result[$row['timestamp']][4] = '';
            $result[$row['timestamp']][5] = '';
            $result[$row['timestamp']][6] = '';
            $result[$row['timestamp']][7] = '';
            $result[$row['timestamp']][8] = '';
            $result[$row['timestamp']][9] = '';
            $result[$row['timestamp']][10] = '';
            $result[$row['timestamp']][11] = '';
        }
        foreach($api['result'] as $row) {
            // dd($row);
            // Timestamp
            $result[$row['timestamp']][0] = self::convertToSortableDate($row['timestamp']);

            // STATE
            if($row['probenumber'] == 1) {
                if($row['value'] == 2) {
                    $result[$row['timestamp']][1] = '<img src="/img/irr_irrigation_green.png" width="30px">';
                } else if($row['value'] == 1) {
                    $result[$row['timestamp']][1] = '<img src="/img/irr_settling_green.png" width="30px">';
                } else {
                    $result[$row['timestamp']][1] = '<img src="/img/irr_idle_green.png" width="30px">';
                }
            }


            // RUN ID
            if($row['probenumber'] == 13) $result[$row['timestamp']][2] = $row['value'];

            // LAT & LNG
            if($row['probenumber'] == 2) $result[$row['timestamp']][3] = $row['value'];
            if($row['probenumber'] == 3) $result[$row['timestamp']][4] = $row['value'];

            // RSSI
            if($row['probenumber'] == 5) {
                if($row['value'] < -90) {
                    $result[$row['timestamp']][5] = '<div class="text-danger">'.$row['value'].'</div>';
                } else if ($row['value'] > -70){
                    $result[$row['timestamp']][5] = '<div class="text-success">'.$row['value'].'</div>';
                } else {
                    $result[$row['timestamp']][5] = '<div class="text-warning">'.$row['value'].'</div>';
                }
            }

            // TILT
            if($row['probenumber'] == 8) {
                if($row['value'] > 0.1) {
                    // dd($row['value'])
                    $result[$row['timestamp']][6] = '<div class="text-success">'.$row['value'].'</div>';
                } else if($row['value'] == 0) {
                    $result[$row['timestamp']][6] = '';
                } else {
                    $result[$row['timestamp']][6] = '<div class="text-danger">'.$row['value'].'</div>';
                }
            }
            
            // VIBRATIONS
            if($row['probenumber'] == 9) {
                // dd($row);
                if($row['value'] > 0.1) {
                    $result[$row['timestamp']][7] = '<div class="text-success">'.$row['value'].'</div>';
                } else {
                    $result[$row['timestamp']][7] = '<div class="text-danger">'.$row['value'].'</div>';
                }

            } 

            // VBAT
            if($row['probenumber'] == 12) $result[$row['timestamp']][8] = $row['value'];

            // PRESSURE
            if($row['probenumber'] == 14) $result[$row['timestamp']][9] = $row['value'];

            // FLOWRATE
            if($row['probenumber'] == 15) $result[$row['timestamp']][10] = round($row['value'],2);
            
            //SEQUENCE
            $result[$row['timestamp']][11] = $row['sequencenumber'];
        }
        $res = array_values($result);
        $data = json_encode($res,true);
        return view('admin.sensorunit.debug', compact('data', 'serial'));
    }
}
