<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Sensorunit;
use App\Models\Customervariables;
use App\Mail\InfoMail;
use Redirect, Log, Mail, DB;


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

    public static function getOverview($id) {
        $customer = Customer::find($id);
        $customertypes = Customer::getCustomerTypes();
        $countries = Customer::getCountries();
        $variables = Customervariables::where('customernumber', $customer->customernumber)->get();
        foreach($variables as $row) {
            $customer[trim($row->variable)] = trim($row->value);
        }

        $customer->users = User::where('customer_id_ref', $id)->get();
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
            $feedback_string= 'Feedback';
            if($create_db) {
                $feedback_string = 'New database created for '.$customernumber;
                Log::info('New database created for '.$customernumber);
                Log::info($create_db);
            } else {
                $feedback_string = 'Failed to create new database for '.$customernumber;
                Log::info('Failed to create new database for '.$customernumber);
                Log::info($create_db);
            }

            $data = array(
                'name'=>'Vegard', 
                'email'=>'vegard@7sense.no',
                'string'=>$feedback_string
            );

            $admin_email = 'vegard@7sense.no';
            $admin_name = 'Vegard Steinstø';
            Mail::to($admin_email)->send(new InfoMail($data));
            dd('s');

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
        
        $string = 'CREATE DATABASE sensordata_'.$number.' WITH TEMPLATE=sensordata_template';
        //$result = DB::statement($string);
        $result = false;
        if($result) {
            return 1;
        } else {
            return 0;
        }
    }
}
