<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersLog extends Model
{
 use HasFactory;
    protected $table = 'users_log';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'USERNAME',
        'TRANS_TYPE',
        'LOG_DATETIME',
        'LOG_TYPE',
        'LOG_ID'
    ];
}
