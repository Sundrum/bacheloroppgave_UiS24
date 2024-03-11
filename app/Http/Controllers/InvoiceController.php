<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SubscriptionPayment;
use App\Models\PaymentsProducts;
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

        $netsResponse = Payment::getNetsResponse($payment_id);
        $netsResponse->payment->orderDetails->amount = $netsResponse->payment->orderDetails->amount /100;
        $date = date('d-m-y', strtotime($netsResponse->payment->created));
        $netsResponse->payment->created = date('jS \of F, Y', strtotime($netsResponse->payment->created));

        $paymentProduct = PaymentsProducts::where('payment_id', $payment_id)->first();

        $customer_id = Payment::where('payment_id', $payment_id)->first()->customer_id_ref;
        $product_id = $paymentProduct->product_id;
        $amount = $paymentProduct->Amount;

        $product = Product::find($product_id);
        $price_ex_vat = $product->product_price / 1.25;
        $vat = $product->product_price - $price_ex_vat;

        $subscription_ex_vat = $product->subscription_price / 1.25;
        $subscription_vat = $product->subscription_price - $subscription_ex_vat;

        $is_subscription = isset($netsResponse->payment->subscription);
        $invoice_number = $organization_id . '-' . $payment_id;
        $PDF_name = $organization_id . '-' . $customer_id . '-' . $date . '.pdf';
        $pdf = PDF::loadView('pages/payment/invoice', compact('netsResponse', 'invoice_number', 'product', 'amount', 'price_ex_vat', 'vat', 'is_subscription', 'subscription_ex_vat', 'subscription_vat', 'ordertype'));

        return $pdf->download($PDF_name);
    }
    
}