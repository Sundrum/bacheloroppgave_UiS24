<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;

class SubscriptionsController extends Controller
{
    public function subscriptions()
    {
        self::setActivity("Entered subscriptions", "subscriptions");
        return view('pages/subscriptions');
    }
}
