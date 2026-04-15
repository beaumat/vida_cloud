<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositFunds extends Model
{
    use HasFactory;
    protected $table = 'deposit_funds';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DEPOSIT_ID',
        'RECEIVED_FROM_ID',
        'ACCOUNT_ID',
        'PAYMENT_METHOD_ID',
        'CHECK_NO',
        'AMOUNT',
        'SOURCE_OBJECT_TYPE',
        'SOURCE_OBJECT_ID'
    ];
}
