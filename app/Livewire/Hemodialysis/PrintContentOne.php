<?php
namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\DateServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PatientDoctorServices;
use App\Services\UserServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintContentOne extends Component
{
    #[Reactive]
    public int $HEMO_ID;
    public $DATE;
    public $PHIC_NO;
    public string $FULL_NAME;
    public string $CODE;
    public string $DOB;
    public int $AGE = 0;
    private $hemoServices;
    private $contactServices;
    public int $ADD_NO_TREATMENT = 0;
    public int $MACHINE_NO;
    public string $EMPLOYEE_NAME;
    public string $PRE_WEIGHT;
    public string $PRE_BLOOD_PRESSURE;
    public string $PRE_BLOOD_PRESSURE2;
    public string $PRE_HEART_RATE;
    public string $PRE_O2_SATURATION;
    public string $PRE_TEMPERATURE;
    public string $POST_WEIGHT;
    public string $POST_BLOOD_PRESSURE;
    public string $POST_BLOOD_PRESSURE2;
    public string $POST_HEART_RATE;
    public string $POST_O2_SATURATION;
    public string $POST_TEMPERATURE;

    public string $OLD_PRE_WEIGHT;
    public string $OLD_PRE_BLOOD_PRESSURE;
    public string $OLD_PRE_BLOOD_PRESSURE2;
    public string $OLD_PRE_HEART_RATE;
    public string $OLD_PRE_O2_SATURATION;
    public string $OLD_PRE_TEMPERATURE;
    public string $OLD_POST_WEIGHT;
    public string $OLD_POST_BLOOD_PRESSURE;
    public string $OLD_POST_BLOOD_PRESSURE2;
    public string $OLD_POST_HEART_RATE;
    public string $OLD_POST_O2_SATURATION;
    public string $OLD_POST_TEMPERATURE;
    public int $CUSTOMER_ID;
    public int $LOCATION_ID;
    public array $collection = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
    public int $r = 0;
    public int $e = 15;
    public string $NEPRHO_NAME;
    public string $DIAGNOSIS = " ";
    public int $NO_OF_TREATMENT = 0;

    public string $SE_DETAILS;
    public string $SO_DETAILS;

    public int $BFR;
    public int $DFR;
    public int $DURATION;
    public string $DIALYZER;
    public string $HEPARIN;
    public string $REUSE_NO;
    public string $FLUSHING;
    public string $DIALSATE_N;
    public string $DIALSATE_K;
    public string $DIALSATE_C;

    public bool $SC_MACHINE_TEST;
    public bool $SC_SECURED_CONNECTIONS;
    public bool $SC_SALINE_LINE_DOUBLE_CLAMP;
    public string $SC_CONDUCTIVITY;
    public string $SC_DIALYSATE_TEMP;
    public bool $SC_RESIDUAL_TEST_NEGATIVE;
    public string $UF_GOAL;
    public bool $DB_STANDARD_HCOA;
    public bool $DB_ACID;

    public bool $AT_FISTULA;
    public bool $AT_GRAFT;
    public bool $AT_RIGHT;
    public bool $AT_LEFT;
    public bool $B_STRONG;
    public bool $B_WEEK;
    public bool $B_ABSENT;
    public bool $T_STRONG;
    public bool $T_WEAK;
    public bool $T_ABSENT;
    public bool $H_PRESENT;
    public bool $H_ABSENT;
    public string $H_OTHER_NOTES;

    public bool $CVC_SUBCATH;
    public bool $CVC_JUGCATH;
    public bool $CVC_FEMCATCH;
    public bool $CVC_PERMACATH;
    public bool $CVC_RIGHT;
    public bool $CVC_LEFT;
    public bool $CVC_GOOD_FLOW_A;
    public bool $CVC_GOOD_FLOW_V;
    public bool $CVC_W_RESISTANCE_A;
    public bool $CVC_W_RESISTANCE_V;
    public bool $CVC_CLOTTED_A;
    public bool $CVC_CLOTTED_V;

    public bool $PRE_AMBULATORY;
    public bool $PRE_AMBULATORY_W_ASSIT;
    public bool $PRE_WHEEL_CHAIR;
    public bool $PRE_CONSCIOUS;
    public bool $PRE_COHERENT;
    public bool $PRE_DISORIENTED;
    public bool $PRE_DROWSY;
    public bool $PRE_CLEAR;
    public bool $PRE_CRACKLES;
    public bool $PRE_RHONCHI;
    public bool $PRE_WHEEZES;
    public bool $PRE_RALES;
    public bool $PRE_DISTENDED_JUGULAR_VIEW;
    public bool $PRE_ASCITES;
    public bool $PRE_EDEMA;
    public bool $PRE_LOCATION;
    public string $PRE_LOCATION_NOTES;
    public bool $PRE_DEPTH;
    public string $PRE_DEPTH_NOTES;
    public bool $PRE_REGULAR;
    public bool $PRE_IRREGULAR;

    public bool $POST_AMBULATORY;
    public bool $POST_AMBULATORY_W_ASSIT;
    public bool $POST_WHEEL_CHAIR;
    public bool $POST_CONSCIOUS;
    public bool $POST_COHERENT;
    public bool $POST_DISORIENTED;
    public bool $POST_DROWSY;
    public bool $POST_CLEAR;
    public bool $POST_CRACKLES;
    public bool $POST_RHONCHI;
    public bool $POST_WHEEZES;
    public bool $POST_RALES;
    public bool $POST_DISTENDED_JUGULAR_VIEW;
    public bool $POST_ASCITES;
    public bool $POST_EDEMA;
    public bool $POST_LOCATION;
    public string $POST_LOCATION_NOTES;
    public bool $POST_DEPTH;
    public string $POST_DEPTH_NOTES;
    public bool $POST_REGULAR;
    public bool $POST_IRREGULAR;
    public string $DRY_WEIGHT_VALUE;
    public bool $DRY_WEIGHT = true;
    public string $RML;
    public string $HEPA_PROFILE;
    public string $CXR;
    public $noteList = [];

    public $SE_PARTS = [];
    public $SO_PARTS = [];
    public int $SE_COUNT = 0;
    public int $SO_COUNT = 0;
    public bool $OTHER_SIGN = false;
    public string $REPORT_HEADER_1;
    public string $LOGO_FILE = '';

    public bool $VITAL_SIGN_GRAPH = false;
    private $patientDoctorServices;
    private $locationServices;
    private $dateServices;
    public function boot(
        HemoServices $hemoServices,
        ContactServices $contactServices,
        PatientDoctorServices $patientDoctorServices,
        LocationServices $locationServices,
        DateServices $dateServices
    ) {
        $this->hemoServices          = $hemoServices;
        $this->contactServices       = $contactServices;
        $this->patientDoctorServices = $patientDoctorServices;
        $this->locationServices      = $locationServices;
        $this->dateServices          = $dateServices;
    }
    public function getPreviousTreatment()
    {
        $data = $this->hemoServices->ShowLastTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE);

        if ($data) {
            $this->OLD_PRE_WEIGHT           = $data->PRE_WEIGHT ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE   = $data->PRE_BLOOD_PRESSURE ?? "";
            $this->OLD_PRE_BLOOD_PRESSURE2  = $data->PRE_BLOOD_PRESSURE2 ?? "";
            $this->OLD_PRE_HEART_RATE       = $data->PRE_HEART_RATE ?? "";
            $this->OLD_PRE_O2_SATURATION    = $data->PRE_O2_SATURATION ?? "";
            $this->OLD_PRE_TEMPERATURE      = $data->PRE_TEMPERATURE ?? "";
            $this->OLD_POST_WEIGHT          = $data->POST_WEIGHT ?? "";
            $this->OLD_POST_BLOOD_PRESSURE  = $data->POST_BLOOD_PRESSURE ?? "";
            $this->OLD_POST_BLOOD_PRESSURE2 = $data->POST_BLOOD_PRESSURE2 ?? "";
            $this->OLD_POST_HEART_RATE      = $data->POST_HEART_RATE ?? "";
            $this->OLD_POST_O2_SATURATION   = $data->POST_O2_SATURATION ?? "";
            $this->OLD_POST_TEMPERATURE     = $data->POST_TEMPERATURE ?? "";

            return;
        }

        $this->OLD_PRE_WEIGHT           = "";
        $this->OLD_PRE_BLOOD_PRESSURE   = "";
        $this->OLD_PRE_BLOOD_PRESSURE2  = "";
        $this->OLD_PRE_HEART_RATE       = "";
        $this->OLD_PRE_O2_SATURATION    = "";
        $this->OLD_PRE_TEMPERATURE      = "";
        $this->OLD_POST_WEIGHT          = "";
        $this->OLD_POST_BLOOD_PRESSURE  = "";
        $this->OLD_POST_BLOOD_PRESSURE2 = "";
        $this->OLD_POST_HEART_RATE      = "";
        $this->OLD_POST_O2_SATURATION   = "";
        $this->OLD_POST_TEMPERATURE     = "";
    }
    public function mount()
    {
        $data = $this->hemoServices->GetFirst($this->HEMO_ID);
        if ($data) {
            $this->MACHINE_NO = $data->MACHINE_NO ?? 0;
            if (UserServices::GetUserRightAccess('full-treatment-sheet')) {

                $emp = $this->contactServices->get($data->EMPLOYEE_ID ?? 0, 2);
                if ($emp) {
                    $this->EMPLOYEE_NAME = $emp->PRINT_NAME_AS ?? '';
                }
            }

            $this->PRE_WEIGHT          = ''; // $data->PRE_WEIGHT ?? '';
            $this->PRE_BLOOD_PRESSURE  = ''; // $data->PRE_BLOOD_PRESSURE ?? '';
            $this->PRE_BLOOD_PRESSURE2 = ''; // $data->PRE_BLOOD_PRESSURE2 ?? '';
            $this->PRE_HEART_RATE      = ''; // $data->PRE_HEART_RATE ?? '';
            $this->PRE_O2_SATURATION   = ''; // $data->PRE_O2_SATURATION ?? '';
            $this->PRE_TEMPERATURE     = ''; // $data->PRE_TEMPERATURE ?? '';

            $this->POST_WEIGHT          = ''; // $data->POST_WEIGHT ?? '';
            $this->POST_BLOOD_PRESSURE  = ''; // $data->POST_BLOOD_PRESSURE ?? '';
            $this->POST_BLOOD_PRESSURE2 = ''; // $data->POST_BLOOD_PRESSURE2 ?? '';
            $this->POST_HEART_RATE      = ''; // $data->POST_HEART_RATE ?? '';
            $this->POST_O2_SATURATION   = ''; // $data->POST_O2_SATURATION ?? '';
            $this->POST_TEMPERATURE     = ''; // $data->POST_TEMPERATURE ?? '';

            $this->UF_GOAL          = $data->UF_GOAL ?? '';
            $this->DB_STANDARD_HCOA = $data->DB_STANDARD_HCOA ?? false;
            $this->DB_ACID          = $data->DB_ACID ?? false;

            $this->SC_MACHINE_TEST             = $data->SC_MACHINE_TEST ?? false;
            $this->SC_SECURED_CONNECTIONS      = $data->SC_SECURED_CONNECTIONS ?? false;
            $this->SC_SALINE_LINE_DOUBLE_CLAMP = $data->SC_SALINE_LINE_DOUBLE_CLAMP ?? false;
            $this->SC_CONDUCTIVITY             = $data->SC_CONDUCTIVITY ?? '';
            $this->SC_DIALYSATE_TEMP           = $data->SC_DIALYSATE_TEMP ?? '';
            $this->SC_RESIDUAL_TEST_NEGATIVE   = $data->SC_RESIDUAL_TEST_NEGATIVE ?? false;

            $this->AT_FISTULA    = $data->AT_FISTULA ?? false;
            $this->AT_GRAFT      = $data->AT_GRAFT ?? false;
            $this->AT_RIGHT      = $data->AT_RIGHT ?? false;
            $this->AT_LEFT       = $data->AT_LEFT ?? false;
            $this->B_STRONG      = $data->B_STRONG ?? false;
            $this->B_WEEK        = $data->B_WEEK ?? false;
            $this->B_ABSENT      = $data->B_ABSENT ?? false;
            $this->T_STRONG      = $data->T_STRONG ?? false;
            $this->T_WEAK        = $data->T_WEAK ?? false;
            $this->T_ABSENT      = $data->T_ABSENT ?? false;
            $this->H_PRESENT     = $data->H_PRESENT ?? false;
            $this->H_ABSENT      = $data->H_ABSENT ?? false;
            $this->H_OTHER_NOTES = $data->H_OTHER_NOTES ?? '';

            $this->CVC_SUBCATH        = $data->CVC_SUBCATH ?? false;
            $this->CVC_JUGCATH        = $data->CVC_JUGCATH ?? false;
            $this->CVC_FEMCATCH       = $data->CVC_FEMCATCH ?? false;
            $this->CVC_PERMACATH      = $data->CVC_PERMACATH ?? false;
            $this->CVC_RIGHT          = $data->CVC_RIGHT ?? false;
            $this->CVC_LEFT           = $data->CVC_LEFT ?? false;
            $this->CVC_GOOD_FLOW_A    = $data->CVC_GOOD_FLOW_A ?? false;
            $this->CVC_GOOD_FLOW_V    = $data->CVC_GOOD_FLOW_V ?? false;
            $this->CVC_W_RESISTANCE_A = $data->CVC_W_RESISTANCE_A ?? false;
            $this->CVC_W_RESISTANCE_V = $data->CVC_W_RESISTANCE_V ?? false;
            $this->CVC_CLOTTED_A      = $data->CVC_CLOTTED_A ?? false;
            $this->CVC_CLOTTED_V      = $data->CVC_CLOTTED_V ?? false;

            $this->PRE_AMBULATORY             = $data->PRE_AMBULATORY ?? false;
            $this->PRE_AMBULATORY_W_ASSIT     = $data->PRE_AMBULATORY_W_ASSIT ?? false;
            $this->PRE_WHEEL_CHAIR            = $data->PRE_WHEEL_CHAIR ?? false;
            $this->PRE_CONSCIOUS              = $data->PRE_CONSCIOUS ?? false;
            $this->PRE_COHERENT               = $data->PRE_COHERENT ?? false;
            $this->PRE_DISORIENTED            = $data->PRE_DISORIENTED ?? false;
            $this->PRE_DROWSY                 = $data->PRE_DROWSY ?? false;
            $this->PRE_CLEAR                  = $data->PRE_CLEAR ?? false;
            $this->PRE_CRACKLES               = $data->PRE_CRACKLES ?? false;
            $this->PRE_RHONCHI                = $data->PRE_RHONCHI ?? false;
            $this->PRE_WHEEZES                = $data->PRE_WHEEZES ?? false;
            $this->PRE_RALES                  = $data->PRE_RALES ?? false;
            $this->PRE_DISTENDED_JUGULAR_VIEW = $data->PRE_DISTENDED_JUGULAR_VIEW ?? false;
            $this->PRE_ASCITES                = $data->PRE_ASCITES ?? false;
            $this->PRE_EDEMA                  = $data->PRE_EDEMA ?? false;
            $this->PRE_LOCATION               = $data->PRE_LOCATION ?? false;
            $this->PRE_LOCATION_NOTES         = $data->PRE_LOCATION_NOTES ?? '';
            $this->PRE_DEPTH                  = $data->PRE_DEPTH ?? false;
            $this->PRE_DEPTH_NOTES            = $data->PRE_DEPTH_NOTES ?? '';
            $this->PRE_REGULAR                = $data->PRE_REGULAR ?? false;
            $this->PRE_IRREGULAR              = $data->PRE_IRREGULAR ?? false;

            $this->POST_AMBULATORY             = $data->POST_AMBULATORY ?? false;
            $this->POST_AMBULATORY_W_ASSIT     = $data->POST_AMBULATORY_W_ASSIT ?? false;
            $this->POST_WHEEL_CHAIR            = $data->POST_WHEEL_CHAIR ?? false;
            $this->POST_CONSCIOUS              = $data->POST_CONSCIOUS ?? false;
            $this->POST_COHERENT               = $data->POST_COHERENT ?? false;
            $this->POST_DISORIENTED            = $data->POST_DISORIENTED ?? false;
            $this->POST_DROWSY                 = $data->POST_DROWSY ?? false;
            $this->POST_CLEAR                  = $data->POST_CLEAR ?? false;
            $this->POST_CRACKLES               = $data->POST_CRACKLES ?? false;
            $this->POST_RHONCHI                = $data->POST_RHONCHI ?? false;
            $this->POST_WHEEZES                = $data->POST_WHEEZES ?? false;
            $this->POST_RALES                  = $data->POST_RALES ?? false;
            $this->POST_DISTENDED_JUGULAR_VIEW = $data->POST_DISTENDED_JUGULAR_VIEW ?? false;
            $this->POST_ASCITES                = $data->POST_ASCITES ?? false;
            $this->POST_EDEMA                  = $data->POST_EDEMA ?? false;
            $this->POST_LOCATION               = $data->POST_LOCATION ?? false;
            $this->POST_LOCATION_NOTES         = $data->POST_LOCATION_NOTES ?? '';
            $this->POST_DEPTH                  = $data->POST_DEPTH ?? false;
            $this->POST_DEPTH_NOTES            = $data->POST_DEPTH_NOTES ?? '';
            $this->POST_REGULAR                = $data->POST_REGULAR ?? false;
            $this->POST_IRREGULAR              = $data->POST_IRREGULAR ?? false;

            //
            $this->FULL_NAME        = $data->CONTACT_NAME;
            $this->DATE             = $data->DATE;
            $this->CODE             = $data->CODE;
            $this->PHIC_NO          = $data->PHIC_NO;
            $this->DOB              = $data->DATE_OF_BIRTH;
            $this->AGE              = $this->contactServices->calculateUserAge($this->DOB);
            $this->CUSTOMER_ID      = $data->CUSTOMER_ID;
            $this->LOCATION_ID      = $data->LOCATION_ID;
            $this->SE_DETAILS       = $data->SE_DETAILS ?? '';
            $this->SO_DETAILS       = $data->SO_DETAILS ?? '';
            $this->BFR              = $data->BFR ?? '';
            $this->DFR              = $data->DFR ?? '';
            $this->DURATION         = $data->DURATION ?? 0;
            $this->DIALYZER         = $data->DIALYZER ?? '';
            $this->HEPARIN          = $data->HEPARIN ?? '';
            $this->REUSE_NO         = $data->REUSE_NO ?? '';
            $this->FLUSHING         = $data->FLUSHING ?? '';
            $this->DIALSATE_N       = $data->DIALSATE_N ?? '';
            $this->DIALSATE_K       = $data->DIALSATE_K ?? '';
            $this->DIALSATE_C       = $data->DIALSATE_C ?? '';
            $this->DRY_WEIGHT_VALUE = $data->DRY_WEIGHT ?? '';
            $this->RML              = $this->dateServices->isValidDateFormat($data->RML) ? date('m/d/Y', strtotime($data->RML)) : $data->RML ?? '';
            $this->HEPA_PROFILE     = $this->dateServices->isValidDateFormat($data->HEPA_PROFILE) ? date('m/d/Y', strtotime($data->HEPA_PROFILE)) : $data->HEPA_PROFILE ?? '';
            $this->CXR              = $this->dateServices->isValidDateFormat($data->CXR) ? date('m/d/Y', strtotime($data->CXR)) : $data->CXR ?? '';
            $this->SE_COUNT         = 0;
            // $this->SE_PARTS         = str_split($this->SE_DETAILS, 40);
            $this->SE_PARTS = preg_split('/;|(.{40})/u', $this->SE_DETAILS, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $this->SO_COUNT = 0;
            // $this->SO_PARTS = str_split($this->SO_DETAILS, 40);
            $this->SO_PARTS = preg_split('/;|(.{40})/u', $this->SO_DETAILS, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $this->getPreviousTreatment();

            $dataDoc = $this->patientDoctorServices->GetList($this->CUSTOMER_ID, $this->LOCATION_ID);
            foreach ($dataDoc as $doc) {
                $this->NEPRHO_NAME = $doc->NAME ?? '';
            }

            $dataPatient = $this->contactServices->get($this->CUSTOMER_ID, 3);
            if ($dataPatient) {
                $this->DIAGNOSIS        = $dataPatient->FINAL_DIAGNOSIS ?? '';
                $this->ADD_NO_TREATMENT = is_numeric($dataPatient->CUSTOM_FIELD2) ? $dataPatient->CUSTOM_FIELD2 : 0;
            }

            $this->NO_OF_TREATMENT = $this->hemoServices->GetNoTreatment($this->CUSTOMER_ID, $this->LOCATION_ID, $this->DATE) + $this->ADD_NO_TREATMENT;

            $locData = $this->locationServices->get($this->LOCATION_ID);
            if ($locData) {
                $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
                $this->LOGO_FILE       = $locData->LOGO_FILE ?? '';
                $this->DRY_WEIGHT      = $locData->USED_DRY_WEIGHT ?? false;
                $this->OTHER_SIGN      = $locData->OTHER_SIGN ?? false;
            }
            $this->noteList = $this->hemoServices->ListNotes($this->HEMO_ID);

            if (in_array((int) $this->LOCATION_ID, [33, 47])) {

                $this->VITAL_SIGN_GRAPH = true;
            }
        }
    }

    public function render()
    {
        return view('livewire.hemodialysis.print-content-one');
    }
}
