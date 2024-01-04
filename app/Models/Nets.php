<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use App\Models\Transaction;
use App\Models\Capturetransaction;
use GuzzleHttp\Exception\RequestException;
use Auth, Log;

class Nets extends Model
{
    use HasFactory;
    //const MERCHANT_ID = env('NETAXEPT_MERCHANT_ID_TEST');

    public function SetCardMetadata($metadata){
        $this->payment_method = isset($metadata['type'])? $metadata['type']:null;
        $this->card_bin = isset($metadata['bin'])? $metadata['bin']:null;
        $this->card_last = isset($metadata['last4'])? $metadata['last4']:null;
        $this->card_brand = isset($metadata['brand'])? $metadata['brand']:null;
        $this->card_country = isset($metadata['country'])? $metadata['country']:null;
        $this->card_secure_type = isset($metadata['3d_secure_type'])? $metadata['3d_secure_type']:null;
        $this->customer_ip = isset($metadata['customer_ip'])? $metadata['customer_ip']:null;
        $this->customer_country = isset($metadata['customer_country'])? $metadata['customer_country']:null;
    }

    public static function registerPayment($req) {
        Log::info("Register Payment Entered by User: ". Auth::user()->user_id);
        $last = Transaction::orderby('id','desc')->first();
        $orderId = $last->id+1 ?? 1;
        $transaction = new Transaction;
        $transaction->orderNumber = "7SA10".$orderId;
        //$transaction->orderNumber = "7SA1002945";
        $transaction->amount = $req->amount*100;
        $transaction->currencyCode = $req->currencyCode;
        $transaction->customerNumber = $req->customerId;
        $transaction->recurringExpiryDate = date('Ymd');
        $transaction->language = $req->language;
        $transaction->orderDescription = $req->description;
        $transaction->save();
        $string = env('NETAXEPT_URL')."Netaxept/Register.aspx?merchantId=".env('NETAXEPT_MERCHANT_ID')."&token=".env('NETAXEPT_TOKEN')."&orderNumber=$transaction->orderNumber&currencyCode=$transaction->currencyCode&amount=$transaction->amount&orderDescription=".urlencode($transaction->orderDescription)."&redirectUrl=https://portal.7sense.no/payment/callback&recurringExpiryDate=$transaction->recurringExpiryDate&customerNumber=$transaction->customerNumber&language=$transaction->language";
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => $string,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            Log::error("Register payment for orderNumber: ". $transaction->orderNumber . " cURL Error #:" . $err);
            return false;
        } else {
            Log::info($response);
            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            if($array && $array['TransactionId']) {
                $transaction->transactionId = $array['TransactionId'];
                $transaction->save();
                return $array['TransactionId'];
            } else {
                Log::error("Something went wrong with orderNumber: ". $transaction->orderNumber . ". Response: " .$response);
                return false;      
            }
        }
    }

    public static function terminalPayment($transactionId) {
        $string = env('NETAXEPT_URL')."Terminal/default.aspx?merchantId=".env('NETAXEPT_MERCHANT_ID')."transactionId=".$transactionId;
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $string,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            Log::error("TerminalPayment for transactionId: ". $transactionId . " cURL Error #:" . $err);
        } else {
            return $response;
        }
    }

    public static function processPayment($transactionId, $operation="AUTH") {
        Log::info("Processing payment for transactionId: ". $transactionId . ", operation = ". $operation);
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => env('NETAXEPT_URL')."Netaxept/Process.aspx?merchantId=".env('NETAXEPT_MERCHANT_ID')."&token=".env('NETAXEPT_TOKEN')."&operation=$operation&transactionId=$transactionId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error("Processing payment for transactionId: ". $transactionId . " cURL Error #:" . $err);
            return false;
        } else {
            $xml = simplexml_load_string($response);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            if($array['TransactionId']) {
                $capture = new Capturetransaction;
                $capture->batchNumber = $array['BatchNumber'];
                $capture->executionTime = $array['ExecutionTime'];
                $capture->merchantId = $array['MerchantId'];
                $capture->operation = $array['Operation'];
                $capture->responseCode = $array['ResponseCode'];
                $capture->transactionId = $array['TransactionId'];
                $capture->created_at = now();
                $capture->save();
                Log::info("Processing payment for transactionId: ". $transactionId . ", operation = ". $operation ." - finished ");
                return $array['TransactionId'];
            } else {
                Log::error("Something went wrong with the payment: ". $transactionId . ", operation = " . $operation . ". Response " .$response);
                return false;      
            }
        }
    }
}
