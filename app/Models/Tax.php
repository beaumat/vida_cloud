<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $table = 'tax';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'NAME',
        'TAX_TYPE',
        'RATE',
        'VAT_METHOD',
        'TAX_ACCOUNT_ID',
        'ASSET_ACCOUNT_ID',
        'INACTIVE'
    ];
}
