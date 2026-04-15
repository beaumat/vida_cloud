<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckExpenses extends Model
{
    use HasFactory;
    protected $table = 'check_expenses';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CHECK_ID',
        'LINE_NO',
        'ACCOUNT_ID',
        'AMOUNT',
        'TAXABLE',
        'TAXABLE_AMOUNT',
        'TAX_AMOUNT',
        'PARTICULARS',
        'CLASS_ID'
    ];
}
