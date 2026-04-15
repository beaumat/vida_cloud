<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhicAgreementFormItems extends Model
{
    use HasFactory;

    protected $table = 'phic_agreement_form_items';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'HEMO_ID',
        'DESCRIPTION',
        'QUANTITY',
        'RATE'
    ];
}
