<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    use HasFactory;
    protected $table = 'account';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'NAME',
        'GROUP_ACCOUNT_ID',
        'TYPE',
        'BANK_ACCOUNT_NO',
        'INACTIVE',
        'TAG',
        'LINE_NO'
    ];
}
