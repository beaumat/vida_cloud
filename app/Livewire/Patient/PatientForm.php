<?php

namespace App\Livewire\Patient;

use App\Models\ContactGroup;
use App\Models\Contacts;
use App\Models\HemodialysisMachines;
use App\Models\PatientStatus;
use App\Models\PaymentMethods;
use App\Models\PaymentTerms;
use App\Models\PriceLevels;
use App\Models\ScheduleType;
use App\Models\Tax;
use App\Services\ContactRequirementServices;
use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\LocationServices;
use App\Services\PatientClassServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;



#[Title('Patients')]
class PatientForm extends Component
{

    public bool $modify = false;
    public int $ID;
    public int $TYPE = 3;
    public string $NAME;
    public string $COMPANY_NAME;
    public string $SALUTATION;
    public string $FIRST_NAME;
    public string $MIDDLE_NAME;
    public string $LAST_NAME;
    public string $PRINT_NAME_AS;
    public string $POSTAL_ADDRESS;
    public string $CONTACT_PERSON;
    public string $TELEPHONE_NO;
    public string $FAX_NO;
    public string $MOBILE_NO;
    public string $ALT_TELEPHONE_NO;
    public string $ALT_CONTACT_PERSON;
    public string $EMAIL;
    public string $ACCOUNT_NO;
    public bool $INACTIVE;
    public int $GROUP_ID;
    public int $PAYMENT_TERMS_ID;
    public float $CREDIT_LIMIT;
    public int $PREF_PAYMENT_METHOD_ID;
    public string $CREDIT_CARD_NO;
    public string $CREDIT_CARD_EXPIRY_DATE;
    public int $SALES_REP_ID;
    public int $PRICE_LEVEL_ID;
    public string $TAXPAYER_ID;
    public int $TAX_ID;
    public int $EW_TAX_ID;
    public string $SSS_NO;
    public int $GENDER;
    public string $DATE_OF_BIRTH;
    public string $NICKNAME;
    public string $HIRE_DATE;
    public $taxList = [];
    public $contactGroup = [];
    public $paymentTermList = [];
    public $salesMan = [];
    public $paymentMethod = [];
    public $priceLevels = [];
    public $age = null;
    public $memberage = null;
    public int $LOCATION_ID;
    public int $SCHEDULE_TYPE;
    public $scheduleTypeList = [];
    public $locationList = [];
    public int $PATIENT_TYPE_ID;
    public int $PATIENT_STATUS_ID;
    public bool $ADMITTED;
    public bool $LONG_HRS_DURATION;
    public string $ADDRESS_UNIT_ROOM_FLOOR;
    public string $ADDRESS_BUILDING_NAME;
    public string $ADDRESS_LOT_BLK_HOUSE_BLDG;
    public string $ADDRESS_STREET;
    public string $ADDRESS_SUB_VALL;
    public string $ADDRESS_BRGY;
    public string $ADDRESS_CITY_MUNI;
    public string $ADDRESS_PROVINCE;
    public string $ADDRESS_COUNTRY;
    public string $ADDRESS_ZIP_CODE;
    public string $PIN = '';
    public string $PEN = '';
    public bool $IS_PATIENT = true;
    public string $MEMBER_TEL_NO;
    public string $MEMBER_MOBILE;
    public string $MEMBER_EMAIL;
    public string $MEMBER_FIRST_NAME;
    public string $MEMBER_LAST_NAME;
    public string $MEMBER_MIDDLE_NAME;
    public string $MEMBER_EXTENSION;
    public $MEMBER_BIRTH_DATE;
    public int $MEMBER_GENDER;
    public string $MEMBER_UNIT_ROOM_FLOOR;
    public string $MEMBER_BUILDING_NAME;
    public string $MEMBER_LOT_BLK_HOUSE_BLDG;
    public string $MEMBER_STREET;
    public string $MEMBER_SUB_VALL;
    public string $MEMBER_BRGY;
    public string $MEMBER_CITY_MUNI;
    public string $MEMBER_PROVINCE;
    public string $MEMBER_COUNTRY;
    public string $MEMBER_ZIP_CODE;
    public bool $IS_REPRESENTATIVE;
    public bool $MEMBER_IS_CHILD;
    public bool $MEMBER_IS_PARENT;
    public bool $MEMBER_IS_SPOUSE;
    public string $PEN_CONTACT;
    public string $FIRST_CASE_RATE;
    public string $SECOND_CASE_RATE;
    public string $FINAL_DIAGNOSIS;
    public string $OTHER_DIAGNOSIS;
    public string $PIN_DEPENDENT;
    public bool $IS_DEPENDENT;
    public float $HEIGHT;
    public string $CUSTOM_FIELD2;
    public string $CUSTOM_FIELD3;
    public string $CUSTOM_FIELD4;
    public string $CUSTOM_FIELD5;
    public $DATE_EXPIRED;
    public int $CLASS_ID;
    public $patientClassList = [];
    public function updateddateofbirth()
    {
        $this->age = $this->contactServices->calculateUserAge($this->DATE_OF_BIRTH);
    }
    public function updatedMEMBERBIRTHDATE()
    {
        $this->memberage = $this->contactServices->calculateUserAge($this->MEMBER_BIRTH_DATE);
    }

