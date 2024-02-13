<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Log;
use Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        self::setActivity("Entered checkout", "checkout");
        Log::info("checkout method");
        // $user = User::select('users.*', 'customers.customer_name')
        //             ->where('users.user_id', Auth::user()->user_id)
        //             ->join('customers', 'customers.customer_id', 'users.customer_id_ref')->first();
        // dd($user);
        $paymentId = request()->paymentId;
        $language = Auth::user()->user_language;
        return view('pages/checkout', ['paymentId' => $paymentId, 'language' => $language]);
    }
    public function success()
    {
        self::setActivity("Checkout success", "success");
        Log::info("success method");
        return view('pages/checkoutsuccess');
    }
}