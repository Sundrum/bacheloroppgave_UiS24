<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Session;
use App\Models\User;
use App\Models\Payment;
use App\Models\SubscriptionPayment;
use App\Http\Controllers\Controller;


class ManageBillingController extends Controller
{
    public function managebilling()
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

        // $unique_payments = [];
        // $unique_pans = [];
        // $all_payments = Payment::joinNetsResponseAndPayments($customer_id);

        // foreach ($all_payments as $payment)
        // {
        //     $masked_pan1 = $payment->nets->paymentDetails->cardDetails->maskedPan;
        //     dd($masked_pan1);
        //     if (!in_array($masked_pan1, $unique_pans)) {
        //         $unique_pans[] = $masked_pan1;
        //         $unique_payments[] = $payment;
        //     }
        // }
        // dd($unique_payments);
        return view('pages.payment.managebilling', compact('unique_payments', 'no_subscriptions', 'cardtype'));
    }
}