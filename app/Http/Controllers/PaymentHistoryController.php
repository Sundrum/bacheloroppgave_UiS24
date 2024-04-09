<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\SubscriptionPayment;
use App\Models\Customer;
use DateTime;
use DateTimeZone;
use Log;
use Auth;

class PaymentHistoryController extends Controller
{
    public function paymentHistory(){
        $userData=$this->getUser();
        //$paymentData= Payment::joinNetsResponseAndPayments($userData['customer_id']);
        $currentDate = new DateTime('now', new DateTimeZone('Europe/Oslo'));
        $currentDate->setTime(0, 0, 0);
        $currentDateString = $currentDate->format('Y-m-d');
        $paymentData= Payment::joinNetsResponseAndPaymentsForCustomerAndDate($userData['customer_id'],$currentDateString);

        $user = User::find(Auth::user()->user_id);
        $customer = Customer::find($user->customer_id_ref);
        return view('pages/payment/paymenthistory', compact('userData', 'paymentData','customer'));
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
    public function fetchPaymentHistory(Request $request){
        try {
            $date = $request->query('date');
    
            // Validate date format or handle missing date parameter
    
            // Fetch user data
            $userData = $this->getUser();
    
            // Fetch payment data for the specified date and customer
            $paymentData = Payment::joinNetsResponseAndPaymentsForCustomerAndDate($userData['customer_id'], $date);
    
            // Return the payment data in JSON format
            return response()->json($paymentData);
        } catch (\Exception $e) {
            // Handle any exceptions and return an error response
            Log::error('Error fetching payment history: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch payment history'], 500);
        }
    }
    
}
