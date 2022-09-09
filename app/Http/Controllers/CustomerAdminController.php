<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Sensoraccess;
use App\Models\Sensorunit;

class CustomerAdminController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('customeradmin');
    }

    public function updateUser(Request $req) {
        if ($req->user_id) {
            $user = User::find($req->user_id);
            $user->user_email = $req->user_email;
            $user->user_name = $req->user_name;
            $user->user_phone_work = $req->user_phone_work;
            $user->roletype_id_ref = $req->roletype_id_ref;
            //$user->user_language = $req->user_language;
            // md5 is outdated pass hash*
            if($req->user_password) $user->user_password = md5($req->user_password);
            $result = $user->save();
            if ($result) {
                return '1';
            } else {
                return '0';
            }
        } else {
            $user = new User;
            $user->user_email = $req->user_email;
            $user->user_name = $req->user_name;
            $user->user_phone_work = $req->user_phone_work;
            $user->roletype_id_ref = $req->roletype_id_ref;
            //$user->user_language = $req->user_language;
            $changes = false;
            if ($req->roletype_id_ref == 1) {
                $changes = false;
            } else {
                $changes = true;
            }
            
            $customer = Customer::where('customernumber', '=', $req->customernumber)->first();
            $user->customernumber = $req->customernumber;
            $user->customer_id_ref = $customer->customer_id;

            $user->user_language = 1;
            if($req->user_password) $user->user_password = md5($req->user_password);    
            $result = $user->save();

            $units = Sensorunit::where('customer_id_ref', '=', $customer->customer_id)->get();
            foreach ($units as $unit) {
                $access = new Sensoraccess;
                $access->user_id = $user->user_id;
                $access->serialnumber = $unit->serialnumber;
                $access->changeallowed = $changes;
                $access->save();                
            }
            if ($result) {
                return '2';
            } else {
                return '0';
            }
        }
    }

    public function deleteUser(Request $req) {
        $user = User::find($req->id);
        $access = Sensoraccess::where('user_id', '=', $req->id)->delete();
        $result = $user->delete();
        if ($result == 1) {
            return '1';
        } else {
            return '0';
        }
    }

    public function deleteAccess(Request $req) {
        $access = Sensoraccess::find($req->id);
        $result = $access->delete();
        if ($result == 1) {
            return '1';
        } else {
            return '0';
        }
    }
}
