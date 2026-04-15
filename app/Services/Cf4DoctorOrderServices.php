<?php

namespace App\Services;

use App\Models\Cf4DoctorOrder;

class Cf4DoctorOrderServices
{

    private $objectServices;
    public function __construct(ObjectServices $objectServices)
    {
        $this->objectServices =   $objectServices;
    }

    public function Get(int $ID)
    {
        $result = Cf4DoctorOrder::where('ID', $ID)->first();
        if ($result) {
            return $result;
        }

        return [];
    }
    public function dataIsExists(int $HEMO_ID) :bool
    {
        return Cf4DoctorOrder::where('HEMO_ID', $HEMO_ID)->exists();
    }
    public function Store(int $HEMO_ID, string $DESCRIPTION)
    {

        $ID = (int) $this->objectServices->ObjectNextID('CF4_DOCTOR_ORDER');

        Cf4DoctorOrder::create([
            'ID'            => $ID,
            'HEMO_ID'       => $HEMO_ID,
            'DESCRIPTION'   => $DESCRIPTION
        ]);
    }

    public function Update(int $ID, string $DESCRIPTION)
    {
        Cf4DoctorOrder::where('ID', $ID)
            ->update(['DESCRIPTION' => $DESCRIPTION]);
    }
    public function Delete(int $ID)
    {

        Cf4DoctorOrder::where('ID', $ID)->delete();
    }

    public function GetList(int $HEMO_ID)
    {
        $dataList =  Cf4DoctorOrder::query()
            ->select([
                'ID',
                'DESCRIPTION'
            ])
            ->where('HEMO_ID', $HEMO_ID)
            ->get();

        return  $dataList;
    }
}
