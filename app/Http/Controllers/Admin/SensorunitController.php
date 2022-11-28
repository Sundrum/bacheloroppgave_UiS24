<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensorunit;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Status;
use App\Models\User;
use App\Models\Sensoraccess;
use App\Models\Cases;
use App\Models\Service_persons;
use App\Models\Service_status;
use Redirect, DB;

class SensorunitController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }

    public function index() {
        $data = Unit::getSensorunits();
        $result = array();
        $i = 0;
        foreach ($data as $row) {
            $result[$i][0] = $row['sensorunit_id'];
            $result[$i][1] = $row['serialnumber'];
            $result[$i][2] = $row['sensorunit_location'];
            $result[$i][3] = $row['customer_name'];
            $result[$i][4] = $row['product_name'];
            $i++;
        }
        $data = json_encode($result);
        return view('admin.sensorunit.view', compact('data'));

    }

    public function new() {
        $product = Product::orderBy('productnumber')->get();
        return view('admin.sensorunit.new', compact('product'));
    }

    // delete Sensor
    public function delete(Request $request) {
        $unit = Sensorunit::where('serialnumber', '=', $request->input('id'))->delete();
        $id = $request->input('id');
        return $id;
    }

    // cases list
    public function casesIndex() {
        $data = Cases::getCases();
        // dd($data);
        $result = array();
        $i = 0;
        foreach ($data as $row) {
            $customer = Customer::find($row->customer_id_ref);
            $service_person = Service_persons::find($row->case_manager);
            $result[$i][0] = $row->case_id;
            $result[$i][1] = $row->serialnumber;
            if(isset($customer->customer_name)){
                $result[$i][2] = $customer->customer_name;
            }
            else if(isset($row->customer_id_ref)){
                $result[$i][2] = $row->customer_id_ref;
            }
            else {
                $result[$i][2] = 'NaN';
            }
            $result[$i][3] = $service_person->service_person_name;
            $result[$i][4] = $row->service_id;
            $result[$i][6] = '<button onclick="deleteCase('.$row->case_id.')" class="btn btn-danger">Slett</button>';
            if($row->status == 2){
                $result[$i][5] = '<span class="align-top ml-1" style="color:yellow">Under arbeid</span></div>';
            }
            elseif($row->status == 3){
                $result[$i][5] = '<span class="align-top ml-1" style="color:green">Ferdig</span></div>';
            }
            else{
                $result[$i][5] = '<span class="align-top ml-1" style="color:red">Ikke påbegynt</span></div>';
            }
            $i++;
        }
        $data = json_encode($result);
        return view('admin.sensorunit.casesindex', compact('data'));

    }

    // exsisting case
    public function oneCase($id) {
        $data = Cases::find($id);
        $customer = Customer::find($data->customer_id_ref);
        $service_status = Service_status::get();
        $service_persons = Service_persons::get();

        return view('admin.sensorunit.cases', compact('data', 'customer', 'service_status', 'service_persons'));
    }

    // create new case
    public function newCase() {
        $customers = Customer::orderBy("customer_name")->get();
        foreach($customers as $customer){
            $customer->sensorunits = Sensorunit::orderBy("serialnumber")->where("customer_id_ref", $customer->customer_id)->get();
        }
        // dd($customers[1]);
        $service_status = Service_status::get();
        $service_persons = Service_persons::get();

        return view('admin.sensorunit.cases', compact( 'customers', 'service_status', 'service_persons'));
    }

    // create new case (post)
    public function createCase(Request $req) {
        $case = new Cases();
        $customer = Customer::find($req["customer_id_ref"]);
        $case->serialnumber = $req["serialnumber"];
        $case->serialnumber_2 = $req["serialnumber_2"];
        $case->customer_id_ref = $req["customer_id_ref"];
        $case->date_recived = $req["date_recived"];
        $case->service_id = $req["service_id"];
        $case->fault_description = $req["fault_comment"];
        $case->repair_description = $req["rep_comment"];
        $case->test_ok = $req["test_ok"] == "1" ? true : false;
        $case->case_manager = $req["service_person"];
        $case->status = $req["service_status"];
        // dd($req);
        $case->save();
        
        $customer->customer_visitaddr1 = $req["customer_address"];
        $customer->customer_visitpostcode = $req["customer_postcode"];
        $customer->customer_visitcity = $req["customer_city"];
        
        $customer->save();
        return 1;
    }

    // update exisiting case
    public function updateCase($id, Request $req) {
        // return $req;
        $case = Cases::find($id);
        $customer = Customer::find($req["customer_id_ref"]);
        $case->service_id = $req["service_id"];
        $case->fault_description = $req["fault_comment"];
        $case->repair_description = $req["rep_comment"];
        $case->test_ok = $req["test_ok"] == "1" ? true : false;
        $case->case_manager = $req["service_person"];
        $case->status = $req["service_status"];
        $case->save();
        // dd($req);

        $customer->customer_visitaddr1 = $req["customer_address"];
        $customer->customer_visitpostcode = $req["customer_postcode"];
        $customer->customer_visitcity = $req["customer_city"];

        $customer->save();
        return 1;
    }

       // delete case
    public function deleteCase(Request $req) {
        $case = Cases::find($req->case_id)->delete();
   
        return 1;

    }