    public string $selectTab = 'gen';
    public function SelectTab($tab)
    {
        $this->selectTab = $tab;
    }
    private $contactServices;
    private $locationServices;
    private $userServices;
    private $contactRequirementServices;
    private $dateServices;
    public $patientTypeList = [];
    public $patientStatusList = [];
    public string $DATE_ADMISSION;

    public int $LOCK_LOCATION_ID = 0;
    private $patientClassServices;
    public function boot(
        ContactServices $contactServices,
        LocationServices $locationServices,
        UserServices $userServices,
        ContactRequirementServices $contactRequirementServices,
        DateServices $dateServices,
        PatientClassServices $patientClassServices
    ) {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->userServices = $userServices;
        $this->contactRequirementServices = $contactRequirementServices;
        $this->dateServices = $dateServices;
        $this->patientClassServices = $patientClassServices;
    }

    public $refreshToggle = false;

    #[On('refresh-requirements')]
    public function refreshComponent()
    {
        $this->refreshToggle = !$this->refreshToggle;
    }
    public function mount($id = null, $locationId = null)
    {
        if ($locationId) {
            $this->LOCK_LOCATION_ID = (int) $locationId;
        } else {
            if ($this->userServices->isLocationLock()) {
                $this->LOCK_LOCATION_ID = $this->userServices->getLocationDefault();
            }

        }

        $this->patientClassList = $this->patientClassServices->getList();
        $this->taxList = Tax::query()->select('ID', 'NAME')->where('TAX_TYPE', '=', 3)->orderBy('ID', 'desc')->get();
        $this->salesMan = Contacts::query()->select('ID', 'NAME')->where('INACTIVE', '0')->where('TYPE', '2')->get();
        $this->contactGroup = ContactGroup::query()->where('TYPE', $this->TYPE)->get();
        $this->paymentTermList = PaymentTerms::query()->select('ID', 'DESCRIPTION')->where('INACTIVE', '0')->get();
        $this->paymentMethod = PaymentMethods::query()->select("ID", 'DESCRIPTION')->get();
        $this->priceLevels = PriceLevels::query()->select('ID', 'DESCRIPTION')->where('INACTIVE', '0')->get();
        $this->locationList = $this->locationServices->getList();
        $this->scheduleTypeList = ScheduleType::all();
        $this->patientTypeList = HemodialysisMachines::select(['ID', 'DESCRIPTION'])->get();
        $this->patientStatusList = PatientStatus::all();

        if (is_numeric($id)) {
            $contact = $this->contactServices->get($id, $this->TYPE);
            if ($contact) {
                $this->LOCATION_ID = $locationId > 0 ? $locationId : $contact->LOCATION_ID;
                $this->TYPE = $contact->TYPE ?? 3;
                $this->ID = $contact->ID;
                $this->NAME = $contact->NAME;
                $this->COMPANY_NAME = $contact->COMPANY_NAME ? $contact->COMPANY_NAME : '';
                $this->SALUTATION = $contact->SALUTATION ? $contact->SALUTATION : '';
                $this->FIRST_NAME = $contact->FIRST_NAME ? $contact->FIRST_NAME : '';
                $this->MIDDLE_NAME = $contact->MIDDLE_NAME ? $contact->MIDDLE_NAME : '';
                $this->LAST_NAME = $contact->LAST_NAME ? $contact->LAST_NAME : '';
                $this->PRINT_NAME_AS = $contact->PRINT_NAME_AS ? $contact->PRINT_NAME_AS : '';
                $this->POSTAL_ADDRESS = $contact->POSTAL_ADDRESS ? $contact->POSTAL_ADDRESS : '';
                $this->CONTACT_PERSON = $contact->CONTACT_PERSON ? $contact->CONTACT_PERSON : '';
                $this->TELEPHONE_NO = $contact->TELEPHONE_NO ? $contact->TELEPHONE_NO : '';
                $this->FAX_NO = $contact->FAX_NO ? $contact->FAX_NO : '';
                $this->MOBILE_NO = $contact->MOBILE_NO ? $contact->MOBILE_NO : '';
                $this->ALT_TELEPHONE_NO = $contact->ALT_TELEPHONE_NO ? $contact->ALT_TELEPHONE_NO : '';
                $this->ALT_CONTACT_PERSON = $contact->ALT_CONTACT_PERSON ? $contact->ALT_CONTACT_PERSON : '';
                $this->EMAIL = $contact->EMAIL ? $contact->EMAIL : '';
                $this->ACCOUNT_NO = $contact->ACCOUNT_NO ? $contact->ACCOUNT_NO : '';
                $this->INACTIVE = $contact->INACTIVE;
                $this->GROUP_ID = $contact->GROUP_ID ? $contact->GROUP_ID : 0;
                $this->PAYMENT_TERMS_ID = $contact->PAYMENT_TERMS_ID ? $contact->PAYMENT_TERMS_ID : 0;
                $this->CREDIT_LIMIT = $contact->CREDIT_LIMIT ? $contact->CREDIT_LIMIT : 0;
                $this->PREF_PAYMENT_METHOD_ID = $contact->PREF_PAYMENT_METHOD_ID ? $contact->PREF_PAYMENT_METHOD_ID : 0;
                $this->CREDIT_CARD_NO = $contact->CREDIT_CARD_NO ? $contact->CREDIT_CARD_NO : '';
                $this->CREDIT_CARD_EXPIRY_DATE = $contact->CREDIT_CARD_EXPIRY_DATE ? $contact->CREDIT_CARD_EXPIRY_DATE : '';
                $this->SALES_REP_ID = $contact->SALES_REP_ID ? $contact->SALES_REP_ID : 0;
                $this->PRICE_LEVEL_ID = $contact->PRICE_LEVEL_ID ? $contact->PRICE_LEVEL_ID : 0;
                $this->TAXPAYER_ID = $contact->TAXPAYER_ID ? $contact->TAXPAYER_ID : '';
                $this->TAX_ID = $contact->TAX_ID ? $contact->TAX_ID : 0;
                $this->EW_TAX_ID = $contact->EW_TAX_ID ? $contact->EW_TAX_ID : 0;
                $this->SSS_NO = $contact->SSS_NO ? $contact->SSS_NO : 0;
                $this->GENDER = $contact->GENDER ? $contact->GENDER : 0;
                $this->DATE_OF_BIRTH = $contact->DATE_OF_BIRTH ? $contact->DATE_OF_BIRTH : '';
                $this->NICKNAME = $contact->NICKNAME ? $contact->NICKNAME : '';
                $this->HIRE_DATE = $contact->HIRE_DATE ? $contact->HIRE_DATE : '';


                $this->PATIENT_TYPE_ID = 1; // $contact->PATIENT_TYPE_ID ?? 1;
                $this->PATIENT_STATUS_ID = $contact->PATIENT_STATUS_ID ?? 0;
                $this->ADMITTED = $contact->ADMITTED ? $contact->ADMITTED : false;
                $this->LONG_HRS_DURATION = $contact->LONG_HRS_DURATION ? $contact->LONG_HRS_DURATION : false;
                $this->DATE_ADMISSION = $contact->DATE_ADMISSION ? $contact->DATE_ADMISSION : '';
                $this->DATE_EXPIRED = $contact->DATE_EXPIRED ? $contact->DATE_EXPIRED : null;
                $this->CLASS_ID = $contact->CLASS_ID ? $contact->CLASS_ID : 0;
                $this->ADDRESS_UNIT_ROOM_FLOOR = $contact->ADDRESS_UNIT_ROOM_FLOOR ?? '';
                $this->ADDRESS_BUILDING_NAME = $contact->ADDRESS_BUILDING_NAME ?? '';
                $this->ADDRESS_LOT_BLK_HOUSE_BLDG = $contact->ADDRESS_LOT_BLK_HOUSE_BLDG ?? '';
                $this->ADDRESS_STREET = $contact->ADDRESS_STREET ?? '';
                $this->ADDRESS_SUB_VALL = $contact->ADDRESS_SUB_VALL ?? '';
                $this->ADDRESS_BRGY = $contact->ADDRESS_BRGY ?? '';
                $this->ADDRESS_CITY_MUNI = $contact->ADDRESS_CITY_MUNI ?? '';
                $this->ADDRESS_PROVINCE = $contact->ADDRESS_PROVINCE ?? '';
                $this->ADDRESS_COUNTRY = $contact->ADDRESS_COUNTRY ?? '';
                $this->ADDRESS_ZIP_CODE = $contact->ADDRESS_ZIP_CODE ?? '';
                $this->PIN = $contact->PIN ?? '';
                $this->PEN = $contact->PEN ?? '';
                $this->IS_PATIENT = $contact->IS_PATIENT ?? true;
                $this->MEMBER_TEL_NO = $contact->MEMBER_TEL_NO ?? '';
                $this->MEMBER_MOBILE = $contact->MEMBER_MOBILE ?? '';
                $this->MEMBER_EMAIL = $contact->MEMBER_EMAIL ?? '';
                $this->MEMBER_FIRST_NAME = $contact->MEMBER_FIRST_NAME ?? '';
                $this->MEMBER_LAST_NAME = $contact->MEMBER_LAST_NAME ?? '';
                $this->MEMBER_MIDDLE_NAME = $contact->MEMBER_MIDDLE_NAME ?? '';
                $this->MEMBER_EXTENSION = $contact->MEMBER_EXTENSION ?? '';
                $this->MEMBER_BIRTH_DATE = $contact->MEMBER_BIRTH_DATE ?? null;
                $this->MEMBER_GENDER = $contact->MEMBER_GENDER ?? 0;

                $this->MEMBER_UNIT_ROOM_FLOOR = $contact->MEMBER_UNIT_ROOM_FLOOR ?? '';
                $this->MEMBER_BUILDING_NAME = $contact->MEMBER_BUILDING_NAME ?? '';
                $this->MEMBER_LOT_BLK_HOUSE_BLDG = $contact->MEMBER_LOT_BLK_HOUSE_BLDG ?? '';
                $this->MEMBER_STREET = $contact->MEMBER_STREET ?? '';
                $this->MEMBER_SUB_VALL = $contact->MEMBER_SUB_VALL ?? '';
                $this->MEMBER_BRGY = $contact->MEMBER_BRGY ?? '';
                $this->MEMBER_CITY_MUNI = $contact->MEMBER_CITY_MUNI ?? '';
                $this->MEMBER_PROVINCE = $contact->MEMBER_PROVINCE ?? '';
                $this->MEMBER_COUNTRY = $contact->MEMBER_COUNTRY ?? '';
                $this->MEMBER_ZIP_CODE = $contact->MEMBER_ZIP_CODE ?? '';

                $this->IS_REPRESENTATIVE = $contact->IS_REPRESENTATIVE ?? false;
                $this->MEMBER_IS_CHILD = $contact->MEMBER_IS_CHILD ?? false;
                $this->MEMBER_IS_PARENT = $contact->MEMBER_IS_PARENT ?? false;
                $this->MEMBER_IS_SPOUSE = $contact->MEMBER_IS_SPOUSE ?? false;

                $this->PEN_CONTACT = $contact->PEN_CONTACT ?? '';
                $this->FIRST_CASE_RATE = $contact->FIRST_CASE_RATE ?? '';
                $this->SECOND_CASE_RATE = $contact->SECOND_CASE_RATE ?? '';
                $this->FINAL_DIAGNOSIS = $contact->FINAL_DIAGNOSIS ?? '';
                $this->OTHER_DIAGNOSIS = $contact->OTHER_DIAGNOSIS ?? '';

                $this->PIN_DEPENDENT = $contact->PIN_DEPENDENT ?? '';
                $this->IS_DEPENDENT = $contact->IS_DEPENDENT ?? false;
                $this->CUSTOM_FIELD2 = $contact->CUSTOM_FIELD2 ?? '';
                $this->CUSTOM_FIELD3 = $contact->CUSTOM_FIELD3 ?? '';
                $this->CUSTOM_FIELD4 = $contact->CUSTOM_FIELD4 ?? '';
                $this->CUSTOM_FIELD5 = $contact->CUSTOM_FIELD5 ?? '';

                $this->HEIGHT = $contact->HEIGHT ?? 0;
                $this->updateddateofbirth();
                $this->updatedMEMBERBIRTHDATE();
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancecontactpatients')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->NAME = '';
        $this->COMPANY_NAME = '';
        $this->SALUTATION = '';
        $this->FIRST_NAME = '';
        $this->MIDDLE_NAME = '';
        $this->LAST_NAME = '';
        $this->PRINT_NAME_AS = '';
        $this->POSTAL_ADDRESS = '';
        $this->CONTACT_PERSON = '';
        $this->TELEPHONE_NO = '';
        $this->FAX_NO = '';
        $this->MOBILE_NO = '';
        $this->ALT_TELEPHONE_NO = '';
        $this->ALT_CONTACT_PERSON = '';
        $this->EMAIL = '';
        $this->ACCOUNT_NO = '';
        $this->INACTIVE = false;
        $this->GROUP_ID = 0;
        $this->PAYMENT_TERMS_ID = 0;
        $this->CREDIT_LIMIT = 0;
        $this->PREF_PAYMENT_METHOD_ID = 0;
        $this->CREDIT_CARD_NO = '';
        $this->CREDIT_CARD_EXPIRY_DATE = '';
        $this->SALES_REP_ID = 0;
        $this->PRICE_LEVEL_ID = 0;
        $this->TAXPAYER_ID = '';
        $this->TAX_ID = 0;
        $this->EW_TAX_ID = 0;
        $this->SSS_NO = 0;
        $this->GENDER = 0;
        $this->DATE_OF_BIRTH = '';
        $this->NICKNAME = '';
        $this->HIRE_DATE = '';
        $this->age = null;
        $this->SCHEDULE_TYPE = 0;
        $this->LOCATION_ID = $this->userServices->getLocationDefault();
        $this->PATIENT_TYPE_ID = 1;
        $this->PATIENT_STATUS_ID = 1;
        $this->ADMITTED = false;
        $this->LONG_HRS_DURATION = false;
        $this->DATE_ADMISSION = $this->dateServices->NowDate();
        $this->DATE_EXPIRED = null;
        $this->CLASS_ID = 0;
        $this->ADDRESS_UNIT_ROOM_FLOOR = '';
        $this->ADDRESS_BUILDING_NAME = '';
        $this->ADDRESS_LOT_BLK_HOUSE_BLDG = '';
        $this->ADDRESS_STREET = '';
        $this->ADDRESS_SUB_VALL = '';
        $this->ADDRESS_BRGY = '';
        $this->ADDRESS_CITY_MUNI = '';
        $this->ADDRESS_PROVINCE = '';
        $this->ADDRESS_COUNTRY = 'PHILIPPINES';
        $this->ADDRESS_ZIP_CODE = '';
        $this->PIN = '';
        $this->PEN = '';
        $this->IS_PATIENT = true;
        $this->MEMBER_TEL_NO = '';
        $this->MEMBER_MOBILE = '';
        $this->MEMBER_EMAIL = '';
        $this->MEMBER_FIRST_NAME = '';
        $this->MEMBER_LAST_NAME = '';
        $this->MEMBER_MIDDLE_NAME = '';
        $this->MEMBER_EXTENSION = '';
        $this->MEMBER_BIRTH_DATE = null;
        $this->MEMBER_GENDER = 0;

        $this->MEMBER_UNIT_ROOM_FLOOR = '';
        $this->MEMBER_BUILDING_NAME = '';
        $this->MEMBER_LOT_BLK_HOUSE_BLDG = '';
        $this->MEMBER_STREET = '';
        $this->MEMBER_SUB_VALL = '';
        $this->MEMBER_BRGY = '';
        $this->MEMBER_CITY_MUNI = '';
        $this->MEMBER_PROVINCE = '';
        $this->MEMBER_COUNTRY = '';
        $this->MEMBER_ZIP_CODE = '';

        $this->IS_REPRESENTATIVE = false;
        $this->MEMBER_IS_CHILD = false;
        $this->MEMBER_IS_PARENT = false;
        $this->MEMBER_IS_SPOUSE = false;
        $this->CUSTOM_FIELD2 = '';
        $this->PEN_CONTACT = '';
        $this->FIRST_CASE_RATE = '90935';
        $this->SECOND_CASE_RATE = '';
        $this->FINAL_DIAGNOSIS = '';
        $this->OTHER_DIAGNOSIS = '';

        $this->PIN_DEPENDENT = '';
        $this->IS_DEPENDENT = false;
        $this->HEIGHT = 0;
        $this->CUSTOM_FIELD3 = '';
        $this->CUSTOM_FIELD4 = '';
        $this->CUSTOM_FIELD5 = '';
    }
    public function updatedISPATIENT()
    {
        $this->MEMBER_IS_CHILD = false;
        $this->MEMBER_IS_PARENT = false;
        $this->MEMBER_IS_SPOUSE = false;

        $this->IS_DEPENDENT = false;
        $this->PIN_DEPENDENT = '';
    }
    public function updatedADMITTED()
    {
        $this->LONG_HRS_DURATION = false;
    }
    public function updatedLONGHRSDURATION()
    {
        $this->ADMITTED = false;
    }
    public function FullName()
    {
        if ($this->MIDDLE_NAME) {
            $firstLetter = substr($this->MIDDLE_NAME, 0, 1); // Get the first character of the middle name
            $fullname = $this->FIRST_NAME . ' ' . $firstLetter . '. ' . $this->LAST_NAME;
        } else {
            $fullname = $this->FIRST_NAME . ' ' . $this->MIDDLE_NAME . ' ' . $this->LAST_NAME;
        }

        $this->NAME = strtoupper($fullname);
        $this->PRINT_NAME_AS = $this->NAME;
    }
    public function updatedlastname()
    {
        $this->FullName();
    }
    public function updatedfirstname()
    {
        $this->FullName();
    }
    public function updatedmiddlename()
    {
        $this->FullName();
    }
    private function FollowUpUpdate()
    {
        Contacts::where('ID', '=', $this->ID)
            ->where('TYPE', '=', $this->TYPE)
            ->update([
                'LOCATION_ID' => $this->LOCATION_ID,
                'ADDRESS_UNIT_ROOM_FLOOR' => $this->ADDRESS_UNIT_ROOM_FLOOR,
                'ADDRESS_BUILDING_NAME' => $this->ADDRESS_BUILDING_NAME,
                'ADDRESS_LOT_BLK_HOUSE_BLDG' => $this->ADDRESS_LOT_BLK_HOUSE_BLDG,
                'ADDRESS_STREET' => $this->ADDRESS_STREET,
                'ADDRESS_SUB_VALL' => $this->ADDRESS_SUB_VALL,
                'ADDRESS_BRGY' => $this->ADDRESS_BRGY,
                'ADDRESS_CITY_MUNI' => $this->ADDRESS_CITY_MUNI,
                'ADDRESS_PROVINCE' => $this->ADDRESS_PROVINCE,
                'ADDRESS_COUNTRY' => $this->ADDRESS_COUNTRY,
                'ADDRESS_ZIP_CODE' => $this->ADDRESS_ZIP_CODE,
                'MEMBER_TEL_NO' => $this->MEMBER_TEL_NO,
                'MEMBER_MOBILE' => $this->MEMBER_MOBILE,
                'MEMBER_EMAIL' => $this->MEMBER_EMAIL,
                'IS_PATIENT' => $this->IS_PATIENT,
                'MEMBER_IS_CHILD' => $this->MEMBER_IS_CHILD,
                'MEMBER_IS_PARENT' => $this->MEMBER_IS_PARENT,
                'MEMBER_IS_SPOUSE' => $this->MEMBER_IS_SPOUSE,
                'IS_REPRESENTATIVE' => $this->IS_REPRESENTATIVE,
                'MEMBER_FIRST_NAME' => $this->MEMBER_FIRST_NAME,
                'MEMBER_LAST_NAME' => $this->MEMBER_LAST_NAME,
                'MEMBER_MIDDLE_NAME' => $this->MEMBER_MIDDLE_NAME,
                'MEMBER_EXTENSION' => $this->MEMBER_EXTENSION,
                'MEMBER_BIRTH_DATE' => $this->MEMBER_BIRTH_DATE ?? null,
                'MEMBER_GENDER' => $this->MEMBER_GENDER,
                'PIN' => $this->PIN,
                'PEN' => $this->PEN,
                'PEN_CONTACT' => $this->PEN_CONTACT,
                'FIRST_CASE_RATE' => $this->FIRST_CASE_RATE,
                'SECOND_CASE_RATE' => $this->SECOND_CASE_RATE,
                'FINAL_DIAGNOSIS' => $this->FINAL_DIAGNOSIS,
                'OTHER_DIAGNOSIS' => $this->OTHER_DIAGNOSIS,
                'MEMBER_UNIT_ROOM_FLOOR' => $this->MEMBER_UNIT_ROOM_FLOOR,
                'MEMBER_BUILDING_NAME' => $this->MEMBER_BUILDING_NAME,
                'MEMBER_LOT_BLK_HOUSE_BLDG' => $this->MEMBER_LOT_BLK_HOUSE_BLDG,
                'MEMBER_STREET' => $this->MEMBER_STREET,
                'MEMBER_SUB_VALL' => $this->MEMBER_SUB_VALL,
                'MEMBER_BRGY' => $this->MEMBER_BRGY,
                'MEMBER_CITY_MUNI' => $this->MEMBER_CITY_MUNI,
                'MEMBER_PROVINCE' => $this->MEMBER_PROVINCE,
                'MEMBER_COUNTRY' => $this->MEMBER_COUNTRY,
                'MEMBER_ZIP_CODE' => $this->MEMBER_ZIP_CODE,
                'PIN_DEPENDENT' => $this->PIN_DEPENDENT,
                'IS_DEPENDENT' => $this->IS_DEPENDENT,
                'HEIGHT' => $this->HEIGHT,
                'PATIENT_TYPE_ID' => $this->PATIENT_TYPE_ID,
                'DATE_ADMISSION' => $this->DATE_ADMISSION,
                'DATE_EXPIRED' => $this->DATE_EXPIRED ? $this->DATE_EXPIRED : null,
                'CLASS_ID' => $this->CLASS_ID > 0 ? $this->CLASS_ID : 0
            ]);
    }
    public function save()
    {

        $this->validate(
            [
                'NAME' => 'required|max:100|unique:contact,name,' . $this->ID,
                'FIRST_NAME' => 'required',
                'LAST_NAME' => 'required',
                'DATE_OF_BIRTH' => 'required',
                'HEIGHT' => 'required|not_in:0',
                'LOCATION_ID' => 'required|not_in:0',
                'DATE_ADMISSION' => 'required|date',
                'PATIENT_TYPE_ID' => 'required|not_in:0',
                'CLASS_ID' => 'required|exists:patient_class,id',

            ],
            [],
            [
                'NAME' => 'Name',
                'FIRST_NAME' => 'Firstname',
                'LAST_NAME' => 'Lastname',
                'DATE_OF_BIRTH' => 'Date of Birth',
                'HEIGHT' => 'Height',
                'LOCATION_ID' => 'Branch',
                'DATE_ADMISSION' => 'Date Admission',
                'PATIENT_TYPE_ID' => 'Type',
                'CLASS_ID' => 'Classification',

            ]
        );

        if ($this->ID > 0) {
            if ($this->contactRequirementServices->pdpIsComplete($this->ID)) {
                $this->validate(
                    [
                        'SECOND_CASE_RATE' => 'required|string|min:20|unique:contact,SECOND_CASE_RATE,' . $this->ID,
                    ],
                    [],
                    [
                        'SECOND_CASE_RATE' => 'PDD Registration No.'
                    ]
                );
                if($this->contactRequirementServices->pdpIsUploaded($this->ID) == false) {
                    session()->flash('error', 'Please upload PDP file.');
                    return;
                }                
            }
        }




        if ($this->contactServices->is12CharRequired($this->PIN)) {
            session()->flash('error', 'Invalid (PIN). must 12 character only.');
            return;
        }

        if ($this->contactServices->is12CharRequired($this->PEN)) {
            session()->flash('error', 'Invalid (PEN). must 12 character only.');
            return;
        }

        if ($this->IS_DEPENDENT) {
            if ($this->contactServices->is12CharRequired($this->PIN_DEPENDENT)) {
                session()->flash('error', 'Invalid (PIN Dependent). must 12 character only.');
                return;
            }
        }

        try {
            if ($this->ID === 0) {
                $this->ID = $this->contactServices->Store(
                    $this->TYPE,
                    $this->NAME,
                    $this->COMPANY_NAME ?? null,
                    $this->SALUTATION ?? null,
                    $this->FIRST_NAME ?? null,
                    $this->MIDDLE_NAME ?? null,
                    $this->LAST_NAME ?? null,
                    $this->PRINT_NAME_AS ?? null,
                    $this->POSTAL_ADDRESS,
                    $this->CONTACT_PERSON,
                    $this->TELEPHONE_NO,
                    $this->FAX_NO,
                    $this->MOBILE_NO,
                    $this->ALT_TELEPHONE_NO ?? null,
                    $this->ALT_CONTACT_PERSON,
                    $this->EMAIL,
                    $this->ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->GROUP_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->CREDIT_LIMIT,
                    $this->PREF_PAYMENT_METHOD_ID,
                    $this->CREDIT_CARD_NO,
                    $this->CREDIT_CARD_EXPIRY_DATE,
                    $this->SALES_REP_ID,
                    $this->PRICE_LEVEL_ID,
                    $this->TAXPAYER_ID,
                    $this->TAX_ID,
                    $this->EW_TAX_ID,
                    $this->SSS_NO,
                    $this->GENDER,
                    $this->DATE_OF_BIRTH,
                    $this->NICKNAME,
                    $this->HIRE_DATE,
                    $this->LOCATION_ID,
                    $this->CUSTOM_FIELD2,
                    $this->CUSTOM_FIELD3,
                    $this->CUSTOM_FIELD4,
                    $this->CUSTOM_FIELD5
                );

                $this->FollowUpUpdate();
                $this->contactRequirementServices->AutoCreateList($this->ID);
                Redirect::route('maintenancecontactpatients_edit', ['id' => $this->ID])->with('message', 'Successfully created');
            } else {
                $this->contactServices->Update(
                    $this->ID,
                    $this->TYPE,
                    $this->NAME,
                    $this->COMPANY_NAME,
                    $this->SALUTATION,
                    $this->FIRST_NAME,
                    $this->MIDDLE_NAME,
                    $this->LAST_NAME,
                    $this->PRINT_NAME_AS,
                    $this->POSTAL_ADDRESS,
                    $this->CONTACT_PERSON,
                    $this->TELEPHONE_NO,
                    $this->FAX_NO,
                    $this->MOBILE_NO,
                    $this->ALT_TELEPHONE_NO,
                    $this->ALT_CONTACT_PERSON,
                    $this->EMAIL,
                    $this->ACCOUNT_NO,
                    $this->INACTIVE,
                    $this->GROUP_ID,
                    $this->PAYMENT_TERMS_ID,
                    $this->CREDIT_LIMIT,
                    $this->PREF_PAYMENT_METHOD_ID,
                    $this->CREDIT_CARD_NO,
                    $this->CREDIT_CARD_EXPIRY_DATE,
                    $this->SALES_REP_ID,
                    $this->PRICE_LEVEL_ID,
                    $this->TAXPAYER_ID,
                    $this->TAX_ID,
                    $this->EW_TAX_ID,
                    $this->SSS_NO,
                    $this->GENDER,
                    $this->DATE_OF_BIRTH,
                    $this->NICKNAME,
                    $this->HIRE_DATE,
                    null,
                    $this->CUSTOM_FIELD2,
                    $this->CUSTOM_FIELD3,
                    $this->CUSTOM_FIELD4,
                    $this->CUSTOM_FIELD5
                );

                $this->FollowUpUpdate();
                session()->flash('message', 'Successfully updated');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function openMedCert()
    {
        $this->dispatch('open-med-cert');
    }
    public function render()
    {
        return view('livewire.patient.patient-form');
    }
}
