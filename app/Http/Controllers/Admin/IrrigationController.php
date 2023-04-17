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
                $temp[$row['timestamp']]['timestamp'] = self::convertToSortableDate($row['timestamp']);
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
        $units = SensorunitVariable::where('variable','irrigation_state')->where('value', '>=', '1')->where('serialnumber', 'LIKE', '%21-1020-AC%')->get();
        $i = 0;
        foreach ($units as $unit) {
            $data = Api::getApi('sensorunits/data/latest?serialnumber='.trim($unit->serialnumber));
            if (isset($data['result']) && count($data['result']) > 0) {
                foreach ($data['result'] as $row) {
                    $latlngs[$i]['serialnumber'] = $unit['serialnumber'];
                    $latlngs[$i]['state'] = $unit['value'];
                    if(trim($row['probenumber'])==13 && $row['value'] != '0') {
                        $latlngs[$i]['lat'] = $row['value'];
                    }
                    if(trim($row['probenumber'])==14 && $row['value'] != '0') {
                        $latlngs[$i]['lng'] = $row['value'];
                    }
                }
                if(!isset($latlngs[$i]['lat']) || !isset($latlngs[$i]['lng'])) {
                    unset($latlngs[$i]);
                }
                $i++;
            }
        }
        $latlngs = array_values($latlngs);
        return $latlngs;
    }

    public function debug($serial){
        $api = Api::getApi("sensorunits/data?serialnumber=$serial&timestart=2023-01-12&sortfield=timestamp");
        $result = array();

        foreach($api['result'] as $row) {
            $result[$row['timestamp']]['timestamp'] = self::convertToSortableDate($row['timestamp']);
            if($row['probenumber'] == 0) {
                if($row['value']) {
                    $result[$row['timestamp']]['img'] = '<img src="/img/irrigation/state_'.$row['value'].'.png" width="30" height="30">';
                } else {
                    continue;
                    $result[$row['timestamp']]['img'] = '<img src="/img/irrigation/state.png" width="30" height="30">';
                }
            } 
            $result[$row['timestamp']][$row['probenumber']] = $row['value'];
        }
        $res = array_values($result);
        $data = json_encode($res,true);
        return view('admin.sensorunit.debug', compact('data', 'serial'));
    }
}
