<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\SubscriptionPayment;
use PDF;
use Log;
use Auth;

class InvoiceController extends Controller
{
    public function invoice(Request $request)
    {
        $payment_id = $request->payment_id;
        $netsResponse = Payment::getNetsResponse($payment_id);
        $netsResponse->payment->orderDetails->amount = $netsResponse->payment->orderDetails->amount /100;
        $netsResponse->payment->created = date('jS \of F, Y', strtotime($netsResponse->payment->created));
        $pdf = PDF::loadView('pages/payment/invoice', compact('netsResponse'));
        $PDF_name = $payment_id . '.pdf';
        return $pdf->download($PDF_name);
    }
    
}