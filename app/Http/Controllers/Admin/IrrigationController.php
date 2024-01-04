<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensorunit;
use App\Models\Sensorlatestvalues;
use App\Models\Unit;
use App\Models\Status;
use App\Models\Api;
use App\Models\Product;
use App\Models\Irrigationrun;
use App\Models\SensorunitVariable;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CommandController;
use DateTime, DateTimeZone, DB, Session, Log, Config;


class IrrigationController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    // admin/irrigationstatus/+id
    public function get($serial){
        $data = Status::where('serialnumber',$serial)->get();
        $latest = Api::getApi('sensorunits/data/latest?serialnumber='.$serial);
        $variable = array();
        foreach ($latest['result'] as $probe) {
            if ($probe['probenumber'] == '0') $variable['latest']['state'] = trim($probe['value']); // State
            else if ($probe['probenumber'] == '1') $variable['latest']['vibration'] = trim(($probe['value']/1)*100); // Vibration
            else if ($probe['probenumber'] == '2') $variable['latest']['water_lost'] = trim($probe['value']); // Water Lost
            else if ($probe['probenumber'] == '3') $variable['latest']['tilt_alert'] = trim($probe['value']); // Tilt alert
            else if ($probe['probenumber'] == '4') $variable['latest']['tilt'] = trim($probe['value']); // tilt abs
            else if ($probe['probenumber'] == '5') $variable['latest']['tilt_relative'] = trim($probe['value']); // tilt relative
            else if ($probe['probenumber'] == '6') $variable['latest']['acc_x'] = trim($probe['value']); // ACC X
            else if ($probe['probenumber'] == '7') $variable['latest']['acc_y'] = trim($probe['value']); // ACC Y
            else if ($probe['probenumber'] == '8') $variable['latest']['acc_z'] = trim($probe['value']); // ACC Z
            else if ($probe['probenumber'] == '9') $variable['latest']['button_pressed'] = trim($probe['value']); // Button Pressed
            else if ($probe['probenumber'] == '10') $variable['latest']['temperature'] = trim($probe['value']); // Temperature
            else if ($probe['probenumber'] == '11') $variable['latest']['rh'] = trim($probe['value']); // Relative Humidity
            else if ($probe['probenumber'] == '12') $variable['latest']['barro'] = trim($probe['value']); // Unit Barro
            else if ($probe['probenumber'] == '13') $variable['latest']['lat'] = trim($probe['value']); // LAT
            else if ($probe['probenumber'] == '14') $variable['latest']['lng'] = trim($probe['value']); // LNG
            else if ($probe['probenumber'] == '15') $variable['latest']['vbat'] = trim($probe['value']); // Vbat
            else if ($probe['probenumber'] == '16') $variable['latest']['rssi'] = trim($probe['value']); // rssi
            else if ($probe['probenumber'] == '17') $variable['latest']['psm_tau'] = trim($probe['value']); // psm tau
            else if ($probe['probenumber'] == '18') $variable['latest']['psm_active_time'] = trim($probe['value']); // psm active time
            else if ($probe['probenumber'] == '19') $variable['latest']['battery_mah'] = trim($probe['value']); // battery mah
            else if ($probe['probenumber'] == '20') $variable['latest']['heartbeat_lis'] = trim($probe['value']); // heartbeat_lis
            else if ($probe['probenumber'] == '21') $variable['latest']['pressure'] = trim($probe['value']); // Pressure
            else if ($probe['probenumber'] == '22') $variable['latest']['flow_velocity'] = trim($probe['value']); // Flow Velocity
            else if ($probe['probenumber'] == '30') $variable['latest']['sleep'] = trim($probe['value']); // Flow Velocity
            else if ($probe['probenumber'] == '31') $variable['latest']['boot'] = trim($probe['value']); // Flow Velocity
            else if ($probe['probenumber'] == '32') $variable['latest']['packets'] = trim($probe['value']); // Flow Velocity
            else if ($probe['probenumber'] == '33') $variable['latest']['sensors'] = trim($probe['value']); // Flow Velocity
            else if ($probe['probenumber'] == '34') $variable['latest']['gnss'] = trim($probe['value']); // Flow Velocity
            else if ($probe['probenumber'] == '35') $variable['latest']['led'] = trim($probe['value']); // Flow Velocity
        }
        $variable['unit'] = Sensorunit::where('serialnumber', $serial)->first();
        $variable_temp = SensorunitVariable::where('serialnumber', $serial)->get();
        foreach($variable_temp as $var) {
            $variable['unit'][$var->variable] = $var->value;
        }
        $variable['products'] = Product::all();
        $variable['unit']['status'] = DB::connection('sensordata')->select('SELECT * FROM status WHERE serialnumber = ? ORDER BY variable ASC', [$serial]);
        $variable['unit']['config'] = DB::connection('sensordata')->select('SELECT * FROM config WHERE serialnumber = ? ORDER BY variable ASC', [$serial]);
        $variable['unit']['last_time'] = self::convertToSortableDate($variable['unit']->sensorunit_lastconnect);

        foreach ($data as $row) {
            $name = trim($row->variable);
            $variable[$name]['value'] = $row->value;
            $variable[$name]['time'] = $row->dateupdated;
            // $result->$name->time = trim($row->dateupdated);
            // $result[trim($variable->variable)]->time = trim($variable->dateupdated);
        }
        $queue = CommandController::queueList($serial);
        $variable['firmware'] = CommandController::firmwareList($serial);
        $variable['runtable'] = self::runtable($serial);

        return view('admin.sensorunit.irrigationstatus',compact('variable', 'queue')); // admin/irrigationstatus/+id
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
                $temp[$row['timestamp']]['timestamp'] = self::convertToSortableTimestamp($row['timestamp']);
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


    public static function updateStatusPage(){
        $units = DB::select("SELECT sensorunits.*,
                                customer.customer_name as customer_name,
                                max(case when(sensorslatestvalues.probenumber='0') then sensorslatestvalues.value else NULL end) as state 
                                FROM sensorunits
                                LEFT JOIN customer ON (sensorunits.customer_id_ref = customer.customer_id)
                                LEFT JOIN sensorslatestvalues ON (sensorunits.serialnumber = sensorslatestvalues.serialnumber AND sensorslatestvalues.probenumber='0')
                                WHERE sensorunits.serialnumber LIKE '%21-1020-AC%'
                                GROUP BY sensorunits.serialnumber, customer.customer_name");
        $proxy_variables = DB::connection('sensordata')->select("SELECT status.serialnumber, 
                                    max(case when(status.variable='swversion') then status.value else NULL end) as swversion,
                                    max(case when(status.variable='lastconnect') then status.value else NULL end) as lastconnect,
                                    max(case when(status.variable='sequencenumber') then status.value else NULL end) as sequencenumber,
                                    max(case when(status.variable='rebootcounter') then status.value else NULL end) as rebootcounter,
                                    max(case when(status.variable='rebootcounter') then status.dateupdated else NULL end) as reboot_at, 
                                    max(case when(status.variable='resetcode') then status.value else NULL end) as resetcode
                                    FROM status
                                    WHERE status.serialnumber LIKE '%21-1020-AC%'
                                    GROUP BY status.serialnumber");
        AdminController::processIrrigationArray($units);

        foreach($units as &$unit) {
            foreach($proxy_variables as $key => $variable) {
                if($variable->serialnumber == $unit->serialnumber) {
                    $unit->variable = $variable;
                    unset($proxy_variables[$key]);
                }
            }

        }
        $response = json_encode($units);
        return $response;
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

    public function autoStart(Request $req) {
        $autostart = SensorunitVariable::where('variable','irrigation_autostart')->where('serialnumber', $req->serialnumber)->first();
        $threshold = SensorunitVariable::where('variable','irrigation_autostart_threshold')->where('serialnumber', $req->serialnumber)->first();
        $probe = SensorunitVariable::where('variable','irrigation_autostart_probe')->where('serialnumber', $req->serialnumber)->first();

        if($autostart) {
            $autostart->value = $req->autostart;
            $autostart->save();    
        } else {
            $autostart = new SensorunitVariable();
            $autostart->variable = 'irrigation_autostart';
            $autostart->value = $req->autostart;
            $autostart->serialnumber = $req->serialnumber;
            $autostart->save();
        }

        if($threshold) {
            $threshold->value = $req->threshold;
            $threshold->save();
        } else {
            $threshold = new SensorunitVariable();
            $threshold->variable = 'irrigation_autostart_threshold';
            $threshold->value = $req->threshold;
            $threshold->serialnumber = $req->serialnumber;
            $threshold->save();
        }

        if($probe) {
            $probe->value = $req->probenumber;
            $probe->save();
        } else {
            $probe = new SensorunitVariable();
            $probe->variable = 'irrigation_autostart_probe';
            $probe->value = $req->probenumber;
            $probe->serialnumber = $req->serialnumber;
            $probe->save();
        }
        return true;
    }

    public function debug($serial){
        $api = Api::getApi("sensorunits/data?serialnumber=$serial&timestart=2023-01-12&sortfield=timestamp");
        $result = array();

        foreach($api['result'] as $row) {
            $result[$row['timestamp']]['timestamp'] = self::convertToSortableTimestamp($row['timestamp']);
            if($row['probenumber'] == 0) {
                if($row['value']) {
                    $result[$row['timestamp']]['img'] = '<img src="/img/irrigation/state_'.$row['value'].'.png" width="30" height="30">';
                    $result[$row['timestamp']]['seq'] = $row['sequencenumber'];
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

    public function removePosition(Request $req) {
        $unit = Sensorunit::where('serialnumber', $req->serialnumber)->first();
        $date = $req->point['timestamp'];
        $temp = explode('.',$date);
        $mainipulatedtime = $temp[0].'-'.$temp[1].'-'.$temp[2];
        
        $time = new DateTime(date('Y-m-d H:i:s', strtotime($mainipulatedtime)), new DateTimeZone(Session::get('timezone')));
        $timestamp = $time->format('Y-m-d H:i:sP');

        $lat = $req->point['lat'];
        $lng = $req->point['lng'];
        self::changeDBConnection($unit->dbname);
        $check = DB::connection('7sensor')->select("UPDATE sensordata SET value='NaN' WHERE timestamp='$timestamp' AND value='$lat' AND serialnumber='$req->serialnumber'");
        $check = DB::connection('7sensor')->select("UPDATE sensordata SET value='NaN' WHERE timestamp='$timestamp' AND value='$lng' AND serialnumber='$req->serialnumber'");
        return $check;
    }

    public function cleanData(Request $req) {
        $unit = Sensorunit::where('serialnumber', $req->serialnumber)->first();
        self::changeDBConnection($unit->dbname);

        $starttime = substr($req->starttime,0,16);
        $endtime = substr($req->endtime,0,16);


        $check = DB::connection('7sensor')->select("DELETE FROM sensordata WHERE timestamp BETWEEN '$starttime' AND '$endtime' AND probenumber=22 AND value<'0.05' AND serialnumber='$req->serialnumber'");
        return $check;
    }

    public static function changeDBConnection($db_name) {
        Config::set('database.connections.7sensor.database', $db_name);
    }
}
