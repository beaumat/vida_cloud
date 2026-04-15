<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'CUSTOMER_ID',
        'LOCATION_ID',
        'AMOUNT',
        'AMOUNT_APPLIED',
        'PAYMENT_METHOD_ID',
        'CARD_NO',
        'CARD_EXPIRY_DATE',
        'RECEIPT_REF_NO',
        'RECEIPT_DATE',
        'NOTES',
        'UNDEPOSITED_FUNDS_ACCOUNT_ID',
        'OVERPAYMENT_ACCOUNT_ID',
        'STATUS',
        'STATUS_DATE',
        'DEPOSITED',
        'ACCOUNTS_RECEIVABLE_ID',
        'PAYMENT_PERIOD_ID',
        'IS_XERO',
    ];
}
