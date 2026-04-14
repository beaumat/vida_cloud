<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditMemoInvoices extends Model
{
    use HasFactory;
    protected $table = 'credit_memo_invoices';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CREDIT_MEMO_ID',
        'INVOICE_ID',
        'AMOUNT_APPLIED'
    ];
}
