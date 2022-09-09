<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sensoraccess;

class AccessController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('checkadmin');
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
