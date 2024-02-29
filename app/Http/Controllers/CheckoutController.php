<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Sensorunit;
use Log;
use Auth;
class CheckoutController extends Controller
{
    //Handles checkout page
    public function checkout(Request $request)
    {
        self::setActivity("Entered checkout", "checkout");
        Log::info("checkout method");
        $payment_id = request()->paymentId;
        $language = Auth::user()->user_language;
        $customer_id = Auth::user()->customer_id_ref;
        $checkoutKey = env('NETS_EASY_CHECKOUT_KEY');

        self::initPaymentEntry($payment_id, $customer_id);
        $sensorUnits=Sensorunit::getSensorunits();
        $paddedIncrementedDigits = str_pad(intval(substr($sensorUnits[count($sensorUnits) - 1]->serialnumber, -5)) + 1, 5, '0', STR_PAD_LEFT);
        $newSerialNumber = '21-9031-AA-' . $paddedIncrementedDigits;
        dd($newSerialNumber);

        //PASSE PÅ AT ALLE INIT KJØRER ELLER INGEN.(??)
        // self::initSubscriptionEntry($, $customer_id, $payment_id);
        // self::initSubscriptionPaymentEntry($, $payment_id);

        return view('pages/payment/checkout', ['paymentId' => $payment_id, 'language' => $language, 'checkoutKey' => $checkoutKey]);
    }
    //Handles checkout success
    public function success(Request $request)
    {     
        $payment_id = $request->query('payment_id');
        $payment = Payment::find($payment_id);
        $payment->payment_status = 3; //Success
        $payment->save();
        self::setActivity("Checkout success", "success");
        return view('pages/payment/checkoutsuccess', compact('payment_id'));
    }

    public static function initPaymentEntry($payment_id, $customer_id_ref){
        $payment = new Payment;
        $payment->payment_id = $payment_id;
        $payment->payment_status = 0; //Created
        $payment->customer_id_ref = $customer_id_ref;
        $payment->save();
    }

    public static function initSubscriptionEntry($subscription_id, $customer_id_ref, $serialnumber){
        $subscription = new Subscription;
        $subscription->subscription_id = $subscription_id;
        $subscription->customer_id_ref = $customer_id_ref;
        $subscription->interval = 31556926; // One year
        $subscription->serialnumber = $serialnumber;
        $subscription->subscription_status = 0; //Inactive
        $subscription->save();
    }

    public static function initSubscriptionPaymentEntry($subscription_id, $payment_id){
        $subscription_payment = new SubscriptionPayment;
        $subscription_payment->subscription_id = $subscription_id;
        $subscription_payment->payment_id = $payment_id;
        $subscription_payment->save();
    }

    public static function initPaymentsUnitsEntry($payment_id, $serialnumber){
        $paymentsUnits = new PaymentsUnits;
        $paymentsUnits->payment_id = $payment_id;
        $paymentsUnits->serialnumber = $serialnumber;
        $paymentsUnits->save();
    }
}