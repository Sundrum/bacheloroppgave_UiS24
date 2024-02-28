<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class Payment extends Model
{

    protected $table = 'payments';
    protected $primaryKey = 'payment_id'; // Assuming payment_id is the primary key
    public $incrementing = false;
    public $timestamps = false;

    public function getStatus()
    {
        switch ($this->subscription_status) {
            case 0:
                return 'Created';
            case 1:
                return 'Cancelled';
            case 2:
                return 'Failed';
            case 3:
                return 'Completed';
        }
    }
    public static function getNetsResponse($paymentId)
    {
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
            return $err;
        }
        $return = json_decode($response);
        return $return;
    }
    public static function getNextPayment($customerId)
    {
        $payment = Payment::where('customer_id_ref', $customerId)
            ->where('payment_status', 3)
            ->distinct('serialnumber')
            ->orderBy('created_at', 'desc')
            ->first();
        return $payment;
    }

    public static function getPaymentsForCustomer($customerId) {
        $payments = Payment::select('*')
            ->where('payments.customer_id_ref', '=', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $payments;
    }

    public static function joinNetsResponseAndPayments($customerId)
    {
        $payments = self::getPaymentsForCustomer($customerId); 
        foreach ($payments as $payment) {                                               // Loop through each payment
            $netsResponse = Payment::getNetsResponse($payment->payment_id);             // Get the Nets response for the payment
            if ($netsResponse) {
                $payment->nets = $netsResponse->payment;
            }
        }
        return $payments;
    }
}