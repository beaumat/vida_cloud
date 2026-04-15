<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HemoJournal extends Model
{
    use HasFactory;
    protected $table = 'hemo_journal';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DEBIT_ACCOUNT_ID',
        'CREDIT_ACCOUNT_ID',
        'ITEM_CLASS_ID',
    ];

}
