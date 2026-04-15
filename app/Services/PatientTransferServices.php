<?php

namespace App\Services;

use App\Models\PatientTransfer;

class PatientTransferServices
{
    private $object;
    public function __construct(ObjectServices $objectServices)
    {
        $this->object = $objectServices;
    }
    public function store(int $PATIENT_ID, string $DATE_TRANSFER, string $NOTES)
    {


        $ID = (int)  $this->object->ObjectNextID('PATIENT_TRANSFER');

        PatientTransfer::create(
            [
                'ID'            => $ID,
                'PATIENT_ID'    => $PATIENT_ID,
                'DATE_TRANSFER' => $DATE_TRANSFER,
                'NOTES'         => $NOTES
            ]
        );
    }
    public function update(int $ID, $DATE_TRANSFER, string $NOTES)
    {
        PatientTransfer::where('ID', '=', $ID)->update(['DATE_TRANSFER' => $DATE_TRANSFER, 'NOTES' => $NOTES]);
    }
    public function delete(int $ID)
    {
        PatientTransfer::where('ID', '=', $ID)->delete();
    }

    public function list(int $PATIENT_ID, int $perPage, $search)
    {
        $result = PatientTransfer::query()
            ->select([
                'ID',
                'DATE_TRANSFER',
                'NOTES'
            ])
            ->when($search, function ($query) use (&$search) {
                $query->where('NOTES', 'like', '%' . $search . '%');
            })
            ->where('PATIENT_ID', '=', $PATIENT_ID)
            ->orderBy('DATE_TRANSFER', 'desc')
            ->paginate($perPage);


        return $result;
    }
    public function get(int $ID)
    {
        $result = PatientTransfer::where('ID', '=', $ID)->first();
        if ($result) {
            return $result;
        }
        return null;
    }
}
