<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HemodialysisMachines extends Model
{
    use HasFactory;
    protected $table = 'hemodialysis_machine';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CODE',
        'TYPE',
        'DESCRIPTION',
        'LOCATION_ID',
        'CAPACITY'
    ];
}
