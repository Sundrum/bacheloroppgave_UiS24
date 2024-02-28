<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Auth;
use Session;
use App\Models\Product;
use App\Models\Payment;

class ShopController extends Controller
{
    public function shop()
    {
        $products = Product::whereNotNull('product_price')
                    ->where('product_price', '>', 0)
                    ->orWhere('subscription_price', '>', 0)
                    ->get();
        return view('pages/payment/shop', ['products' => $products]);
    }

    public function createPayloadObject($product) 
    {
        $currentDateTime = new DateTime('now', new DateTimeZone('UTC'));
        $oneYearLater = $currentDateTime->add(new DateInterval('P1Y'));
        $oneYearLaterFormatted = $oneYearLater->format('Y-m-d\TH:i:sP');    
        $vat = 0.25;
        $data = [
            'checkout' => [
                "integrationType" => "EmbeddedCheckout",
                "url" => "https://student.portal.7sense.no/checkout",
                "termsUrl" => "https://student.portal.7sense.no/terms",
                "consumerType"=> [
                    "supportedTypes"=> ["B2B"], //"B2B","B2C" 
                    "default"=> "B2B"
                ],
                "appearance" => [
                    "textOptions" => [
                        "completePaymentButtonText" => "Purchase now"
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
            ],
            'order' => [
                'items' => [
                    [
                        'reference' => $product->product_name,
                        'name' => $product->product_name,
                        'quantity' => 1,
                        'unit' => 'pcs',
                        'unitPrice' => $product->product_price / (1 + $vat),
                        'grossTotalAmount' => $product->product_price,
                        'netTotalAmount' => $product->product_price / (1 + $vat),
                    ],
                    [
                        'reference' => $product->product_name . ' yearly fee',
                        'name' => $product->product_name . ' subscription',
                        'quantity' => 1,
                        'unit' => 'year',
                        'unitPrice' => $product->subscription_price / (1 + $vat),
                        'grossTotalAmount' => $product->subscription_price,
                        'netTotalAmount' => $product->subscription_price / (1 + $vat),
                    ],
                ],
                'amount' => $product->product_price + $product->subscription_price,
                'currency' => 'NOK',
                'reference' => $product->product_name . " + subscription.",
            ],
            'subscription' => [ 
                "interval" => 365,
                "endDate" => $oneYearLaterFormatted,
            ]
        ];
        
        // Convert the PHP array to a JSON string
        $jsonString = json_encode($data, JSON_PRETTY_PRINT);
        
        // Output the JSON string
        return $jsonString;
    }
}