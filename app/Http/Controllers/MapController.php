<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Http\Controllers\DashboardController;
use App\Models\Irrigationrun;
use App\Models\Api;

use DateTime, Log, DB;

class MapController extends Controller {

    public static function irrigationunit($serial) {
        $data = Unit::getCurrentRun($serial);
        $variables = Unit::getVariables($serial);
        $state = 0;

        foreach ($variables['result'] as $variable) {
            if (trim($variable['variable']) == 'irrigation_state') {
                $result['irrigation_state'] = trim($variable['value']);
                $result['state_timestamp'] = $variable['dateupdated'];
            } else if (trim($variable['variable']) == 'irrigation_endpoint') {
                $result['irrigation_endpoint'] = trim($variable['value']);
            } else if (trim($variable['variable']) == 'irrigation_meters') {
                $result['irrigation_meters'] = trim($variable['value']);
            } else if (trim($variable['variable']) == 'irrigation_portalstart') {
                $result['irrigation_portalstart'] = trim($variable['value']);
            } else if (trim($variable['variable']) == 'irrigation_portalstop') {
                $result['irrigation_portalstop'] = trim($variable['value']);
            } else if (trim($variable['variable']) == 'irrigation_endpoint') {
                $result['irrigation_endpoint'] = trim($variable['value']);
            } else if (trim($variable['variable']) == 'irrigation_poi_1') {
                $result['irrigation_poi_1'] = trim($variable['value']);
            } else if (trim($variable['variable']) == 'irrigation_poi_2') {
                $result['irrigation_poi_2'] = trim($variable['value']);
            }
        }
        

        if ($data == -1) {
            return view('pages.map')->with('serial', $serial)->with('message', 'Waiting for first GPS position');
        }
        
        $sorted = array();
        foreach ($data as $interval) {
            if (isset($interval['lat']) && $interval['lat'] != 0 && isset($interval['lng']) && $interval['lng'] != 0) {
                $sorted[] = $interval; 
            }
        }

        if (isset($result['irrigation_state'])) {
            $state = $result['irrigation_state'];
            if (count($sorted) == 0 && $state == 1) {
                return view('pages.map')->with('serial', $serial)->with('message', '1E: Waiting for first GPS Position.');
            } else if (count($sorted) == 0 && $state == 0) {
                return view('pages.map')->with('serial', $serial)->with('message', '0E: Press the button or start the sensor remote.');
            } else if (count($sorted) == 0 && $state == 2 && $result['irrigation_endpoint'] == '0,0') {
                
                return view('pages.map')->with('serial', $serial)->with('message', '2E: Waiting for first GPS Position.');
            } else if (count($sorted) == 0 && $state == 3) {
                return view('pages.map')->with('serial', $serial)->with('errormessage', 'Something went wrong, please restart the sensor by pushing the button.');
            }
        }
        //dd(isset($result['irrigation_endpoint']));
        $irrigationrun = Unit::getNewestIrrigationLog($serial);
        return view('pages.map')->with('variables', $result)->with('data', $sorted)->with('serial', $serial)->with('irrigationrun', $irrigationrun);
    }

    public function irrigationrun($serial) {
        $data = Unit::getCurrentRun($serial);
        $sorted = array();
        $i = 0;
        foreach ($data as &$interval) {
           $interval['timestamp'] = self::convertTimestampToUserTimezone($interval['timestamp']);
            if (isset($interval['lat']) && $interval['lat'] != 0 && $interval['lng'] && $interval['lng'] != 0) {
                $sorted[$i]['lat'] = (float)$interval['lat'];
                $sorted[$i]['lng'] = (float)$interval['lng'];
                $sorted[$i]['timestamp'] = $interval['timestamp'];
                $sorted[$i]['vibration'] = (float)$interval['vibration'];
                $i++;
            }
        }
        return $sorted;
    }

    public function oldRunMap($serial) {
        $data = self::oldIrrigaitonRun($serial, 380);
        return view('pages.irrigationrun', compact('data'))->with('serial', $serial);
    }

