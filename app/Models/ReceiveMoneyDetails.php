<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveMoneyDetails extends Model
{
    use HasFactory;
    protected $table = 'receive_money_details';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECEIVE_MONEY_ID',
        'LINE_NO',
        'ACCOUNT_ID',
        'AMOUNT',
        'NOTES'
    ];
}
