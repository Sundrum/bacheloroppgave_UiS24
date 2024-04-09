<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Log;
use DB;

class Payment extends Model
{

    protected $table = 'payments';
    protected $primaryKey = 'payment_id'; // Assuming payment_id is the primary key
    public $incrementing = false;
    public $timestamps = false;

    public function getStatus($status = null)
    {
        $statusCodes = [
            0 => 'Created',
            1 => 'Cancelled',
            2 => 'Failed',
            3 => 'Completed'
        ];
    
        if ($status !== null) {
            return $statusCodes[$status] ?? 'Unknown';
        }
    
        return $statusCodes[$this->subscription_status] ?? 'Unknown';
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
    public static function getPaymentsForCustomerAndDate($customerId,$date) {
        $payments = Payment::select('*')
            ->where('payments.customer_id_ref', '=', $customerId)
            ->whereDate('created_at', $date)
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
    public static function joinNetsResponseAndPaymentsForCustomerAndDate($customerId,$date)
    {
        $payments = self::getPaymentsForCustomerAndDate($customerId, $date); 
        foreach ($payments as $payment) {                                               // Loop through each payment
            $netsResponse = Payment::getNetsResponse($payment->payment_id);             // Get the Nets response for the payment
            if ($netsResponse) {
                $payment->nets = $netsResponse->payment;
            }
        }
        return $payments;
    }
    public static function getByCustomerIdAndSerialNumber($customerId, $serialNumber)
    {
        return self::join('paymentsUnits', 'paymentsUnits.payment_id', '=', 'payments.payment_id')
                   ->where('payments.customer_id_ref', $customerId)
                   ->where('paymentsUnits.serialnumber', $serialNumber)
                   ->get();
                } 
                
    public static function getUnallocatedProductsForCustomer($customerId)
    {
        $products = DB::table('payments')
            ->leftJoin('paymentsUnits', 'payments.payment_id', '=', 'paymentsUnits.payment_id')
            ->join('paymentsProducts', 'paymentsProducts.payment_id', '=', 'payments.payment_id')
            ->join('products', 'paymentsProducts.product_id', '=', 'products.product_id')
            ->where('payments.customer_id_ref', $customerId)    //only for specific customer
            ->whereNotNull('products.subscription_price')       //only subscription products
            ->whereNull('paymentsUnits.serialnumber')           //only unallocated sensors
            ->get();
        
        return $products;
    }
    public static function getProductForSubscriptionWithPaymentID($payment_id)
    {
        $product = Payment::where('payments.payment_id', $payment_id)
            ->join('subscriptions_payments', 'payments.payment_id', '=', 'subscriptions_payments.payment_id')
            ->join('subscriptions', 'subscriptions_payments.subscription_id', '=', 'subscriptions.subscription_id')
            ->join('sensorunits', 'subscriptions.serialnumber', '=', 'sensorunits.serialnumber')
            ->first();
        return $product;
    }
    

}