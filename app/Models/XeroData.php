<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XeroData extends Model
{
    use HasFactory;
    protected $table = 'xero_data';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'LOCATION_ID',
        'IS_OPEN',
        'ACCOUNT',
        'DATE',
        'SOURCE_TYPE',
        'DESCRIPTION',
        'REFERENCE',
        'DEBIT',
        'CREDIT',
        'BALANCE',
        'GROSS',
        'TAX'

    ];
}
