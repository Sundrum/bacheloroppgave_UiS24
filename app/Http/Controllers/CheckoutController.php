<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Auth;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        self::setActivity("Entered checkout", "checkout");
        Log::info("checkout method");
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