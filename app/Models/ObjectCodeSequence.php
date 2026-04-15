<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectCodeSequence extends Model
{
    use HasFactory;
    protected $table = 'object_code_sequence';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'OBJECT_TYPE',
        'LOCATION_ID',
        'PREFIX',
        'POSTFIX',
        'INCREMENT',
        'WIDTH',
        'NEXT_SEQUENCE',
        'INCREMENT'

    ];
}
