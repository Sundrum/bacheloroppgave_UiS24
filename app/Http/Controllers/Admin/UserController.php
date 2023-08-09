<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sensoraccess;
use App\Models\Sensorunit;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Roletype;
use Illuminate\Http\Request;
use Redirect;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }

    public function index() {
        $users = User::all();
        $result = array();
        $i = 0;
        foreach ($users as $user) {
            $result[$i][0] = $user->user_id;
            $result[$i][1] = $user->user_name;
            $result[$i][2] = $user->user_email;
            $result[$i][3] = $user->user_phone_work;
            $i++;
        }
        $data = json_encode($result);
        return view('admin.user.view', compact('data'));

    }

    public function new(){
        $table['roletype'] = Roletype::all();
        $table['customer'] = Customer::all();

        return view('admin.user.user', compact('table'));
    }

    public static function get($id) {
        $user = User::find($id);
        $table['roletype'] = Roletype::all();
        $table['customer'] = Customer::all();
        $user['sensorunits'] = Sensoraccess::where('user_id','=', $id)->get();
        return view('admin.user.user', compact('user', 'table'));
    }

    public static function user(Request $req) {
        if ($req->user_id) {
            $user = User::find($req->user_id);
            $user->user_email = $req->user_email;
            $user->user_name = $req->user_name;
            $user->user_phone_work = $req->user_phone_work;
            $user->user_alternative_email = $req->user_alternative_email;
            $user->roletype_id_ref = $req->roletype_id_ref;
            $user->user_language = $req->user_language;
            $user->measurement = $req->measurement;
            if($user->customer_id_ref != $req->customer_id) {
                Sensoraccess::where('user_id', $req->user_id)->delete();
                $sensorunits = Sensorunit::where('customer_id_ref', $req->customer_id)->get();
                foreach ($sensorunits as $row) {
                    $access = new Sensoraccess;
                    $access->user_id = $user->user_id;
                    $access->serialnumber = $row->serialnumber;
                    $access->changeallowed = true;
                    $access->save();
                }
            }
            $user->customer_id_ref = $req->customer_id;
            $customer = Customer::find($req->customer_id);
            $user->customernumber = $customer->customernumber;
            if($req->user_password) $user->user_password = md5($req->user_password);
            $result = $user->save();
            if ($result) {
                return Redirect::to('admin/account/'.$user->user_id);
            } else {
                return 'Error: USER result';
            }

        } else {
            $user = new User;
            $user->user_email = $req->user_email;
            $user->user_name = $req->user_name;
            $user->user_phone_work = $req->user_phone_work;
            $user->user_alternative_email = $req->user_alternative_email;
            $user->roletype_id_ref = $req->roletype_id_ref;
            $user->customer_id_ref = $req->customer_id;
            $user->user_language = $req->user_language;
            $user->measurement = $req->measurement;
            $customer = Customer::find($req->customer_id);
            $user->customernumber = $customer->customernumber;
            $user->user_language = 1;
            // md5 is outdated
            $user->user_password = md5($req->user_password);    
            $result = $user->save();
            $sensorunits = Sensorunit::where('customer_id_ref', $req->customer_id)->get();
            foreach ($sensorunits as $row) {
                $access = new Sensoraccess;
                $access->user_id = $user->user_id;
                $access->serialnumber = $row->serialnumber;
                $access->changeallowed = true;
                $access->save();
            }
            if ($result) {
                return Redirect::to('admin/account/'.$user->user_id);
            } else {
                return '0';
            }
        }
    } 

    public function delete(Request $request) {
        $row = User::where('user_id', '=', $request->input('user_id'))->delete();
        return Redirect::to('admin/user');
    }

    public function deleteAccess(Request $request) {
        $row = Sensoraccess::where('serialnumber', '=', $request->input('serialnumber'))->where('user_id', '=', $request->input('userid'))->delete();
        return $row;
    }

    public function addAccess(Request $request) {
        $access = Sensoraccess::create([
            'user_id' => $request->input('userid'),
            'serialnumber' => $request->input('serialnumber'),
            'changeallowed' => $request->input('access'),
        ]);

        return $access;
    }
}
