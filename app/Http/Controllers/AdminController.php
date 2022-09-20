<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\Sensorunit;
use App\Models\User;
use App\Models\Customer;
use App\Models\Api;
use App\Models\Product;
use App\Models\Status;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SensorunitController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnittypeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use Auth, Redirect, Session;

class AdminController extends Controller {
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
        $this->middleware('language');
    }

    public function dashboard() {

        $units = Sensorunit::all();
        $products = Product::all();
        $customers = Customer::all();
        $users = User::all();

        $count['units'] = $units->count();
        $count['products'] = $products->count();
        $count['customers'] = $customers->count();
        $count['users'] = $users->count();

        return view('admin.dashboard', compact('count'));
    }

    public function setUser($userid, $customernumber) {

        //$userid = $request->userid;
        //$customernumber = $request->customernumber;
        Session::put('user_id',$userid);
        Session::put('customernumber', $customernumber);
        return Redirect::to('dashboard');
    }

    public function getSensorunits() {
        $sensorunits = json_encode(SensorunitController::getSensorunits(1));
        return view('admin.sensorunit')->with('data', $sensorunits);
    }

    public function user(){
        return view('admin.user.view');
    }

    public function getProbes() {
        $products = json_encode(ProductController::getProducts(1));
        return view('admin.probe')->with('data', $products);
    }

    public function getProducts() {
        $products = json_encode(ProductController::getProducts(1));
        //$products = Product::orderBy('product_id','ASC')->get();
        return view('admin.products', compact('products'));
    }

    public function customer() {
        $customers = json_encode(CustomerController::getCustomers(1));
        return view('admin.customer')->with('data', $customers);
    }

    public function apismartsensor () {
        $customers = DB::connection('sensordata')->select('SELECT * FROM customers');
        return view('admin.apismartsensor.dashboard')->with('customers', $customers);
    }

    public function getUnittypes() {
        $data = json_encode(UnittypeController::getUnittypes(1));
        return view('admin.unittypes', compact('data'));
    }

    public function irrigationStatus() {
        $data = Sensorunit::where('serialnumber', 'LIKE', '%21-1020%' )->get();
        // dd($data);
        $sorted = array();
        $result = array();

        foreach ($data as &$unit) {
            if (isset($unit['serialnumber'])) {
                if($unit['customer_id_ref'] == 1 || $unit['customer_id_ref'] == 18 || $unit['customer_id_ref'] == 3 || $unit['customer_id_ref'] == 285) continue;
                $serial = trim($unit['serialnumber']);
                $status = Status::where('serialnumber', $serial)->where('variable', 'swversion')->first();
                $imei = Status::where('serialnumber', $serial)->where('variable', 'imei')->first();

                if (isset($unit['sensorunit_lastconnect'])) {
                    if(!in_array(trim($unit['serialnumber']),$sorted)){
                        $variables = Unit::getVariables($serial);
                        if(is_array($variables['result'])) {
                            foreach ($variables['result'] as $variable) {
                                if (trim($variable['variable']) == 'irrigation_state') {
                                    array_push($sorted, trim($unit['serialnumber']));
                                    $unit['irrigation_state'] = trim($variable['value']);
                                    $unit['swversion'] = $status;
                                    if(isset($imei)) {
                                        $unit['imei'] = $imei['value'];
                                    }
                                    $result[] = $unit;
                                }
                            }
                            if(!isset($unit['irrigation_state'])) {
                                array_push($sorted, trim($unit['serialnumber']));
                                $unit['irrigation_state'] = -1;
                                $unit['swversion'] = $status;
                                $result[] = $unit;
                            }
                        } else {
                            array_push($sorted, trim($unit['serialnumber']));
                            $unit['irrigation_state'] = -1;
                            $unit['swversion'] = $status;
                            $result[] = $unit;
                        }
                    }
                }
            }
        }

        $allirrigation = self::processIrrigationArray($result);

        $dataset = array();
        $variable = array();
        $variable['notused'] = 0;
        $variable['idle'] = 0;
        $variable['idle_green'] = 0;
        $variable['settling'] = 0;
        $variable['irrigation'] = 0;
        $i = 0;
        foreach ($result as $row) {
            $dataset[$i][0] = '<a href="/unit/'.$row['serialnumber'].'"><img src="'.$row['img'].'"><span style="font-size:0px;">'.$row['sortstate'].'</span></a>';
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
            if($row['serialnumber']) { $dataset[$i][1]=$row['serialnumber']; } else { $dataset[$i][1] = null; }
            if($row['sensorunit_location']) { $dataset[$i][2]=$row['sensorunit_location']; } else { $dataset[$i][2] = null; }
            if(isset($row->swversion->value)) { $dataset[$i][3]=$row['swversion']['value']; } else { $dataset[$i][3] = null; }
            if($row['customer_id_ref']) { 
                $customer = Customer::find($row['customer_id_ref']);
                $dataset[$i][4]=$customer->customer_name; 
            } else { 
                $dataset[$i][4] = null; 
            }
            if($row['imei']) { $dataset[$i][5]=$row['imei']; } else { $dataset[$i][5] = null; }
            if($row['sensorunit_lastconnect']) { $dataset[$i][6]=self::convertToSortableDate($row['sensorunit_lastconnect']); } else { $dataset[$i][6] = null; }
            $dataset[$i][7] = '<a href="/admin/irrigationstatus/'.$row['serialnumber'].'"><button class="btn-primary-filled">Open</button></a>';

            $i++;
        }
        $data = json_encode($dataset);
        return view('admin.irrigationstatus',  compact('data', 'variable'));
    }

    public function select() {
        $data = self::getUsers(1);

        return view('admin.select')->with('data', $data);
    }

    public function connectUser() {
        $data = self::getUsers(2);
        return view('admin.connect.select')->with('data', $data);
    }

    public function userAccess($userid) {
        $accesslist = Unit::userAccess($userid);
        foreach ($accesslist as &$access) {
            $serial = $access['serialnumber'];
            $access['others'] = Unit::getAccess($serial);
            foreach ($access['others'] as &$users) {
                $userinfo = User::getUser($users['user_id']);
                $users = array_merge($users, $userinfo[0]);
            }
            usort($access['others'], function($a, $b) {
                return $a['user_id'] <=> $b['user_id'];
            });
        }

        usort($accesslist, function($a, $b) {
            return $a['serialnumber'] <=> $b['serialnumber'];
        });
        $user = User::getUser($userid);
        
        return view('admin.connect.useraccess')->with('accesslist', $accesslist)->with('user',$user[0]);
    }

    public static function getUsers($action){
        $data = User::getUsers();

        $count_data = count($data);
        $sorted = array();

        for ($i = 0; $i < $count_data; $i++) {

            if (isset($data[$i]['user_id'])) {
                $sorted[$i][0] = trim($data[$i]['user_id']);
            } else {
                $sorted[$i][0] = '-';
            }

            if (isset($data[$i]['user_name'])) {
                $sorted[$i][1] = trim($data[$i]['user_name']);
            } else {
                $sorted[$i][1] = '-';
            }

            if (isset($data[$i]['user_email'])) {
                $sorted[$i][2] = trim($data[$i]['user_email']);
            } else {
                $sorted[$i][2] = '-';
            }

            if (isset($data[$i]['customer_name'])) {
                $sorted[$i][3] = trim($data[$i]['customer_name']);
            } else {
                $sorted[$i][3] = '-';
            }
            
            if (isset($data[$i]['customernumber'])) {
                $sorted[$i][4] = trim($data[$i]['customernumber']);
            } else {
                $sorted[$i][4] = '-';
            }
            if ($action == 1) {
                $sorted[$i][5] = '<button class="btn btn-primary"><a href="/select/'.$sorted[$i][0].'/'.$sorted[$i][4].'" style="color:#FFFFFF;">Login</a></button>';
            } else if ($action == 2) {
                $sorted[$i][5] = '<button class="btn btn-primary"><a href="/admin/connect/user/'.$sorted[$i][0].'" style="color:#FFFFFF;">Select</a></button>';
            }            
        }
        $data = json_encode($sorted);
        
        return $data;
    }

    public static function processIrrigationArray($irrigationunits) {
        //dd($irrigationunits);
        foreach ($irrigationunits as &$irrUnit) {
            if (isset($irrUnit['sensorunit_lastconnect'])) {
                $irrUnit['manipulatedTimestamp'] = DashboardController::convertTimestampToUserTimezone($irrUnit['sensorunit_lastconnect']);
                $irrUnit['timestampDifference'] = DashboardController::getTimestampDifference($irrUnit['sensorunit_lastconnect']);
                $irrUnit['timestampComment'] = DashboardController::getTimestampComment($irrUnit['timestampDifference'], $irrUnit['manipulatedTimestamp']);

                if ($irrUnit['timestampDifference'] < 5400) {
                    if ($irrUnit['irrigation_state'] === '-1') {
                        $irrUnit['img'] = '../img/irr_notused.png';
                        $irrUnit['sortstate'] = 'state1';
                        $irrUnit['display'] = 'none';
                        $irrUnit['class'] = 'all_units';
                    } else if ($irrUnit['irrigation_state'] === '0') {
                        $irrUnit['img'] = '../img/irr_idle_green.png';
                        $irrUnit['display'] = 'none';
                        $irrUnit['class'] = 'all_units';
                        $irrUnit['sortstate'] = 'state3';
                    } else if ($irrUnit['irrigation_state'] === '1') {
                        $irrUnit['img'] = '../img/irr_settling_green.png';
                        $irrUnit['display'] = '';
                        $irrUnit['class'] = 'irrigation_units';
                        $irrUnit['sortstate'] = 'state4';
                    } else if ($irrUnit['irrigation_state'] === '2') {
                        $irrUnit['img'] = '../img/irr_irrigation_green.png';
                        $irrUnit['display'] = '';
                        $irrUnit['class'] = 'irrigation_units';
                        $irrUnit['sortstate'] = 'state5';
                    }
                } else {
                    if ($irrUnit['sensorunit_lastconnect'] < '2020-05-04 19:10:09.60511+00') {
                        $irrUnit['img'] = '../img/irr_notused.png';
                        $irrUnit['display'] = 'none';
                        $irrUnit['class'] = 'all_units';
                        $irrUnit['sortstate'] = 'state1';
                    } else {
                        $irrUnit['img'] = '../img/irr_idle_yellow.png';
                        $irrUnit['display'] = 'none';
                        $irrUnit['class'] = 'all_units';
                        $irrUnit['sortstate'] = 'state2';
                    }
                }
            }
        }
        
        return $irrigationunits;
    }
}
