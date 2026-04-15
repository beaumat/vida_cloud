<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowKey extends Model
{
    use HasFactory;
    protected $table = 'cash_flow_key';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    protected $fillable = [
        'ID',
        'ACCOUNT_BASE',
        'ACCOUNT_KEY',
        'DEBIT_DEFAULT',
        'LINE_NO',
        'INACTIVE',
        'CS_FLOW_DETAILS_ID',
        'NAME'
    ];
}
