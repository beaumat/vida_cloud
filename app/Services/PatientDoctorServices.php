<?php
namespace App\Services;

use App\Models\PatientDoctor;
use Illuminate\Support\Facades\DB;

class PatientDoctorServices
{

    private $object;

    public function __construct(ObjectServices $objectService)
    {
        $this->object = $objectService;
    }
    public function DeletePatient(int $id)
    {
        PatientDoctor::where('PATIENT_ID', '=', $id)->delete();
    }
    public function Store(int $PATIENT_ID, int $DOCTOR_ID)
    {
        $ID = (int) $this->object->ObjectNextID('PATIENT_DOCTOR');

        PatientDoctor::create([
            'ID'         => $ID,
            'PATIENT_ID' => $PATIENT_ID,
            'DOCTOR_ID'  => $DOCTOR_ID,
        ]);
    }
    public function GetList(int $PATIENT_ID, int $LOCATION_ID)
    {
        return PatientDoctor::query()
            ->select([
                'patient_doctor.ID',
                'c.PIN',
                'c.NAME',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'patient_doctor.DOCTOR_ID')
            ->join('doctor_location as d', 'd.DOCTOR_ID', '=', 'patient_doctor.DOCTOR_ID')
            ->where('patient_doctor.PATIENT_ID', $PATIENT_ID)
            ->where('d.LOCATION_ID', '=', $LOCATION_ID)
            ->get();
    }
    public function GetbyTemp(int $id)
    {

        $result = PatientDoctor::query()
            ->select([
                'patient_doctor.ID',
                DB::raw('0 as AMOUNT'),
                DB::raw('0 as DISCOUNT'),
                DB::raw('0 as FIRST_CASE'),
                'c.PIN',
                'c.NAME',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'patient_doctor.DOCTOR_ID')
            ->where('patient_doctor.PATIENT_ID', $id)
            ->get();

        return $result;
    }
    public function Delete(int $ID)
    {
        PatientDoctor::where('ID', $ID)->delete();
    }

    public function AlreadyExists(int $CONTACT_ID, int $LOCATION_ID): bool
    {

        return PatientDoctor::query()
            ->select([
                'patient_doctor.ID',
                'c.PIN',
                'c.NAME',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'patient_doctor.DOCTOR_ID')
            ->join('doctor_location as d', 'd.DOCTOR_ID', '=', 'patient_doctor.DOCTOR_ID')
            ->where('d.LOCATION_ID', $LOCATION_ID)
            ->where('patient_doctor.PATIENT_ID', $CONTACT_ID)
            ->exists();

    }
}
