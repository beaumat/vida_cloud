<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintCf2Back extends Component
{
    public int $LOCATION_ID;
    public string $PIN;
    public string $PEN;
    public string $PIN_DEPENDENT;
    public bool $MEMBER_RELATIONSHIP_CHILD = false;
    public bool $MEMBER_RELATIONSHIP_PARENT = false;
    public bool $MEMBER_RELATIONSHIP_SPOUSE = false;
    public string $PATIENT_LASTNAME;
    public string $PATIENT_FIRSTNAME;
    public string $PATIENT_MIDDLENAME;
    public string $PATIENT_EXTENSION;
    public string $PATIENT_BIRTH_DATE;
    public int $PATIENT_GENDER;
    public string $DATE_ADMITTED;
    public string $TIME_ADMITTED;
    public string $DATE_DISCHARGED;
    public string $TIME_DISCHARGED;
    public string $NAME_REPRESENTATIVE;
    public string $PEN_CONTACT;
    public string $COMPANY_NAME;
    public string $HCP_1_AN;
    public string $HCP_2_AN;
    public string $HCP_3_AN;
    public string $HCP_1_NAME;
    public string $HCP_2_NAME;
    public string $HCP_3_NAME;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public bool $IS_PATIENT;
    public bool $IS_DEPENDENT;
    public bool $MEMBER_IS_CHILD;
    public bool $MEMBER_IS_PARENT;
    public bool $MEMBER_IS_SPOUSE;
    public bool $IS_REPRESENTATIVE;
    public string $MEMBER_FIRST_NAME;
    public string $MEMBER_LAST_NAME;
    public string $MEMBER_MIDDLE_NAME;
    public string $MEMBER_EXTENSION;
    public string $MEMBER_BIRTH_DATE;
    public int $MEMBER_GENDER;
    public string $NAME_OF_BUSINESS;
    public string $ACCREDITATION_NO;
    public string $BLDG_NAME_LOT_BLOCK;
    public string $STREET_SUB_VALL;
    public string $BRGY_CITY_MUNI;
    public string $PROVINCE;
    public string $ZIP_CODE;
    public string $CHIEF_OF_COMPLAINT = 'HEMODIALYSIS';
    public string $ADMITTING_DIAGNOSIS = 'CHRONIC KIDNEY DISEASE';
    public string $DEFAULT_SEC_TO = "CKD Stage 5 Sec to ";
    public string $ICD_CODE = 'N18.5';
    public string $FINAL_DIAGNOSIS;
    public string $HISTORY_OF_PRESENT_ILLNESS = 'CHRONIC KIDNEY DISEASE STAGE 5';
    public int $AGE;
    public float $HEIGHT;
    public string $POST_WEIGHT;
    public string $POST_BLOOD_PRESSURE;
    public string $POST_HEART_RATE;
    public string $POST_O2_SATURATION;
    public string $POST_TEMPERATURE;
    public string $POST_BLOOD_PRESSURE2;
    public bool $PRE_SIGN_DATA = false;
    public bool $OUTPUT_SIGN = false;



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
    public string $HCI_NAME;
    public string $HCI_POSITION;

    public string $RR_NO;
    private $philHealthServices;
    private $contactServices;
    private $locationServices;
    private $hemoServices;
    private $philHealthProfFeeServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        HemoServices $hemoServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->hemoServices = $hemoServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }
    public function mount(int $id = 0, int $PATIENT_ID = 0, $OUTPUT = true)
    {

        $this->OUTPUT_SIGN = $OUTPUT;
        if ($id > 0) {
            $this->PRE_SIGN_DATA = false;
            $this->FIRST_CASE_RATE = '90935';
            $data = $this->philHealthServices->get($id);
            if ($data) {
                $this->RR_NO = $data->RR_NO ?? '';
                $this->DATE_ADMITTED = $data->DATE_ADMITTED ?? '';
                $this->TIME_ADMITTED = $data->TIME_ADMITTED ? Carbon::createFromFormat('H:i:s', $data->TIME_ADMITTED)->format('h:i A') : '';
                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED ?? '';
                $this->TIME_DISCHARGED = $data->TIME_DISCHARGED ? Carbon::createFromFormat('H:i:s', $data->TIME_DISCHARGED)->format('h:i A') : '';

                $this->LOCATION_ID = $data->LOCATION_ID;


                // number start

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

                $this->P1_SUB_TOTAL = $data->P1_SUB_TOTAL;

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


                // number end

                $fee = $this->philHealthProfFeeServices->getProfFee($id);
                $row = 1;

                foreach ($fee as $list) {
                    switch ($row) {
                        case '1':
                            $this->HCP_1_AN = $list->PIN_NUM;
                            $this->HCP_1_NAME = strtoupper($list->NAME);
                            break;
                        case '2':
                            $this->HCP_2_AN = $list->PIN_NUM;
                            $this->HCP_2_NAME = strtoupper($list->NAME);
                            break;
                        case '3':
                            $this->HCP_3_AN = $list->PIN_NUM;
                            $this->HCP_3_NAME = strtoupper($list->NAME);
                            break;
                        default:
                            # code...
                            break;
                    }

                    $row++;
                }



                $contact = $this->contactServices->get($data->CONTACT_ID, 3);

                if ($contact) {

    
                    $locData = $this->locationServices->get($this->LOCATION_ID);

                    if ($locData) {
                        $this->NAME_OF_BUSINESS = $locData->NAME_OF_BUSINESS;
                        $this->ACCREDITATION_NO = $locData->ACCREDITATION_NO;
                        $this->BLDG_NAME_LOT_BLOCK = $locData->BLDG_NAME_LOT_BLOCK;
                        $this->STREET_SUB_VALL = $locData->STREET_SUB_VALL;
                        $this->BRGY_CITY_MUNI = $locData->BRGY_CITY_MUNI;
                        $this->PROVINCE = $locData->PROVINCE;
                        $this->ZIP_CODE = $locData->ZIP_CODE;
                    }


                    $locDataMgt = $this->locationServices->getPesonel($this->LOCATION_ID);
                    if ($locDataMgt) {
                        if ($locDataMgt->MANAGER_NAME) {
                            $this->HCI_NAME = strtoupper($locDataMgt->MANAGER_NAME) ?? '';
                            $this->HCI_POSITION = strtoupper($locDataMgt->MANAGER_POSITION) ?? '';

                        } else {
                            $userData = $this->contactServices->get(Auth::user()->contact_id, 2);
                            if ($userData) {
                                $this->HCI_NAME = $userData->NAME ?? '';
                                $this->HCI_POSITION = $userData->NICKNAME ?? '';
                            }
                        }
                    }


                    $this->HEIGHT = $contact->HEIGHT ?? 0;
                    $this->PATIENT_LASTNAME = $contact->LAST_NAME;
                    $this->PATIENT_FIRSTNAME = strtoupper($contact->FIRST_NAME);
                    $this->PATIENT_MIDDLENAME = strtoupper($contact->MIDDLE_NAME);
                    $this->PATIENT_EXTENSION = strtoupper($contact->SALUTATION);
                    $this->PATIENT_BIRTH_DATE = $contact->DATE_OF_BIRTH;
                    $this->PATIENT_GENDER = $contact->GENDER;
                    $this->IS_PATIENT = $contact->IS_PATIENT;
                    $this->FINAL_DIAGNOSIS = strtoupper($contact->FINAL_DIAGNOSIS) ?? '';
                    $this->AGE = $this->contactServices->calculateUserAge($this->PATIENT_BIRTH_DATE);

                    if ($this->IS_PATIENT) {
                        $this->MEMBER_FIRST_NAME = strtoupper($contact->FIRST_NAME);
                        $this->MEMBER_LAST_NAME = strtoupper($contact->LAST_NAME);
                        $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MIDDLE_NAME);
                        $this->MEMBER_EXTENSION = strtoupper($contact->SALUTATION);
                        $this->MEMBER_BIRTH_DATE = $contact->DATE_OF_BIRTH;
                        $this->MEMBER_GENDER = $contact->GENDER;
                    } else {
                        $this->MEMBER_FIRST_NAME = strtoupper($contact->MEMBER_FIRST_NAME);
                        $this->MEMBER_LAST_NAME = strtoupper($contact->MEMBER_LAST_NAME);
                        $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MEMBER_MIDDLE_NAME);
                        $this->MEMBER_EXTENSION = strtoupper($contact->MEMBER_EXTENSION);
                        $this->MEMBER_BIRTH_DATE = $contact->MEMBER_BIRTH_DATE;
                        $this->MEMBER_GENDER = $contact->MEMBER_GENDER;
                    }

                    $this->IS_DEPENDENT = $contact->IS_DEPENDENT;
                    $this->PIN = $contact->PIN ?? '';

                    if ($this->IS_DEPENDENT) {
                        $this->PIN_DEPENDENT = $contact->PIN_DEPENDENT;
                    } else {
                        $this->PIN_DEPENDENT = $this->PIN;
                    }

                    $this->IS_PATIENT = $contact->IS_PATIENT;
                    $this->MEMBER_IS_CHILD = $contact->MEMBER_IS_CHILD;
                    $this->MEMBER_IS_PARENT = $contact->MEMBER_IS_PARENT;
                    $this->MEMBER_IS_SPOUSE = $contact->MEMBER_IS_SPOUSE;
                    $this->IS_REPRESENTATIVE = $contact->IS_REPRESENTATIVE;

                    if ($this->IS_REPRESENTATIVE) {
                        $this->NAME_REPRESENTATIVE = strtoupper($contact->CONTACT_PERSON);
                    } else {
                        $this->NAME_REPRESENTATIVE = "";
                    }

                    $this->PEN = $contact->PEN ?? '';
                    $this->PEN_CONTACT = $contact->PEN_CONTACT ?? '';
                    $this->COMPANY_NAME = $contact->COMPANY_NAME ?? '';
                }


                $hemo = $this->hemoServices->GetPost($data->CONTACT_ID, $this->LOCATION_ID, $this->DATE_DISCHARGED);
                if ($hemo) {
                    $this->POST_WEIGHT = $hemo->POST_WEIGHT;
                    $this->POST_BLOOD_PRESSURE = $hemo->POST_BLOOD_PRESSURE;
                    $this->POST_HEART_RATE = $hemo->POST_HEART_RATE;
                    $this->POST_O2_SATURATION = $hemo->POST_O2_SATURATION;
                    $this->POST_TEMPERATURE = $hemo->POST_TEMPERATURE;
                    $this->POST_BLOOD_PRESSURE2 = $hemo->POST_BLOOD_PRESSURE2;
                }
            }
        }
        if ($PATIENT_ID > 0) {
            $this->PRE_SIGN_DATA = true;

            $contact = $this->contactServices->get($PATIENT_ID, 3);

            if ($contact) {

                $this->LOCATION_ID = $contact->LOCATION_ID;
                $locData = $this->locationServices->get($this->LOCATION_ID);

                if ($locData) {
                    $this->NAME_OF_BUSINESS = $locData->NAME_OF_BUSINESS;
                    $this->ACCREDITATION_NO = $locData->ACCREDITATION_NO;
                    $this->BLDG_NAME_LOT_BLOCK = $locData->BLDG_NAME_LOT_BLOCK;
                    $this->STREET_SUB_VALL = $locData->STREET_SUB_VALL;
                    $this->BRGY_CITY_MUNI = $locData->BRGY_CITY_MUNI;
                    $this->PROVINCE = $locData->PROVINCE;
                    $this->ZIP_CODE = $locData->ZIP_CODE;
                }


                $this->HEIGHT = $contact->HEIGHT ?? 0;
                $this->PATIENT_LASTNAME = $contact->LAST_NAME;
                $this->PATIENT_FIRSTNAME = strtoupper($contact->FIRST_NAME);
                $this->PATIENT_MIDDLENAME = strtoupper($contact->MIDDLE_NAME);
                $this->PATIENT_EXTENSION = strtoupper($contact->SALUTATION);
                $this->PATIENT_BIRTH_DATE = $contact->DATE_OF_BIRTH;
                $this->PATIENT_GENDER = $contact->GENDER;
                $this->IS_PATIENT = $contact->IS_PATIENT;
                $this->FINAL_DIAGNOSIS = strtoupper($contact->FINAL_DIAGNOSIS) ?? '';
                $this->AGE = $this->contactServices->calculateUserAge($this->PATIENT_BIRTH_DATE);

                if ($this->IS_PATIENT) {
                    $this->MEMBER_FIRST_NAME = strtoupper($contact->FIRST_NAME);
                    $this->MEMBER_LAST_NAME = strtoupper($contact->LAST_NAME);
                    $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MIDDLE_NAME);
                    $this->MEMBER_EXTENSION = strtoupper($contact->SALUTATION);
                    $this->MEMBER_BIRTH_DATE = $contact->DATE_OF_BIRTH;
                    $this->MEMBER_GENDER = $contact->GENDER;
                } else {
                    $this->MEMBER_FIRST_NAME = strtoupper($contact->MEMBER_FIRST_NAME);
                    $this->MEMBER_LAST_NAME = strtoupper($contact->MEMBER_LAST_NAME);
                    $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MEMBER_MIDDLE_NAME);
                    $this->MEMBER_EXTENSION = strtoupper($contact->MEMBER_EXTENSION);
                    $this->MEMBER_BIRTH_DATE = $contact->MEMBER_BIRTH_DATE;
                    $this->MEMBER_GENDER = $contact->MEMBER_GENDER;
                }

                $this->IS_DEPENDENT = $contact->IS_DEPENDENT;
                $this->PIN = $contact->PIN ?? '';

                if ($this->IS_DEPENDENT) {
                    $this->PIN_DEPENDENT = $contact->PIN_DEPENDENT;
                } else {
                    $this->PIN_DEPENDENT = $this->PIN;
                }

                $this->IS_PATIENT = $contact->IS_PATIENT;
                $this->MEMBER_IS_CHILD = $contact->MEMBER_IS_CHILD;
                $this->MEMBER_IS_PARENT = $contact->MEMBER_IS_PARENT;
                $this->MEMBER_IS_SPOUSE = $contact->MEMBER_IS_SPOUSE;
                $this->IS_REPRESENTATIVE = $contact->IS_REPRESENTATIVE;

                if ($this->IS_REPRESENTATIVE) {
                    $this->NAME_REPRESENTATIVE = strtoupper($contact->CONTACT_PERSON);
                } else {
                    $this->NAME_REPRESENTATIVE = "";
                }

                $this->PEN = $contact->PEN ?? '';
                $this->PEN_CONTACT = $contact->PEN_CONTACT ?? '';
                $this->COMPANY_NAME = $contact->COMPANY_NAME ?? '';
            }
        }
    }
    public function render()
    {
        return view('livewire.phil-health.print-cf2-back');
    }
}
