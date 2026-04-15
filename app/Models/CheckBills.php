<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckBills extends Model
{
    use HasFactory;

    protected $table = 'check_bills';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CHECK_ID',
        'BILL_ID',
        'DISCOUNT',
        'AMOUNT_PAID',
        'DISCOUNT_ACCOUNT_ID',
        'ACCOUNTS_PAYABLE_ID'
    ];

}
