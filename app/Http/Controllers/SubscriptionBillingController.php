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

        $payment_id = '0044000065ca120c5d4f663879361566';

        $Sub = SubscriptionPayment::Join($customer_id);

        if ($Sub) {
            // Access the payment attributes
            Log::info($Sub);
            // Access other attributes as needed
        } else {
            // Handle the case where the payment with the given $paymentId is not found
            Log::info("Payment not found for ID: $Sub");
        }

        $lastPaymentDate = $Sub[0]->updated_at;
        $interval = $Sub[0]->interval;

        $nextPaymentDate = Subscription::getPaymentDate($lastPaymentDate, $interval);
        $lastPaymentId='01b3000065ccbb89c07bfb936313aa83';
        $lastPaymentObject = Payment::getNetsResponse($lastPaymentId);
        return view('pages/payment/subscriptionbilling',compact('Sub', 'nextPaymentDate', 'lastPaymentObject'));
    }
}