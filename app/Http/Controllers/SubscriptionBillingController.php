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

class SubscriptionBillingController extends Controller
{
    public function subscriptionbilling()
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
        $customer_id = $user->customer_id_ref;


        // $pay = new Payment();
        $payment_id = '0044000065ca120c5d4f663879361566';

        // $pay->payment_id = $paymentId;
        // $pay->payment_status = 0;
        // $pay->customer_id_ref = 239;
        // $pay->save();

        // Access the paymentId value
        // Log::info($pay);

        $Sub = Subscription::select('*')
            ->where('subscriptions.customer_id_ref', $customer_id)
            ->join('subscriptions_payments', 'subscriptions.subscription_id', '=', 'subscriptions_payments.subscription_id')
            ->join('payments', 'subscriptions_payments.payment_id', '=', 'payments.payment_id')
            ->distinct()
            ->get();
        if ($Sub) {
            // Access the payment attributes
            Log::info($Sub);
            // Access other attributes as needed
        } else {
            // Handle the case where the payment with the given $paymentId is not found
            Log::info("Payment not found for ID: $Sub");
        }

        return view('pages/payment/subscriptionbilling',compact('Sub'));
    }

}