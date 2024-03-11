<?php


namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Sensorunit;
use App\Models\PaymentsProducts;
use App\Models\PaymentsUnits;

use Log;
use Auth;
class CheckoutController extends Controller
{
   //Handles checkout page
   public function checkout(Request $request)
    {
    self::setActivity("Entered checkout", "checkout");

    $product_id = $request->input('productId');
    $payment_id = $request->input('paymentId');
    $serial_number = $request->input('serialNumber', null); // Default value if serialNumber is not present
    $language = Auth::user()->user_language;
    $customer_id = Auth::user()->customer_id_ref;
    $checkoutKey = env('NETS_EASY_CHECKOUT_KEY');

    //Initialize DB
    self::initPaymentEntry($payment_id, $customer_id);
    self::initPaymentsProductsEntry($payment_id, $product_id);
    if ($serial_number) {
        self::initPaymentsUnitsEntry($payment_id,$serial_number);
    } 
    
    return view('pages/payment/checkout', ['paymentId' => $payment_id, 'language' => $language, 'checkoutKey' => $checkoutKey]);
   }
   //Handles checkout success
   public function success(Request $request)
   {  
       //Payment DB
       $payment_id = $request->query('payment_id');
       $payment = Payment::find($payment_id);
       $payment->payment_status = 3; //Success
       $payment->save();

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

   //not currently in use:
   public static function initSubscriptionEntry($subscription_id, $customer_id_ref, $serialnumber){
       $subscription = new Subscription;
       $subscription->subscription_id = $subscription_id;
       $subscription->customer_id_ref = $customer_id_ref;
       $subscription->interval = 31556926; // One year
       $subscription->serialnumber = $serialnumber;
       $subscription->subscription_status = 2; //Active
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

}
