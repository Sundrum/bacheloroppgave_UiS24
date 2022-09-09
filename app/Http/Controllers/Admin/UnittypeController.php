<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unittype;
use App\Models\Sensorprobe;
use Illuminate\Support\Facades\Log;


class UnittypeController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
    }
    
    public function list(){
        $data = Unittype::all();
        $sorted = array();
        $i = 0;
        foreach ($data as $row) {
            $sorted[$i][0] = trim($row->unittype_id);
            $sorted[$i][1] = trim($row->unittype_description);
            $sorted[$i][2] = trim($row->unittype_shortlabel);
            $i++;
        }
        return json_encode($sorted);
    }

    public function addSensorprobe(Request $req) {
        if($req->unittype_id) {
            if ($req->sensorprobe_number) {
                if ($req->product_id) {
                    $probe = new Sensorprobe;
                    $probe->sensorprobes_number = $req->sensorprobe_number;
                    $probe->unittype_id_ref = $req->unittype_id;
                    $probe->product_id_ref = $req->product_id;
                    $probe->hidden = true;
                    $probe->sensorprobes_alert_hidden = true;
                    $probe->save();
                    $unittype = Unittype::find($probe->unittype_id_ref);
                    $data['sensorprobes_number'] = $probe->sensorprobes_number;
                    $data['unittype_description'] = $unittype->unittype_description;
                    $data['sensorprobes_id'] = $probe->sensorprobes_id;
                    return $data;
                }
            } else {
                if($req->sensorprobe_number == 0) {
                    $probe = new Sensorprobe;
                    $probe->sensorprobes_number = $req->sensorprobe_number;
                    $probe->unittype_id_ref = $req->unittype_id;
                    $probe->product_id_ref = $req->product_id;
                    $probe->hidden = true;
                    $probe->sensorprobes_alert_hidden = true;
                    $probe->save();
                    $unittype = Unittype::find($probe->unittype_id_ref);
                    $data['sensorprobes_number'] = $probe->sensorprobes_number;
                    $data['unittype_description'] = $unittype->unittype_description;
                    $data['sensorprobes_id'] = $probe->sensorprobes_id;
                    return $data;
                } else {
                    Log::info('Could not find sensorprobe_number in request');
                    Log::info($req);
                }

            }
        } else {
            Log::info('Could not find unittype_id in request');
            Log::info($req);  
        }

        
        return $req;
    }

    public function deleteSensorprobe(Request $req) {
        if($req->id) {
            $data = Sensorprobe::where('sensorprobes_id', $req->id)->delete();
            return $data;
        } else {
            return $req;
        }
    }

    public function changeHidden(Request $req) {
        if($req->id){
            if ($req->value == 1) {
                $probe = Sensorprobe::find($req->id);
                $probe->hidden = false;
                $probe->save();
                return '1';
            } else {
                $probe = Sensorprobe::find($req->id);
                $probe->hidden = true;
                $probe->save();
                return '1';
            }
        }
        return 'None';
    }

    public function changeAlert(Request $req) {
        if($req->id){
            if ($req->value == 1) {
                $probe = Sensorprobe::find($req->id);
                $probe->sensorprobes_alert_hidden = false;
                $probe->save();
                return '1';
            } else {
                $probe = Sensorprobe::find($req->id);
                $probe->sensorprobes_alert_hidden = true;
                $probe->save();
                return '1';
            }
        }
        return 'None';
    }
}
