<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Unit;
use App\Models\Api;
use App\Models\SensorunitVariable;
use App\Models\Status;
use Illuminate\Support\Facades\Log;

use Session, DateTime, DateTimeZone;

class Unit extends Model
{
    public static function getUnitsList()
    {
        if (Session::get('user_id')) {
            $user_id = Session::get('user_id');
        } else {
            $user_id = Auth::user()->user_id;
        }

        $data = Api::getApi('sensorunits/list?user_id='.$user_id);
        // dd($data);
        if (isset($data['result'][0]['customer_site_title'])) {
            Session::put('customer_site_title',$data['result'][0]['customer_site_title']);
        }

        $units = array();
        $irrigation = array();
        $sensorunits = array();
        $productnumbers = array();
        $customerunits = array();
        $sharedunits = array();
        $productinfo = array();

        foreach ($data['result'] as &$unit) {
            $serial = trim($unit['serialnumber']);
            $unit['serialnumber'] = trim($unit['serialnumber']);
            $units[] = $unit;
            $unittype = substr($serial,0,7);
            $producttype = substr($serial,0,10);
            if(!trim($unit['sensorunit_location'])){
                $unit['sensorunit_location'] = trim($unit['serialnumber']);
            }
            if (strcmp($unittype,'21-1020') === 0 || strcmp($unittype,'21-1019') === 0 || strcmp($unittype,'21-1021') === 0 || strcmp($unittype,'21-9020') === 0 || strcmp($unittype,'21-1076') === 0 ) {
                $irrigation[] = $unit;
                //dd($irrigation);
            } else if (strcmp($unittype,'21-1018') === 0 ) {

            } else {
                $unitcustomer = trim($unit['customernumber']);
                if (Session::get('customernumber')) {
                    $customernumber = trim(Session::get('customernumber'));
                } else {
                    Session::put('customernumber', Auth::user()->customernumber);
                    $customernumber = trim(Session::get('customernumber'));
                }

                if (strcmp($unitcustomer,$customernumber) === 0) {
                    $customerunits[$unit['serialnumber']] = $unit;
                    $customerunits[$unit['serialnumber']]['probe'] = self::latestSensorReadings($unit['serialnumber']);
                    usort($customerunits[$unit['serialnumber']]['probe'], function($a, $b) {
                        return $a['probenumber'] <=> $b['probenumber'];
                    });

                } else {
                    $sharedunits['customernumber'][trim($unit['customernumber'])][$unit['serialnumber']] = $unit;
                    $sharedunits['customernumber'][trim($unit['customernumber'])][$unit['serialnumber']]['probe'] = self::latestSensorReadings($unit['serialnumber']);
                    usort($sharedunits['customernumber'][trim($unit['customernumber'])][$unit['serialnumber']]['probe'], function($a, $b) {
                        return $a['probenumber'] <=> $b['probenumber'];
                    });
                }
                $sensorunits[$unit['serialnumber']] = $unit;
                $sensorunits[$unit['serialnumber']]['probe'] = self::latestSensorReadings($unit['serialnumber']);
            }

            if(!in_array($producttype,$productnumbers)) {
                $productnumbers[] = $producttype;
                $productinfo[] = $unit;
            }
        }
        //dd($irrigation);

        Session::put('productnumbers', $productinfo);
        
        /*usort($sharedunits, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });*/
        Session::put('sharedunits', $sharedunits);

        usort($customerunits, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        Session::put('customerunits', $customerunits);

        usort($units, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        Session::put('units', $units);

        usort($irrigation, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        Session::put('irrigation', $irrigation);

        usort($sensorunits, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        Session::put('sensorunits', $sensorunits);

        return $data;
    }

    public static function getUnits()
    {
        $data = Unit::getUnitsList();

        foreach ($data['result'] as $unit) {
            $units[] = trim($unit['serialnumber']);
        }
        sort($units);

        return $units;
    }

    public static function getAllIrrigationSensor() {
        $data = Api::getApi('sensorunits/list?productnumber=21-1020-AA');
        // dd($data);
        $sorted = array();
        $result = array();

        foreach ($data['result'] as &$unit) {
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

        $data = Api::getApi('sensorunits/list?productnumber=21-1020-AB');
        foreach ($data['result'] as &$unit) {
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


        usort($result, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });

        return $result;
    }

    public static function getLatestIrrigation() // DASHBOARD OVERVIEW
    {
        Unit::getUnitsList();
        $units = Session::get('irrigation');
        //dd($units);
        $latestIrrgationReadings = array();
        foreach ($units as $unit) {
            $result = array();
            $variables = Unit::getVariables($unit['serialnumber']);
            $data = Api::getApi('sensorunits/data/latest?serialnumber='.$unit['serialnumber']);
            if(strcmp($unit['serialnumber'], '21-1020-AC-00510') == 0) {
                // dd($unit['serialnumber'], $data);
            }
            foreach ($data['result'] as $probe) {
                if ($probe['probenumber'] == '0'); // ??
                else if ($probe['probenumber'] == '1') $result['vibration'] = trim($probe['value']); // Vibration
                else if ($probe['probenumber'] == '2') $result['water_lost'] = trim($probe['value']); // Water Lost
                else if ($probe['probenumber'] == '3') $result['tilt_alert'] = trim($probe['value']); // Tilt alert
                else if ($probe['probenumber'] == '4') $result['tilt'] = trim($probe['value']); // tilt abs
                else if ($probe['probenumber'] == '5') $result['tilt_relative'] = trim($probe['value']); // tilt relative
                else if ($probe['probenumber'] == '6') ; // ACC X
                else if ($probe['probenumber'] == '7') ; // ACC Y
                else if ($probe['probenumber'] == '8') ; // ACC Z
                else if ($probe['probenumber'] == '9') $result['button_pressed'] = trim($probe['value']); // Button Pressed
                else if ($probe['probenumber'] == '10') $result['temperature'] = trim($probe['value']); // Temperature
                else if ($probe['probenumber'] == '11') $result['rh'] = trim($probe['value']); // Relative Humidity
                else if ($probe['probenumber'] == '12') ; // Unit Barro
                else if ($probe['probenumber'] == '13') $result['lat'] = trim($probe['value']); // LAT
                else if ($probe['probenumber'] == '14') $result['lng'] = trim($probe['value']); // LNG
                else if ($probe['probenumber'] == '15') $result['vbat'] = trim($probe['value']); // Vbat
                // else if ($probe['probenumber'] == '16') $result['tilt_relative'] = trim($probe['value']); // tilt relative
                // else if ($probe['probenumber'] == '17') $result['tilt'] = trim($probe['value']); // tilt abs
                else if ($probe['probenumber'] == '22') $result['pressure'] = trim($probe['value']); // Pressure
                else if ($probe['probenumber'] == '23') $result['flow_velocity'] = trim($probe['value']); // Flow Velocity
                //else if ($probe['probenumber'] == '21') $result['flowrate'] = trim($probe['value']); // Flow rate
            }
            
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
            if (isset($result['irrigation_state'])){
                if ($result['irrigation_state'] == 4 || $result['irrigation_state'] == 5 || $result['irrigation_state'] == 6 ) {
                    $currentRun = Unit::getCurrentRun($unit['serialnumber']);
                    $coordinates = array();
                    if (is_array($currentRun)) {
                        $index = 0;
                        foreach ($currentRun as $run) {
                            if ($run['lat'] != 0 && $run['lng'] != 0) {
                                $coordinates[$index] = $run;
                                $index++;
                            }
                        }
    
                        if (count($coordinates) > 3) {
                            $coordinates_length = count($coordinates);
                            $distance_current = self::getDistance($coordinates[0]['lat'],$coordinates[0]['lng'], $coordinates[$coordinates_length-1]['lat'],$coordinates[$coordinates_length-1]['lng']);
                            $distance_to_active = self::getDistance($coordinates[0]['lat'],$coordinates[0]['lng'], $coordinates[$coordinates_length-1]['lat'],$coordinates[$coordinates_length-1]['lng']);
                            if (count($coordinates) > 15) {
                                $distance_current = self::getDistance($coordinates[$coordinates_length-15]['lat'],$coordinates[$coordinates_length-15]['lng'], $coordinates[$coordinates_length-1]['lat'],$coordinates[$coordinates_length-1]['lng']);
                            }
                            $result['starttime'] = self::hourMinuteUser($coordinates[0]['timestamp']);
                            $starttime = strtotime($coordinates[0]['timestamp']);
                            $currenttime = strtotime($coordinates[$coordinates_length-1]['timestamp']);
                            if (count($coordinates) > 15) {
                                $starttime = strtotime($coordinates[$coordinates_length-15]['timestamp']);
                            }
                            $time_diff = $currenttime - $starttime;
                            $meter_sec = $distance_current / $time_diff;
                            $meter_time = $meter_sec * 3600.0;
                            if (is_nan($meter_time)) $meter_time = 0;
    
                            if (isset($result['irrigation_endpoint']) && $result['irrigation_endpoint'] !== '0,0') {
                                $endpoint = explode(",",$result['irrigation_endpoint']);
                                $distance_total = self::getDistance($coordinates[0]['lat'],$coordinates[0]['lng'], $endpoint[0], $endpoint[1]);
                                $result['total_meters'] = $distance_total;
                                $distance_diff = round($distance_total - $distance_to_active, 1);
                                $result['irrigation_meters'] = $distance_diff;
                                $time_left = ( $distance_diff / $meter_time);
                                $result['percent_done'] = (1 -  ($distance_diff / $distance_total)) * 100;
                                $var = $time_left*3600;
                                $eta = self::timestampUserTimezone($coordinates[$coordinates_length-1]['timestamp'], $var);
                                $result['eta'] = $eta;
                            }
                            $result['speed'] = round($meter_time,1);
                        }
                    }
                }
            }

            $result['timestamp'] = $unit['sensorunit_lastconnect'];
            $result['serialnumber'] = $unit['serialnumber'];
            $result['sensorname'] = $unit['sensorunit_location'];
            array_push($latestIrrgationReadings, $result);
        }
        return $latestIrrgationReadings;
    }
    

    public static function getLatestSensorReadings() 
    {
        Unit::getUnitsList();
        $units = Session::get('sensorunits'); 
        $latestSensorReadings = array();

        foreach ($units as $unit){
            $result = array();
            $unittype = substr($unit['serialnumber'],0,10);
            $unitinformation = Api::getApi('sensorunits/list?serialnumber='.$unit['serialnumber']);
            $data = Api::getApi('sensorunits/data/latest?serialnumber='.$unit['serialnumber']);
            $probeinformation = Api::getApi('sensorprobes/list?productnumber='.$unittype);
            foreach ($data['result'] as $probe) {
                $probenumber = $probe['probenumber'];
                foreach ($probeinformation['result'] as $probeinfo) {
                    if ($probenumber == $probeinfo['sensorprobes_number']) {
                        $row['serialnumber'] = $unit['serialnumber'];
                        $row['probenumber'] = $probeinfo['sensorprobes_number'];
                        $row['hidden'] = $probeinfo['hidden'];
                        $row['sensorprobes_alert_hidden'] = $probeinfo['sensorprobes_alert_hidden'];
                        $row['unittype_icon'] = $probeinfo['unittype_url'];
                        $row['unittype_description'] = $probeinfo['unittype_description'];
                        $row['unittype_decimals'] = $probeinfo['unittype_decimals'];
                        $row['unittype_label'] = $probeinfo['unittype_label'];
                        $row['unittype_shortlabel'] = $probeinfo['unittype_shortlabel'];
                        $row['value'] = $probe['value'];
                        $row['timestamp'] = $probe['timestamp'];
                        $timestamp = $probe['timestamp'];
                        array_push($result, $row);
                    }
                }
            }
            usort($result, function($a, $b) {
                return $a['probenumber'] <=> $b['probenumber'];
            });
            $result['serialnumber'] = $unit['serialnumber'];
            //$result['timestamp'] = $timestamp;
            $result['sensorname'] = $unit['sensorunit_location'];
            array_push($latestSensorReadings, $result);
        }
        usort($latestSensorReadings, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });

        return $latestSensorReadings;
    }

    public static function latestSensorReadings($serial) {

        $result = array();
        $latestSensorReadings = array();
        $unittype = substr($serial,0,10);
        $data = Api::getApi('sensorunits/data/latest?serialnumber='.$serial);
        $probeinformation = Api::getApi('sensorprobes/list?productnumber='.$unittype);
        foreach ($data['result'] as $probe) {
            $probenumber = $probe['probenumber'];
            foreach ($probeinformation['result'] as $probeinfo) {
                if ($probenumber == $probeinfo['sensorprobes_number']) {
                    $result['serialnumber'] = $serial;
                    $result['probenumber'] = $probeinfo['sensorprobes_number'];
                    $result['unittype_id'] = $probeinfo['unittype_id'];
                    $result['hidden'] = $probeinfo['hidden'];
                    $result['sensorprobes_alert_hidden'] = $probeinfo['sensorprobes_alert_hidden'];
                    $result['unittype_icon'] = $probeinfo['unittype_url'];
                    $result['unittype_description'] = $probeinfo['unittype_description'];
                    $result['unittype_decimals'] = $probeinfo['unittype_decimals'];
                    $result['unittype_label'] = $probeinfo['unittype_label'];
                    $result['unittype_shortlabel'] = $probeinfo['unittype_shortlabel'];
                    $result['value'] = $probe['value'];
                    $result['timestamp'] = $probe['timestamp'];
                    array_push($latestSensorReadings, $result);
                }
            }
        }
        
        return $latestSensorReadings;
    }

    public static function latestArrayData($serial) {
        $latestSensorReadings = array();
        $unittype = substr($serial,0,10);
        $data = Api::getApi('sensorunits/data/latest?serialnumber='.$serial); // ?
        $probeinformation = Api::getApi('sensorprobes/list?productnumber='.$unittype);
        foreach ($data['result'] as $probe) {
            $probenumber = $probe['probenumber'];
            foreach ($probeinformation['result'] as $probeinfo) {
                if ($probenumber == $probeinfo['sensorprobes_number']) {
                    $latestSensorReadings[$probenumber]['serialnumber'] = $serial;
                    $latestSensorReadings[$probenumber]['probenumber'] = $probeinfo['sensorprobes_number'];
                    $latestSensorReadings[$probenumber]['unittype_id'] = $probeinfo['unittype_id'];
                    $latestSensorReadings[$probenumber]['hidden'] = $probeinfo['hidden'];
                    $latestSensorReadings[$probenumber]['sensorprobes_alert_hidden'] = $probeinfo['sensorprobes_alert_hidden'];
                    $latestSensorReadings[$probenumber]['unittype_icon'] = $probeinfo['unittype_url'];
                    $latestSensorReadings[$probenumber]['unittype_description'] = $probeinfo['unittype_description'];
                    $latestSensorReadings[$probenumber]['unittype_decimals'] = $probeinfo['unittype_decimals'];
                    $latestSensorReadings[$probenumber]['unittype_label'] = $probeinfo['unittype_label'];
                    $latestSensorReadings[$probenumber]['unittype_shortlabel'] = $probeinfo['unittype_shortlabel'];
                    $latestSensorReadings[$probenumber]['value'] = $probe['value'];
                    $latestSensorReadings[$probenumber]['timestamp'] = $probe['timestamp'];
                }
            }
            // dd($probeinfo);
        }
        
        return $latestSensorReadings;
    }

    public static function getUnitsProbe()
    {
        $units = Session::get('sensorunits');
        $unittypes = array();
        $allProbes = array();
        $result = array();
        if($units) {
            foreach ($units as $unit) {
                if(!in_array(trim($unit['productnumber']),$unittypes)){
    
                    array_push($unittypes, $unit['productnumber']);   
                    $unittype = trim($unit['productnumber']);
                    $probeinformation = Api::getApi('sensorprobes/list?productnumber='.$unittype);
                    foreach($probeinformation['result'] as $probe) {
                        if (count($probe) > 0) {
                            //dd($probeinformation);
                            if (!in_array($probe['unittype_id'],$allProbes)) {
                                array_push($allProbes, $probe['unittype_id']);
                                array_push($result,$probe);
                            }
                        }
                    }
                }
            }
        }

        usort($result, function($a, $b) {
            return $a['unittype_id'] <=> $b['unittype_id'];
        });
        return $result;
        // dd($probeinformation);
    }

    public static function getUnitProbeInfo($serialnumber, $probetype) {
        $unittype = substr($serialnumber,0,10);
        $url = 'productnumber='.$unittype.'&unittype='.$probetype;
        $probeinformation = Api::getApi('sensorprobes/list?'.$url);
        return $probeinformation;
        // dd($probeinformation);
    }

    public static function getAllProbes($serialnumber) {
        $unittype = substr($serialnumber,0,10);
        $url = 'productnumber='.$unittype;
        $probeinformation = Api::getApi('sensorprobes/list?'.$url);
        usort($probeinformation['result'], function($a, $b) {
            return $a['unittype_id'] <=> $b['unittype_id'];
        });
        return $probeinformation;
        // dd($probeinformation);
    }


    public static function getAccess($serialnumber) {
        $data = Api::getApi('sensorunits/access/list?serialnumber='.$serialnumber);
        return $data['result'];
    }


    public static function userAccess($user_id) {
        $url = 'user_id='.$user_id;

        $data = Api::getApi('sensorunits/access/list?'.$url);
        return $data['result'];
    }

    public static function giveAccess($email, $serialnumber, $changeallowed)
    {
        if (Session::get('user_id')) {
            $user_id = Session::get('user_id');
        } else {
            $user_id = Auth::user()->user_id;
        }

        if (isset($email) && isset($serialnumber) && isset($changeallowed)) {
            if ($serialnumber == 'all') {
                $units = Unit::getUnitsList();
                foreach($units['result'] as $unit) {
                    if ($unit['changeallowed']) {
                        $serialnumber = $unit['serialnumber'];
                        $url = 'user_id='.$user_id.'&serialnumber='.$serialnumber.'&user_email='.$email.'&changeallowed='.$changeallowed;
                        $data = Api::postApi('sensorunits/access/grant?'.$url);
                    }
                }
            } else {
                $url = 'user_id='.$user_id.'&serialnumber='.$serialnumber.'&user_email='.$email.'&changeallowed='.$changeallowed;
                $data = Api::postApi('sensorunits/access/grant?'.$url);
            }
        } else {
            $data = 'Missing parameters';
            // Missing parameters
        }
        return $data;
    }

    public static function deleteSensorAccess($userid, $serialnumber, $email) {
        $data = Api::deleteApi('sensorunits/access/delete?user_id='.$userid.'&serialnumber='.$serialnumber.'&user_email='.$email);
        return $data;
    }

    public static function getIrrigationRun($serialnumber)
    {
        $unittype = substr($serialnumber,0,7);
        if (strcmp($unittype,'21-1020') === 0 || strcmp($unittype,'21-1019') === 0 || strcmp($unittype,'21-1021') === 0 || strcmp($unittype,'21-9020') === 0 || strcmp($unittype,'21-1076') === 0 ) {
            $sortfield = "irrigation_run_id DESC";
            $url = 'serialnumber='.$serialnumber.'&sortfield='.$sortfield;
            $data = Api::getApi('irrigation/runlog/list?'.$url);
        }
        return $data;
    }

    public static function getNewestIrrigationLog($serialnumber)
    {
        $unittype = substr($serialnumber,0,7);
        if (strcmp($unittype,'21-1020') === 0 || strcmp($unittype,'21-1019') === 0 || strcmp($unittype,'21-1021') === 0 || strcmp($unittype,'21-9020') === 0 || strcmp($unittype,'21-1076') === 0 ) {
            $data = Unit::getIrrigationRun($serialnumber);
            if(isset($data['result']) && count($data['result']) > 0) return $data['result'][0];
            else return $data;
        }
    }

    public static function getCurrentRun($serialnumber)
    {
        $unittype = substr($serialnumber,0,7);
        if (strcmp($unittype,'21-1020') === 0 || strcmp($unittype,'21-1019') === 0 || strcmp($unittype,'21-1021') === 0 || strcmp($unittype,'21-9020') === 0 || strcmp($unittype,'21-1076') === 0 ) {
            $data = Unit::getNewestIrrigationLog($serialnumber);
            $starttime = $data['irrigation_starttime'] ?? 0;
            $stoptime = $data['irrigation_endtime'] ?? 0;

            
            $timestamp = str_replace('+00', '', $starttime);
            if($stoptime) {
                $stop = substr($stoptime, 0, 19);
                $unitinformation = Api::getApi('sensorunits/data?serialnumber='.$serialnumber.'&timestart='.$timestamp.'&timestop='.$stop);
                // Log::info('Has stoptime sensorunits/data?serialnumber='.$serialnumber.'&timestart='.$timestamp.'&timestop='.$stop);  
            } else {
                if(strtotime($starttime) < strtotime('-3 days')){
                    $starttime = date('Y-m-d h:m:s', strtotime("-2 days", strtotime(now())));
                    // Log::info('New start time for irrigation: '.$starttime);  
                }
                $mytime = now();
                $now = $mytime->toDateTimeString();
                $stop = str_replace('+00', '', $now);
                $unitinformation = Api::getApi('sensorunits/data?serialnumber='.$serialnumber.'&timestart='.$timestamp.'&timestop='.$stop);
                // Log::info('Dont have stoptime sensorunits/data?serialnumber='.$serialnumber.'&timestart='.$timestamp.'&timestop='.$stop);  

            }
            $sorted = array();
            if(is_array($unitinformation)) {
                foreach ($unitinformation['result'] as $row) {
                    $row_count = count($sorted);
                    $check = false;
                    for ($i=0; $i<$row_count; $i++){
                        if (in_array($row['timestamp'], $sorted[$i])) {
                            if ($row['probenumber'] == 13) {
                                $sorted[$i]['lat'] = $row['value'];
                                $check = true;
                            } else if ($row['probenumber'] == 14) {
                                $sorted[$i]['lng'] = $row['value'];
                                $check = true;
                            }
                        }
                    }
        
                    if ($check) {
                        continue;
                    } else if ($row['probenumber'] == 13) {
                        $sorted[] = ['timestamp' => $row['timestamp'], 'lat' =>$row['value'], 'sequencenumber' => $row['sequencenumber']];
                    } else if ($row['probenumber'] == 14) {
                        $sorted[] = ['timestamp' => $row['timestamp'], 'lng' =>$row['value'] , 'sequencenumber' => $row['sequencenumber']];
                    }
                }
                return $sorted;
            } else {
                return "-1";
            }
            
        } else {
            return "Not a valid serial.. Contact support";
        }
    }

    public static function getVariables($serialnumber) {
        $url = 'serialnumber='.$serialnumber;
        $data = Api::getApi('sensorunits/variable/get?'.$url);
        return $data;
    }

    public static function setPoint($serial, $point_id, $latlng, $distance) {
        if ($serial) {
            if ($point_id == 0) {
                $url = 'serialnumber='.$serial.'&variable=irrigation_endpoint&value='.$latlng;
                if ($distance > 0) {
                    $url1 = 'serialnumber='.$serial.'variable=irrigation_meters&value='.$distance;
                    $data1 = Api::patchApi('sensorunits/variable/update?'.$url1);
                }
                
            } else if ($point_id == 1) {
                $url = 'serialnumber='.$serial.'&variable=irrigation_poi_1&value='.$latlng;
            } else if ($point_id == 2) {
                $url = 'serialnumber='.$serial.'&variable=irrigation_poi_2&value='.$latlng;
            } else {
                return "Could not determine which point to set";
            }
            $data = Api::patchApi('sensorunits/variable/update?'.$url);
            return $data;
        } else {
            return "Something went wrong, missing serialnumber";
        }
    }
    
    public static function getSensorunits(){
        $data = Api::getApi('sensorunits/all?sortfield=serialnumber');
        return $data['result'];
    }

    public static function getSensorData($serialnumber,$days,$probe) 
    {
        $url = 'serialnumber='.$serialnumber.'&days='.$days.'&probenumber='.$probe.'&sortfield=timestamp';
        $data = Api::getApi('sensorunits/data?'.$url);
        if ($data == null) return [];
        // dd($data);
        return $data;
    }

    public static function getProbeSettings() 
    {
        Unit::getUnitsList();
        $counter = -1;
        $units = Session::get('sensorunits');
        if(count($units) > 0) {
            $counter = 0;
        }
        $getSensorSettings = array();
        foreach ($units as $unit){
            $result = array();
            $unittype = substr($unit['serialnumber'],0,10);
            $unitinformation = Api::getApi('sensorunits/list?serialnumber='.$unit['serialnumber']);
            $probeinformation = Api::getApi('sensorprobes/list?productnumber='.$unittype);
            $variables = Api::getApi('sensorprobes/variable/list?serialnumber='.$unit['serialnumber']);
            foreach ($probeinformation['result'] as $probeinfo) {
                if (!$probeinfo['sensorprobes_alert_hidden']) {
                    $row['serialnumber'] = $unit['serialnumber'];
                    $row['probenumber'] = $probeinfo['sensorprobes_number'];
                    $row['unittype_icon'] = $probeinfo['unittype_url'];
                    $row['unittype_description'] = $probeinfo['unittype_description'];
                    $row['unittype_label'] = $probeinfo['unittype_label'];
                    $row['unittype_shortlabel'] = $probeinfo['unittype_shortlabel'];
                    $row['sensorprobes_alert_hidden'] = $probeinfo['sensorprobes_alert_hidden'];

                    foreach ($variables['result'] as $variable) {
                        if (trim($variable['serialnumber']) == trim($unit['serialnumber']) ) {
                            if ($variable['sensorprobe_number'] == $probeinfo['sensorprobes_number']) {
                                if (trim($variable['variable']) == 'sensorprobe_send_email'){
                                    $row['email_enabled'] = trim($variable['value']);
                                    $counter += 1;
                                } 
                                if (trim($variable['variable']) == 'sensorprobe_repeat_before_trigger'){
                                    $row['repeats'] = trim($variable['value']);
                                    $counter += 1;
                                }
                                if (trim($variable['variable']) == 'sensorprobe_upper_threshold'){
                                    $row['upper_thersholds'] = trim($variable['value']);
                                    $counter += 1;
                                }
                                if (trim($variable['variable']) == 'sensorprobe_lower_threshold'){
                                    $row['lower_thersholds'] = trim($variable['value']);
                                    $counter += 1;
                                }
                                if (trim($variable['variable']) == 'sensorprobe_note'){
                                    $row['sensorprobe_note'] = trim($variable['value']);
                                    $counter += 1;
                                }
                                if (trim($variable['variable']) == 'sensorprobe_send_sms'){
                                    $row['sms_enabled'] = trim($variable['value']);
                                    $counter += 1;
                                }
                            }
                        }
                    }
                    array_push($result, $row);
                }
            }
            $result['sensorunit_note'] = $unit['sensorunit_note'];
            $result['serialnumber'] = $unit['serialnumber'];
            $result['sensorname'] = $unit['sensorunit_location'];
            $result['tree_specie'] = SensorunitVariable::where('serialnumber', $unit['serialnumber'])->where('variable', 'tree_species')->first();
            array_push($getSensorSettings, $result);
        }

        Session::put('settingscounter', $counter);

        usort($getSensorSettings, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        return $getSensorSettings;
    }

    public static function getIrrigationSettings() {
        Unit::getUnitsList();

        $units = Session::get('irrigation');
        $getIrrigationSettings = array();
        foreach ($units as $unit){
            $result = array();
            $variables = Api::getApi('sensorunits/variable/get?serialnumber='.$unit['serialnumber']);
            // dd( $variables);
            foreach ($variables['result'] as $variable) {
                if (trim($variable['serialnumber']) == trim($unit['serialnumber']) ) {
                    if (trim($variable['variable']) == 'irrigation_nozzlewidth'){
                        $result['irrigation_nozzlewidth'] = trim($variable['value']);
                    } 
                    if (trim($variable['variable']) == 'irrigation_nozzlebar'){
                        $result['irrigation_nozzlebar'] = trim($variable['value']);
                    }
                    if (trim($variable['variable']) == 'irrigation_nozzleadjustment'){
                        $result['irrigation_nozzleadjustment'] = trim($variable['value']);
                    }
                    if (trim($variable['variable']) == 'irrigation_points2calcwagonstop'){
                        $result['irrigation_points2calcwagonstop'] = trim($variable['value']);
                    }
                    if (trim($variable['variable']) == 'irrigation_note'){
                        $result['irrigation_note'] = trim($variable['value']);
                    }
                    if (trim($variable['variable']) == 'irrigation_tilt'){
                        // $tilt = (((trim($variable['value'])*90.0)-90.0)*-1.0);
                        // $result['irrigation_tilt'] =  round($tilt,0); // Z-axis
                        $result['irrigation_tilt'] = trim($variable['value']); // Z-axis
                    }
                    if (trim($variable['variable']) == 'irrigation_endpoint_radius'){
                        $result['irrigation_endpoint_radius'] = trim($variable['value']);
                    }
                }
            }
           
            
            $result['irrigation_pressure_bar'] = Status::select('value')->where('serialnumber', $unit['serialnumber'])->where('variable', 'pressure_threshold_low')->first();
            $result['sensorunit_note'] = $unit['sensorunit_note'];
            $result['serialnumber'] = $unit['serialnumber'];
            $result['sensorname'] = $unit['sensorunit_location'];
            array_push($getIrrigationSettings, $result);
        }

        usort($getIrrigationSettings, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        return $getIrrigationSettings;
    }

    public static function updateSensorSettings($serial, $variable, $value) {
        $data = Api::patchApi('sensorunits/variable/update?serialnumber='.$serial.'&variable='.$variable.'&value='.$value);

        /*if($variable == 'irrigation_tilt') {
            if ($data['result'] == 'OK') {
                //dd(round($value,4), 8, 2, 'NAN', $value);
            }
        }*/
        
        return $data;
    }

    public static function updateSensorprobes($serial, $probe, $variable, $value) {
        $data = Api::patchApi('sensorprobes/variable/update?serialnumber='.$serial.'&sensorprobe_number='.$probe.'&variable='.$variable.'&value='.$value);
        return $data;
    }

    public static function updateSensorunits($serial, $sensorname, $sensornotes) {
        $data = Api::patchApi('sensorunits/update?serialnumber='.$serial.'&sensorunit_location='.$sensorname.'&sensorunit_note='.$sensornotes);
        return $data;
    }

    public static function startIrrigation($serial, $variable) {
        if ($variable == '1') {
            $data[] = Api::patchApi('sensorunits/variable/update?serialnumber='.$serial.'&variable=irrigation_portalstart&value=1');
            $data[] = Api::patchApi('sensorunits/variable/update?serialnumber='.$serial.'&variable=irrigation_portalstop&value=0');
        } else {
            $data[] = Api::patchApi('sensorunits/variable/update?serialnumber='.$serial.'&variable=irrigation_portalstop&value=1');
            $data[] = Api::patchApi('sensorunits/variable/update?serialnumber='.$serial.'&variable=irrigation_portalstart&value=0');
        }

        return $data;
    }

    public static function getGroup($customernumber) {
        $data = Api::getApi('gui/viewgroup/list?customernumber='.$customernumber.'&sortfield=viewgroup_id');
        return $data['result'];
    }

    public static function getOrder($serial) {
        $data = Api::getApi('gui/viewgroup/order/list?serialnumber='.$serial);
        return $data['result'];
    }

    public static function setOrder($serial, $group, $index) {
        $data = Api::patchApi('gui/viewgroup/order/update?serialnumber='.$serial.'&viewgroup_id_ref='.$group.'&viewgroup_order='.$index);
        
        return $data;
    }

    public static function setGroup($id, $name, $description) {
        $data = array();
        if (isset($id)) {
            $url = 'viewgroup_id='.$id;
            if (isset($name)) {
                $url .='&viewgroup_name='.$name;
            }
            if (isset($description)) {
                $url .='&viewgroup_description='.$description;
            }
            $data = Api::patchApi('gui/viewgroup/update?'.$url);
        }
        
        return $data;
    }

    public static function deleteOrder($serialnumber) {
        $data = Api::deleteApi('gui/viewgroup/order/delete?serialnumber='.$serialnumber);
        return $data;
    }

    public static function deleteGroup ($id, $customernumber) {
        $data = array();
        if (trim($customernumber) == trim(Session::get('customernumber'))) {
            $customerunits = Session::get('customerunits');
            foreach ($customerunits as $unit) {
                $result = self::getOrder($unit['serialnumber']);
                if(is_array($result)){
                    foreach ($result as $row){
                        if ($row['viewgroup_id'] == $id) {
                            $data[] = self::deleteOrder($unit['serialnumber']);
                        }
                    }
                }
            }
            $data[] = Api::deleteApi('gui/viewgroup/delete?viewgroup_id='.$id);
            
            return $data;
        } else {

            return 'Customernumber is not equal.';
        }
    }

    public static function addOrder($serial, $group, $index) {
        $data = Api::postApi('gui/viewgroup/order/add?serialnumber='.$serial.'&viewgroup_id_ref='.$group.'&viewgroup_order='.$index);
        
        return $data;
    }
    public static function addGroup($customernumber, $name) {
        $data = Api::postApi('gui/viewgroup/add?customernumber='.$customernumber.'&viewgroup_name='.$name);

        return $data;
    }

    

    /* Helper Functions */
    public static function timestampUserTimezone($timestamp, $var) {
        if (Session::get('timezone')) {
            $timezone = Session::get('timezone');
        } else {
            $timezone = 'Europe/London';
        }
        
        $datetime = new DateTime($timestamp);
        $datetime->setTimezone(new DateTimeZone($timezone));
        $manipulatedTimestamp = $datetime->format('Y-m-d H:i:s');
        $seconds = strtotime($manipulatedTimestamp) + $var;
        $time = date('H:i', $seconds);
        return $time;
    }

    public static function hourMinuteUser($timestamp) {
        if (Session::get('timezone')) {
            $timezone = Session::get('timezone');
        } else {
            $timezone = 'Europe/London';
        }
        
        $datetime = new DateTime($timestamp);
        $datetime->setTimezone(new DateTimeZone($timezone));
        $manipulatedTimestamp = $datetime->format('Y-m-d H:i:s');
        $seconds = strtotime($manipulatedTimestamp);
        $time = date('H:i', $seconds);
        return $time;
    }

    public static function rad($x) {
        return $x * 3.14159265 / 180;
    }

    public static function getDistance($p1Lat, $p1Lon, $p2Lat, $p2Lon) {
        $R = 6378137; // Earth’s mean radius in meter
        $dLat = self::rad($p2Lat - $p1Lat);
        $dLon = self::rad($p2Lon - $p1Lon);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(self::rad($p1Lat)) * cos(self::rad($p2Lat)) *
                sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        return $d; // returns the distance in meter
    }

}

