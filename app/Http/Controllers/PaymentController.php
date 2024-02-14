<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
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
        if ($this->IsEmpty($userData)){
            Log::info("User Data IsEmpty!");
            return response()->json($userData);
        }
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
                'Authorization: test-secret-key-95aff51c8b1d4af6a34907c5d139ddb8'));                                                
        $result = curl_exec($ch);
        echo $result;
    }

    public function createPayload($userData)
    {   
        // Define the checkout data
        $checkoutData = [
            "integrationType" => "EmbeddedCheckout",
            "url" => "https://student.portal.7sense.no/checkout",
            "termsUrl" => "https://student.portal.7sense.no/terms",
            "appearance" => [
                "textOptions" => [
                    "completePaymentButtonText" => "Yabba Dabba Doo"
                ],
                "displayOptions" => [
                    "showMerchantName" => true,
                    "showOrderSummary" => true
                ]
            ],
            "merchantHandlesConsumerData" => true,
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
                "unit" => "pcs",
                "unitPrice" => 89000,
                "grossTotalAmount" => 89000,
                "netTotalAmount" => 80100
            ],
            [
                "reference" => "irrigation-sensor",
                "name" => "Irrigation Sensor",
                "quantity" => 1,
                "unit" => "pcs",
                "unitPrice" => 1500000,
                "grossTotalAmount" => 1500000,
                "netTotalAmount" => 1350000
            ],
            [
                "reference" => "irrigation-subscription",
                "name" => "Irrigation Sensor Subscription",
                "quantity" => 1,
                "unit" => "pcs",
                "unitPrice" => 150000,
                "grossTotalAmount" => 150000,
                "netTotalAmount" => 135000
            ]
        ];
    
        // Define the order data
        $orderData = [
            "items" => $orderItems,
            "amount" => 1739000,
            "currency" => "NOK",
            "reference" => "Demo Order"
        ];
    
        // Combine checkout and order data into the final payload
        $payload = [
            "checkout" => $checkoutData,
            "order" => $orderData
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
                     'customer.customer_visitcity')
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

        return view('pages/updateUserData',['paymentId' => 'test', 'language' => 'test']);
    }
}
