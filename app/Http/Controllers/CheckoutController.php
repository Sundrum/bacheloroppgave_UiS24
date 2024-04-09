<?php


namespace App\Http\Controllers;
use App\Mail\PurchaseMade;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Sensorunit;
use App\Models\PaymentsProducts;
use App\Models\PaymentsUnits;
use Illuminate\Support\Facades\Mail;
use PDF;
use Log;
use Auth;
class CheckoutController extends Controller
{
   //Handles checkout page
   public function checkout(Request $request)
    {
    
    self::setActivity("Entered checkout", "checkout");

    $payment_id = $request->input('paymentId');
    $product_id = $request->input('productId', null);
    $serial_number = $request->input('serialNumber', null);
    $language = Auth::user()->user_language;
    $customer_id = Auth::user()->customer_id_ref;
    $checkoutKey = env('NETS_EASY_CHECKOUT_KEY');

    if ($product_id==null){
        $unit = Sensorunit::where('serialnumber', $serial_number)->first();
        $product = Product::find($unit->product_id_ref);
        $product_id = $product->product_id;
    }
    $managebool = false;
    //Initialize DB
    self::initPaymentEntry($payment_id, $customer_id);
    self::initPaymentsProductsEntry($payment_id, $product_id);
    if ($serial_number) {
        self::initPaymentsUnitsEntry($payment_id,$serial_number);
    } 
    
    return view('pages/payment/checkout', ['managebool' => $managebool, 'paymentId' => $payment_id, 'language' => $language, 'checkoutKey' => $checkoutKey]);
   }
   //Handles checkout success
   public function success(Request $request)
   {  
       //Payment DB
       $payment_id = $request->query('payment_id');
       $payment = Payment::find($payment_id);
       if ($payment){
           $payment->payment_status = 3; //Success
           $payment->save();
       }

       //Subscription DB
       $netsResponse = Payment::getNetsResponse($payment_id);
       $is_subscription = isset($netsResponse->payment->subscription);
       if ($is_subscription){
            $subscription_id= $netsResponse->payment->subscription->id;
            $customer_id_ref= $payment->customer_id_ref;
            $paymentUnit = PaymentsUnits::firstDistinctPaymentUnit($payment_id);
            $serialnumber = $paymentUnit->serialnumber ?? null; //set serialnumber if it exists, else null.
            self::initSubscriptionEntry($subscription_id, $customer_id_ref, $serialnumber);
            self::initSubscriptionPaymentEntry($subscription_id,$payment_id);
       }

       //Send mail to 7Sense and inform a sale has been made
        $pdf = self::generatePDF($payment_id);
        $tempFilePath = tempnam(sys_get_temp_dir(), 'invoice_');
        $pdf->save($tempFilePath);
        Mail::to('sigurd.undrum@hotmail.no')->send(new PurchaseMade($tempFilePath));
       unlink($tempFilePath);

       self::setActivity("Checkout success", "success");
       return view('pages/payment/checkoutsuccess', compact('payment_id'));
   }

   public static function initPaymentEntry($payment_id, $customer_id_ref){
       $payment = new Payment;
       $payment->payment_id = $payment_id;
       $payment->payment_status = 0; //Created
       $payment->customer_id_ref = $customer_id_ref;
       $payment->save();
   }

   public static function initPaymentsProductsEntry($payment_id, $product_id){
        $paymentProduct = new PaymentsProducts;
        $paymentProduct->payment_id = $payment_id;
        $paymentProduct->product_id = $product_id;
        $paymentProduct->save();
   }

   public static function initSubscriptionEntry($subscription_id, $customer_id_ref, $serialnumber){
       $subscription = new Subscription;
       $subscription->subscription_id = $subscription_id;
       $subscription->customer_id_ref = $customer_id_ref;
       $subscription->interval = 31556926; // One year
       $subscription->serialnumber = $serialnumber;
       $subscription->subscription_status = 2; //Active
       $subscription->next_payment=now()->addSeconds($subscription->interval)->toDateString();
       $subscription->save();
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

    public function manageBilling(Request $request){
        self::setActivity("Managing billingdetails", "managebilling");

        $subscriptionId = $request->input('subscriptionId');
        $paymentId = $request->input('paymentId');
        $language = Auth::user()->user_language;
        $checkoutKey = env('NETS_EASY_CHECKOUT_KEY');
        $managebool = true;

        return view('pages/payment/checkout', ['managebool' => $managebool, 'subscriptionId' => $subscriptionId, 'paymentId' => $paymentId, 'language' => $language, 'checkoutKey' => $checkoutKey]);
    }

    public static function generatePDF($payment_id){
        $organization_id = "913036999";

        $netsResponse = Payment::getNetsResponse($payment_id);
        $netsResponse->payment->orderDetails->amount = $netsResponse->payment->orderDetails->amount /100;
        $date = date('d-m-y', strtotime($netsResponse->payment->created));
        $netsResponse->payment->created = date('jS \of F, Y', strtotime($netsResponse->payment->created));
        $paymentProduct = PaymentsProducts::where('payment_id', $payment_id)->first();
        $product_id = $paymentProduct->product_id;
        $amount = $paymentProduct->Amount;
        $customer=null;
        $customer_id = Payment::where('payment_id', $payment_id)->first()->customer_id_ref;
        $product = Product::find($product_id);
        $price_ex_vat = $product->product_price / 1.25;
        $vat = $product->product_price - $price_ex_vat;

        $subscription_ex_vat = $product->subscription_price / 1.25;
        $subscription_vat = $product->subscription_price - $subscription_ex_vat;

        if($netsResponse->payment->orderDetails->amount == $product->product_price){
            $ordertype = 0;
        } else if($netsResponse->payment->orderDetails->amount == $product->subscription_price){
            $ordertype = 1;
        } else if($netsResponse->payment->orderDetails->amount == $product->product_price + $product->subscription_price) {
            $ordertype = 2;
        }

        $invoice_number = $organization_id . '-' . $payment_id;
        $pdf = PDF::loadView('pages/payment/invoice', compact('netsResponse', 'invoice_number', 'product', 'amount', 'price_ex_vat', 'vat', 'subscription_ex_vat', 'subscription_vat', 'ordertype','customer'));

        return $pdf;
    }
}
