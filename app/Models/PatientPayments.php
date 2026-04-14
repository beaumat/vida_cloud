<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPayments extends Model
{
    use HasFactory;

    protected $table = 'patient_payment';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'PATIENT_ID',
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
        'FILE_NAME',
        'FILE_PATH',
        'IS_CONFIRM',
        'DATE_CONFIRM',
        'PHILHEALTH_ID',
        'WTAX_AMOUNT',
        'WTAX_ACCOUNT_ID',
        'LESS_AMOUNT',
        'IS_INVOICE',
        'REF_ID'
    ];
}
