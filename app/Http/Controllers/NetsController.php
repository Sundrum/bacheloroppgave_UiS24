<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Nets;
use App\Models\Transaction;
use App\Models\Customer;
use Log;

class NetsController extends Controller
{
    public function index() {
        //$response['payments'] = Nets::all();
        $response['transactions'] = Transaction::all();
        return $response;
    }
    public function createSubscription() {
        $customer = Customer::find(request()->customerId);
        return view('pages.payment', compact('customer'));
    }

    public function capturePaymentInformaiton(Request $req) {
        $data = Nets::registerPayment($req);
        if($data) {
            $transactionID = $data;
            $url = env('NETAXEPT_URL')."Terminal/default.aspx?merchantId=".env('NETAXEPT_MERCHANT_ID')."&transactionId=".$transactionID;
            $result['paymentLink'] = $url;
            $status = 'success';
            return response()->json([
                'status' => $status,
                'result' => $result, 
                'message'=> 'Redirecting to paymentlink to continue payment'
            ]);
        } else {
            $status = 'error';

            return response()->json([
                'status' => $status,
                'message' => 'Something went wrong'
            ]);        
        }
    }

    public function callback() {
        Log::info("Callback called from Nets for transactionId: " .request()->transactionId);
        Log::info(request());
        $transaction = Transaction::where('transactionId', request()->transactionId)->first();
        $transaction->responseCode = request()->responseCode;
        $transaction->save();
        $response = array();
        if($transaction->responseCode && $transaction->responseCode === 'OK') {
            $response['sale'] = Nets::processPayment(request()->transactionId, "SALE");
            //$response['auth'] = Nets::processPayment(request()->transactionId);
            //$response['capture'] = Nets::processPayment(request()->transactionId, "CAPTURE");
        }
        return view('pages.payment.callback', compact('response'));
    }

    
}
