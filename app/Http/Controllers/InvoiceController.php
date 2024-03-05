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

        $netsResponse = Payment::getNetsResponse($payment_id);
        $netsResponse->payment->orderDetails->amount = $netsResponse->payment->orderDetails->amount /100;
        $netsResponse->payment->created = date('jS \of F, Y', strtotime($netsResponse->payment->created));

        $paymentProduct = PaymentsProducts::where('payment_id', $payment_id)->first();

        $product_id = $paymentProduct->product_id;
        $amount = $paymentProduct->Amount;

        $product = Product::find($product_id);

        $invoice_number = $organization_id . '-' . $payment_id;
        $PDF_name = $invoice_number . '.pdf';
        $pdf = PDF::loadView('pages/payment/invoice', compact('netsResponse', 'invoice_number', 'product', 'amount'));

        return $pdf->download($PDF_name);
    }
    
}