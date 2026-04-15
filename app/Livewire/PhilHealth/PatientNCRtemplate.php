<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use App\Services\PhilHealthSoaCustomServices;
use Carbon\Carbon;
use Livewire\Component;

class PatientNCRtemplate extends Component
{


    public int $LOCATION_ID;
    public string $PIN;
    public string $PEN;
    public string $EMAIL;
     public string $MOBILE_NO;
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
    public bool $PRE_SIGN_DATA =  false;
    public bool $OUTPUT_SIGN = false;
    public string $RR_NO;

    public string $CF4_AD_NOTES;
    public string $CF4_DD_NOTES;
    public string $CF4_COMPLAINT;
    public string $CF4_HPI;
    public string $CF4_PPMH;

    private $philHealthServices;
    private $contactServices;
    private $locationServices;
    private $hemoServices;

    public $TIME_HIDE;
    public bool $IS_HIDE = false;
    private $philHealthSoaCustomServices;
    private $philHealthProfFeeServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        HemoServices $hemoServices,
        PhilHealthSoaCustomServices $philHealthSoaCustomServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices
    ) {
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->hemoServices = $hemoServices;
        $this->philHealthSoaCustomServices = $philHealthSoaCustomServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }
    private function gotHide()
    {
        $cusFirst =   $this->philHealthSoaCustomServices->GetFirst($this->LOCATION_ID);
        if ($cusFirst) {
            if ($cusFirst->HIDE_FEE > 0) {
                $this->IS_HIDE = true;
            }
        }
    }
    public function mount(int $id = 0,  int $PATIENT_ID = 0, $OUTPUT = true)
    {


        $this->OUTPUT_SIGN = $OUTPUT;
        if ($id > 0) {
            
            $this->PRE_SIGN_DATA =  false;
            $this->FIRST_CASE_RATE = $this->philHealthServices->FIRST_CASE_RATE;
            $data = $this->philHealthServices->get($id);
            if ($data) {
                $this->LOCATION_ID = $data->LOCATION_ID;
                $this->gotHide();

                $this->RR_NO = $data->RR_NO ?? '';
                $this->CF4_AD_NOTES = $data->CF4_AD_NOTES ?? '';
                $this->CF4_DD_NOTES = $data->CF4_DD_NOTES ?? '';
                $this->CF4_COMPLAINT = $data->CF4_COMPLAINT ?? '';
                $this->CF4_HPI = $data->CF4_HPI ?? '';
                $this->CF4_PPMH = $data->CF4_PPMH ?? '';
                $this->TIME_HIDE = $data->TIME_HIDE ?? $data->TIME_DISCHARGED;
                $this->DATE_ADMITTED = $data->DATE_ADMITTED ?? '';
                $this->TIME_ADMITTED = $data->TIME_ADMITTED ? Carbon::createFromFormat('H:i:s', $data->TIME_ADMITTED)->format('h:i A') : '';
                $this->DATE_DISCHARGED = $data->DATE_DISCHARGED ?? '';
                $this->TIME_DISCHARGED = $data->TIME_DISCHARGED ? Carbon::createFromFormat('H:i:s', $this->IS_HIDE ? $this->TIME_HIDE : $data->TIME_DISCHARGED)->format('h:i A') : '';

                $fee = $this->philHealthProfFeeServices->getProfFee($id);
                $row = 1;

                foreach ($fee as $list) {
                    switch ($row) {
                        case '1':
                            $this->HCP_1_AN = $list->PIN;
                            $this->HCP_1_NAME = strtoupper($list->NAME);
                            break;
                        case '2':
                            $this->HCP_2_AN = $list->PIN;
                            $this->HCP_2_NAME = strtoupper($list->NAME);
                            break;
                        case '3':
                            $this->HCP_3_AN = $list->PIN;
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
                    // $this->LOCATION_ID = $contact->LOCATION_ID;
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
                    $this->FINAL_DIAGNOSIS =  strtoupper($contact->FINAL_DIAGNOSIS) ?? '';
                    $this->AGE = $this->contactServices->calculateUserAge($this->PATIENT_BIRTH_DATE);

                    if ($this->IS_PATIENT) {
                        $this->MEMBER_FIRST_NAME = strtoupper($contact->FIRST_NAME);
                        $this->MEMBER_LAST_NAME = strtoupper($contact->LAST_NAME);
                        $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MIDDLE_NAME);
                        $this->MEMBER_EXTENSION =  strtoupper($contact->SALUTATION);
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
                    $this->EMAIL = $contact->EMAIL ?? '';
                     $this->MOBILE_NO = $contact->MOBILE_NO ?? '';
                    
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
                    $this->POST_HEART_RATE =  $hemo->POST_HEART_RATE;
                    $this->POST_O2_SATURATION =  $hemo->POST_O2_SATURATION;
                    $this->POST_TEMPERATURE = $hemo->POST_TEMPERATURE;
                    $this->POST_BLOOD_PRESSURE2 =  $hemo->POST_BLOOD_PRESSURE2;
                }
            }
            return;
        }

        if ($PATIENT_ID > 0) {
            $this->PRE_SIGN_DATA =  true;

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
                $this->FINAL_DIAGNOSIS =  strtoupper($contact->FINAL_DIAGNOSIS) ?? '';
                $this->AGE = $this->contactServices->calculateUserAge($this->PATIENT_BIRTH_DATE);

                if ($this->IS_PATIENT) {
                    $this->MEMBER_FIRST_NAME = strtoupper($contact->FIRST_NAME);
                    $this->MEMBER_LAST_NAME = strtoupper($contact->LAST_NAME);
                    $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MIDDLE_NAME);
                    $this->MEMBER_EXTENSION =  strtoupper($contact->SALUTATION);
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
                $this->EMAIL = $contact->EMAIL ?? '';
  $this->MOBILE_NO = $contact->MOBILE_NO ?? '';
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
        return view('livewire.phil-health.print-ncr');
    }
}
