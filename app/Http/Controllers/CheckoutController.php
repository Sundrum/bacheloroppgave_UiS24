<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        self::setActivity("Entered checkout", "checkout");
        Log::info("checkout method");
        $paymentId = request()->paymentId;
        return view('pages/checkout', ['paymentId' => $paymentId]);
    }
    public function success()
    {
        self::setActivity("Order success", "success");
        Log::info("success method");
        return view('pages/ordersuccess');
    }
}