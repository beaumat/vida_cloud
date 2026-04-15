<?php

namespace App\Services;

use App\Models\PatientConfinement;

class PatientConfinementServices
{
    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }

    public function store(string $DATE_START, $DATE_END, string $DESCRIPTION, int $PATIENT_ID)
    {
        $ID = (int) $this->object->ObjectNextID('PATIENT_CONFINEMENT');

        PatientConfinement::create([
            'ID'            => $ID,
            'DATE_START'    => $DATE_START,
            'DATE_END'      => strtotime($DATE_END)  !== false ?  $DATE_END :  null,
            'DESCRIPTION'   => $DESCRIPTION,
            'PATIENT_ID'    => $PATIENT_ID
        ]);
    }
    public function update(int $ID, string $DATE_START, string $DATE_END, string $DESCRIPTION)
    {
        PatientConfinement::where('ID', '=', $ID)
            ->update([
                'DATE_START'    => $DATE_START,
                'DATE_END'      => strtotime($DATE_END)  !== false ?  $DATE_END :  null,
                'DESCRIPTION'   => $DESCRIPTION,
            ]);
    }
    public function delete(int $ID)
    {
        PatientConfinement::where('ID', '=', $ID)->delete();
    }
    public function list(int $PATIENT_ID, $search)
    {
        $result = PatientConfinement::query()
            ->select([
                'ID',
                'DESCRIPTION',
                'DATE_START',
                'DATE_END'
            ])
            ->when($search, function ($query) use (&$search) {
                $query->where('DESCRIPTION', 'like', '%' . $search . '%');
            })
            ->where('PATIENT_ID', '=', $PATIENT_ID)
            ->paginate(30);

        return $result;
    }

    public function get(int $ID)
    {
        $result =  PatientConfinement::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
}
