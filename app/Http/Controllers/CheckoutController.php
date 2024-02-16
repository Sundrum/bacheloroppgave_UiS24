<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Log;
use Auth;
class CheckoutController extends Controller
{
    //Handles checkout page
    public function checkout(Request $request)
    {
        self::setActivity("Entered checkout", "checkout");
        Log::info("checkout method");
        $paymentId = request()->paymentId;
        $language = Auth::user()->user_language;
        $checkoutKey = env('NETS_EASY_CHECKOUT_KEY');
        return view('pages/payment/checkout', ['paymentId' => $paymentId, 'language' => $language, 'checkoutKey' => $checkoutKey]);
    }
    //Handles checkout success
    public function success()
    {
        self::setActivity("Checkout success", "success");
        Log::info("success method");
        return view('pages/payment/checkoutsuccess');
    }
}