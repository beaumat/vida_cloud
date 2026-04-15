<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillCreditExpenses extends Model
{
    use HasFactory;
    protected $table = 'bill_credit_expenses';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'BILL_CREDIT_ID',
        'LINE_NO',
        'ACCOUNT_ID',
        'AMOUNT',
        'TAXABLE',
        'TAXABLE_AMOUNT',
        'TAX_AMOUNT',
        'PARTICULARS',
        'CLASS_ID'
    ];
}
