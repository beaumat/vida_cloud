<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HemoNurseNotes extends Model
{
    use HasFactory;
    protected $table = 'hemo_nurse_notes';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'HEMO_ID',
        'TIME',
        'BP_1',
        'BP_2',
        'HR',
        'BFR',
        'AP',
        'VP',
        'TFP',
        'TMP',
        'HEPARIN',
        'FLUSHING',
        'NOTES'
    ];
}
