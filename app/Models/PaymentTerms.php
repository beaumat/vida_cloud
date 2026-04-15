<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTerms extends Model
{
    use HasFactory;
    protected $table = 'payment_terms';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DESCRIPTION',
        'TYPE',
        'NET_DUE',
        'DISCOUNT_PCT',
        'DISCOUNT_DUE',
        'DATE_MONTH_PARAM',
        'DATE_DAY_PARAM',
        'DATE_MIN_DAYS',
        'INACTIVE'
    ];
}
