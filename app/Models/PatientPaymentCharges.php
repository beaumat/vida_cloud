<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientPaymentCharges extends Model
{
    use HasFactory;

    protected $table = 'patient_payment_charges';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PATIENT_PAYMENT_ID',
        'SERVICE_CHARGES_ITEM_ID',
        'DISCOUNT',
        'AMOUNT_APPLIED',
        'DISCOUNT_ACCOUNT_ID',
        'ACCOUNTS_RECEIVABLE_ID'
    ];
}
