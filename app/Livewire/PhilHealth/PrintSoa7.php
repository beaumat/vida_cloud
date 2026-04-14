<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use App\Services\PhilHealthSoaCustomServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintSoa7 extends Component
{

    public int $PRINT_ID;
    public int $LOCATION_ID;
    public int $PATIENT_ID;
    public int $CONTACT_ID;
    public int $AGE;
    public string $PATIENT_NAME;
    public string $DATE_BIRTH;
    public string $PATIENT_CONTACT;
    public string $USER_CONTACT;
    public string $PIN;
    public string $USER_NAME;
    public string $USER_POSITION;
    public string $CODE;
    public $DATE;
    public $DATE_ADMITTED;
    public $TIME_ADMITTED;
    public $DATE_DISCHARGED;
    public $TIME_DISCHARGED;
    public string $FINAL_DIAGNOSIS;
    public string $OTHER_DIAGNOSIS;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public float $CHARGES_ROOM_N_BOARD;
    public float $CHARGES_DRUG_N_MEDICINE;
    public float $CHARGES_LAB_N_DIAGNOSTICS;
    public float $CHARGES_OPERATING_ROOM_FEE;
    public float $CHARGES_SUPPLIES;
    public float $CHARGES_OTHERS;
    public float $CHARGES_SUB_TOTAL;
    public string $OTHER_SPECIFY;
    public float $VAT_ROOM_N_BOARD;
    public float $VAT_DRUG_N_MEDICINE;
    public float $VAT_LAB_N_DIAGNOSTICS;
    public float $VAT_OPERATING_ROOM_FEE;
    public float $VAT_SUPPLIES;
    public float $VAT_OTHERS;
    public float $VAT_SUB_TOTAL;
    public float $SP_ROOM_N_BOARD;
    public float $SP_DRUG_N_MEDICINE;
    public float $SP_LAB_N_DIAGNOSTICS;
    public float $SP_OPERATING_ROOM_FEE;
    public float $SP_SUPPLIES;
    public float $SP_OTHERS;
    public float $SP_SUB_TOTAL;
    public float $GOV_ROOM_N_BOARD;
    public float $GOV_DRUG_N_MEDICINE;
    public float $GOV_LAB_N_DIAGNOSTICS;
    public float $GOV_OPERATING_ROOM_FEE;
    public float $GOV_SUPPLIES;
    public float $GOV_OTHERS;
    public float $GOV_SUB_TOTAL;
    public bool $GOV_PCSO;
    public bool $GOV_DSWD;
    public bool $GOV_DOH;
    public bool $GOV_HMO;
    public bool $GOV_LINGAP;
    public float $P1_SUB_TOTAL;
    public float $P2_SUB_TOTAL;
    public float $OP_ROOM_N_BOARD;
    public float $OP_DRUG_N_MEDICINE;
    public float $OP_LAB_N_DIAGNOSTICS;
    public float $OP_OPERATING_ROOM_FEE;
    public float $OP_SUPPLIES;
    public float $OP_OTHERS;
    public float $OP_SUB_TOTAL;
    public float $PROFESSIONAL_FEE_SUB_TOTAL;
    public float $PROFESSIONAL_DISCOUNT_SUB_TOTAL;
    public float $PROFESSIONAL_P1_SUB_TOTAL;
    public float $CHARGE_TOTAL;
    public float $VAT_TOTAL;
    public float $SP_TOTAL;
    public float $GOV_TOTAL;
    public float $P1_TOTAL;
    public float $P2_TOTAL;
    public float $OP_TOTAL;
    public float $AD_SUB_TOTAL;
    public float $AD_TOTAL = 0;
    public bool $PRE_SIGN_DATA = false;
    public bool $OUTPUT_SIGN = false;
    public bool $HEADER = true; // default TRUE;


    public float $P1_ROOM_N_BOARD = 0;
    public float $P1_DRUG_N_MEDICINE = 0;
    public float $P1_LAB_N_DIAGNOSTICS = 0;
    public float $P1_OPERATING_ROOM_FEE = 0;
    public float $P1_SUPPLIES = 0;
    public float $P1_OTHERS = 0;



    public int $PREPARED_BY_ID;
    public string $DATE_SIGNED;
    public string $OTHER_NAME;
    public string $ADDRESS1;
    public string $ADDRESS2;
    private $philHealthServices;
    private $contactServices;
    private $locationServices;
    private $hemoServices;
    public $feeList = [];
    public $i;
    public int $NO_OF_TREATMENT;
    public string $allDate = '';
    public string $REPORT_HEADER_1;
    public string $REPORT_HEADER_2;
    public string $REPORT_HEADER_3;
    public string $LOGO_FILE;
    private $patientDoctorServices;
    public $TIME_HIDE;
    public $breakDownDate = [];
    public bool $IS_HIDE = false;
    private $philHealthSoaCustomServices;
    private $philHealthProfFeeServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        HemoServices $hemoServices,
        PatientDoctorServices $patientDoctorServices,
        PhilHealthSoaCustomServices $philHealthSoaCustomServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->hemoServices = $hemoServices;
        $this->patientDoctorServices = $patientDoctorServices;
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }
    private function gotHide()
    {
        $cusFirst = $this->philHealthSoaCustomServices->GetFirst($this->LOCATION_ID);
        if ($cusFirst) {
            if ($cusFirst->HIDE_FEE > 0) {
                $this->IS_HIDE = true;
            }
        }
    }


    public function mount(int $PRINT_ID, int $PATIENT_ID = 0, bool $OUTPUT = true)
    {
        $this->OUTPUT_SIGN = $OUTPUT;
        if ($PRINT_ID > 0) {
            $this->PRE_SIGN_DATA = false;
            $this->HEADER = !$OUTPUT;
            $this->GetInfo($PRINT_ID);

            return;
        }
        // pre-sign
        if ($PATIENT_ID > 0) {
            $this->PATIENT_ID = $PATIENT_ID;

            $this->PRE_SIGN_DATA = true;
            $this->HEADER = false;
            $contact = $this->contactServices->get($PATIENT_ID, 3);
            if ($contact) {
                $MI = substr($contact->MIDDLE_NAME, 0, 1);
                $MI_COUNT = strlen($contact->MIDDLE_NAME);
                $EX_COUNT = strlen($contact->SALUTATION);
                $MI_NAME = $MI_COUNT > 0 ? ' ' . $MI . '. ' : ' ';
                $EX_NAME = $EX_COUNT > 0 ? ' ' . $contact->SALUTATION . '.' : ' ';
                $this->DATE_BIRTH = date('m/d/Y', strtotime($contact->DATE_OF_BIRTH));
                $this->PATIENT_NAME = strtoupper($contact->LAST_NAME . ', ' . $contact->FIRST_NAME . ' ' . $contact->MIDDLE_NAME . ' ' . $EX_NAME);

                if ($contact->IS_DEPENDENT) {
                    $this->PIN = $contact->PIN_DEPENDENT ?? '';
                } else {
                    $this->PIN = $contact->PIN ?? '';
                }

                $this->LOCATION_ID = $contact->LOCATION_ID;
                $this->gotHide();
                $this->AGE = $this->contactServices->calculateUserAge($contact->DATE_OF_BIRTH);
                $this->ADDRESS1 = $this->GetAddress1($contact);
                $this->ADDRESS2 = $this->GetAddress2($contact);
                $this->PATIENT_CONTACT = $contact->MOBILE_NO ?? $contact->TELEPHONE_NO;
                $this->FINAL_DIAGNOSIS = $this->philHealthServices->ADMITTING_DIAGNOSIS_DEFAULT . ' ' . $contact->FINAL_DIAGNOSIS ?? '';
                $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';
                $this->FIRST_CASE_RATE = 'Hemodialysis-' . $contact->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
            }


            $locData = $this->locationServices->get($this->LOCATION_ID);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                $conUser = $this->contactServices->get($locData->PHIC_INCHARGE_ID ?? Auth()->user()->contact_id, 2); // Employee
                if ($conUser) {
                    $this->USER_CONTACT = $conUser->MOBILE_NO ?? '';
                    $this->USER_NAME = $conUser->PRINT_NAME_AS ?? '';
                    $this->USER_POSITION = $conUser->NICKNAME ?? '';

                }
            }
        }
    }
    public function GetInfo($ID)
    {
        if (is_numeric($ID)) {
            $data = $this->philHealthServices->get($ID);
            if ($data) {
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->gotHide();
                $this->CONTACT_ID = $data->CONTACT_ID;
                $this->CODE = $data->CODE;
                $this->DATE_ADMITTED = $data->DATE_ADMITTED;
                $this->TIME_ADMITTED = $data->TIME_ADMITTED;
                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED;
                $this->TIME_DISCHARGED = $this->IS_HIDE ? $data->TIME_HIDE : $data->TIME_DISCHARGED;
                $this->FINAL_DIAGNOSIS = $data->FINAL_DIAGNOSIS ?? '';
                $this->OTHER_DIAGNOSIS = $data->OTHER_DIAGNOSIS ?? '';
                $this->FIRST_CASE_RATE = $data->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE ?? '';

                $this->PREPARED_BY_ID = $data->PREPARED_BY_ID ?? 0;
                $this->DATE_SIGNED = $data->DATE_SIGNED ?? '';
                $this->OTHER_NAME = $data->OTHER_NAME ?? '';
                $contact = $this->contactServices->get($this->CONTACT_ID, 3);
                if ($contact) {
                    $MI = substr($contact->MIDDLE_NAME, 0, 1);
                    $MI_COUNT = strlen($contact->MIDDLE_NAME);
                    $EX_COUNT = strlen($contact->SALUTATION);
                    $MI_NAME = $MI_COUNT > 0 ? ' ' . $MI . '. ' : ' ';
                    $EX_NAME = $EX_COUNT > 0 ? ' ' . $contact->SALUTATION . '.' : ' ';

                    if ($contact->IS_DEPENDENT) {
                        $this->PIN = $contact->PIN_DEPENDENT ?? '';
                    } else {
                        $this->PIN = $contact->PIN ?? '';
                    }

                    $this->DATE_BIRTH = date('m/d/Y', strtotime($contact->DATE_OF_BIRTH));
                    $this->PATIENT_NAME = strtoupper($contact->LAST_NAME . ', ' . $contact->FIRST_NAME . ' ' . $contact->MIDDLE_NAME . ' ' . $EX_NAME);
                    $this->AGE = $this->contactServices->calculateUserAge($contact->DATE_OF_BIRTH);
                    $this->ADDRESS1 = $this->GetAddress1($contact);
                    $this->ADDRESS2 = $this->GetAddress2($contact);
                    $this->PATIENT_CONTACT = $contact->MOBILE_NO ?? $contact->TELEPHONE_NO;
                    $this->FINAL_DIAGNOSIS = $this->philHealthServices->DEFAULT_DIAGNOSIS2 . ' ' . $contact->FINAL_DIAGNOSIS ?? '';
                    $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';
                    $this->FIRST_CASE_RATE = 'Hemodialysis-' . $contact->FIRST_CASE_RATE ?? '';
                    $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
                }


                $locData = $this->locationServices->get($this->LOCATION_ID);
                if ($locData) {
                    $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                    $this->REPORT_HEADER_2 = $locData->REPORT_HEADER_2 ?? '';
                    $this->REPORT_HEADER_3 = $locData->REPORT_HEADER_3 ?? '';
                    $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
                    $conUser = $this->contactServices->get($locData->PHIC_INCHARGE_ID ?? Auth()->user()->contact_id, 2); // Employee
                    if ($conUser) {
                        $this->USER_CONTACT = $conUser->MOBILE_NO ?? '';
                        $this->USER_NAME = $conUser->PRINT_NAME_AS ?? '';
                        $this->USER_POSITION = $conUser->NICKNAME ?? '';

                    }
                }
                $this->NO_OF_TREATMENT = (int) $this->philHealthServices->getNumberOfTreatment($this->CONTACT_ID, $this->LOCATION_ID, $this->DATE_ADMITTED ?? '', $this->DATE_DISCHARGED ?? '');
                $this->DATE_SIGNED = Carbon::today()->format('F j, Y');

                $this->allDate = '';
                $dataList = $this->hemoServices->GetSummary($this->CONTACT_ID, $this->LOCATION_ID, $this->DATE_ADMITTED ?? '', $this->DATE_DISCHARGED ?? '');
                $this->breakDownDate = $dataList;

                $LastDate = '';

                foreach ($dataList as $list) {

                    if ($this->allDate == '') {
                        $this->allDate = date('M d', strtotime($list->DATE));
                    } else {
                        $this->allDate = $this->allDate . ', ' . date('d', strtotime($list->DATE));
                    }
                    $LastDate = $list->DATE;
                }

                if ($LastDate !== '') {
                    $this->allDate = $this->allDate . ', ' . date('Y', strtotime($LastDate));
                }
            }
        }
    }

    public function GetAddress1($contact): string
    {
        $ADDRESS_UNIT_ROOM_FLOOR = $contact->ADDRESS_UNIT_ROOM_FLOOR ?? '';
        $ADDRESS_BUILDING_NAME = $contact->ADDRESS_BUILDING_NAME ?? '';
        $ADDRESS_LOT_BLK_HOUSE_BLDG = $contact->ADDRESS_LOT_BLK_HOUSE_BLDG ?? '';
        $ADDRESS_STREET = $contact->ADDRESS_STREET ?? '';
        $ADDRESS_SUB_VALL = $contact->ADDRESS_SUB_VALL ?? '';
        $ADDRESS_BRGY = $contact->ADDRESS_BRGY ?? '';

        $ADDRESS = $ADDRESS_UNIT_ROOM_FLOOR . ' ' . $ADDRESS_BUILDING_NAME . ' ' . $ADDRESS_LOT_BLK_HOUSE_BLDG . ' ' . $ADDRESS_STREET . ' ' . $ADDRESS_SUB_VALL . ' ' . $ADDRESS_BRGY;
        return trim($ADDRESS);
    }

    public function GetAddress2($contact): string
    {
        $ADDRESS_CITY_MUNI = $contact->ADDRESS_CITY_MUNI ?? '';
        $ADDRESS_PROVINCE = $contact->ADDRESS_PROVINCE ?? '';
        $ADDRESS_COUNTRY = $contact->ADDRESS_COUNTRY ?? '';
        $ADDRESS_ZIP_CODE = $contact->ADDRESS_ZIP_CODE ?? '';
        $ADDRESS = $ADDRESS_CITY_MUNI . ', ' . $ADDRESS_PROVINCE . ', ' . $ADDRESS_COUNTRY . ' ' . $ADDRESS_ZIP_CODE;
        return trim($ADDRESS);
    }
    public function render()
    {
        return view('livewire.phil-health.print-soa7');
    }
}
