<?php

namespace App\Services;

use App\Models\PhilHealthProfFee;
use Illuminate\Support\Facades\DB;

class DoctorPFServices
{

    public function getDoctorList(int $LOCATION_ID)
    {
        $STATUS_ID = 11; // 11 is paid
        $doctors = DB::table(DB::raw('(
            select 
                c.ID,
                c.PRINT_NAME_AS as DOCTOR_NAME,
                CONCAT( p.LAST_NAME, ", ", p.FIRST_NAME, ", ", LEFT(p.MIDDLE_NAME, 1) ) as PATIENT_NAME,
                ph.DATE_ADMITTED,
                ph.DATE_DISCHARGED,
                (select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = ph.CONTACT_ID and hemodialysis.DATE between ph.DATE_ADMITTED and ph.DATE_DISCHARGED ) as HEMO_TOTAL,
                pf.FIRST_CASE
            from
            philhealth_prof_fee as pf
            join philhealth as ph on ph.ID = pf.PHIC_ID
            inner join contact as c on c.ID = pf.CONTACT_ID
            inner join contact as p on p.id = ph.CONTACT_ID
            where p.LOCATION_ID = ' . $LOCATION_ID . ' 
              and ph.STATUS_ID = ' . $STATUS_ID . ' 
              and ph.PF_RECEIVED_DATE is null
        ) as pf
         '))
            ->select('DOCTOR_NAME', 'ID as DOCTOR_ID', DB::raw(' sum(HEMO_TOTAL) as NO_TREAT'), DB::raw(' sum(FIRST_CASE) as TOTAL'))
            ->groupBy('DOCTOR_NAME', 'ID')
            ->get();

        return $doctors;
    }

    public function PatientGenerate(int $LOCATION_ID, int $DOCTOR_ID)
    {
        $STATUS_ID = 11; // 11 is paid
        $patients = DB::table(DB::raw('(
            select 
                c.ID,
                c.PRINT_NAME_AS as DOCTOR_NAME,
                CONCAT( p.LAST_NAME, ", ", p.FIRST_NAME, ", ", LEFT(p.MIDDLE_NAME, 1) ) as PATIENT_NAME,
                ph.DATE_ADMITTED,
                ph.DATE_DISCHARGED,
                (select count(*) from hemodialysis where hemodialysis.STATUS_ID = 2 and hemodialysis.CUSTOMER_ID = ph.CONTACT_ID and hemodialysis.DATE between ph.DATE_ADMITTED and ph.DATE_DISCHARGED ) as HEMO_TOTAL,
                pf.FIRST_CASE,
                pf.PHIC_ID
            from
                philhealth_prof_fee as pf
            inner join philhealth as ph on ph.ID = pf.PHIC_ID
            inner join contact as c on c.ID = pf.CONTACT_ID
            inner join contact as p on p.id = ph.CONTACT_ID
            where p.LOCATION_ID = ' . $LOCATION_ID . '
              and ph.STATUS_ID = ' . $STATUS_ID . ' 
              and ph.PF_RECEIVED_DATE is null
              and pf.CONTACT_ID = ' . $DOCTOR_ID . '
        ) as pf'))
            ->select('PATIENT_NAME', DB::raw('min(DATE_ADMITTED) as DATE_ADMITTED'), DB::raw('max(DATE_DISCHARGED) as DATE_DISCHARGED'), DB::raw('sum(HEMO_TOTAL) as NO_TREAT'), 'FIRST_CASE as TOTAL', 'PHIC_ID')
            ->groupBy('PATIENT_NAME')
            ->get();

        return $patients;
    }

    public function getPayFee(int $LOCATION_ID, int $DOCTOR_ID, string $DATE_FROM, string $DATE_TO)
    {

        $data = DB::table('philhealth_prof_fee as pf')
            ->select(
                DB::raw(' IFNULL(SUM(pf.FIRST_CASE),0) as AMOUNT')
            )
            ->join('philhealth as ph', '=', 'pf.PHIC_ID')
            ->join('contact as c', 'c.ID', '=', 'pf.CONTACT_ID')
            ->where('pf.CONTACT_ID', '=', $DOCTOR_ID)
            ->whereNull('ph.PF_RECEIVED_DATE')
            ->whereBetween('ph.DATE_ADMITTED', [$DATE_FROM, $DATE_TO])
            ->whereBetween('ph.DATE_DISCHARGED', [$DATE_FROM, $DATE_TO])
            ->first();

        if ($data) {
            $data->AMOUNT ?? 0;
        }

        return 0;
    }
 
}
