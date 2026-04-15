<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhicAgreementFormDetails extends Model
{
    use HasFactory;

    protected $table = 'phic_agreement_form_details';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'HEMO_ID',
        'PHIC_AFT_ID',
        'IS_CHECK'
    ];
}
