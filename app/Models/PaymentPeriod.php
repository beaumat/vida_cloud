<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPeriod extends Model
{
    use HasFactory;
    protected $table = 'payment_period';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECEIPT_NO',
        'DATE_FROM',
        'DATE_TO',
        'LOCATION_ID',
        'TOTAL_PAYMENT',
        'TOTAL_WTAX',
        'BANK_ACCOUNT_ID',
        'DATE'
    ];
}
