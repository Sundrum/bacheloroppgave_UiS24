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
use App\Models\Cases;
use App\Models\Sensorlatestvalues;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\IrrigationController;
use App\Http\Controllers\SensorunitController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UnittypeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\DB;
use Auth, Redirect, Session, Log, Lang, App;

class AdminController extends Controller {
    /*
        Middleware which are executed before any function can run
    */
    public function __construct() {
        $this->middleware('auth'); // Verify that the user is authenticated
        $this->middleware('checkadmin'); // Verify that the user is admin
        $this->middleware('language'); // Set the language based on user settings
    }


    /*
        Used in admin dashboard
        Called from route: /admin
    */
    public function dashboard() {
        $units = Sensorunit::all(); // Retrieve all sensorunits
        $variable['products'] = Product::all(); // Retrieve all products
        $products = Product::all(); // Retrieve all products
        $customers = Customer::all(); // Retrieve all customers
        $users = User::all(); // Retrieve all users

        $count['units'] = $units->count();
        $count['products'] = $products->count();
        $count['customers'] = $customers->count();
        $count['users'] = $users->count();

        return view('admin.dashboard', compact('count', 'variable', 'customers'));
    }


    /*
        Used for logging in as a user
        Called from route: /select/{userid}/{customernumber}
    */
    public function setUser($userid, $customernumber) {
        Session::put('user_id',$userid);
        Session::put('customernumber', $customernumber);
        return Redirect::to('dashboard');
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
        $timing = time();

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
        $timing_2 = time();
        Log::info($timing_2 - $timing. "s loading Irrigation status" );
        $data = json_encode($units);
        return view('admin.irrigationstatus',  compact('data'));
    }

    public function select() {
        $data = self::getUsers(1);

        return view('admin.select')->with('data', $data);
    }

    public function connectUser() {
        $data = self::getUsers(2);
        return view('admin.connect.select')->with('data', $data);
    }

    public function getLanguage() {
        $response = array();
        App::setLocale('no');
        self::processLanguage($response, Lang::get('admin'));
        self::processLanguage($response, Lang::get('general'));
        self::processLanguage($response, Lang::get('dashboard'));
        self::processLanguage($response, Lang::get('map'));
        self::processLanguage($response, Lang::get('myaccount'));
        self::processLanguage($response, Lang::get('navbar'));
        self::processLanguage($response, Lang::get('settings'));
        self::processLanguage($response, Lang::get('support'));

        App::setLocale('fr');
        self::processLanguage($response, Lang::get('admin'));
        //self::processLanguage($response, Lang::get('general'));
        self::processLanguage($response, Lang::get('dashboard'));
        self::processLanguage($response, Lang::get('map'));
        self::processLanguage($response, Lang::get('myaccount'));
        self::processLanguage($response, Lang::get('navbar'));
        self::processLanguage($response, Lang::get('settings'));
        self::processLanguage($response, Lang::get('support'));

        App::setLocale('en');
        self::processLanguage($response, Lang::get('admin'));
        self::processLanguage($response, Lang::get('general'));
        self::processLanguage($response, Lang::get('dashboard'));
        self::processLanguage($response, Lang::get('map'));
        self::processLanguage($response, Lang::get('myaccount'));
        self::processLanguage($response, Lang::get('navbar'));
        self::processLanguage($response, Lang::get('settings'));
        self::processLanguage($response, Lang::get('support'));

        $response = array_values($response);
        $response = json_encode($response);
        return view('admin.language', compact('response'));
    }

    public function processLanguage(&$response, $array) {
        $language = App::getLocale();
        foreach ($array as $key => $value) {
            if(isset($response[$key][$language])) {
                if($language == 'en') {
                    $response[$key]['count_en'] = 1; 
                } else if($language == 'fr') {
                    $response[$key]['count_fr'] = 1; 
                } else if($language == 'no'){
                    $response[$key]['count_no'] = 1; 
                }
            }
            $response[$key]['index'] = $key;
            $response[$key][$language] = $value;
        }
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
                $sorted[$i][5] = '<a href="/select/'.$sorted[$i][0].'/'.$sorted[$i][4].'" style="color:#FFFFFF;"><button class="btn-7s">Login</button></a>';
            } else if ($action == 2) {
                $sorted[$i][5] = '<button class="btn btn-primary"><a href="/admin/connect/user/'.$sorted[$i][0].'" style="color:#FFFFFF;">Select</a></button>';
            }            
        }
        $data = json_encode($sorted);
        
        return $data;
    }

    public static function processIrrigationArray(&$irrigationunits) {
        foreach ($irrigationunits as &$irrUnit) {
            if (isset($irrUnit->sensorunit_lastconnect)) {
                $irrUnit->manipulatedTimestamp = DashboardController::convertTimestampToUserTimezone($irrUnit->sensorunit_lastconnect);
                $irrUnit->timestampDifference = DashboardController::getTimestampDifference($irrUnit->sensorunit_lastconnect);
                $irrUnit->timestampComment = DashboardController::getTimestampComment($irrUnit->timestampDifference, $irrUnit->manipulatedTimestamp);

                if ($irrUnit->timestampDifference < 5400) {
                    if ($irrUnit->state === '-1') {
                        $irrUnit->img = '../img/irrigation/state.png';
                        $irrUnit->sortstate = 'state-1';
                        $irrUnit->display = 'none';
                        $irrUnit->class = 'all_units';
                    } else if ($irrUnit->state === '0') {
                        $irrUnit->img = '../img/irrigation/state_0.png';
                        $irrUnit->display = 'none';
                        $irrUnit->class = 'all_units';
                        $irrUnit->sortstate = 'state0';
                    } else if ($irrUnit->state === '1') {
                        $irrUnit->img = '../img/irrigation/state_1.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state1';
                    } else if ($irrUnit->state === '2') {
                        $irrUnit->img = '../img/irrigation/state_2.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state2';
                    } else if ($irrUnit->state === '3') {
                        $irrUnit->img = '../img/irrigation/state_3.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state3';
                    } else if ($irrUnit->state === '4') {
                        $irrUnit->img = '../img/irrigation/state_4.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state4';
                    } else if ($irrUnit->state === '5') {
                        $irrUnit->img = '../img/irrigation/state_5.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state5';
                    } else if ($irrUnit->state === '6') {
                        $irrUnit->img = '../img/irrigation/state_6.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state6';
                    } else if ($irrUnit->state === '7') {
                        $irrUnit->img = '../img/irrigation/state_7.png';
                        $irrUnit->display = '';
                        $irrUnit->class = 'irrigation_units';
                        $irrUnit->sortstate = 'state7';
                    }
                } else {
                    if ($irrUnit->sensorunit_lastconnect > '2023-05-07 11:10:09.60511+00') {
                        $irrUnit->img = '../img/irrigation/state_0.png';
                        $irrUnit->display = 'none';
                        $irrUnit->class = 'all_units';
                        $irrUnit->sortstate = 'state0';
                        
                        if ($irrUnit->state === '7') {
                            $irrUnit->img = '../img/irrigation/state_7.png';
                            $irrUnit->display = '';
                            $irrUnit->class = 'irrigation_units';
                            $irrUnit->sortstate = 'state7';
                        }
                    } else {
                        $irrUnit->img = '../img/irrigation/state.png';
                        $irrUnit->display = 'none';
                        $irrUnit->class = 'all_units';
                        $irrUnit->sortstate = 'state-1';
                    }
                }
            }
        }
    }
}