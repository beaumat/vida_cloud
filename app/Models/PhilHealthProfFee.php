<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhilHealthProfFee extends Model
{
    use HasFactory;

    protected $table = 'philhealth_prof_fee';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PHIC_ID',
        'LINE_NO',
        'CONTACT_ID',
        'AMOUNT',
        'DISCOUNT',
        'FIRST_CASE',
        'BILL_ID'
    ];

}
