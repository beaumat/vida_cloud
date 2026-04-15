<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillCreditBills extends Model
{
    use HasFactory;

    protected $table = 'bill_credit_bills';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'BILL_CREDIT_ID',
        'BILL_ID',
        'AMOUNT_APPLIED'
    ];
}
