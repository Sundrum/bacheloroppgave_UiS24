<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use Log;
use Auth;
class DbOperationsController extends Controller
{
    public function DbOperations(Request $request)
    {
        $payments = Payment::select('*')
            ->orderBy('created_at', 'asc')
            ->get();
        return view('pages/payment/dboperations', compact('payments'));
    }
    public function delete(Request $request)
    {
        $id = $request->payment_id;
        $payment = Payment::find($id);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully');
    }
    public function update(Request $request){
        $id = $request->payment_id;
        $payment = Payment::find($id);
        $payment->payment_id = $id;
        $payment->payment_status = $request->payment_status;
        $payment->customer_id_ref = $request->customer_id_ref;
        $payment->save();
        return redirect()->back()->with('success', 'Payment updated successfully');
    }
}