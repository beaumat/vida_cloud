<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipVia extends Model
{
    use HasFactory;
    protected $table = 'ship_via';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'DESCRIPTION'
    ];
}
