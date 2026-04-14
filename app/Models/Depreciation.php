<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    use HasFactory;
    protected $table = 'depreciation';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DATE',
        'LOCATION_ID',
        'DEPRECIATION_ACCOUNT_ID',
        'NOTES',
        'IS_AUTO',
        'AMOUNT',
        'STATUS',
        'STATUS_DATE'
    ];
}
