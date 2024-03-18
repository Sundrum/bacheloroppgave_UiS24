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
    public function subscriptions(Request $request)
    {
        $subscription_id = $request->input("subscription_id");
        $serialnumber = null;
        if ($subscription_id) {
            $subscription = Subscription::find($subscription_id);
            $serialnumber = $subscription->serialnumber;
        }
        $user_id = Session::get('user_id');
        if ($user_id == null) {
            Log::error("user_id not found");
            $user_id = Auth::user()->user_id;
        }

        $user = User::find($user_id);
        if ($user == null) {
            Log::error("User not found");
        }

        //Get all subscription sensors for customer:
        $sensorUnits = Sensorunit::getSensorunitsForCustomer($user->customer_id_ref);
        $allocatedSensorUnitsSub = Sensorunit::getSensorUnitsWithSubscriptionForCustomer($user->customer_id_ref);
        $unallocatedSensorUnitsSub= Payment::getUnallocatedProductsForCustomer($user->customer_id_ref);

        //Get subscription data for allocated sensorunits:
        foreach ($allocatedSensorUnitsSub as $sensorUnit) {
            $subscriptionData = Subscription::getByCustomerIdAndSerialNumber($user->customer_id_ref, $sensorUnit->serialnumber); 
            $paymentData = Payment::getByCustomerIdAndSerialNumber($user->customer_id_ref, $sensorUnit->serialnumber);
            $sensorUnit->subscriptionData = $subscriptionData->isNotEmpty() ? $subscriptionData : false; //append susbcription data
            $sensorUnit->paymentData = $paymentData->isNotEmpty() ? $paymentData : false;   //append payment data
        }
        $subscriptions = Subscription::getSubscriptionsForCustomerJoinAllDistinctSN($user->customer_id_ref);
        self::setActivity("Entered subscriptions", "subscriptions");
        return view('pages/payment/subscriptions', compact('subscriptions', 'user', 'serialnumber'));
        // return view('pages/payment/subscriptions', compact('allocatedSensorUnitsSub','unallocatedSensorUnitsSub', 'user'));
    }
    
    public function subscriptionDetails(Request $request)
    {
        $sensorunitId = $request->input('sensorunitId');
        $subscriptionId = $request->input('subscriptionId');

        $sensorUnit = Sensorunit::getUnitWithSerialnumber($sensorunitId);
        $subscription = Subscription::find($subscriptionId);

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
        return view('pages/payment/subscriptiondetails', compact('sensorUnit','subscription'));
    }
    public function cancelSubscription(Request $request)
    {
        $sensorunitId = $request->input('sensorunitId');
        $subscriptionId = $request->input('subscriptionId');
        $sensorUnit = Sensorunit::getUnitWithSerialnumber($sensorunitId);
        $subscription = Subscription::find($subscriptionId);
        $subscription->subscription_status = 1;
        $subscription->save();

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
        return view('pages/payment/subscriptiondetails', compact('sensorUnit','subscription'));
    }
    public function reactivateSubscription(Request $request)
    {
        $sensorunitId = $request->input('sensorunitId');
        $subscriptionId = $request->input('subscriptionId');
        $sensorUnit = Sensorunit::getUnitWithSerialnumber($sensorunitId);
        $subscription = Subscription::find($subscriptionId);
        $subscription->subscription_status = 2;
        $subscription->save();

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
        return view('pages/payment/subscriptiondetails', compact('sensorUnit','subscription'));
    }
    public function manageSubscription(Request $request)
    {
        $sensorunitId = $request->input('sensorunitId');
        $sensorUnit = Sensorunit::getUnitWithSerialnumber($sensorunitId);

        // Find customer id
        $user_id = Session::get('user_id');
        if ($user_id == null) {
            $user_id = Auth::user()->id;
        }
        $user = User::find($user_id);
        if ($user == null) {
            Log::error("User not found");
        }
        $customer_id = $user->customer_id_ref;

        //Perform security check
        if ($sensorUnit->customer_id_ref != $customer_id)
        {
            return view('pages/payment/subcriptiondetails');
        }

        //get subscription id
        $subscription = Subscription::getByCustomerIdAndSerialNumber($customer_id, $sensorunitId);
        $subscriptionId = $subscription->subscription_id;
        return view('pages/payment/updatepaymentdetails', compact(''));
    }
}
