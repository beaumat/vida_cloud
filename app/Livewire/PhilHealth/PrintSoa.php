<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintSoa extends Component
{
    public int $PRINT_ID;
    public int $LOCATION_ID;
    public int $CONTACT_ID;
    public int $AGE;
    public string $PATIENT_NAME;
    public string $PATIENT_CONTACT;
    public string $USER_CONTACT;
    public string $USER_NAME;
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
    private $philHealthProfFeeServices;

    public function boot(
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        HemoServices $hemoServices,
        PatientDoctorServices $patientDoctorServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->hemoServices = $hemoServices;
        $this->patientDoctorServices = $patientDoctorServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }

    public function profFeeList($PHIC_ID)
    {
        $this->i = 0;
        $this->feeList = $this->philHealthProfFeeServices->getProfFee($PHIC_ID);
    }
    public function mount(int $PRINT_ID, int $PATIENT_ID = 0, bool $OUTPUT = true)
    {
        $this->OUTPUT_SIGN = $OUTPUT;
        if ($PRINT_ID > 0) {
            $this->PRE_SIGN_DATA = false;

            $this->HEADER = !$OUTPUT;

            $this->PreLoad($PRINT_ID);
            $this->profFeeList($PRINT_ID);
            return;
        }
        // pre-sign
        if ($PATIENT_ID > 0) {
            $this->PRE_SIGN_DATA = true;
            $this->HEADER = false;
            $contact = $this->contactServices->get($PATIENT_ID, 3);
            if ($contact) {
                $MI = substr($contact->MIDDLE_NAME, 0, 1);
                $MI_COUNT = strlen($contact->MIDDLE_NAME);
                $EX_COUNT = strlen($contact->SALUTATION);
                $MI_NAME = $MI_COUNT > 0 ? ' ' . $MI . '. ' : ' ';
                $EX_NAME = $EX_COUNT > 0 ? ' ' . $contact->SALUTATION . '.' : ' ';

                $this->PATIENT_NAME = strtoupper($contact->FIRST_NAME . $MI_NAME . $contact->LAST_NAME . $EX_NAME);

                $this->LOCATION_ID = $contact->LOCATION_ID;
                $this->AGE = $this->contactServices->calculateUserAge($contact->DATE_OF_BIRTH);
                $this->ADDRESS1 = $this->GetAddress1($contact);
                $this->ADDRESS2 = $this->GetAddress2($contact);
                $this->PATIENT_CONTACT = $contact->MOBILE_NO ?? $contact->TELEPHONE_NO;
                $this->FINAL_DIAGNOSIS = $this->philHealthServices->DEFAULT_DIAGNOSIS2 . $contact->FINAL_DIAGNOSIS ?? '';
                $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';
                $this->FIRST_CASE_RATE = 'Hemodialysis-' . $contact->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
            }
            $this->i = 0;
            $this->feeList = $this->patientDoctorServices->GetbyTemp($PATIENT_ID);

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
                }
            }
        }
    }
    public function PreLoad($ID)
    {
        if (is_numeric($ID)) {
            $data = $this->philHealthServices->get($ID);
            if ($data) {
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->CONTACT_ID = $data->CONTACT_ID;
                $this->CODE = $data->CODE;
                $this->DATE_ADMITTED = $data->DATE_ADMITTED;
                $this->TIME_ADMITTED = $data->TIME_ADMITTED;
                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED;
                $this->TIME_DISCHARGED = $data->TIME_DISCHARGED;
                $this->FINAL_DIAGNOSIS = $data->FINAL_DIAGNOSIS ?? '';
                $this->OTHER_DIAGNOSIS = $data->OTHER_DIAGNOSIS ?? '';
                $this->FIRST_CASE_RATE = $data->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE ?? '';

                $this->CHARGES_ROOM_N_BOARD = $data->CHARGES_ROOM_N_BOARD;
                $this->CHARGES_DRUG_N_MEDICINE = $data->CHARGES_DRUG_N_MEDICINE;
                $this->CHARGES_LAB_N_DIAGNOSTICS = $data->CHARGES_LAB_N_DIAGNOSTICS;
                $this->CHARGES_OPERATING_ROOM_FEE = $data->CHARGES_OPERATING_ROOM_FEE;
                $this->CHARGES_SUPPLIES = $data->CHARGES_SUPPLIES;
                $this->CHARGES_OTHERS = $data->CHARGES_OTHERS;
                $this->CHARGES_SUB_TOTAL = $data->CHARGES_SUB_TOTAL;
                $this->OTHER_SPECIFY = $data->OTHER_SPECIFY ?? '';
                $this->VAT_ROOM_N_BOARD = $data->VAT_ROOM_N_BOARD;
                $this->VAT_DRUG_N_MEDICINE = $data->VAT_DRUG_N_MEDICINE;
                $this->VAT_LAB_N_DIAGNOSTICS = $data->VAT_LAB_N_DIAGNOSTICS;
                $this->VAT_OPERATING_ROOM_FEE = $data->VAT_OPERATING_ROOM_FEE;
                $this->VAT_SUPPLIES = $data->VAT_SUPPLIES;
                $this->VAT_OTHERS = $data->VAT_OTHERS;
                $this->VAT_SUB_TOTAL = $data->VAT_SUB_TOTAL;
                $this->SP_ROOM_N_BOARD = $data->SP_ROOM_N_BOARD;
                $this->SP_DRUG_N_MEDICINE = $data->SP_DRUG_N_MEDICINE;
                $this->SP_LAB_N_DIAGNOSTICS = $data->SP_LAB_N_DIAGNOSTICS;
                $this->SP_OPERATING_ROOM_FEE = $data->SP_OPERATING_ROOM_FEE;
                $this->SP_SUPPLIES = $data->SP_SUPPLIES;
                $this->SP_OTHERS = $data->SP_OTHERS;
                $this->SP_SUB_TOTAL = $data->SP_SUB_TOTAL;
                $this->GOV_ROOM_N_BOARD = $data->GOV_ROOM_N_BOARD;
                $this->GOV_DRUG_N_MEDICINE = $data->GOV_DRUG_N_MEDICINE;
                $this->GOV_LAB_N_DIAGNOSTICS = $data->GOV_LAB_N_DIAGNOSTICS;
                $this->GOV_OPERATING_ROOM_FEE = $data->GOV_OPERATING_ROOM_FEE;
                $this->GOV_SUPPLIES = $data->GOV_SUPPLIES;
                $this->GOV_OTHERS = $data->GOV_OTHERS;
                $this->GOV_SUB_TOTAL = $data->GOV_SUB_TOTAL;
                $this->GOV_PCSO = $data->GOV_PCSO;
                $this->GOV_DSWD = $data->GOV_DSWD;
                $this->GOV_DOH = $data->GOV_DOH;
                $this->GOV_HMO = $data->GOV_HMO;
                $this->GOV_LINGAP = $data->GOV_LINGAP;
                // $this->P1_ROOM_N_BOARD = $data->P1_ROOM_N_BOARD;
                // $this->P1_DRUG_N_MEDICINE = $data->P1_DRUG_N_MEDICINE;
                // $this->P1_LAB_N_DIAGNOSTICS = $data->P1_LAB_N_DIAGNOSTICS;
                // $this->P1_OPERATING_ROOM_FEE = $data->P1_OPERATING_ROOM_FEE;
                // $this->P1_SUPPLIES = $data->P1_SUPPLIES;
                // $this->P1_OTHERS = $data->P1_OTHERS;
                $this->P1_SUB_TOTAL = $data->P1_SUB_TOTAL;
                // $this->P2_ROOM_N_BOARD = $data->P2_ROOM_N_BOARD;
                // $this->P2_DRUG_N_MEDICINE = $data->P2_DRUG_N_MEDICINE;
                // $this->P2_LAB_N_DIAGNOSTICS = $data->P2_LAB_N_DIAGNOSTICS;
                // $this->P2_OPERATING_ROOM_FEE = $data->P2_OPERATING_ROOM_FEE;
                // $this->P2_SUPPLIES = $data->P2_SUPPLIES;
                // $this->P2_OTHERS = $data->P2_OTHERS;
                $this->P2_SUB_TOTAL = $data->P2_SUB_TOTAL;
                $this->OP_ROOM_N_BOARD = $data->OP_ROOM_N_BOARD;
                $this->OP_DRUG_N_MEDICINE = $data->OP_DRUG_N_MEDICINE;
                $this->OP_LAB_N_DIAGNOSTICS = $data->OP_LAB_N_DIAGNOSTICS;
                $this->OP_OPERATING_ROOM_FEE = $data->OP_OPERATING_ROOM_FEE;
                $this->OP_SUPPLIES = $data->OP_SUPPLIES;
                $this->OP_OTHERS = $data->OP_OTHERS;
                $this->OP_SUB_TOTAL = $data->OP_SUB_TOTAL;
                $this->PROFESSIONAL_FEE_SUB_TOTAL = $data->PROFESSIONAL_FEE_SUB_TOTAL;
                $this->PROFESSIONAL_DISCOUNT_SUB_TOTAL = $data->PROFESSIONAL_DISCOUNT_SUB_TOTAL;
                $this->PROFESSIONAL_P1_SUB_TOTAL = $data->PROFESSIONAL_P1_SUB_TOTAL;
                $this->CHARGE_TOTAL = $data->CHARGE_TOTAL;
                $this->VAT_TOTAL = $data->VAT_TOTAL;
                $this->SP_TOTAL = $data->SP_TOTAL;
                $this->GOV_TOTAL = $data->GOV_TOTAL;
                $this->P1_TOTAL = $data->P1_TOTAL;
                $this->P2_TOTAL = $data->P2_TOTAL;
                $this->OP_TOTAL = $data->OP_TOTAL;

                $this->AD_SUB_TOTAL = $data->AD_SUB_TOTAL;
                $this->AD_TOTAL = $data->AD_TOTAL;

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

                    $this->PATIENT_NAME = strtoupper($contact->FIRST_NAME . $MI_NAME . $contact->LAST_NAME . $EX_NAME);
                    $this->AGE = $this->contactServices->calculateUserAge($contact->DATE_OF_BIRTH);
                    $this->ADDRESS1 = $this->GetAddress1($contact);
                    $this->ADDRESS2 = $this->GetAddress2($contact);
                    $this->PATIENT_CONTACT = $contact->MOBILE_NO ?? $contact->TELEPHONE_NO;
                    $this->FINAL_DIAGNOSIS = $this->philHealthServices->DEFAULT_DIAGNOSIS2 . $contact->FINAL_DIAGNOSIS ?? '';
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
                    }
                }
                $this->NO_OF_TREATMENT = (int) $this->philHealthServices->getNumberOfTreatment($this->CONTACT_ID, $this->LOCATION_ID, $this->DATE_ADMITTED ?? '', $this->DATE_DISCHARGED ?? '');
                $this->DATE_SIGNED = Carbon::today()->format('F j, Y');
                $this->allDate == '';
                $dataList = $this->hemoServices->GetSummary($this->CONTACT_ID, $this->LOCATION_ID, $this->DATE_ADMITTED ?? '', $this->DATE_DISCHARGED ?? '');
                $LastDate = '';
                foreach ($dataList as $list) {
                    if ($this->allDate == '') {
                        $this->allDate = date('F d', strtotime($list->DATE));
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
        return view('livewire.phil-health.print-soa');
    }
}
