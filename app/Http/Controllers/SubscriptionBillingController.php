<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Auth;
use Session;
use App\Models\Sensorunit;
use App\Models\Customer;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\SubscriptionPayment;

class SubscriptionBillingController extends Controller
{

    // KONTROLLEREN IKKE LENGER I BRUK
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
        $Sub = SubscriptionPayment::Join($customer_id);

        $paid_subscription = Customer::where("customer_id", $customer_id)->first()->paid_subscription;

        if ($Sub->isEmpty()) {
            // Access other attributes as needed
            Log::info("Payment not found for ID: $Sub");
            return view('pages/payment/subscriptionbilling');
        } else {
            // Handle the case where the payment with the given $paymentId is not found
            $lastPaymentDate = $Sub[0]->updated_at;
            $interval = $Sub[0]->interval;
            $nextPaymentDate = Subscription::getPaymentDate($lastPaymentDate, $interval);
            $lastPaymentId = Payment::getPaymentsForCustomer($customer_id)->first()->payment_id;
            $lastPaymentObject = Payment::getNetsResponse($lastPaymentId);
        }
        return view('pages/payment/subscriptionbilling',compact('Sub', 'nextPaymentDate', 'lastPaymentObject', 'paid_subscription'));
    }
}