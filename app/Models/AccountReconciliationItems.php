<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountReconciliationItems extends Model
{
    use HasFactory;


    protected $table = 'account_reconciliation_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ACCOUNT_RECONCILIATION_ID',
        'LINE_NO',
        'OBJECT_ID',
        'OBJECT_TYPE',
        'ENTRY_TYPE',
        'CLEARED_DEBIT',
        'CLEARED_CREDIT',
        'AMOUNT',
        'OBJECT_DATE'
    ];
}
