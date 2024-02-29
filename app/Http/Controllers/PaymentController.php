<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\SubscriptionPayment;
use App\Models\Product;
use DateTime;
use DateTimeZone;
use DateInterval;
use Log;
use Auth;

class PaymentController extends Controller
{
    
    //Genreates (user sepcific) payment object
    //public function createPayment($items)
    //public function createPayment()
    public function createPayment(Request $request)
    {
        Log::info("createPayment function");
        //Log::info($items);

        // Retrieve the 'items' query parameter from the request
        $itemsString = $request->query('items');

        // Parse the items string into an array
        $items = json_decode(urldecode($itemsString), true);



        $userData=$this->getUser();
        Log::info("User Data", $userData);
        //Handles insufficient user data
            // if ($this->IsEmpty($userData)){
            //     Log::info("User Data IsEmpty!");
            //     return response()->json($userData);
            // }
        $secretAPIKey = env('NETS_EASY_API_KEY_SECRET');
        //Generates Payload
        $payload = $this->createPayload($userData,$items);
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
        Log::info($result);
        $paymentId = $data->paymentId;
        

        // Access the paymentId value
        echo $result;
    }

    public function createPayload($userData, $items)
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
            //"charge"=>true,
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

        $sumGrossTotalAmount=0;
        $orderItems = [];
        foreach ($items as $item) {
            $orderItems[]=   
            [
                "reference" => $item['reference'],
                "name" => $item['name'],
                "quantity" => $item['quantity'],
                "unit" => $item['unit'],
                "unitPrice" => $item['unitPrice'], // Fill in the unit price with the product price
                "grossTotalAmount" => $item['grossTotalAmount'],
                "netTotalAmount" => $item['netTotalAmount']
            ];
            $sumGrossTotalAmount += $item['grossTotalAmount'];
        }
    
        // Define the order data
        $orderData = [
            "items" => $orderItems,
            "amount" => $sumGrossTotalAmount,
            "currency" => "NOK",
            "reference" => "Subscription Test Order"
        ];
    
        $currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
        $oneYearLater = $currentDateTime->add(new DateInterval('P1Y'));
        $oneYearLaterFormatted = $oneYearLater->format('Y-m-d\TH:i:sP');

        $subscription = [
            "interval" => 1,
            "endDate" => $oneYearLaterFormatted,
        ];

    
        // Combine checkout and order data into the final payload
        $payload = [
            "checkout" => $checkoutData,
            "order" => $orderData,
            "subscription" => $subscription,
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
