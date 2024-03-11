<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\PaymentsProducts;
use App\Models\PaymentsUnits;
use App\Models\Sensorunit;
use App\Models\Product;
use App\Models\Customer;
use Log;
use Auth;
class DbOperationsController extends Controller
{
    public function DbOperations(Request $request)
    {
        $payments = Payment::select('*')
            ->orderBy('created_at', 'asc')
            ->get();
        $subscriptions = Subscription::select('*')
            ->orderBy('created_at', 'asc')
            ->get();
        $products = Product::whereNotNull('product_price')
            ->where('product_price', '>', 0)
            ->orWhere('subscription_price', '>', 0)
            ->get();

        $subscriptionpayments = SubscriptionPayment::select('*')
            ->get();

        $paymentsunits = PaymentsUnits::select('*')
            ->get();

        return view('pages/payment/dboperations', compact('payments', 'subscriptions', 'products', 'subscriptionpayments', 'paymentsunits'));
    }

    public function changePrice (Request $request)
    {
        $validator = validator($request->all(), [
            'product_price' => ['required', 'numeric', 'min:0'],
            'subscription_price' => ['required', 'numeric', 'min:0'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid input');
        }
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if (!$product)
        {
            return redirect()->back()->with('error', 'Product not found');
        }

        $product->product_price = $request->product_price;
        $product->subscription_price = $request->subscription_price;
        $product->save();
        return redirect()->back()->with('success', 'Price updated successfully');
    }

    public function deletesubpay(Request $request)
    {
        $payment_id = $request->payment_id;
        $subscription_id = $request->subscription_id;
        $subscription_payment = SubscriptionPayment::where('payment_id', $payment_id)
            ->where('subscription_id', $subscription_id)
            ->first();
        $subscription_payment->delete();
        return redirect()->back()->with('success', 'Subscription payment deleted successfully');
    }

    public function deletepayunits(Request $request)
    {
        $payment_id = $request->payment_id;
        $serialnumber = $request->serialnumber;
        $payments_units = PaymentsUnits::where('payment_id', $payment_id)
            ->where('serialnumber', $serialnumber)
            ->first();
        if ($payments_units) {
            $payments_units->delete();
        }else{
            return redirect()->back()->with('error', 'Payment unit not found');
        }
        return redirect()->back()->with('success', 'Payment unit deleted successfully');
    }


    public function delete(Request $request)
    {
        if ($request->payment_id)
        {
            $id = $request->payment_id;
            $payment = Payment::find($id);
            // if (PaymentsProducts::where('payment_id', $id)->exists()) {
            // PaymentsProducts::where('payment_id', $id)->delete();
            // }
            // if (SubscriptionPayment::where('payment_id', $id)->exists()) {
            // SubscriptionPayment::where('payment_id', $id)->delete();
            // }
            // if (PaymentsUnits::where('payment_id', $id)->exists()) {
            // PaymentsUnits::where('payment_id', $id)->delete();
            // }
            if (!$payment)
            {
                return redirect()->back()->with('error', 'Payment not found');
            }
            $payment->delete();
            return redirect()->back()->with('success', 'Payment deleted successfully');
        }
        $id = $request->subscription_id;
        $subscription = Subscription::find($id);
        $subscription->delete();
        return redirect()->back()->with('success', 'Subscription deleted successfully');
    }

    public function update(Request $request){
        if($request->payment_id)
        {
            $id = $request->payment_id;
            $payment = Payment::find($id);
            $payment->payment_status = $request->payment_status;
            $payment->customer_id_ref = $request->customer_id_ref;
            $payment->save();
            return redirect()->back()->with('success', 'Payment updated successfully'); 
        }
        $id = $request->subscription_id;
        $subscription = Subscription::find($id);
        $subscription->customer_id_ref = $request->customer_id_ref;
        $subscription->interval = $request->interval;
        $subscription->serialnumber = $request->serialnumber;
        $subscription->subscription_status = $request->subscription_status;
        $subscription->save();
        return redirect()->back()->with('success', 'Subscription updated successfully');  
    }
    public function newSensorUnit(Request $request)
    {
        $payment_id = $request->payment_id;
        $serialnumber = $request->serialnumber;
        $product_id = $request->product_id_ref;
        $customer_id = $request->customer_id_ref;
        $payment = Payment::find($payment_id);
        if (!$payment)
        {
            return redirect()->back()->with('error', 'Payment not found');
        }
        $sensorunit = Sensorunit::where('serialnumber', $serialnumber)->first();
        if ($sensorunit)
        {
            return redirect()->back()->with('error', 'Sensor unit already exists');
        }
        $product = Product::find($product_id);
        if (!$product)
        {
            return redirect()->back()->with('error', 'Product not found');
        }
        $customer = Customer::find($customer_id);
        if (!$customer)
        {
            return redirect()->back()->with('error', 'Customer not found');
        }

        $sensorunit = new Sensorunit;
        $sensorunit->serialnumber = $serialnumber;
        $sensorunit->customer_id_ref = $customer_id;
        $sensorunit->product_id_ref = $product_id;
        $sensorunit->sensorunit_position = 0;
        $sensorunit->save();

        $payments_units = new PaymentsUnits;
        $payments_units->payment_id = $payment_id;
        $payments_units->serialnumber = $serialnumber;
        $payments_units->save();

        $subscription_payment=SubscriptionPayment::GetWithPaymentID($payment_id);
        $subscription=Subscription::find($subscription_payment->subscription_id);
        $subscription->serialnumber=$serialnumber;
        $subscription->save();
        
        return redirect()->back()->with('success', 'Sensor unit added successfully');
    }
}