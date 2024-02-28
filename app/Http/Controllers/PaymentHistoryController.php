<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\SubscriptionPayment;
use Log;
use Auth;

class PaymentHistoryController extends Controller
{
    public function paymentHistory(){
        $userData=$this->getUser();
        $paymentData= Payment::joinNetsResponseAndPayments($userData['customer_id']);
        return view('pages/payment/paymenthistory', compact('userData', 'paymentData'));
    }

    public function getUser()
    {
        //$user = User::select('users.*', 'customer.customer_name')
        $user = User::select('customer.customer_email',
                     'customer.customer_visitpostcode',
                     'customer.customer_phone',
                     'users.user_name',
                     'users.user_surname',
                     'customer.customer_visitaddr1',
                     'customer.customer_name',
                     'customer.customer_visitcountry',
                     'customer.customer_visitcity',
                     'customer.customer_id')
            ->where('users.user_id', Auth::user()->user_id)
            ->join('customer', 'customer.customer_id', 'users.customer_id_ref')
            ->first();
        return $user->toArray();
    }
}
