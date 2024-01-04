<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Irrigationrun;


class IrrigationrunController extends Controller
{
    public function index() {
        $runs = self::getRuns();
        return view('admin.irrigationrun.view', compact('runs'));
    }

    public function getRuns() {
        return Irrigationrun::all();
    }

    public static function getRun($id) {
        return Irrigationrun::find($id);
    }

    public function editRun(Request $req) {
        $run = Irrigationrun::find($req->log_id);
        $run->irrigation_starttime = $req->starttime;
        $run->irrigation_startpoint = $req->startpoint;
        $run->irrigation_endpoint = $req->endpoint;
        $run->irrigation_endtime = $req->endtime;
        $run->save();
        return $run;
    }
}
