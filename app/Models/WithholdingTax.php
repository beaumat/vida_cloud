<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingTax extends Model
{
    use HasFactory;

    protected $table = 'withholding_tax';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'WITHHELD_FROM_ID',
        'EWT_ID',
        'EWT_RATE',
        'EWT_ACCOUNT_ID',
        'LOCATION_ID',
        'AMOUNT',
        'NOTES',
        'STATUS',
        'STATUS_DATE',
        'ACCOUNTS_PAYABLE_ID'
    ];
}
