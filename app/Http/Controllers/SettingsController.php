<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Unit;
use App\Models\Status;
use App\Models\Api;
use Redirect, App;

class SettingsController extends Controller
{
    public static function irrigationsettings(Request $request){
        $result = array();
        $serial = $request->input('serialnumber');
        $sensorname = $request->input('unitname');
        $sensornote = $request->input('sensorunit_note');
        $result[] = Unit::updateSensorunits($serial, trim($sensorname), trim($sensornote));
        $irrigation_tilt = $request->input('irrigation_tilt');
        $irrigation_points2calcwagonstop = $request->input('irrigation_points2calcwagonstop');
        $radius = $request->input('radius');
        $irrigation_nozzlewidth = $request->input('irrigation_nozzlewidth');
        $irrigation_nozzlebar = $request->input('irrigation_nozzlebar');
        $irrigation_pressure_bar = $request->input('irrigation_pressure_bar');

        if (isset($irrigation_tilt)) {
            $value = ((($irrigation_tilt/-1.0)+90.0)/90.0);
            $variable = 'irrigation_tilt';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);
        }
        if (isset($irrigation_points2calcwagonstop)) {
            $value = $irrigation_points2calcwagonstop;
            $variable = 'irrigation_points2calcwagonstop';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);
        } 
        if (isset($irrigation_nozzlewidth)) {
            $value = $irrigation_nozzlewidth;
            $variable = 'irrigation_nozzlewidth';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);
        } 
        if (isset($irrigation_nozzlebar)) {
            $value = $irrigation_nozzlebar;
            $variable = 'irrigation_nozzlebar';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);
        } 
        if (isset($radius)) {
            $value = $radius;
            $variable = 'irrigation_endpoint_radius';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);

            $variable = 'irrigation_poi_1_radius';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);

            $variable = 'irrigation_poi_2_radius';
            $result[] = Unit::updateSensorSettings($serial, $variable, $value);
        }

        // if (isset($irrigation_pressure_bar)) {
        //     $old_pressure = Status::select('value')->where('serialnumber', $serial)->where('variable', 'pressure_threshold_low')->first();
        //     if(isset($old_pressure)) {
        //         if ($old_pressure->value !== $irrigation_pressure_bar) {
        //             $settling = $irrigation_pressure_bar - 1;
        //             $comment = 'User update pressure';
        //             $data = '7,4,nan,'.$settling.';8,4,0,nan,'.$irrigation_pressure_bar;
        //             Api::postQueue('queue/add?serialnumber='.$serial.'&typeid=2&data='.base64_encode($data).'&comment='.rawurlencode($comment));
        //             $status = Status::where('serialnumber', $serial)->where('variable', 'pressure_threshold_low')->first();
        //             $status->value = $irrigation_pressure_bar;
        //             $status->save();
        //         }
        //     } else {
        //         $settling = $irrigation_pressure_bar - 1;
        //         $comment = 'User update pressure';
        //         $data = '7,4,nan,'.$settling.';8,4,0,nan,'.$irrigation_pressure_bar;
        //         Api::postQueue('queue/add?serialnumber='.$serial.'&typeid=2&data='.base64_encode($data).'&comment='.rawurlencode($comment));
        //         $status = Status::where('serialnumber', $serial)->where('variable', 'pressure_threshold_low')->first();
        //         $status->value = $irrigation_pressure_bar;
        //         $status->save();
        //     }
        // }

        return Redirect::to('settings/1');
    }

    public function changeaccount(Request $request)
    {
        $name = $request->input('name');
        $email = strtolower($request->input('email'));
        $work = $request->input('phone_work');
        $home = $request->input('phone_home');
        $prefix_work = $request->input('prefixphonework');
        $prefix_home = $request->input('prefixphonehome');
        if($work) {
            $phone_work = '%2B'.$prefix_work.$work;
        } else {
            $phone_work = null;
        }
        
        $user_alternative_email = $request->input('altemail');
        $language = $request->input('language');

        $data = User::changeAccountSettings($name, $email, $phone_work, $user_alternative_email, $language);
        return Redirect::to('myaccount');
    }

    public function shareunit (Request $request)
    {
        $email = $request->input('email');
        $serialnumber = $request->input('serialnumber');
        $changeallowed = $request->input('access');
        $response = Unit::giveAccess($email, $serialnumber, $changeallowed);

        return Redirect::to('settings/1');
    }

    public function deleteunit (Request $request)
    {
        $email = $request->input('email');
        $serialnumber = $request->input('serialnumber');
        $response = Unit::deleteAccess($email, $serialnumber);

        return Redirect::to('settings');
    }

    public function updateCustomerSettings(Request $request) 
    {
        $result = array();
        $customernumber = $request->input('customernumber');
        if (isset($customernumber)) {
            $customer_variables_sms = $request->input('customer_variables_sms');
            if($customer_variables_sms) {
                $customer_variables_sms = '%2B' .$request->input('prefix_customer_variables_sms').$request->input('customer_variables_sms');
            } else {
                $customer_variables_sms = null;
            }

            $customer_variables_sms_1 = $request->input('customer_variables_sms_1');
            if ($customer_variables_sms_1) {
                $customer_variables_sms .= ',%2B'.$request->input('prefix_customer_variables_sms_1').$customer_variables_sms_1;
            }
            $variable = 'customer_variables_sms';
            $value = $customer_variables_sms;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);

            $customer_variables_email = $request->input('customer_variables_email');
            $customer_variables_email_1 = $request->input('customer_variables_email_1');
            if ($customer_variables_email_1) {
                $customer_variables_email .= ','.$customer_variables_email_1;
            }
            $variable = 'customer_variables_email';
            $value = $customer_variables_email;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);

            $user_defined_title = $request->input('user_defined_title');
            $variable = 'user_defined_title';
            $value = $user_defined_title;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);

            $customer_variables_irrigation_sms = $request->input('customer_variables_irrigation_sms');
            if($customer_variables_irrigation_sms) {
                $customer_variables_irrigation_sms = '%2B' .$request->input('prefix_customer_variables_irrigation_sms').$request->input('customer_variables_irrigation_sms');
            } else {
                $customer_variables_sms = null;
            }
            $customer_variables_irrigation_sms_1 = $request->input('customer_variables_irrigation_sms_1');
            
            if ($customer_variables_irrigation_sms_1) {
                $customer_variables_irrigation_sms .= ',%2B'.$request->input('prefix_customer_variables_irrigation_sms_1').$customer_variables_irrigation_sms_1;
            }
            $variable = 'customer_variables_irrigation_sms';
            $value = $customer_variables_irrigation_sms;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);

            $customer_variables_irrigation_email = $request->input('customer_variables_irrigation_email');
            $customer_variables_irrigation_email_1 = $request->input('customer_variables_irrigation_email_1');
            if ($customer_variables_irrigation_email_1) {
                $customer_variables_irrigation_email .= ','.$customer_variables_irrigation_email_1;
            }
            $variable = 'customer_variables_irrigation_email';
            $value = $customer_variables_irrigation_email;
            $result[] = Customer::updateAlertSettings($customernumber, $variable, $value);
        }
        return Redirect::to('settings');
    }

    public function updateSensorSettings (Request $request)
    {
        $result = array();
        $serial = $request->input('serialnumber');
        $sensorname = $request->input('unitname');
        $sensornote = $request->input('sensorunit_note');
        if($request->input('sensorunit_tree_species')) {
            Unit::updateSensorSettings($serial, 'tree_species', $request->input('sensorunit_tree_species'));
        }
        $result[] = Unit::updateSensorunits($serial, trim($sensorname), trim($sensornote));

        $probes = $request->input('probe');
        if(is_array($probes)){
            foreach ($probes as $probe) {
                $serial = $probe['serialnumber'];
                $probenumber = $probe['probenumber'];
                
                if(isset($probe['sms_enabled'])) {
                    $variable = 'sensorprobe_send_sms';
                    $value = 1;
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                } else {
                    $variable = 'sensorprobe_send_sms';
                    $value = 0;
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                }
    
                if(isset($probe['email_enabled'])) {
                    $variable = 'sensorprobe_send_email';
                    $value = 1;
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                } else {
                    $variable = 'sensorprobe_send_email';
                    $value = 0;
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                }
    
                if(isset($probe['repeats'])) {
                    $variable = 'sensorprobe_repeat_before_trigger';
                    $value = $probe['repeats'];
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                }
    
                if(isset($probe['upper_thersholds'])){
                    $variable = 'sensorprobe_upper_threshold';
                    $value = $probe['upper_thersholds'];
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                }
    
                if(isset($probe['lower_thersholds'])){
                    $variable = 'sensorprobe_lower_threshold';
                    $value = $probe['lower_thersholds'];
                    $result[] = Unit::updateSensorprobes($serial, $probenumber, $variable, $value);
                }            
            }
        }
        return Redirect::to('settings/1');
    }
}
