<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use Log;
use Auth;

class PaymentController extends Controller
{
    //Genreates (user sepcific) payment object
    public function createPayment()
    {
        Log::info("createPayment function");

        $userData=$this->getUser();
        Log::info("User Data", $userData);
        //Handles insufficient user data
            // if ($this->IsEmpty($userData)){
            //     Log::info("User Data IsEmpty!");
            //     return response()->json($userData);
            // }
        $secretAPIKey = env('NETS_EASY_API_KEY_SECRET');
        //Generates Payload
        $payload = $this->createPayload($userData);
        Log::info("Payload gotten");
        assert(json_decode($payload) && json_last_error() == JSON_ERROR_NONE);
        //Generates payment object
        $ch = curl_init('https://test.api.dibspayment.eu/v1/payments');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                         
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization:' . $secretAPIKey));                                                
        $result = curl_exec($ch);
        $data = json_decode($result);
        $paymentId = $data->paymentId;

        // Create a new subscription record in db
        $sub = new Subscription();
        $sub->paymentId = $paymentId;

        // Access the paymentId value
        Log::info($sub);
        echo $result;
    }

    public function createPayload($userData)
    {   
        // Define the checkout data
        $checkoutData = [
            "integrationType" => "EmbeddedCheckout",
            "url" => "https://student.portal.7sense.no/checkout",
            "termsUrl" => "https://student.portal.7sense.no/terms",
            "consumerType"=> [
                "supportedTypes"=> ["B2B"], //"B2B","B2C" 
                "default"=> "B2B"
            ],
            "appearance" => [
                "textOptions" => [
                    "completePaymentButtonText" => "Yabba Dabba Doo"
                ],
                "displayOptions" => [
                    "showMerchantName" => true,
                    "showOrderSummary" => true
                ]
            ],
            "merchantHandlesConsumerData" => false,
            "company" => [
                "name" => "string",
                "contact" => [
                    "firstName" => "string",
                    "lastName" => "string"
                ]
            ]
        ];
    
        // Define the order items
        $orderItems = [
            [
                "reference" => "portal-access-subscription",
                "name" => "Portal Access Subscription",
                "quantity" => 1,
                "unit" => "day",
                "unitPrice" => 89000,
                "grossTotalAmount" => 89000,
                "netTotalAmount" => 80100
            ]
            // [
            //     "reference" => "irrigation-sensor",
            //     "name" => "Irrigation Sensor",
            //     "quantity" => 1,
            //     "unit" => "pcs",
            //     "unitPrice" => 1500000,
            //     "grossTotalAmount" => 1500000,
            //     "netTotalAmount" => 1350000
            // ],
            // [
            //     "reference" => "irrigation-subscription",
            //     "name" => "Irrigation Sensor Subscription",
            //     "quantity" => 1,
            //     "unit" => "pcs",
            //     "unitPrice" => 150000,
            //     "grossTotalAmount" => 150000,
            //     "netTotalAmount" => 135000
            // ]
        ];
    
        // Define the order data
        $orderData = [
            "items" => $orderItems,
            "amount" => 89000,
            "currency" => "NOK",
            "reference" => "Subscription Test Order"
        ];

        $subscription = [
            "interval" => 1,
            "endDate" => "2024-02-18T00:00:00+00:00",
        ];
    
        // Combine checkout and order data into the final payload
        $payload = [
            "checkout" => $checkoutData,
            "order" => $orderData,
            "subscription" => $subscription
        ];
    
        // Convert the payload array to JSON and return it
        return json_encode($payload, JSON_PRETTY_PRINT);
    }
    //Returns associative array of user info
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

    //Checks for empty fields
    function IsEmpty($data)
    {
        // Iterate through the fields
        foreach ($data as $key => $value) {
            // If the value is an array, recursively check its fields
            if (is_array($value)) {
                // Recursively check the sub-fields
                if (IsEmpty($value)) {
                    return true;
                }
            } else {
                // If the value is empty, return true
                if (empty($value)) {
                    return true;
                }
            }
        }
        // If no empty fields are found, return false
        return false;
    }
    public function updateUserData(){
        $userData=$this->getUser();
        return view('pages/payment/updateUserData',compact('userData'));
    }
    public function paymentHistory(){
        $userData=$this->getUser();
        return view('pages/payment/paymenthistory', compact('userData'));
    }
}
