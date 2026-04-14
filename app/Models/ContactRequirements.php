<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequirements extends Model
{
    use HasFactory;
    protected $table = 'contact_requirement';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'CONTACT_ID',
        'REQUIREMENT_ID',
        'IS_COMPLETE',
        'DATE_COMPLETED',
        'NOT_APPLICABLE',
        'FILE_NAME',
        'FILE_PATH',
        'FILE_CONFIRM_DATE'
    ];
}
