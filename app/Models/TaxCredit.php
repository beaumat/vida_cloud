<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxCredit extends Model
{
    use HasFactory;

    protected $table = 'tax_credit';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'CUSTOMER_ID',
        'EWT_ID',
        'EWT_RATE',
        'EWT_ACCOUNT_ID',
        'LOCATION_ID',
        'NOTES',
        'AMOUNT',
        'STATUS',
        'STATUS_DATE',
        'ACCOUNTS_RECEIVABLE_ID'
    ];
}
