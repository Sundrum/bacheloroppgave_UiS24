<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceNumber extends Model
{
    use HasFactory;
    protected $table = 'invoice_numbers';
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    public $timestamps = false;

    public static function getInvoiceNumber($payment_id)
    {
        $invoice_number = InvoiceNumber::find($payment_id);
        if (!$invoice_number) {
            return null;
        }
        return $invoice_number->invoice_number;
    }

    public static function getNextInvoiceNumber()
    {
        $lastInvoiceNumber = InvoiceNumber::orderBy('invoice_number', 'desc')->first();
        if (!$lastInvoiceNumber) {
            return 1;
        }
        return $lastInvoiceNumber->invoice_number + 1;
    }
}


