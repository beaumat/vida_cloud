<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithholdingTaxBills extends Model
{
    use HasFactory;

    protected $table = 'withholding_tax_bills';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'WITHHOLDING_TAX_ID',
        'BILL_ID',
        'AMOUNT_WITHHELD',
        'ACCOUNTS_PAYABLE_ID'
    ];
}