// add new sensorunit
    public function add(Request $req) {
        $product = Product::find($req->product);
        $serial = $product->productnumber.'-'.$req->serialnumber;
        $exsist_string = 'Already exsists: ';
        $exsist_count = 0;
        for ($i = 0; $i < $req->amount; $i++) {
            $last = str_pad($req->serialnumber + $i, 5,"0", STR_PAD_LEFT);
            $newserial = $product->productnumber.'-'.$last;
            $exsist = Sensorunit::where('serialnumber', $newserial)->first();
            if($exsist){
                $exsist_string .= $newserial.', ';
                $exsist_count += 1;
            } else {
                $unit = new Sensorunit();
                $unit->serialnumber = $newserial;
                $unit->custom_id = 1;
                $unit->sensorunit_installdate = now();
                $unit->sensorunit_lastconnect = now();
                $unit->dbname = 'sensordata_onstock';
                $unit->customernumber = '10-0000-AA';
                $unit->sensorunit_status = 1;
                $unit->customer_id_ref = 18;
                $unit->helpdesk_id_ref = 1;
                $unit->product_id_ref = $product->product_id;
                $unit->sensorunit_position = ' ';
                $unit->save();
            }
        }
        $product = Product::orderBy('productnumber')->get();
        return view('admin.sensorunit.new', compact('product'));
    }

    public function all() {
        $data = Sensorunit::getSensorunits();
        $sorted = array();
        $i = 0;
        foreach ($data as $unit) {
            $sorted[$i][0] = trim($unit->serialnumber);
            $sorted[$i][1] = trim($unit->sensorunit_id);
            $sorted[$i][2] = trim($unit->sensorunit_location);
            $sorted[$i][3] = trim($unit->sensorunit_position);
            $i++;
        }
        return json_encode($sorted);
    }

    public function get($id) {
        // dd($id);
        $unit = Sensorunit::find($id);
        $unit->access = Sensoraccess::where('serialnumber', $unit->serialnumber)->join('users', 'sensoraccess.user_id','users.user_id')->get();
        $table['customer'] = Customer::all();
        $api = DB::connection('sensordata')->select('SELECT * FROM status WHERE serialnumber = ?', [$unit->serialnumber]);
        if($api) {
            foreach ($api as $row) {
                // dd($row);
                $unit[trim($row->variable)] = trim($row->value);
                if(trim($row->variable) == 'gnss') {
                    $unit['timegnss'] = self::convertTimestampToUserTimezone($row->dateupdated);
                }
            }
        } else {
            $data = Status::where('serialnumber',$unit->serialnumber)->get();
            foreach ($data as $row) {
                $unit[trim($row->variable)] = trim($row->value);
            }
        }
        return view('admin.sensorunit.details', compact('unit', 'table'));
    }

    public function getCustomer($id) {
        $data = Sensorunit::getUnit($id);
        return json_encode('Connected to customer: '.$data->customer_name);
    }

    public function update(Request $req) {
        if($req->sensorunit_id) {
            $unit = Sensorunit::find($req->sensorunit_id);
            $customer = Customer::find($req->customer_id_ref);

            if($unit->customer_id_ref != $req->customer_id_ref) {
                $access = Sensoraccess::where('serialnumber', $unit->serialnumber)->delete();
                $users = User::where('customer_id_ref', $req->customer_id_ref)->get();
                foreach($users as $user) {
                    $access = new Sensoraccess;
                    $access->user_id = $user->user_id;
                    $access->serialnumber = $unit->serialnumber;
                    $access->changeallowed = true;
                    $access->save();
                }
            }

            $unit->sensorunit_location = $req->sensorunit_location;
            $unit->sensorunit_pba = $req->pba;
            if($req->sensorunit_position) $unit->sensorunit_position = $req->sensorunit_position;
            $unit->customernumber = $customer->customernumber;
            $temp = explode('-',$customer->customernumber);
            $customnum = $temp[1];
            if ($customnum == '1000') {
                $customnum = '7products';
            } else if ($customnum == '0000') {
                $customnum = 'onstock';
            } else if ($customnum == '1010') {
                $customnum = 'eftedal';
            } else if ($customnum == '1014') {
                $customnum = 'stenersen';
            } else if ($customnum == '1015') {
                $customnum = 'linnes';
            } else if ($customnum == '1016') {
                $customnum = 'bjertnaes';
            } else if ($customnum == '9000') {
                $customnum = 'demo';
            }
            $unit->dbname = 'sensordata_'.$customnum;
            $unit->customer_id_ref = $customer->customer_id;
            $unit->save();

            return Redirect::to('admin/sensorunit/'.$unit->sensorunit_id);
        } else {
            return 'error';
        }
    } 

    public function updateSensorunitCustomer(Request $req) {
        if($req->sensorunit_id) {
            $unit = Sensorunit::find($req->sensorunit_id);
            $customer = Customer::find($req->customer_id_ref);

            if($unit->customer_id_ref != $req->customer_id_ref) {
                $access = Sensoraccess::where('serialnumber', $unit->serialnumber)->delete();
                $users = User::where('customer_id_ref', $req->customer_id_ref)->get();
                foreach($users as $user) {
                    $access = new Sensoraccess;
                    $access->user_id = $user->user_id;
                    $access->serialnumber = $unit->serialnumber;
                    $access->changeallowed = true;
                    $access->save();
                }
            }
            $unit->customernumber = $customer->customernumber;
            $temp = explode('-',$customer->customernumber);
            $customnum = $temp[1];
            if ($customnum == '1000') {
                $customnum = '7products';
            } else if ($customnum == '0000') {
                $customnum = 'onstock';
            } else if ($customnum == '1010') {
                $customnum = 'eftedal';
            } else if ($customnum == '1014') {
                $customnum = 'stenersen';
            } else if ($customnum == '1015') {
                $customnum = 'linnes';
            } else if ($customnum == '1016') {
                $customnum = 'bjertnaes';
            } else if ($customnum == '9000') {
                $customnum = 'demo';
            }
            $unit->dbname = 'sensordata_'.$customnum;
            $unit->customer_id_ref = $customer->customer_id;
            $unit->save();

            return Redirect::to('admin/customer/'.$customer->customer_id.'?message=New sensorunit added to customer. Serialnumber: '.$unit->serialnumber);
        } else {
            return Redirect::to('admin/customer/'.$customer->customer_id.'?errormessage=Something went wrong. Cant find Sensorunit ID');
        }

    }

    public function debug($serial){
        return view('admin.sensorunit.debug', compact('serial'));
    }

    public function xyz() {

        $data[] = self::checks('21-1057-AB-00244');
        $data[] = self::checks('21-1057-AB-00245');
        $data[] = self::checks('21-1057-AB-00246');
        $data[] = self::checks('21-1057-AB-00247');

        return view('admin.development.xyz', compact('data'));
    }

    public static function calc($serial) {
        $readings = Unit::latestSensorReadings($serial);
        $data = array();
        foreach ($readings as $row) {
            // dd($row);
            $data['serial'] = $serial;
            $data['time'] = self::convertTimestampToUserTimezone($row['timestamp']);
            if ($serial == '21-1057-AB-00244') {
                $data['color'] = '#228B22';
                $data['text-color'] = '#FFFFFF';
            } else if ($serial == '21-1057-AB-00245') {
                $data['color'] = '#4682B4';
                $data['text-color'] = '#FFFFFF';
            } else if ($serial == '21-1057-AB-00246') {
                $data['color'] = '#FED16D';
                $data['text-color'] = '#000000';
            } else if ($serial == '21-1057-AB-00247') {
                $data['color'] = 'grey';
                $data['text-color'] = '#FFFFFF';
            }

            if ($row['value'] > 1000) {
                $row['value'] = 1000;
            } else if ($row['value'] < -1000) {
                $row['value'] = -1000;
            }

            if ($row['value'] > -33 && $row['value'] < 33) {
                $row['value'] = 0;
            }

            if($row['unittype_id'] == 10) {
                $data['xdeg'] = (90/1000*$row['value'])+90;
                $data['xorg'] = $row['value'];
            } else if ($row['unittype_id'] == 11) {
                $data['ydeg'] = (90/1000*$row['value'])+90;
                $data['yorg'] = $row['value'];

            } else if ($row['unittype_id'] == 26) {
                $data['zdeg'] = (90/1000*$row['value'])+90;
                $data['zorg'] = $row['value'];
            }
        }

        if ($data['xorg'] >= 0) {
            $data['x'] = deg2rad((90/1000*$data['xorg']) + 90 );
        } else {
            $data['x'] = deg2rad((90/1000*$data['xorg']) + 90 );
        }
        if ($data['yorg'] >= 0) {
            if ($data['yorg'] >= 800) {
                $data['y'] =  deg2rad((180/1000*$data['yorg'])+270);
            } else {
                $data['y'] =  deg2rad((90/1000*$data['yorg'])+270);
            }
        } else {
            if ($data['xorg'] >= 0) {
                $data['y'] =  deg2rad((180/1000*$data['yorg']) - 45);
            } else {
                $data['y'] =  deg2rad((180/1000*$data['yorg'])-270);
            }
        }
        
        $data['z'] =  deg2rad(-(90/1000*$data['zorg']) + 90);

        return $data;
    }

    public function testxyz(){
        $data[] = self::checks('21-1057-AB-00244');

        return view('admin.development.unit1', compact('data'));
    }
    public static function checks($serial) {
        $readings = Unit::latestSensorReadings($serial);
        foreach ($readings as $row) {
            $data['serial'] = $serial;
            $data['time'] = self::convertTimestampToUserTimezone($row['timestamp']);
            if ($serial == '21-1057-AB-00244') {
                $data['color'] = '#228B22';
                $data['text-color'] = '#FFFFFF';
            } else if ($serial == '21-1057-AB-00245') {
                $data['color'] = '#4682B4';
                $data['text-color'] = '#FFFFFF';
            } else if ($serial == '21-1057-AB-00246') {
                $data['color'] = '#FED16D';
                $data['text-color'] = '#000000';
            } else if ($serial == '21-1057-AB-00247') {
                $data['color'] = 'grey';
                $data['text-color'] = '#FFFFFF';
            }

            if ($row['value'] > 1000) {
                $row['value'] = 1000;
            } else if ($row['value'] < -1000) {
                $row['value'] = -1000;
            }

            if ($row['value'] > -33 && $row['value'] < 33) {
                $row['value'] = 0;
            }

            if($row['unittype_id'] == 10) {
                $data['xdeg'] = (90/1000*$row['value'])+90;
                $data['xorg'] = $row['value'];
            } else if ($row['unittype_id'] == 11) {
                $data['ydeg'] = (90/1000*$row['value'])+90;
                $data['yorg'] = $row['value'];

            } else if ($row['unittype_id'] == 26) {
                $data['zdeg'] = (90/1000*$row['value'])+90;
                $data['zorg'] = $row['value'];
            }
        }

        $data['z'] =  deg2rad(-(90/1000*0) + 90);

        $data['xorg'] = 0;
        $data['yorg'] = 0;

        if (abs($data['xorg']) > abs($data['yorg'])) {
            // X
            $data['x'] = deg2rad(-(90/1000*$data['xorg']) + 90);
            if (abs($data['yorg']) >= 32) {
                $data['y'] =  deg2rad((90/1000*0) + 90);
                if ($data['xorg'] >= 0) {
                    $data['z'] =  deg2rad((90/1000*$data['yorg']) + 90);
                } else {
                    $data['z'] =  deg2rad(-(90/1000*$data['yorg']) + 90);
                }

            } else {
                $data['y'] =  deg2rad((90/1000*$data['yorg']) + 90);
            }
        } else {
            // Y
            if ($data['yorg'] >= 0) {
                $data['x'] = deg2rad(-(90/1000*$data['xorg']) + 90);
                $data['y'] =  deg2rad((90/1000*$data['yorg']));
            } else {
                $data['y'] =  deg2rad((90/1000*-1000) + 180);
                if (abs($data['xorg']) >= 32) {
                    $data['x'] = deg2rad((90/1000*$data['xorg']) + 270);
                    $data['z'] =  deg2rad(-(90/1000*$data['yorg']) );
                } else {
                    $data['x'] = deg2rad(-(90/1000*$data['xorg']) + 270);
                    $data['z'] =  deg2rad(-(90/1000*$data['yorg']) );
                }
            }


        }

        return $data;
    }
}
