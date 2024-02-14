<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Auth;
use Session;
use App\Models\Sensorunit;
use App\Models\User;

class SubscriptionsController extends Controller
{
    public function subscriptions()
    {
        $user_id = Session::get('user_id');
        if ($user_id == null) {
            Log::error("user_id not found");
            $user_id = Auth::user()->user_id;
        }

        $user = User::find($user_id);
        if ($user == null) {
            Log::error("User not found");
        }
        $sensorUnits = Sensorunit::getSensorunitsForCustomer($user->customer_id_ref);
        //dd($sensorUnits);
        self::setActivity("Entered subscriptions", "subscriptions");
        return view('pages/subscriptions', compact('sensorUnits', 'user'));
    }

    public function subscriptionDetails(Request $request, $sensorunit_id)
    {
        $sensorUnit = Sensorunit::getUnit($sensorunit_id);

        $user_id = Session::get('user_id');
        if ($user_id == null) {
            $user_id = Auth::user()->id;
        }

        $user = User::find($user_id);
        if ($user == null) {
            Log::error("User not found");
        }
        if ($sensorUnit->customer_id_ref != $user->customer_id_ref)
        {
            return view('fallback');
        }
        //dd($sensorUnit);
        return view('pages/subscriptiondetails', compact('sensorUnit'));
    }
}
