<?php
namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\PhilHealthProfFeeServices;
use App\Services\PhilHealthServices;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintCsf extends Component
{

    public int $LOCATION_ID;
    public string $PIN;
    public string $PEN;
    public string $PIN_DEPENDENT;
    public string $AUTORIZE_REP_NAME1;
    public string $AUTORIZE_REP_NAME2;
    public bool $MEMBER_RELATIONSHIP_CHILD  = false;
    public bool $MEMBER_RELATIONSHIP_PARENT = false;
    public bool $MEMBER_RELATIONSHIP_SPOUSE = false;
    public string $PATIENT_LASTNAME;
    public string $PATIENT_FIRSTNAME;
    public string $PATIENT_MIDDLENAME;
    public string $PATIENT_EXTENSION;
    public string $PATIENT_BIRTH_DATE;
    public int $PATIENT_GENEDER;
    public string $DATE_ADMITTED;
    public string $DATE_DISCHARGED;
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
    public string $HCI_NAME;
    public string $HCI_POSITION;
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
    public string $MEMBER_POSITION;
    public bool $PRE_SIGN_DATA = false;
    public bool $OUTPUT_SIGN   = false;
    private $philHealthServices;
    private $contactServices;
    private $locationServices;
    private $patientDoctorServices;
    private $philHealthProfFeeServices;
    public function boot(
        PhilHealthServices $philHealthServices,
        ContactServices $contactServices,
        LocationServices $locationServices,
        PatientDoctorServices $patientDoctorServices,
        PhilHealthProfFeeServices $philHealthProfFeeServices
    ) {
        $this->philHealthServices        = $philHealthServices;
        $this->contactServices           = $contactServices;
        $this->locationServices          = $locationServices;
        $this->patientDoctorServices     = $patientDoctorServices;
        $this->philHealthProfFeeServices = $philHealthProfFeeServices;
    }
    public function mount(int $id = 0, int $PATIENT_ID = 0, bool $OUTPUT = true)
    {
        $this->OUTPUT_SIGN = $OUTPUT;

        if ($id > 0) {
            $this->PRE_SIGN_DATA = false;
            $data                = $this->philHealthServices->get($id);

            if ($data) {
                $this->DATE_ADMITTED    = $data->DATE_ADMITTED ?? '';
                $this->DATE_DISCHARGED  = $data->DATE_DISCHARGED ?? '';
                $this->FIRST_CASE_RATE  = $data->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $data->SECOND_CASE_RATE ?? '';
                $this->LOCATION_ID      = $data->LOCATION_ID;
                $fee                    = $this->philHealthProfFeeServices->getProfFee($id);
                $row                    = 1;
                foreach ($fee as $list) {
                    switch ($row) {
                        case '1':
                            $this->HCP_1_AN   = str_replace('-', '', $list->PIN);
                            $this->HCP_1_NAME = strtoupper($list->NAME);
                            break;
                        case '2':
                            $this->HCP_2_AN   = str_replace($list->PIN, '-', '');
                            $this->HCP_2_NAME = strtoupper($list->NAME);
                            break;
                        case '3':
                            $this->HCP_3_AN   = $list->PIN;
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
                    $this->AUTORIZE_REP_NAME1 = $contact->CUSTOM_FIELD5 ?? '';
                    $this->AUTORIZE_REP_NAME2 = $contact->CUSTOM_FIELD4 ?? '';
                    $this->PATIENT_LASTNAME   = strtoupper($contact->LAST_NAME);
                    $this->PATIENT_FIRSTNAME  = strtoupper($contact->FIRST_NAME);
                    $this->PATIENT_MIDDLENAME = strtoupper($contact->MIDDLE_NAME);
                    $this->PATIENT_EXTENSION  = strtoupper($contact->SALUTATION);
                    $this->PATIENT_BIRTH_DATE = $contact->DATE_OF_BIRTH;

                    $this->IS_PATIENT = (bool) $contact->IS_PATIENT;
                    if ($this->IS_PATIENT) {
                        $this->MEMBER_FIRST_NAME  = strtoupper($contact->FIRST_NAME);
                        $this->MEMBER_LAST_NAME   = strtoupper($contact->LAST_NAME);
                        $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MIDDLE_NAME);
                        $this->MEMBER_EXTENSION   = strtoupper($contact->SALUTATION);
                        $this->MEMBER_BIRTH_DATE  = $contact->DATE_OF_BIRTH;
                        $this->MEMBER_GENDER      = $contact->GENDER;
                    } else {
                        $this->MEMBER_FIRST_NAME  = strtoupper($contact->MEMBER_FIRST_NAME);
                        $this->MEMBER_LAST_NAME   = strtoupper($contact->MEMBER_LAST_NAME);
                        $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MEMBER_MIDDLE_NAME);
                        $this->MEMBER_EXTENSION   = strtoupper($contact->MEMBER_EXTENSION);
                        $this->MEMBER_BIRTH_DATE  = $contact->MEMBER_BIRTH_DATE;
                        $this->MEMBER_GENDER      = $contact->MEMBER_GENDER;
                    }
                    $this->MEMBER_POSITION = $contact->CUSTOM_FIELD3 ?? '';
                    $this->IS_DEPENDENT    = $contact->IS_DEPENDENT;
                    $this->PIN             = $contact->PIN ?? '';
                    if ($this->IS_DEPENDENT) {
                        $this->PIN_DEPENDENT = $contact->PIN_DEPENDENT;
                    } else {
                        $this->PIN_DEPENDENT = $this->PIN;
                    }

                    $this->MEMBER_IS_CHILD   = $contact->MEMBER_IS_CHILD;
                    $this->MEMBER_IS_PARENT  = $contact->MEMBER_IS_PARENT;
                    $this->MEMBER_IS_SPOUSE  = $contact->MEMBER_IS_SPOUSE;
                    $this->IS_REPRESENTATIVE = $contact->IS_REPRESENTATIVE;

                    if ($this->IS_REPRESENTATIVE) {
                        $this->NAME_REPRESENTATIVE = $contact->CONTACT_PERSON;
                    } else {
                        $this->NAME_REPRESENTATIVE = "";
                    }

                    $this->PEN              = $contact->PEN ?? '';
                    $this->PEN_CONTACT      = $contact->PEN_CONTACT ?? '';
                    $this->COMPANY_NAME     = $contact->COMPANY_NAME ?? '';
                    $this->FIRST_CASE_RATE  = $contact->FIRST_CASE_RATE ?? '';
                    $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';

                    $locData = $this->locationServices->getPesonel($this->LOCATION_ID);
                    if ($locData) {
                        if ($locData->MANAGER_NAME) {
                            $this->HCI_NAME     = strtoupper($locData->MANAGER_NAME) ?? '';
                            $this->HCI_POSITION = strtoupper($locData->MANAGER_POSITION) ?? '';

                        } else {
                            $userData = $this->contactServices->get(Auth::user()->contact_id, 2);
                            if ($userData) {
                                $this->HCI_NAME     = $userData->NAME ?? '';
                                $this->HCI_POSITION = $userData->NICKNAME ?? '';
                            }
                        }
                    }
                }
            }
        }

        if ($PATIENT_ID > 0) {
            $this->PRE_SIGN_DATA = true;
            $contact             = $this->contactServices->get($PATIENT_ID, 3);
            if ($contact) {
                $this->AUTORIZE_REP_NAME1 = $contact->CUSTOM_FIELD5 ?? '';
                $this->AUTORIZE_REP_NAME2 = $contact->CUSTOM_FIELD4 ?? '';

                $this->PATIENT_LASTNAME   = strtoupper($contact->LAST_NAME);
                $this->PATIENT_FIRSTNAME  = strtoupper($contact->FIRST_NAME);
                $this->PATIENT_MIDDLENAME = strtoupper($contact->MIDDLE_NAME);
                $this->PATIENT_EXTENSION  = strtoupper($contact->SALUTATION);
                $this->PATIENT_BIRTH_DATE = $contact->DATE_OF_BIRTH;

                $this->IS_PATIENT = $contact->IS_PATIENT;
                if ($this->IS_PATIENT) {
                    $this->MEMBER_FIRST_NAME  = strtoupper($contact->FIRST_NAME);
                    $this->MEMBER_LAST_NAME   = strtoupper($contact->LAST_NAME);
                    $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MIDDLE_NAME);
                    $this->MEMBER_EXTENSION   = strtoupper($contact->SALUTATION);
                    $this->MEMBER_BIRTH_DATE  = $contact->DATE_OF_BIRTH;
                    $this->MEMBER_GENDER      = $contact->GENDER;
                } else {
                    $this->MEMBER_FIRST_NAME  = strtoupper($contact->MEMBER_FIRST_NAME);
                    $this->MEMBER_LAST_NAME   = strtoupper($contact->MEMBER_LAST_NAME);
                    $this->MEMBER_MIDDLE_NAME = strtoupper($contact->MEMBER_MIDDLE_NAME);
                    $this->MEMBER_EXTENSION   = strtoupper($contact->MEMBER_EXTENSION);
                    $this->MEMBER_BIRTH_DATE  = $contact->MEMBER_BIRTH_DATE;
                    $this->MEMBER_GENDER      = $contact->MEMBER_GENDER;
                }
                $this->MEMBER_POSITION = $contact->CUSTOM_FIELD3 ?? '';
                $this->IS_DEPENDENT    = $contact->IS_DEPENDENT;
                $this->PIN             = $contact->PIN ?? '';
                if ($this->IS_DEPENDENT) {
                    $this->PIN_DEPENDENT = $contact->PIN_DEPENDENT;
                } else {
                    $this->PIN_DEPENDENT = $this->PIN;
                }

                $this->IS_PATIENT = $contact->IS_PATIENT;

                $this->MEMBER_IS_CHILD   = $contact->MEMBER_IS_CHILD;
                $this->MEMBER_IS_PARENT  = $contact->MEMBER_IS_PARENT;
                $this->MEMBER_IS_SPOUSE  = $contact->MEMBER_IS_SPOUSE;
                $this->IS_REPRESENTATIVE = $contact->IS_REPRESENTATIVE;

                if ($this->IS_REPRESENTATIVE) {
                    $this->NAME_REPRESENTATIVE = $contact->CONTACT_PERSON;
                } else {
                    $this->NAME_REPRESENTATIVE = "";
                }

                $this->PEN              = $contact->PEN ?? '';
                $this->PEN_CONTACT      = $contact->PEN_CONTACT ?? '';
                $this->COMPANY_NAME     = $contact->COMPANY_NAME ?? '';
                $this->FIRST_CASE_RATE  = $contact->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
                $locData                = $this->locationServices->getPesonel($contact->LOCATION_ID);
                if ($locData) {
                    $this->HCI_NAME     = strtoupper($locData->MANAGER_NAME) ?? '';
                    $this->HCI_POSITION = strtoupper($locData->MANAGER_POSITION) ?? '';
                }

                $fee = $this->patientDoctorServices->GetList($PATIENT_ID, $contact->LOCATION_ID);
                $row = 1;
                foreach ($fee as $list) {
                    switch ($row) {
                        case '1':
                            $this->HCP_1_AN   = str_replace('-', '', $list->PIN);
                            $this->HCP_1_NAME = strtoupper($list->NAME);
                            break;
                        case '2':
                            $this->HCP_2_AN   = str_replace($list->PIN, '-', '');
                            $this->HCP_2_NAME = strtoupper($list->NAME);
                            break;
                        case '3':
                            $this->HCP_3_AN   = $list->PIN;
                            $this->HCP_3_NAME = strtoupper($list->NAME);
                            break;
                        default:
                            # code...
                            break;
                    }

                    $row++;
                }

            }
        }
    }

    public function render()
    {
        return view('livewire.phil-health.print-csf');
    }
}
