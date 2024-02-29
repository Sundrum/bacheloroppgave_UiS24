<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Auth;
use Session;
use App\Models\Sensorunit;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\PaymentsUnits;

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
        foreach ($sensorUnits as $sensorUnit) {
            $subscriptionData = Subscription::getByCustomerIdAndSerialNumber($user->customer_id_ref, $sensorUnit->serialnumber);
            $paymentData = Payment::getByCustomerIdAndSerialNumber($user->customer_id_ref, $sensorUnit->serialnumber);
            $sensorUnit->subscriptionData = $subscriptionData->isNotEmpty() ? $subscriptionData : false;
            $sensorUnit->paymentData = $paymentData->isNotEmpty() ? $paymentData : false;
        }
        self::setActivity("Entered subscriptions", "subscriptions");
        return view('pages/payment/subscriptions', compact('sensorUnits', 'user'));
    }

    public function subscriptionDetails(Request $request)
    {
        $sensorunit_id = $request->input('id');
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
        return view('pages/payment/subscriptiondetails', compact('sensorUnit'));
    }
}
