<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Unit;
use App\Models\Api;
use Session;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    public static function getCustomerVariable()
    {
        if (Session::get('customernumber')) {
            $customernumber = trim(Session::get('customernumber'));
        } else {
            $customernumber = trim(Auth::user()->customernumber);
        }
        
        $data = Api::getApi('customers/variable/list?customernumber='.$customernumber);
        $variables = array();

        foreach ($data['result'] as $variable) {
           if (trim($variable['variable']) == 'customer_variables_sms') {
               $variables['customer_variables_sms'] = trim($variable['value']);
               $variables['customer_variables_sms_1'] = null;
               if (strpos($variables['customer_variables_sms'], ',') !== false) {
                   $temp = explode(',',$variables['customer_variables_sms']);
                   $variables['customer_variables_sms'] = $temp[0];
                   $variables['customer_variables_sms_1'] = $temp[1];
               }
           } else if (trim($variable['variable']) == 'customer_variables_sms_enable') $variables['customer_variables_sms_enable'] = trim($variable['value']);
           else if (trim($variable['variable']) == 'customer_variables_irrigation_sms') {
               $variables['customer_variables_irrigation_sms'] = trim($variable['value']);
               $variables['customer_variables_irrigation_sms_1'] = null;
               if (strpos($variables['customer_variables_irrigation_sms'], ',') !== false) {
                   $temp = explode(',',$variables['customer_variables_irrigation_sms']);
                   $variables['customer_variables_irrigation_sms'] = $temp[0];
                   $variables['customer_variables_irrigation_sms_1'] = $temp[1];
               }
           } else if (trim($variable['variable']) == 'customer_variables_irrigation_email'){
               $variables['customer_variables_irrigation_email'] = trim($variable['value']);
               $variables['customer_variables_irrigation_email_1'] = null;
               if (strpos($variables['customer_variables_irrigation_email'], ',') !== false) {
                   $temp = explode(',',$variables['customer_variables_irrigation_email']);
                   $variables['customer_variables_irrigation_email'] = $temp[0];
                   $variables['customer_variables_irrigation_email_1'] = $temp[1];
               }
           } else if (trim($variable['variable']) == 'customer_variables_irrigation_sms_enable') $variables['customer_variables_irrigation_sms_enable'] = trim($variable['value']);
           else if (trim($variable['variable']) == 'customer_variables_email') {
               $variables['customer_variables_email'] = trim($variable['value']);
               $variables['customer_variables_email_1'] = null;
               if (strpos($variables['customer_variables_email'], ',') !== false) {
                   $temp = explode(',',$variables['customer_variables_email']);
                   $variables['customer_variables_email'] = $temp[0];
                   $variables['customer_variables_email_1'] = $temp[1];
               }
           }
        }
        $variables['customernumber'] = $customernumber;
        return $variables;
    }

    public static function getCustomers() {
        $data = Api::getApi('customers/list');
        return $data['result'];
    }

    public static function getCustomer($id) {
        $data = Api::getApi('customers/list?customer_id='.$id);
        return $data['result'];
    }

    public static function updateAlertSettings($customernumber, $variable, $value) {
        if(isset($customernumber)) {
            if ($variable == 'user_defined_title') {
                $data = Api::patchApi('customers/update?customernumber='.$customernumber.'&customer_site_title='.$value);
            } else {
                $data = Api::patchApi('customers/variable/update?customernumber='.$customernumber.'&variable='.$variable.'&value='.$value);
            }
        }
        return $data;
    }

    public static function getCustomerDefaultVariables() {
        $data = Api::getApi('variabletypes/list?variables_types_type=0');
        return $data['result'];
    }

    public static function getCountries(){
        $data = Api::getApi('countries/list');
        return $data['result'];
    }

    public static function getCustomerTypes() {
        $data = Api::getApi('customertypes/list');
        return $data['result'];
    }

    public static function updateCustomer($customername, $customernumber, $vatnumber, $phone, $fax, $email, $web, $maincontact, $typecustomer, $sitetitle, $adress1visitt, $adress2visitt, $postcodevisitt, $cityvisitt, $countryvisitt, $adress1invoice, $adress2invoice, $postcodeinvoice, $cityinvoice, $countryinvoice, $ckeckboxadressvisitt, $adress1delivery, $adress2delivery, $postcodedelivery, $citydelivery, $countrydelivery, $ckeckboxadressinv) {
        $urlAPI = ''; 
        $urlAPI = 'customers/update?customernumber=' .$customernumber.'&customer_name='.$customername.
                                '&customer_vatnumber=' .$vatnumber.
                                '&customer_phone=' .$phone.
                                '&customer_fax=' .$fax.
                                '&customer_email=' .$email.
                                '&customer_web=' .$web.
                                '&customer_visitaddr1=' .$adress1visitt.
                                '&customer_visitaddr2=' .$adress2visitt.
                                '&customer_visitcity=' .$cityvisitt.
                                '&customer_visitcountry=' .$countryvisitt.
                                '&customer_invoiceaddr1=' .$adress1invoice.
                                '&customer_invoiceaddr2=' .$adress2invoice.
                                '&customer_invoicepostcode=' .$postcodeinvoice.
                                '&customer_invoicecity=' .$cityinvoice.
                                '&customer_invoicecountry=' .$countryinvoice.
                                '&customer_deliveraddr1=' .$adress1delivery.
                                '&customer_deliveraddr2=' .$adress2delivery.
                                '&customer_deliverpostcode=' .$postcodedelivery.
                                '&customer_delivercity=' .$citydelivery.
                                '&customer_maincontact=' .$maincontact.
                                '&customertype_id_ref=' .$typecustomer.
                                '&customer_visitpostcode=' .$postcodevisitt.
                                '&customer_delivercountry=' .$countrydelivery.
                                '&customer_site_title=' .$sitetitle. 
                                '&customer_deliveraddr_same_as_invoice='.$ckeckboxadressinv. 
                                '&customer_invoiceaddr_same_as_visit='.$ckeckboxadressvisitt;
        $data = Api::patchApi($urlAPI);
        return $data['result'];
    }

    public static function addCustomer($customername, $customernumber, $vatnumber, $phone, $fax, $email, $web, $maincontact, $typecustomer, $sitetitle, $adress1visitt, $adress2visitt, $postcodevisitt, $cityvisitt, $countryvisitt, $adress1invoice, $adress2invoice, $postcodeinvoice, $cityinvoice, $countryinvoice, $ckeckboxadressvisitt, $adress1delivery, $adress2delivery, $postcodedelivery, $citydelivery, $countrydelivery, $ckeckboxadressinv) {
        $urlAPI = ''; 
        $urlAPI = 'customers/add?customernumber=' .$customernumber.'&customer_name='.$customername.
                                '&customer_vatnumber=' .$vatnumber.
                                '&customer_phone=' .$phone.
                                '&customer_fax=' .$fax.
                                '&customer_email=' .$email.
                                '&customer_web=' .$web.
                                '&customer_visitaddr1=' .$adress1visitt.
                                '&customer_visitaddr2=' .$adress2visitt.
                                '&customer_visitcity=' .$cityvisitt.
                                '&customer_visitcountry=' .$countryvisitt.
                                '&customer_invoiceaddr1=' .$adress1invoice.
                                '&customer_invoiceaddr2=' .$adress2invoice.
                                '&customer_invoicepostcode=' .$postcodeinvoice.
                                '&customer_invoicecity=' .$cityinvoice.
                                '&customer_invoicecountry=' .$countryinvoice.
                                '&customer_deliveraddr1=' .$adress1delivery.
                                '&customer_deliveraddr2=' .$adress2delivery.
                                '&customer_deliverpostcode=' .$postcodedelivery.
                                '&customer_delivercity=' .$citydelivery.
                                '&customer_maincontact=' .$maincontact.
                                '&customertype_id_ref=' .$typecustomer.
                                '&customer_visitpostcode=' .$postcodevisitt.
                                '&customer_delivercountry=' .$countrydelivery.
                                '&customer_site_title=' .$sitetitle. 
                                '&customer_deliveraddr_same_as_invoice='.$ckeckboxadressinv. 
                                '&customer_invoiceaddr_same_as_visit='.$ckeckboxadressvisitt;
        $data = Api::postApi($urlAPI);
        return $data['result'];
    }

    public static function getUsers($customernumber){
        $users = User::where('customernumber', '=', $customernumber)->get();
        foreach ($users as &$user) {
            $user->units = Sensoraccess::where('user_id', '=', $user->user_id)
                                        ->join('sensorunits', 'sensorunits.serialnumber', '=', 'sensoraccess.serialnumber')->get();
        }
        return $users;
    }
}
