<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $table = 'deposit';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'CODE',
        'DATE',
        'BANK_ACCOUNT_ID',
        'AMOUNT',
        'NOTES',
        'CASH_BACK_ACCOUNT_ID',
        'CASH_BACK_AMOUNT',
        'CASH_BACK_NOTES',
        'LOCATION_ID',
        'STATUS',
        'STATUS_DATE'
    ];
}