    public function oldIrrigaitonRun($serial, $days) {
        $data = Unit::getIrrigationRun($serial);
        $sorted = array();
        $history_time = time() - ($days * 24 * 60 * 60);
        foreach ($data['result'] as $run) {
            $startpoint = trim($run['irrigation_startpoint']);
            $endpoint = trim($run['irrigation_endpoint']);
            if ($startpoint !== '0,0') {
                if($endpoint && $endpoint != '0.0,0.0') {
                    $strtotime = strtotime($run['irrigation_starttime']);
                    if ($strtotime > $history_time) {
                        $start = explode(',', $startpoint);
                        $end = explode(',', $endpoint);

                        $color_time = time() - (7 * 24 * 60 * 60);
                        if ($strtotime < $color_time)  {
                            $color = false;
                        } else {
                            $color = true;
                        }

                        $timestamp = self::convertTimestampToUserTimezone($run['irrigation_starttime']);
                        $now = self::convertTimestampToUserTimezone(now());
                        $sorted[$run['irrigation_run_id']]['run_id'] = (int)$run['irrigation_run_id'];
                        $sorted[$run['irrigation_run_id']]['startpoint_lat'] = (float)$start[0];
                        $sorted[$run['irrigation_run_id']]['startpoint_lng'] = (float)$start[1];
                        $sorted[$run['irrigation_run_id']]['endpoint_lat'] = (float)$end[0];
                        $sorted[$run['irrigation_run_id']]['endpoint_lng'] = (float)$end[1];
                        $sorted[$run['irrigation_run_id']]['starttime'] = $timestamp;
                        $sorted[$run['irrigation_run_id']]['endtime'] = self::convertTimestampToUserTimezone($run['irrigation_endtime']);
                        $sorted[$run['irrigation_run_id']]['irrigation_nozzleadjustment'] = trim($run['irrigation_nozzleadjustment']);
                        $sorted[$run['irrigation_run_id']]['irrigation_nozzlebar'] = $run['irrigation_nozzlebar'];
                        $sorted[$run['irrigation_run_id']]['irrigation_nozzlewidth'] = $run['irrigation_nozzlewidth'];
                        $sorted[$run['irrigation_run_id']]['irrigation_note'] = trim($run['irrigation_note']);
                        $sorted[$run['irrigation_run_id']]['hidden'] = $run['hidden'];
                        $sorted[$run['irrigation_run_id']]['irrigation_endtime'] = self::convertTimestampToUserTimezone($run['irrigation_endtime']);
                        $sorted[$run['irrigation_run_id']]['green'] = $color;
                        $sorted[$run['irrigation_run_id']]['days'] = self::daysSince($timestamp, $now);
                    }
                }
            }
        }

        foreach ($sorted as $run) {
            if (isset($run['run_id'])) {                                 
                $distance = 0;
                $distance = self::getDistance($run['startpoint_lat'], $run['startpoint_lng'], $run['endpoint_lat'], $run['endpoint_lng']);
                if ($distance > 1000 || $distance < -1000) {
                    unset($sorted[$run['run_id']]);
                }
                if (($distance > -30 && $distance < 30)) {
                    unset($sorted[$run['run_id']]);
                }
            }
        }

        foreach ($sorted as $run) {
            if (isset($run['run_id'])) {
                foreach ($sorted as $comparerun) {
                    if ($run['run_id'] !== $comparerun['run_id']) {
                        $distance = 0;
                        $distance = self::getDistance($comparerun['startpoint_lat'], $comparerun['startpoint_lng'], $run['startpoint_lat'], $run['startpoint_lng']);
                        if (($distance > -30 && $distance < 30)) {
                            if (strtotime($run['starttime']) > strtotime($comparerun['starttime'])) {
                                unset($sorted[$comparerun['run_id']]);
                            }
                        }
                    }
                }
            }
        }
        return $sorted;
    }

    public function updatePoint(Request $request)
    {
        self::setActivity("Irrigation point updated", "updatepoint");
        $lat = $request->lat;
        $lng = $request->lng;
        $distance = $request->distance;
        $point_id = $request->point_id;
        $serial = $request->serial;
        (string)$latlng = $lat.','.$lng;
        $data = Unit::setPoint($serial, $point_id, $latlng, $distance);
        
        return $data;
    }

    public function fleetmanagement() {
        self::setActivity("Entered fleetmanagement", "fleetmanagement");
        $irrigationunits = Unit::getLatestIrrigation();
        foreach($irrigationunits as &$unit) {
            $unit['currentRun'] = Unit::getNewestIrrigationLog($unit['serialnumber']);
            if(isset($unit['currentRun']['irrigation_starttime']) && $unit['currentRun']['irrigation_starttime']) $unit['currentRun']['starttime'] = DashboardController::getTimestampComment(DashboardController::getTimestampDifference($unit['currentRun']['irrigation_starttime']), DashboardController::convertTimestampToUserTimezone($unit['currentRun']['irrigation_starttime']));
            if(isset($unit['currentRun']['irrigation_endtime']) && $unit['currentRun']['irrigation_endtime']) $unit['currentRun']['endtime'] = DashboardController::getTimestampComment(DashboardController::getTimestampDifference($unit['currentRun']['irrigation_endtime']), DashboardController::convertTimestampToUserTimezone($unit['currentRun']['irrigation_endtime']));

        }
        return view('pages.fleetmanagement', compact('irrigationunits'));
    }

    public static function daysSince($starttime, $now) {
        $datediff = date_diff(new DateTime($starttime), new DateTime($now));

        return $datediff->format('%a');
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

        $config = DB::connection('sensordata')->select('SELECT * FROM config WHERE serialnumber = ? ORDER BY variable ASC', [$run->serialnumber]);
        foreach ($config as $variable) {
            $response['config'][trim($variable->variable)] = trim($variable->value);
        }


        $response['run'] = $run;
        $response['log'] = array_values($temp);
        return json_encode($response);
    }

    public function irrigationFlow(Request $req) {
        $run = Irrigationrun::where('log_id', $req->id)->first();
        $start = new DateTime($run->irrigation_starttime);
        $stop = new DateTime($run->irrigation_endtime);
        $interval = $start->diff($stop);
        $minutes = $interval->format('%i');
        $hours = $interval->format('%h');

        $A = 0.005410608;
        $A = 0.0059032;
        $readable =  $interval->format('%h hour(s) %i minutes(s)');

        $data = Api::getApi('sensorunits/data?serialnumber='.$run->serialnumber.'&timestart='.substr($run->irrigation_starttime, 0, 19).'&timestop='.substr($run->irrigation_endtime, 0, 19).'&sortfield=timestamp');
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

        $response['min'] = round($min, 3); // m/s
        $response['max'] = round($max, 3); // m/s
        $response['avg'] = round($flow_v, 3); // m/s
        $response['flowrate'] = round($flow,2); // m3/h*
        $response['time'] = $readable;
        $response['water_applied'] = round($total_flow,2); // m3*

        return json_encode($response);
    }

    public function updateNotes(Request $req) {
        $run = Irrigationrun::where('log_id', $req->id)->first();
        $run->irrigation_note = $req->notes;
        $response = $run->save();
        return $response;
    }

}
