<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SubscriptionPayment;
use App\Models\PaymentsProducts;
use App\Models\Customer;
use App\Models\Country;
use App\Models\InvoiceNumber;
use DateTime;
use DateTimeZone;
use PDF;
use Log;
use Auth;

class InvoiceController extends Controller
{
    public function invoice(Request $request)
    {
        $organization_id = "913036999";
        $payment_id = $request->payment_id;
        $ordertype = $request->ordertype; //0 = product, 1 = subscription, 2 = both

        //backendverifisering
        if ($payment_id){
            $pay = Payment::find($payment_id);
            $user = User::find(Auth::user()->user_id);
            if ($user->customer_id_ref!==$pay->customer_id_ref){
                return;
            }
        }

        $netsResponse = Payment::getNetsResponse($payment_id);
        $netsResponse->payment->orderDetails->amount = $netsResponse->payment->orderDetails->amount /100;
        $date = date('d-m-y', strtotime($netsResponse->payment->created));
        $monthyear = date('m-y', strtotime($netsResponse->payment->created));
        $netsResponse->payment->created = date('jS \of F, Y', strtotime($netsResponse->payment->created));
        //payment is related to a product
        $paymentProduct = PaymentsProducts::where('payment_id', $payment_id)->first();
        Log::info($paymentProduct);
        if ($paymentProduct){
            $product_id = $paymentProduct->product_id;
            $amount = $paymentProduct->Amount;
            $customer=null;
        }
        //payment is related to subscription
        else{
            $paymentProduct = Payment::getProductForSubscriptionWithPaymentID($payment_id);
            $product_id = $paymentProduct->product_id_ref;
            $amount = 1; 
            $user = User::find(Auth::user()->user_id);
            $customer = Customer::find($user->customer_id_ref);
        }
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

        $country = $netsResponse->payment->consumer->billingAddress->country ??
            $customer->customer_invoicecountry ?? 
            $customer->customer_visitcountry ?? 
            $customer->customer_delivercountry ?? 
            null;      

        if (is_int($country)) {
            $country = Country::find($country)->name;
        }

        $invoice_number = InvoiceNumber::getInvoiceNumber($payment_id);
        $PDF_name = $organization_id . '-' . $customer_id . '-' . $date . '.pdf';
        $pdf = PDF::loadView('pages/payment/invoice', compact('netsResponse', 'invoice_number', 'product', 'amount', 'price_ex_vat', 'vat', 'subscription_ex_vat', 'subscription_vat', 'ordertype','customer','country'));

        return $pdf->download($PDF_name);
    }
    public function downloadinvoice(Request $request){
        $organization_id = "913036999";
        $payment_id = $request->query('paymentId');
        $ordertype = $request->ordertype; //0 = product, 1 = subscription, 2 = both

        //backendverifisering
        if ($payment_id){
            $pay = Payment::find($payment_id);
            $user = User::find(Auth::user()->user_id);
            if ($user->customer_id_ref!==$pay->customer_id_ref){
                return;
            }
        }

        $netsResponse = Payment::getNetsResponse($payment_id);
        $netsResponse->payment->orderDetails->amount = $netsResponse->payment->orderDetails->amount /100;
        $date = date('d-m-y', strtotime($netsResponse->payment->created));
        $netsResponse->payment->created = date('jS \of F, Y', strtotime($netsResponse->payment->created));
        //payment is related to a product
        $paymentProduct = PaymentsProducts::where('payment_id', $payment_id)->first();
        Log::info($paymentProduct);
        if ($paymentProduct){
            $product_id = $paymentProduct->product_id;
            $amount = $paymentProduct->Amount;
            $customer=null;
        }
        //payment is related to subscription
        else{
            $paymentProduct = Payment::getProductForSubscriptionWithPaymentID($payment_id);
            $product_id = $paymentProduct->product_id_ref;
            $amount = 1; 
            $user = User::find(Auth::user()->user_id);
            $customer = Customer::find($user->customer_id_ref);
        }
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

        $country = $netsResponse->payment->consumer->billingAddress->country ??
            $customer->customer_invoicecountry ?? 
            $customer->customer_visitcountry ?? 
            $customer->customer_delivercountry ?? 
            null;      

        if (is_int($country)) {
            $country = Country::find($country)->name;
        }

        $invoice_number = InvoiceNumber::getInvoiceNumber($payment_id);
        return view('pages/payment/downloadinvoice', compact('netsResponse', 'invoice_number', 'product', 'amount', 'price_ex_vat', 'vat', 'subscription_ex_vat', 'subscription_vat', 'ordertype','customer', 'payment_id','country'));
    }
}