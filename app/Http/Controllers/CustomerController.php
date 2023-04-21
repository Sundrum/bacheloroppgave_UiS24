<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public static function getCustomers($action) {
        $data = Customer::getCustomers();
        $count_data = count($data);
        $sorted = array();

        for ($i = 0; $i < $count_data; $i++) {

            if (isset($data[$i]['customernumber'])) {
                $sorted[$i][0] = trim($data[$i]['customernumber']);
            } else {
                $sorted[$i][0] = '-';
            }

            if (isset($data[$i]['customer_name'])) {
                $sorted[$i][1] = trim($data[$i]['customer_name']);
            } else {
                $sorted[$i][1] = '-';
            }

            if (isset($data[$i]['customer_maincontact'])) {
                $sorted[$i][2] = trim($data[$i]['customer_maincontact']);
            } else {
                $sorted[$i][2] = '-';
            }

            if (isset($data[$i]['customer_email'])) {
                $sorted[$i][3] = trim($data[$i]['customer_email']);
            } else {
                $sorted[$i][3] = '-';
            }

            if (isset($data[$i]['customer_id'])) {
                $customerid = trim($data[$i]['customer_id']);
            } else {
                $customerid= '-';
            }
            if ($action == 1) {
                $sorted[$i][4] = '<button class="btn btn-7s"><a href="/admin/customer/'.$customerid.'" style="color:#FFFFFF;">Edit</a></button>';
            } else if ($action == 2) {
                $sorted[$i][4] = '<button class="btn btn-7s"><a href="/admin/connect/customer/'.$customerid.'" style="color:#FFFFFF;">Select</a></button>';
            }

            //$sorted[$i][4] = '<a href="/admin/customer/'.$customerid.'"><p> Edit</p> </a>';
        }
        return $sorted;
    }

    public static function updateCustomer(Request $request) {
        $customername = $request->input('name');
        $customernumber = $request->input('customernumber');
        $phone = $request->input('phone');
        $vatnumber = $request->input('vatnumber');
        $fax = $request->input('fax');
        $email = $request->input('email');
        $web = $request->input('web');
        $maincontact = $request->input('maincontact');
        $typecustomer = $request->input('typecustomer');
        $sitetitle = $request->input('sitetitle');
        $adress1visitt = $request->input('adress1visitt');
        $adress2visitt = $request->input('adress2visitt');
        $postcodevisitt = $request->input('postcodevisitt');
        $cityvisitt = $request->input('cityvisitt');
        $countryvisitt = $request->input('countryvisitt');
        $adress1invoice = $request->input('adress1invoice');
        $adress2invoice = $request->input('adress2invoice');
        $postcodeinvoice = $request->input('postcodeinvoice');
        $cityinvoice = $request->input('cityinvoice');
        $countryinvoice = $request->input('countryinvoice');
        $ckeckboxadressvisitt = $request->input('ckeckboxadressvisitt');
        $adress1delivery = $request->input('adress1delivery');
        $adress2delivery = $request->input('adress2delivery');
        $postcodedelivery = $request->input('postcodedelivery');
        $citydelivery = $request->input('citydelivery');
        $countrydelivery = $request->input('countrydelivery');
        $ckeckboxadressinv = $request->input('ckeckboxadressinv');

        $result[] = Customer::updateCustomer($customername, $customernumber, $vatnumber, $phone, $fax, $email, $web, $maincontact, $typecustomer, $sitetitle, $adress1visitt, $adress2visitt, $postcodevisitt, $cityvisitt, $countryvisitt, $adress1invoice, $adress2invoice, $postcodeinvoice, $cityinvoice, $countryinvoice, $ckeckboxadressvisitt, $adress1delivery, $adress2delivery, $postcodedelivery, $citydelivery, $countrydelivery, $ckeckboxadressinv);

        if (isset($customernumber)) {
            $customer_variables_irrigation_email = $request->input('customer_variables_irrigation_email');
            $variable = 'customer_variables_irrigation_email';
            $value = $customer_variables_irrigation_email;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_sms_enable = $request->input('customer_variables_sms_enable');
            $variable = 'customer_variables_sms_enable';
            $value = $customer_variables_sms_enable;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_email = $request->input('customer_variables_email');
            $variable = 'customer_variables_email';
            $value = $customer_variables_email;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_irrigation_sms = $request->input('customer_variables_irrigation_sms');
            $variable = 'customer_variables_irrigation_sms';
            $value = $customer_variables_irrigation_sms;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_irrigation_sms_enable = $request->input('customer_variables_irrigation_sms_enable');
            $variable = 'customer_variables_irrigation_sms_enable';
            $value = $customer_variables_irrigation_sms_enable;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_sms = $request->input('customer_variables_sms');
            $variable = 'customer_variables_sms';
            $value = $customer_variables_sms;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
        }

        return $request;
    }

    public static function newestCustomer() {
        $data = Customer::getCustomers();

        usort($data, function($a, $b) {
            return $a['customer_id'] <=> $b['customer_id'];
        });

        $customernumber = trim($data[count($data)-1]['customernumber']);

        return $customernumber;
    }

    public static function addCustomer(Request $request) {
        $customername = $request->input('name');
        $customernumber = $request->input('customernumber');
        $phone = $request->input('phone');
        $vatnumber = $request->input('vatnumber');
        $fax = $request->input('fax');
        $email = $request->input('email');
        $web = $request->input('web');
        $maincontact = $request->input('maincontact');
        $typecustomer = $request->input('typecustomer');
        $sitetitle = $request->input('sitetitle');
        $adress1visitt = $request->input('adress1visitt');
        $adress2visitt = $request->input('adress2visitt');
        $postcodevisitt = $request->input('postcodevisitt');
        $cityvisitt = $request->input('cityvisitt');
        $countryvisitt = $request->input('countryvisitt');
        $adress1invoice = $request->input('adress1invoice');
        $adress2invoice = $request->input('adress2invoice');
        $postcodeinvoice = $request->input('postcodeinvoice');
        $cityinvoice = $request->input('cityinvoice');
        $countryinvoice = $request->input('countryinvoice');
        $ckeckboxadressvisitt = $request->input('ckeckboxadressvisitt');
        $adress1delivery = $request->input('adress1delivery');
        $adress2delivery = $request->input('adress2delivery');
        $postcodedelivery = $request->input('postcodedelivery');
        $citydelivery = $request->input('citydelivery');
        $countrydelivery = $request->input('countrydelivery');
        $ckeckboxadressinv = $request->input('ckeckboxadressinv');

        $result[] = Customer::addCustomer($customername, $customernumber, $vatnumber, $phone, $fax, $email, $web, $maincontact, $typecustomer, $sitetitle, $adress1visitt, $adress2visitt, $postcodevisitt, $cityvisitt, $countryvisitt, $adress1invoice, $adress2invoice, $postcodeinvoice, $cityinvoice, $countryinvoice, $ckeckboxadressvisitt, $adress1delivery, $adress2delivery, $postcodedelivery, $citydelivery, $countrydelivery, $ckeckboxadressinv);

        if (isset($customernumber)) {
            $customer_variables_irrigation_email = $request->input('customer_variables_irrigation_email');
            $variable = 'customer_variables_irrigation_email';
            $value = $customer_variables_irrigation_email;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_sms_enable = $request->input('customer_variables_sms_enable');
            $variable = 'customer_variables_sms_enable';
            $value = $customer_variables_sms_enable;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_email = $request->input('customer_variables_email');
            $variable = 'customer_variables_email';
            $value = $customer_variables_email;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_irrigation_sms = $request->input('customer_variables_irrigation_sms');
            $variable = 'customer_variables_irrigation_sms';
            $value = $customer_variables_irrigation_sms;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_irrigation_sms_enable = $request->input('customer_variables_irrigation_sms_enable');
            $variable = 'customer_variables_irrigation_sms_enable';
            $value = $customer_variables_irrigation_sms_enable;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
    
            $customer_variables_sms = $request->input('customer_variables_sms');
            $variable = 'customer_variables_sms';
            $value = $customer_variables_sms;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
        }

        return $result;
    }
}
