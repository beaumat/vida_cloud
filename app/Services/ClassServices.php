<?php
namespace App\Services;

use App\Models\Classes;

class ClassServices
{

    public function GetList()
    {
        return Classes::query()->select(['ID', 'NAME'])->where('INACTIVE', 0)->get();
    }

}
