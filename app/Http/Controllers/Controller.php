<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Unit;
use App\Models\Sensorunit;
use App\Models\Irrigationrun;
use App\Models\Api;
use Lang;
use App\Models\Customer;
use App\Models\Treespecies;
use Auth, Session, Redirect, DateTime, DateTimeZone, DB;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\MapController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkusername');
        $this->middleware('messages');
        $this->middleware('language');
    }

    public function dashboard() 
    {
        if(self::checkSubscription() == 0) return view('pages.subscriptionfailed');
        if(self::checkCustomer() == 287) return view('test_folder.demo_uk');

        $irrigationunits = Unit::getLatestIrrigation();

        $sensorunits = DashboardController::getOrder();
        $sensorunits = DashboardController::processSensorArray($sensorunits);

        // Unit::getProbeSettings();
        // $settingscounter = Session::get('settingscounter');
        // if ($settingscounter == 0) {
        //     $settings = 1;
        //     Session::put('settingserror', $settings);
        // } else {
        //     $settings = 0;
        //     Session::put('settingserror', $settings);
        // }

        return view('pages.dashboard', compact('irrigationunits'), compact('sensorunits'));
    }

    public function support() 
    {
        $data = Unit::getUnitsList();
        $units = $data['result'];
        usort($units, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });

        foreach ($units as &$unit) {
            if (isset($unit['sensorunit_lastconnect'])) {
                $timediff= self::getTimestampDifference($unit['sensorunit_lastconnect']);
                $unit['date_time'] = self::convertToDate($unit['sensorunit_lastconnect']);
                if ($timediff < 3600) {
                    $unit['color'] = '#159864';
                } else {
                    if ($timediff < 9200) {
                        $unit['color'] = '#DFC04F';
                    } else {
                        $unit['color'] = '#D10D0D';
                    }
                }
            } else {
                $unit['color'] = '#D10D0D';
            }
        }
        
        return view('pages.support')->with('units', $units);
    }

    public function graph()
    {
        if(self::checkSubscription() == 0) return view('pages.subscriptionfailed');

        $probes = Unit::getUnitsProbe();
        $unit = Unit::getUnitsList();
        $sensorunits = DashboardController::getOrder();
        $sensorunits = DashboardController::processSensorArray($sensorunits);

        return view('pages.graph', compact('probes', 'unit', 'sensorunits'));
    }

    public function myaccount() {
        return view('pages.myaccount');
    }

    public function settings() {
        $sensorunits = Unit::getProbeSettings();
        $irrigationunits = Unit::getIrrigationSettings();
        $customersettings = Customer::getCustomerVariable();
        $users = Customer::getUsers(Session::get('customernumber'));
        $treespecies = Treespecies::all();

        return view('pages.settings', compact('sensorunits', 'customersettings', 'users'), compact('treespecies'))->with('irrigationunits', $irrigationunits);
    }

    public function settingsid($id) {
        $sensorunits = Unit::getProbeSettings();
        $irrigationunits = Unit::getIrrigationSettings();
        $customersettings = Customer::getCustomerVariable();
        $users = Customer::getUsers(Session::get('customernumber'));
        $treespecies = Treespecies::all();
        // dd($sensorunits);
        return view('pages.settings', compact('sensorunits', 'customersettings', 'users'), compact('treespecies'))->with('irrigationunits', $irrigationunits)->with('page',$id);
    }

    public function messages() 
    {
        if(self::checkSubscription() == 0) return view('pages.subscriptionfailed');
        $messages = MessagesController::getMessages();
        MessagesController::checkedUser();
        
        return view('pages.messages', compact('messages'));
    }
    public function testmap() 
    {   
        return view('pages.old.test_map');
    }

    public function sensorunit ($serial) {
        $unittype = substr($serial,0,7);
        if (strcmp($unittype,'21-1020') === 0 || strcmp($unittype,'21-1019') === 0 || strcmp($unittype,'21-1021') === 0 || strcmp($unittype,'21-9020') === 0 || strcmp($unittype,'21-1076') === 0 ) {
            $data = MapController::irrigationunit($serial);

            return $data;

        } else if (strcmp($unittype,'21-1018') === 0 ) {
            echo "Gateway with no probe";
        } else if (strcmp($unittype,'21-1065') === 0 ) {
            $unit = Sensorunit::where('serialnumber', $serial)->first();
            $result = Unit::latestArrayData($serial);
            DashboardController::probeProcess($result);
            ksort($result);
            $unit['probe'] = $result;
            foreach($unit['probe'] as $probe) {
                if($probe['unittype_id'] == 46) {
                    $unit['state'] = $probe['value'];
                }
            }
            $api = DB::connection('sensordata')->select('SELECT * FROM status WHERE serialnumber = ?', [$serial]);
            if($api) {
                foreach ($api as $row) {
                    if(trim($row->variable) == 'dip_switch') {
                        $row->value = str_replace('0x', '', $row->value);
                    }
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
            return view('pages.product.1065', compact('serial', 'unit'));
        } else {
            $data = Api::getApi('sensorunits/list?serialnumber='.$serial);
            $result = Unit::latestArrayData($serial);
            DashboardController::probeProcess($result);
            ksort($result);
            $unit = $data['result'][0];
            $unit['probes'] = $result;
            return view('pages.unitdetails', compact('serial', 'unit'));
        }
    }

    public function phoneEndpoint () {
        $serial = request()->unit;
        $lat = request()->lat;
        $lng = request()->lng;
        $variables = array();
        if (isset($lat)) {
            $phone_lat_lng = $lat;
            $phone_lat_lng .= ',' .$lng;
        }
        $acc = request()->accuracy;
        $irrigationunits = Session::get('irrigation');
        foreach ($irrigationunits as $unit) {
            $owner = in_array($serial, $unit);
            if ($owner == true) {
                $data = Unit::getCurrentRun($serial);
                $variables = Unit::getVariables($serial);
                $state = 0;
                foreach ($variables['result'] as $variable) {
                    $variables[$variable['variable']] = trim($variable['value']);
                }
                $sorted = array();
                if (is_array($data)) {
                    foreach ($data as $interval) {
                        if ($interval['lat'] != 0 && $interval['lng'] != 0) {
                            $sorted[] = $interval; 
                        }
                    }    
                }
                if(!isset($phone_lat_lng)) {
                    if (isset($result['irrigation_state'])) {
                        $state = $result['irrigation_state'];
                        if (count($sorted) == 0 && $state == 2) {
                            $message = '1E: Waiting for first GPS Position.';
                        } else if (count($sorted) == 0 && $state == 1) {
                            $message = '0E: Press the button or start the sensor remote.';
                        } else if (count($sorted) == 0 && $state == 2 && $result['irrigation_endpoint'] == '0,0') {
                            $message = '2E: Waiting for first GPS Position.';
                        } 
                        //else if (count($sorted) == 0 && $state == 3) {
                        //     return view('pages.map')->with('serial', $serial)->with('errormessage', 'Something went wrong, please restart the sensor by pushing the button.');
                        // }
                    }
                }
                
                $irrigationrun = Unit::getNewestIrrigationLog($serial);
                if(isset($phone_lat_lng)) {
                    return view('pages.map')->with('serial', $serial)->with('phone_lat_lng', $phone_lat_lng)->with('irrigationrun', $irrigationrun)->with('variables', $result)->with('data', $sorted);
                } else {
                    if(isset($message)) {
                        return view('pages.map', compact('variables', 'irrigationrun', 'serial'))->with('data', $sorted)->with('message', $message);
                    } else {
                        return view('pages.map', compact('variables', 'irrigationrun', 'serial'))->with('data', $sorted);
                    }
                }
            }
        }
        //return 'Not premissions to set endpoint for this sensor';
        Session::flash('errormessage', 'You do not have premissions to view this unit');
        return Redirect::to('dashboard');
    }

    public function irrigationRuns() {
        $irrigationunits = Session::get('irrigation');
        return view('pages.irrigationlog');
    }

    public function getIrrigationEvents() {
        $irrigationruns = Irrigationrun::where('serialnumber', '21-1020-AA-00096')->get();
        return $irrigationruns;
    }

    /* Helper functions */

    public static function getUserTimezone() {
        if (Session::get('timezone')) {
            $timezone = Session::get('timezone');
        } else {
            $ip = \Request::ip();
            $ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
            $ipInfo = json_decode($ipInfo);
            $timezone = $ipInfo->timezone;
            Session::put('timezone', $timezone);
            date_default_timezone_set($timezone);
            if (!$timezone) {
                $timezone = 'Europe/Oslo';
            }
        }

        return $timezone;
    }

    public static function convertTimestampToUserTimezone($timestamp) {
        $datetime = new DateTime($timestamp);
        $datetime->setTimezone(new DateTimeZone(self::getUserTimezone()));

        return $datetime->format('d.m.Y H:i:s');
    }

    public static function convertToDate($timestamp) {
        $datetime = new DateTime($timestamp);
        $datetime->setTimezone(new DateTimeZone(self::getUserTimezone()));

        return $datetime->format('d.m.Y H:i');
    }

    public static function convertToSortableDate($timestamp) {
        $datetime = new DateTime($timestamp);
        $datetime->setTimezone(new DateTimeZone(self::getUserTimezone()));

        return $datetime->format('Y.m.d H:i');
    }

    public static function getTimestampDifference($timestamp) {
        date_default_timezone_set(self::getUserTimezone());
        $timestampWithUserTimezone = self::convertTimestampToUserTimezone($timestamp);
        $timestampInSeconds = strtotime($timestampWithUserTimezone);
        $currentTimeInSeconds=time();
        $timestampDifference = $currentTimeInSeconds - $timestampInSeconds;

        return $timestampDifference;
    }

    public static function convertWoodMoisture($value, $temperature, $a, $b) {
        if (($value != -10) && ($value > - 100)) {
            $woodtemp = $temperature;
            $M_ohm = $value / 1000000.0;
            $temporary = (log10(log10($M_ohm) + 1) - $b) / $a;
            $woodmoisture = ($temporary + 0.567 - 0.026 * ($woodtemp + 2.8) + 0.000051 * (pow(($woodtemp + 2.8),2))) / (0.881 * (pow((1.0056),($woodtemp  + 2.8))));
        } else {
            $woodmoisture = 0;
        }

        if ($woodmoisture > 50) {
            $woodmoisture = 50;
        } else if ($woodmoisture < 6) {
            $woodmoisture = 6;
        }
        round($woodmoisture,2);
        return $woodmoisture;
    }


    public static function convertWoodTemp($value) {
        $resistance = $value * 100.0;
        $woodtmp = ((10.888 - sqrt(118.548544 + 0.01388 * (1777.3 - $resistance))) / -0.00694) + 30;
        round($woodtmp,2);
        return $woodtmp;
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

    public function checkSubscription() {
        if (Session::get('user_id')) {
            $user_id = Session::get('user_id');
        } else {
            $user_id = Auth::user()->user_id;
        }
        $user = User::find($user_id);
        $customer = Customer::find($user->customer_id_ref);
        
        if($customer->paid_subscription) {
            return 1;
        } else {
            return 0;
        } 
    }

    public function checkCustomer() {
        if (Session::get('user_id')) {
            $user_id = Session::get('user_id');
        } else {
            $user_id = Auth::user()->user_id;
        }
        $user = User::find($user_id);
        return $user->customer_id_ref;
    }

}