<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Auth;
use Session;
use App\Models\Sensorunit;
use App\Models\User;

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
        return view('pages/subscriptionbilling');
    }
}