<?php

namespace App\Services;

use App\Models\PatientClass;

class PatientClassServices
{

    public function getList(): object
    {
        $result = PatientClass::get();

        return $result;
    }
}
