<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Log;
use Auth;

class retrievePaymentController extends Controller
{
    public function retrievePayment()
    {
        $paymentId= '01b3000065ccbb89c07bfb936313aa83';
        $secretAPIKey = env('NETS_EASY_API_KEY_SECRET');

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://test.api.dibspayment.eu/v1/payments/{$paymentId}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
            "Authorization: $secretAPIKey",
          ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return view('pages.payment.retrievePayment', compact('response'));
        }
    }
};
