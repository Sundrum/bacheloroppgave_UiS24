<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Sensorunit;
use App\Models\Customervariables;
use App\Mail\InfoMail;
use Redirect, Log, Mail, DB, Config;


class CustomerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    
    public function index() {
        $customers = Customer::all();
        return view('admin.customer.view', compact('customers'));
    }

    public function newCustomer() {
        $customernumber = self::newestCustomer();
        $temp = explode('-',$customernumber);
        $number = $temp[1] + 1;
        $newcustomernumber = '10-'.$number.'-AA';

        $customertypes = Customer::getCustomerTypes();
        $countries = Customer::getCountries();
        return view('admin.customer.detailes', compact('customertypes','countries','newcustomernumber'));
    }

    public static function getCustomer($id) {
        $customer = Customer::find($id);
        $customertypes = Customer::getCustomerTypes();
        $countries = Customer::getCountries();
        $variables = Customervariables::where('customernumber', $customer->customernumber)->get();
        foreach($variables as $row) {
            $customer[trim($row->variable)] = trim($row->value);
        }

        return view('admin.customer.detailes', compact('customertypes','countries', 'customer'));
    }

    public function sendOrderInformation(Request $req) {
        $user = User::find($req->order_user_id);
        $passwd = explode("@",$user->user_email);
        $user_passwd = $passwd[0]."123";
        $data = array(
            'name'=>$user->user_name, 
            'email'=>$user->user_email,
            'password'=>$user_passwd,
            'tracking'=>$req->tracking
        );
        $email = $user->user_email;
        $name = $user->user_name;

        Mail::send(['html' => 'email.salessensorunit'], $data, function($message) use ($email, $name)
        {
            $message->from(env('MAIL_FROM_ADDRESS', 'no-reply@portal.7sense.no'), env('MAIL_FROM_NAME', '7Sense Portal'));
            $message->to($email, $name)->cc('sales@7sense.no')->subject('Din vanningssensor fra 7Sense er på vei');
        });
        $response ="Mail sent";
        return $response;
    }

    public static function getOverview($id) {
        $customer = Customer::find($id);
        $customertypes = Customer::getCustomerTypes();
        $countries = Customer::getCountries();
        $variables = Customervariables::where('customernumber', $customer->customernumber)->get();
        foreach($variables as $row) {
            $customer[trim($row->variable)] = trim($row->value);
        }

        $customer->users = User::select('users.*', 'roletype.roletype')->where('customer_id_ref', $id)->join('roletype', 'roletype_id', 'users.roletype_id_ref')->get();
        $customer->sensorunits = Sensorunit::where('customer_id_ref', $id)->get();
        // dd($customer);
        return view('admin.customer.overview', compact('customertypes','countries', 'customer'));
    }

    public function customerSubscription(Request $req){
        $customer = Customer::find($req->id);
        $customer->paid_subscription = $req->value;
        $result = $customer->save();
        return json_encode($result);
    }

    public static function newestCustomer() {
        $data = Customer::getCustomers();

        usort($data, function($a, $b) {
            return $a['customer_id'] <=> $b['customer_id'];
        });

        $customernumber = trim($data[count($data)-1]['customernumber']);

        return $customernumber;
    }

    public function update(Request $req){
        $result = array();
        if($req->customer_variables_irrigation_sms_enable) {
            $alert_irr_sms_enable = 1;
        } else {
            $alert_irr_sms_enable = 0;
        }
        if($req->customer_variables_sms_enable) {
            $alert_sms_enable = 1;
        } else {
            $alert_sms_enable = 0;
        }
        if($req->customer_id) {
            $customer = Customer::find($req->customer_id);
        } else {
            $customer = new Customer;
            $customer->customernumber = $req->customernumber;
            $customernumber = $req->customernumber;
            
            $create_db = self::createDB($customernumber);
            if($create_db) {
                $subject_mail = 'New customer database';
                $feedback_string = 'New database created for '.$customernumber;
                Log::info($feedback_string);
                Log::info($create_db);
            } else {
                $subject_mail = 'Error - Creating new customer database';
                $feedback_string = 'According to a system problem, a new database for '.$customernumber.' could not be created. You should examine the Laravel log to see what caused the issue.';
                Log::info($feedback_string);
                Log::info($create_db);
            }

            $data = array(
                'name'=>'Vegard', 
                'email'=>'vegard@7sense.no',
                'string'=>$feedback_string
            );

            $admin_email = env('ADMIN_EMAIL', 'vegard@7sense.no');
            $admin_name = env('ADMIN_NAME', 'Vegard L. Steinstø');
            Log::info('Sent mail to admin: '.$admin_name.' - '.$admin_email);
            Mail::send(['html' => 'email.admin.newcustomer'], $data, function($message) use ($admin_email, $admin_name) {
                $message->from('portal@7sense.no', '7Sense Portal');
                $message->to($admin_email, $admin_name)->subject('New Customer Database');
            });
            $variable = 'customer_variables_irrigation_email';
            $value = $req->customer_variables_irrigation_email;
            Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $variable = 'customer_variables_sms_enable';
            $value = $alert_sms_enable;
            Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $variable = 'customer_variables_email';
            $value = $req->customer_variables_email;
            Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $variable = 'customer_variables_irrigation_sms';
            $value = $req->customer_variables_irrigation_sms;
            Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $variable = 'customer_variables_irrigation_sms_enable';
            $value = $alert_irr_sms_enable;
            Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $variable = 'customer_variables_sms';
            $value = $req->customer_variables_sms;
            Customer::updateAlertSettings($customernumber, $variable, $value);
        }
        $customer->customer_name = $req->name;
        $customer->customer_vatnumber = $req->customer_vatnumber;
        $customer->customer_phone = $req->phone;
        $customer->customer_maincontact = $req->maincontact;
        $customer->customer_email = $req->email;
        $customer->customertype_id_ref = $req->typecustomer;
        $customer->customer_site_title = $req->sitetitle;
        $customer->customer_visitaddr1 = $req->adress1visitt;
        $customer->customer_visitaddr2 = $req->adress2visitt;
        $customer->customer_visitpostcode = $req->postcodevisitt;
        $customer->customer_visitcity = $req->cityvisitt;
        $customer->customer_visitcountry = $req->countryvisitt;
        $customer->customer_invoicecountry = $req->countryvisitt;
        $customer->customer_delivercountry = $req->countryvisitt;
        $customer->customer_deliveraddr_same_as_invoice	= true;
        $customer->customer_invoiceaddr_same_as_visit = true;
        $result[] = $customer->save();
        $sms = Customervariables::where('variable', 'customer_variables_sms')->where('customernumber', $customer->customernumber)->first();
        $sms->value = $req->customer_variables_sms;
        $result[] = $sms->save();
        $sms_enabled = Customervariables::where('variable', 'customer_variables_sms_enable')->where('customernumber', $customer->customernumber)->first();
        $sms_enabled->value= $alert_sms_enable;
        $result[] = $sms_enabled->save();
        $email = Customervariables::where('variable', 'customer_variables_email')->where('customernumber', $customer->customernumber)->first();
        $email->value= $req->customer_variables_email;
        $result[] = $email->save();
        $irr_sms = Customervariables::where('variable', 'customer_variables_irrigation_sms')->where('customernumber', $customer->customernumber)->first();
        $irr_sms->value= $req->customer_variables_irrigation_sms;
        $result[] = $irr_sms->save();
        $irr_sms_enabled = Customervariables::where('variable', 'customer_variables_irrigation_sms_enable')->where('customernumber', $customer->customernumber)->first();
        $irr_sms_enabled->value= $alert_irr_sms_enable;
        $result[] = $irr_sms_enabled->save();
        $irr_email = Customervariables::where('variable', 'customer_variables_irrigation_email')->where('customernumber', $customer->customernumber)->first();
        $irr_email->value= $req->customer_variables_irrigation_email;
        $result[] = $irr_email->save();

        $counter = 0;
        foreach($result as $row) {
            if(!$row){
                $counter++;
            }
        }

        if($counter == 0) {
            $message = 'Customer settings are now updated';
            return Redirect::to('admin/customer/'.$customer->customer_id.'?message='.$message);
        } else {
            $message = 'Something went wrong! ERROR: CUSTOMER SETTINGS';
            return Redirect::to('admin/customer/'.$customer->customer_id.'?errormessage='.$message);
        }
    }

    public function createDB($customernumber) {
        $temp = explode('-',$customernumber);
        $number = $temp[1];

        self::changeDBConnection("sensordata_".$number);
        try { 
            $result = DB::connection('7sensor')->select('SELECT * FROM messages'); 
        } catch(\Illuminate\Database\QueryException $ex){ 
            Log::info($ex->getMessage()); 
            // Note any method of class PDOException can be called on $ex.
        }

        if(isset($result)) {
            return 1;
        } else {
            return 0;
        }
    }

    // Function to change database connection for database.php -> 7sensor.
    // Config 7sensor is used for customer databases (sensordata_xxxx), and needs to be change before obtaining data from DB.
    // The function use $db_name as input, and needs to be the fullname of the database you are trying to connect to.
    public function changeDBConnection($db_name) {
        Config::set('database.connections.7sensor.database', $db_name);
    }
}
