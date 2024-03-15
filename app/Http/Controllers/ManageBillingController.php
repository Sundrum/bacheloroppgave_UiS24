<?php

namespace App\Http\Controllers;

class ManageBillingController extends Controller
{
    public function managebilling()
    {
        
        return view('pages.payment.managebilling', compact(''));
    }
}