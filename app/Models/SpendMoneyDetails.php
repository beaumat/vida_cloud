<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpendMoneyDetails extends Model
{
    use HasFactory;
    protected $table = 'spend_money_details';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'SPEND_MONEY_ID',
        'LINE_NO',
        'ACCOUNT_ID',
        'AMOUNT',
        'NOTES'
    ];
}
