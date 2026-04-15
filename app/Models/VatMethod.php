<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatMethod extends Model
{
    use HasFactory;
    protected $table = 'vat_method_map';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'DESCRIPTION'
    ];
}
