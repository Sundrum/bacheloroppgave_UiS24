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
use App\Models\SubscriptionPayment;
use App\Models\PaymentsUnits;

class SubscriptionsController extends Controller
{
    public function subscriptions(Request $request)
    {
        $subscription_id = $request->input("subscription_id");
        $payment_id = $request->input("payment_id");
        $serialnumber = null;

        
        $user_id = Session::get('user_id');
        if ($user_id == null) {
            Log::error("user_id not found");
            $user_id = Auth::user()->user_id;
        }

        $user = User::find($user_id);
        if ($user == null) {
            Log::error("User not found");
        }

        // If user is redirected from the "Manage payment method" site
        if ($subscription_id && $payment_id) {
            $subscription = Subscription::find($subscription_id);
            $serialnumber = $subscription->serialnumber;

            // New payment entry when you change payment method
            $payment = new Payment;
            $payment->payment_id = $payment_id;
            $payment->payment_status = 3; //Completed
            $payment->customer_id_ref = $user->customer_id_ref;
            $payment->save();

            $subscription_payment = new SubscriptionPayment;
            $subscription_payment->subscription_id = $subscription_id;
            $subscription_payment->payment_id = $payment_id;
            $subscription_payment->save();
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

        $payment = self::GetMostRecentPaymentForSubscription($subscriptionId);
        $netsResponse = Payment::getNetsResponse($payment->payment_id);

        try {
            $maskedPan = $netsResponse->payment->paymentDetails->cardDetails->maskedPan;
            $cardType = $netsResponse->payment->paymentDetails->paymentMethod;
        }
        catch (\Exception $e) {
            $payment = self::GetSecondMostRecentPaymentForSubscription($subscriptionId);
            $netsResponse = Payment::getNetsResponse($payment->payment_id);
            try {
                $maskedPan = $netsResponse->payment->paymentDetails->cardDetails->maskedPan;
                $cardType = $netsResponse->payment->paymentDetails->paymentMethod;
            }
            catch (\Exception $e) {
                $maskedPan = null;
                $cardType = null;
            }
        }

        return view('pages/payment/subscriptiondetails', compact('sensorUnit','subscription', 'maskedPan', 'cardType'));
    }
    public function cancelSubscription(Request $request)
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

        // Authenticate that the user actually owns the sensor unit and subscription (inspect element tampering on the form)
        if ($sensorUnit->customer_id_ref != $user->customer_id_ref)
        {
            return view('fallback');
        }
        if ($subscription->customer_id_ref != $user->customer_id_ref)
        {
            return view('fallback');
        }
        $subscription->subscription_status = 1;
        $subscription->save();

        $payment = self::GetMostRecentPaymentForSubscription($subscriptionId);
        $netsResponse = Payment::getNetsResponse($payment->payment_id);
        if ($netsResponse->payment->paymentDetails->cardDetails == null) {
            $payment = self::GetSecondMostRecentPaymentForSubscription($subscriptionId);
            $netsResponse = Payment::getNetsResponse($payment->payment_id);
        }


        $maskedPan = $netsResponse->payment->paymentDetails->cardDetails->maskedPan;
        $cardType = $netsResponse->payment->paymentDetails->paymentMethod;

        return view('pages/payment/subscriptiondetails', compact('sensorUnit','subscription', 'maskedPan', 'cardType'));
    }
    public function reactivateSubscription(Request $request)
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

        // Authenticate that the user actually owns the sensor unit and subscription (inspect element tampering on the form)
        if ($sensorUnit->customer_id_ref != $user->customer_id_ref)
        {
            return view('fallback');
        }
        if ($subscription->customer_id_ref != $user->customer_id_ref)
        {
            return view('fallback');
        }

        // Reactivate the subscription
        $subscription->subscription_status = 2;
        $subscription->save();

        $payment = self::GetMostRecentPaymentForSubscription($subscriptionId);
        $netsResponse = Payment::getNetsResponse($payment->payment_id);
        if ($netsResponse->payment->paymentDetails->cardDetails == null) {
            $payment = self::GetSecondMostRecentPaymentForSubscription($subscriptionId);
            $netsResponse = Payment::getNetsResponse($payment->payment_id);
        }

        $maskedPan = $netsResponse->payment->paymentDetails->cardDetails->maskedPan;
        $cardType = $netsResponse->payment->paymentDetails->paymentMethod;

        return view('pages/payment/subscriptiondetails', compact('sensorUnit','subscription', 'maskedPan', 'cardType'));
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
            return view('fallback');
        }

        //get subscription id
        $subscription = Subscription::getByCustomerIdAndSerialNumber($customer_id, $sensorunitId);
        $subscriptionId = $subscription->subscription_id;
        return view('pages/payment/updatepaymentdetails', compact(''));
    }


    public static function GetMostRecentPaymentForSubscription($subscriptionId){
        $payment_ids = SubscriptionPayment::getPaymentIdsBySubscriptionId($subscriptionId);
        $old_payment = Payment::find($payment_ids[0]);
        $payment = $old_payment;
        foreach ($payment_ids as $payment_id) {
            $new_payment = Payment::find($payment_id);
            if ($new_payment->created_at > $old_payment->created_at){
                $payment = $new_payment;
            }
            $old_payment = $new_payment;
        }
        return $payment;
    }

    public static function GetSecondMostRecentPaymentForSubscription($subscriptionId){
        $payment_ids = SubscriptionPayment::getPaymentIdsBySubscriptionId($subscriptionId);
        $payments = [];
        foreach ($payment_ids as $payment_id) {
            $payments[] = Payment::find($payment_id);
        }
        $createdAt = array_column($payments, 'created_at');
        array_multisort($createdAt, SORT_DESC, $payments);
        return $payments[1];
    }
}