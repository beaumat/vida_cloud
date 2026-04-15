<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemAccounts extends Model
{
    use HasFactory;
    protected $table = 'item_accounts';
    protected $primaryKey = 'ITEM_ID';
    public $timestamps = false;
    protected $fillable = [
        'ITEM_ID',
        'ACCOUNT_ID'
    ];
}
