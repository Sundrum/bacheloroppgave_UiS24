<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
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
        $checkoutKey = env('NETS_EASY_CHECKOUT_KEY');
        return view('pages/payment/checkout', ['paymentId' => $payment_id, 'language' => $language, 'checkoutKey' => $checkoutKey]);
    }
    //Handles checkout success
    public function success()
    {
        $payment_id = request()->payment_id;
        $nets_response = Payment::getNetsResponse($payment_id);

        $user_id = Auth::user()->user_id;
        $user = User::find($user_id);
        $payment = new Payment;
        $payment->payment_id = $payment_id;
        $payment->payment_status = 3; //Completed
        $payment->customer_id_ref = $user->customer_id_ref;
        $payment->save();

        if ($nets_response->payment->subscription)
        {
            $subscription = new Subscription;
            $subscription->subscription_id = $nets_response->payment->subscription->id;
            $subscription->customer_id_ref = $user->customer_id_ref;
            $subscription->interval = 31556926; // One year
            //$subscription->serialnumber = $nets_response->payment->orderDetails->serialNumber;
            $subscription->subscription_status = 2; //Active
            $subscription->save();

            $subscription_payment = new SubscriptionPayment;
            $subscription_payment->subscription_id = $nets_response->payment->subscription->id;
            $subscription_payment->payment_id = $payment_id;
            $subscription_payment->save();
        }
        self::setActivity("Checkout success", "success");
        Log::info("success method");
        return view('pages/payment/checkoutsuccess');
    }
}