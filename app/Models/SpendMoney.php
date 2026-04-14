<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpendMoney extends Model
{
    use HasFactory;

    protected $table = 'spend_money';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'RECORDED_ON',
        'DATE',
        'CODE',
        'LOCATION_ID',
        'ACCOUNT_ID',
        'NOTES',
        'STATUS',
        'STATUS_DATE',
        'IS_XERO',
        'AMOUNT'
    ];
}
