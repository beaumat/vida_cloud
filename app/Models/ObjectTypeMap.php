<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectTypeMap extends Model
{
    use HasFactory;

    protected $table = 'object_type_map';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'NAME',
        'TABLE_NAME',
        'IS_DOCUMENT',
        'DOCUMENT_TYPE',
        'NEXT_ID',
        'INCREMENT'

    ];
}
