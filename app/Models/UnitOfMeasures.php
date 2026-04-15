<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitOfMeasures extends Model
{
    use HasFactory;

    protected $table = 'unit_of_measure';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'NAME',
        'SYMBOL',
        'INACTIVE'
    ];
}
