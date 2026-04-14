<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemTreatmentTrigger extends Model
{
    use HasFactory;

    protected $table = 'item_treatment_trigger';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'ITEM_TREATMENT_ID',
        'ITEM_ID',
        'QUANTITY',
        'UNIT_ID',

    ];
}
