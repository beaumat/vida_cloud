<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundTransfer extends Model
{
    use HasFactory;
    protected $table = 'fund_transfer';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'CODE',
        'FROM_ACCOUNT_ID',
        'TO_ACCOUNT_ID',
        'FROM_NAME_ID',
        'TO_NAME_ID',
        'FROM_LOCATION_ID',
        'TO_LOCATION_ID',
        'INTER_LOCATION_ACCOUNT_ID',
        'CLASS_ID',
        'AMOUNT',
        'NOTES',
        'STATUS',
        'STATUS_DATE'
    ];
}
