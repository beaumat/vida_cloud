<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBin extends Model
{
    use HasFactory;

    protected $table = 'stock_bin';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DESCRIPTION'
    ];
}
