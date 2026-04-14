<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInvoices extends Model
{
    use HasFactory;
    protected $table = 'payment_invoices';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PAYMENT_ID',
        'INVOICE_ID',
        'DISCOUNT',
        'AMOUNT_APPLIED',
        'DISCOUNT_ACCOUNT_ID',
        'ACCOUNTS_RECEIVABLE_ID'
    ];
}
