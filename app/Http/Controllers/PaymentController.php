<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;

class PaymentController extends Controller
{
    public function createPayment()
    {
        Log::info("createPayment function");
        // Your backend logic here
        // $data = ['paymentId' => '123456789']; // Example data
        // return response()->json($data);
        $payload = file_get_contents(storage_path('app/testing/payload.json'));
        Log::info("Payload gotten");
        assert(json_decode($payload) && json_last_error() == JSON_ERROR_NONE);

        $ch = curl_init('https://test.api.dibspayment.eu/v1/payments');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: test-secret-key-95aff51c8b1d4af6a34907c5d139ddb8'));                                                
        $result = curl_exec($ch);
        echo $result;
        // return response()->json($result);
    }
}
