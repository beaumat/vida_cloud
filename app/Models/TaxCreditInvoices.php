<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxCreditInvoices extends Model
{
    use HasFactory;
    
    protected $table = 'tax_credit_invoices';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'TAX_CREDIT_ID',
        'INVOICE_ID',
        'AMOUNT_WITHHELD',
        'ACCOUNTS_RECEIVABLE_ID'
    ];
}
