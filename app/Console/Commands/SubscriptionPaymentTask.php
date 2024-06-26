<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionTaskStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Sensorunit;
use App\Models\PaymentsProducts;
use App\Models\PaymentsUnits;
use App\Models\InvoiceNumber;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use DateInterval;
use Log;
use Auth;


class SubscriptionPaymentTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptionPayment:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform subscription payment task';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('CUSTOM TASK INITIATED:');
        //Log::info('CUSTOM TASK INITIATED:');

        // Retrieve the current date
        $currentDate = new DateTime('now', new DateTimeZone('Europe/Oslo'));
        $currentDate->setTime(0, 0, 0);
        $currentDateString = $currentDate->format('Y-m-d');
        $err = [];
        try {
            $charged = $this->chargeSubscriptions($currentDateString);
        } catch (\Exception $e) {
            //Log::error("Failed to charge subscriptions" . $e->getMessage());
            $charged = [[],[]];
            $err[] = "Failed to charge subscriptions " . $e->getMessage();
        }
        try {
            $cancelled = $this->cancelSubscriptions($currentDateString);
        } catch (\Exception $e) {
            //Log::error("Failed to cancel subscriptions" . $e->getMessage());
            $cancelled = [[],[]];
            $err[] = "Failed to cancel subscriptions " . $e->getMessage();
        }
        try {
            $outdated = $this->outdatedSubscriptions($currentDateString);
        } catch (\Exception $e) {
            //Log::error("Failed to outdate subscriptions" . $e->getMessage());
            $outdated = [[],[]];
            $err[] = "Failed to outdate subscriptions " . $e->getMessage();
        }

        $this->info('CUSTOM TASK COMPLETED:');
        $admin_mail = env('MAIL_ADMIN_ADDRESS');
        // Send email to admin with task status and errors. Remove comment to activate. 1 email per 24 hours @ 00:00 UTC.
        //Mail::to($admin_mail)->send(new SubscriptionTaskStatus($charged, $cancelled, $outdated, $err, $currentDateString));
        //Log::info('CUSTOM TASK COMPLETED:');
    }

    public function outdatedSubscriptions($currentDateString)
    {   
        // Retrieve subscriptions with status !=0 and next_payment date less than today's date
        $subscriptions = Subscription::outdatedSubscriptionData($currentDateString);
        //$subscriptions = Subscription::outdatedSubscriptionData('2024-03-18');
        $numSubscriptions = count($subscriptions);
        $err = [];
        if ($numSubscriptions === 0) {
            //Log::info("No subscriptions found for outdated processing. DATE->". $currentDateString);
            $this->info('CUSTOM TASK outdated COMPLETED.');
            $err[] = 'No outdated subscriptions found.';
            //Log::info('CUSTOM TASK outdated COMPLETED.');
            return array($subscriptions, $err);
        }
        foreach ($subscriptions as $subscription) {
            try {
                $subscription->subscription_status=0;
                $subscription->save();
            } catch (\Exception $e) {
                //Log::error("Failed to update subscription entry: " . $e->getMessage());
                $err[] = "Failed to update subscription entry: " . $e->getMessage();
            }
        }
        $this->info('CUSTOM TASK outdated COMPLETED.');
        //Log::info('CUSTOM TASK outdated COMPLETED.');
        return array($subscriptions, $err);
    }

    public function cancelSubscriptions($currentDateString)
    {   
        $err = [];
        // Retrieve subscriptions with status 1 and next_payment date equal to today's date
        $subscriptions = Subscription::cancelSubscriptionData($currentDateString);
        //$subscriptions = Subscription::cancelSubscriptionData('2024-03-18');
        $numSubscriptions = count($subscriptions);
        if ($numSubscriptions === 0) {
            //Log::info("No subscriptions found for cancel processing. DATE->". $currentDateString);
            $this->info('CUSTOM TASK cancel COMPLETED.');
            $err[] = 'No subscriptions found for cancel processing.';
            //Log::info('CUSTOM TASK cancel COMPLETED.');
            return array($subscriptions, $err);
        }
        foreach ($subscriptions as $subscription) {
            try {
                $subscription->subscription_status=0;
                $subscription->save();
            } catch (\Exception $e) {
                $err[] = "Failed to update subscription entry: " . $e->getMessage();
                //Log::error("Failed to update subscription entry: " . $e->getMessage());
            }
        }
        $this->info('CUSTOM TASK cancel COMPLETED.');
        //Log::info('CUSTOM TASK cancel COMPLETED.');
        return array($subscriptions, $err);
    }

    public function chargeSubscriptions($currentDateString)
    {   
        // Retrieve subscriptions with status 2 and next_payment date equal to today's date
        $subscriptions = Subscription::chargeSubscriptionData($currentDateString);
        //$subscriptions = Subscription::chargeSubscriptionData('2024-03-18');
        $numSubscriptions = count($subscriptions);
        $err = [];
        if ($numSubscriptions === 0) {
            //Log::info("No subscriptions found for charge processing. DATE->". $currentDateString);
            $err[] = 'No subscriptions found for charge processing.';
            $this->info('CUSTOM TASK charge COMPLETED.');
            //Log::info('CUSTOM TASK charge COMPLETED.');
            return array($subscriptions, $err);
        }
        $payload = $this->createPayload($subscriptions, $currentDateString);
        $chargeSuccessJSON = $this->charge($payload);

        if ($chargeSuccessJSON) {
            $bulkId = json_decode($chargeSuccessJSON, true)['bulkId'];
            $status = "Processing";
            $maxRetries = 60;   // Retry for 1 hour
            $retryCount = 0;    // Initialize the retry counter

            while ($status === "Processing") {
                $retryCount++;
                if ($retryCount >= $maxRetries) {// Maximum retries reached, generate an error and exit the loop
                    //Log::error("ERROR: subscriptionPayment:task reached maximum retry attempts. Retry {$retryCount}/{$maxRetries}. ->", [$bulkId]);
                    $err[] = 'Maximum retries reached.';
                    break;
                }

                $chargesJSON = $this->retreiveChargeDetails($bulkId, $numSubscriptions);

                if ($chargesJSON) {
                    $charges = json_decode($chargesJSON, true);
                    $status = $charges['status'];

                    if ($status === "Done") {
                        foreach ($charges['page'] as $charge) {
                            $errors = $this->readCharge($charge, $currentDateString);
                            if (count($errors) > 0) {
                                foreach($errors as $error){
                                    $err[] = $error;
                                }
                                foreach($subscriptions as $key => $subscription){
                                    if ($subscription['subscription_id'] === $charge['subscriptionId']){
                                        unset($subscriptions[$key]);
                                    }
                                }
                            }
                        }
                    }else{                
                    sleep(60);// Sleep for 1 minute before retrying
                    }
                } else {
                    //Log::error("ERROR: subscriptionPayment:task unable to retrieve charge. Retry {$retryCount}/{$maxRetries}. ->", [$bulkId]);
                    $err[] = 'Unable to retrieve charge.';
                }
            }
        } else {
            $err[] = 'Unable to charge subscriptions.';
            //Log::error("ERROR: subscriptionPayment:task unable to charge subscriptions. DATE->". $currentDateString);
        }

        $this->info('CUSTOM TASK charge COMPLETED.');
        //Log::info('CUSTOM TASK charge COMPLETED.');
        return array($subscriptions, $err);
    }
    public function createPayload($subscriptions,$date)
    {   
        // Initialize payload array
        $payload = [
            "externalBulkChargeId" => $date,
            //"externalBulkChargeId" => "18-04-2024-A0064",
            "subscriptions" => []
        ];

        $VAT=0.25;
        // Iterate over each subscription item
        foreach ($subscriptions as $subscription) {
            // Create an array for each subscription item
            $subscriptionArrayItem = [
                'subscriptionId' => $subscription['subscription_id'],
                'order' => [
                    'items' => [
                        [
                            'reference' => $subscription['productnumber'],
                            'name' => $subscription['product_name'] . " Subscription",
                            'quantity' => 1,
                            'unit' => 'year',
                            'unitPrice' => ($subscription['subscription_price'] / (1 + $VAT)) * 100,
                            'taxRate' => $VAT * 10000,
                            'taxAmount' => (($subscription['subscription_price'] / (1 + $VAT)) * 100) * $VAT,
                            'netTotalAmount' => ($subscription['subscription_price'] / (1 + $VAT)) * 100,
                            'grossTotalAmount' => $subscription['subscription_price'] * 100,
                        ]
                    ],
                    'amount' => $subscription['subscription_price'] * 100,
                    'currency' => 'NOK',
                    'reference' => $subscription['product_name'] . " subscription"
                ]
            ];

            // Add the subscription item to the subscriptions array in payload
            $payload['subscriptions'][] = $subscriptionArrayItem;
        }

        // Encode payload array to JSON and return
        return json_encode($payload, JSON_PRETTY_PRINT);
    }

    public function charge($payload){

        $secretKey = env('NETS_EASY_API_KEY_SECRET');

        $ch = curl_init('https://test.api.dibspayment.eu/v1/subscriptions/charges');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: ' . $secretKey));                                                
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo("HTTP code: " . $http_code . PHP_EOL);
        $json_pretty = json_encode(json_decode($result), JSON_PRETTY_PRINT);
        echo $json_pretty . PHP_EOL;

        if ($http_code==202 || true){
            return $json_pretty;
        }
        else{
            return false;
        }
    }

    public function retreiveChargeDetails($bulkId,$numSubscriptions){
        $secretKey = env('NETS_EASY_API_KEY_SECRET');

        $skip = 0;
        $take = $numSubscriptions;

        $ch = curl_init('https://test.api.dibspayment.eu/v1/subscriptions/charges/' . $bulkId . '?skip=' . $skip . '&take=' . $take);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: ' . $secretKey
        ));
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        echo ("HTTP code: " . $http_code . PHP_EOL);
        $json_pretty = json_encode(json_decode($result), JSON_PRETTY_PRINT);
        echo $json_pretty . PHP_EOL;
        if ($http_code==200){
            return $json_pretty;
        }
        else{
            return false;
        }
    }

    public function readCharge($charge, $currentDate){
        $subscription=Subscription::find($charge['subscriptionId']);
        $err = [];
        if ($charge['status']==='Succeeded'){
            $subscription->subscription_status=2;//Active

            $date = DateTime::createFromFormat('Y-m-d', $currentDate);
            list($hours, $minutes, $seconds) = explode(':', $subscription->interval);
            $intervalString = 'PT' . $hours . 'H' . $minutes . 'M' . $seconds . 'S';
            $interval = new DateInterval($intervalString);
            $newDate = $date->add($interval);

            $subscription->next_payment=$newDate->format('Y-m-d');
            $payment_status=3; //Completed

        }else{
            $subscription->subscription_status=0; //Inactive
            $payment_status=2; //Failed
            $err[] = "Failed to charge subscription with ID: " . $charge['subscriptionId'];
        }
        try {
            $subscription->save();
        } catch (\Exception $e) {
            //Log::error("Failed to update subscription entry: " . $e->getMessage());
            $err[] = "Failed to update subscription entry: " . $e->getMessage();
        }
        try {
            $this->initPaymentEntry($charge['paymentId'], $subscription->customer_id_ref, $payment_status);
        } catch (\Exception $e) {
            //Log::error("Failed to initialize payment entry: " . $e->getMessage());
            $err[] = 'Failed to initialize payment entry: '. $e->getMessage();
        }
        try {
            $this->initSubscriptionPaymentEntry($charge['subscriptionId'], $charge['paymentId']);
        } catch (\Exception $e) {
            //Log::error("Failed to initialize subscription-payment entry: " . $e->getMessage());
            $err[] = 'Failed to initialize subscription-payment entry: '. $e->getMessage();
        }
        try {
            $this->initInvoiceNumberEntry($charge['paymentId']);
        } catch (\Exception $e) {
            //Log::error("Failed to initialize payment units entry: " . $e->getMessage());
            $err[] = 'Failed to initialize InvoiceNumberEntry entry: '. $e->getMessage();
        }
        // try {
        //     $this->initPaymentsUnitsEntry($charge['paymentId'], $subscription->serialnumber);
        // } catch (\Exception $e) {
        //     //Log::error("Failed to initialize payment units entry: " . $e->getMessage());
        //     $err[] = 'Failed to initialize payment units entry: '. $e->getMessage();
        // }
        return $err;
    }

    public static function initPaymentEntry($payment_id, $customer_id_ref,$payment_status){
        $payment = new Payment;
        $payment->payment_id = $payment_id;
        $payment->payment_status = $payment_status; 
        $payment->customer_id_ref = $customer_id_ref;
        $payment->save();
    }
    public static function initSubscriptionPaymentEntry($subscription_id, $payment_id){
        $subscription_payment = new SubscriptionPayment;
        $subscription_payment->subscription_id = $subscription_id;
        $subscription_payment->payment_id = $payment_id;
        $subscription_payment->save();
    }
    public static function initPaymentsUnitsEntry($payment_id, $serialnumber){
       $paymentsUnits = new PaymentsUnits;
       $paymentsUnits->payment_id = $payment_id;
       $paymentsUnits->serialnumber = $serialnumber;
       $paymentsUnits->save();
   }
   public static function initInvoiceNumberEntry($payment_id){
    $invoiceNumber = new InvoiceNumber;
    $invoice_number = InvoiceNumber::getNextInvoiceNumber();
    $invoiceNumber->payment_id = $payment_id;
    $invoiceNumber->invoice_number = $invoice_number;
    $invoiceNumber->save();
}
}
