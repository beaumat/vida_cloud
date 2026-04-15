<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\MedcertSchedule;

class MedCertServices
{

    public function GetList(): object
    {
        $result = MedcertSchedule::get();

        return $result;
    }
    public function GetMedcertSchedule(string $id): object
    {
        $result =  MedcertSchedule::where("ID", '=', $id)->first();
        return $result;
    }
    public function UpdatePatientMedCert(int $CONTACT_ID, int $MED_CERT_NURSE_ID, bool $FIX_MON, bool $FIX_TUE, bool $FIX_WEN, bool $FIX_THU,bool $FIX_FRI,bool $FIX_SAT, bool $FIX_SUN)
    {   


        Contacts::where('ID', '=', $CONTACT_ID)
            ->where('TYPE', '=', 3)
            ->update([
                'MED_CERT_NURSE_ID' => $MED_CERT_NURSE_ID > 0 ? $MED_CERT_NURSE_ID : null,
                'FIX_MON'   => $FIX_MON,
                'FIX_TUE'   => $FIX_TUE,
                'FIX_WEN'   => $FIX_WEN,
                'FIX_THU'   => $FIX_THU,
                'FIX_FRI'   => $FIX_FRI,
                'FIX_SAT'   => $FIX_SAT,
                'FIX_SUN'   => $FIX_SUN
            ]);
    }
    public function updateParamater(int $ID, array $parameter = []) {
        Contacts::where('ID','=', $ID)->update($parameter);
    }
}
