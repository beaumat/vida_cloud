<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountReconciliation extends Model
{
    use HasFactory;


    protected $table = 'account_reconciliation';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'CODE',
        'ACCOUNT_ID',
        'LOCATION_ID',
        'PREVIOUS_ID',
        'SEQUENCE_NO',
        'BEGINNING_BALANCE',
        'CLEARED_DEPOSITS',
        'CLEARED_WITHDRAWALS',
        'CLEARED_BALANCE',
        'ENDING_BALANCE',
        'NOTES',
        'STATUS',
        'STATUS_DATE',
        'SC_ACCOUNT_ID',
        'SC_RATE',
        'IE_ACCOUNT_ID',
        'IE_RATE',
        'SC_DATE',
        'IE_DATE',
        'BANK_STATEMENT_ID'
    ];
}
