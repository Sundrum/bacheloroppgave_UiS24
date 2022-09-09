<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unittype;
use App\Models\Sensorprobe;

class UnittypeController extends Controller
{
    public function newUnittype() {
        return view('admin.unittype.edit');
    }

    public function edit($id) {
        $unittype = Unittype::find($id);
        $counter = Sensorprobe::where('unittype_id_ref', $id)->count();
        // dd($unittype);
        return view('admin.unittype.edit', compact('unittype', 'counter'));
    }

    public function update(Request $req) {
        if($req->old) {
            if($req->id) {
                $type = Unittype::find($req->id);
                $type->unittype_description = $req->description;
                $type->unittype_label = $req->label;
                $type->unittype_shortlabel = $req->shortlabel;
                $type->unittype_decimals = $req->decimals;
                $type->unittype_url = $req->url;
                $type->save();
            } else {
                $type = Unittype::find($req->id);
                $type->unittype_description = $req->description;
                $type->unittype_label = $req->label;
                $type->unittype_shortlabel = $req->shortlabel;
                $type->unittype_decimals = $req->decimals;
                $type->unittype_url = $req->url;
                $type->save();
            }
        } else {
            $type = new Unittype;
            $type->unittype_description = $req->description;
            $type->unittype_label = $req->label;
            $type->unittype_shortlabel = $req->shortlabel;
            $type->unittype_decimals = $req->decimals;
            $type->unittype_url = $req->url;
            $type->save();
        }

        return redirect('/admin/unittype/'.$type->unittype_id)->with('unittype', $type);
    }

    public static function getUnittypes($action) {
        $data = Unittype::getUnittypes();
        $count_data = count($data);
        $sorted = array();

        for ($i = 0; $i < $count_data; $i++) {

            if (isset($data[$i]['unittype_id'])) {
                $sorted[$i][0] = trim($data[$i]['unittype_id']);
            } else {
                $sorted[$i][0] = '-';
            }

            if (isset($data[$i]['unittype_description'])) {
                $sorted[$i][1] = trim($data[$i]['unittype_description']);
            } else {
                $sorted[$i][1] = '-';
            }

            if (isset($data[$i]['unittype_shortlabel'])) {
                $sorted[$i][2] = trim($data[$i]['unittype_shortlabel']);
            } else {
                $sorted[$i][2] = '-';
            }

            if (isset($data[$i]['unittype_decimals'])) {
                $sorted[$i][3] = trim($data[$i]['unittype_decimals']);
            } else {
                $sorted[$i][3] = '-';
            }

            if (isset($data[$i]['unittype_id'])) {
                $id = trim($data[$i]['unittype_id']);
            } else {
                $id= '-';
            }
            if ($action == 1) {
                $sorted[$i][4] = '<button class="btn btn-primary"><a href="/admin/unittype/'.$id.'" style="color:#FFFFFF;">Edit</a></button>';
            } else if ($action == 2) {
                $sorted[$i][4] = '<button class="btn btn-primary"><a href="/admin/connect/customer/'.$customerid.'" style="color:#FFFFFF;">Select</a></button>';
            }
            //$sorted[$i][4] = '<a href="/admin/customer/'.$customerid.'"><p> Edit</p> </a>';
        }
        return $sorted;
    }
}
