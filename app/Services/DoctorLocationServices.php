<?php

namespace App\Services;

use App\Models\Contacts;
use App\Models\DoctorLocation;
use Illuminate\Support\Facades\DB;

class DoctorLocationServices
{
    
    public function Store(int $LOCATION_ID, int $DOCTOR_ID)
    {

        DoctorLocation::create([
            'LOCATION_ID'   => $LOCATION_ID,
            'DOCTOR_ID'     => $DOCTOR_ID
        ]);
    }
    public function Delete(int $LOCATION_ID, int $DOCTOR_ID)
    {

        DoctorLocation::where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('DOCTOR_ID', '=', $DOCTOR_ID)
            ->delete();
    }
    public function GetDoctorList(int $LOCATION_ID): object
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                'contact.NAME'
            ])
            ->where('TYPE', 4)
            ->whereNotExists(function ($query) use (&$LOCATION_ID) {
                $query->select(DB::raw(1))
                    ->from('doctor_location as dl')
                    ->whereRaw('dl.DOCTOR_ID = contact.ID')
                    ->where('dl.LOCATION_ID', $LOCATION_ID);
            })
            ->get();

        return $result;
    }
    public function ViewList(int $LOCATION_ID)
    {
        $result = DoctorLocation::query()
            ->select([
                'c.ID',
                'c.NAME'
            ])
            ->join('contact as c', 'c.ID', '=', 'doctor_location.DOCTOR_ID')
            ->where('doctor_location.LOCATION_ID', $LOCATION_ID)
            ->where('c.TYPE', 4)
            ->get();

        return $result;
    }
}
