<?php
namespace App\Services;

use App\Models\Contacts;
use App\Models\Hemodialysis;
use App\Models\HemodialysisItems;
use App\Models\HemoJournal;
use App\Models\HemoNurseNotes;
use App\Models\ItemSubClass;
use App\Models\PhilhealthItemAdjustment;
use Illuminate\Support\Facades\DB;

class HemoServices
{

    public $object_type_hemo      = 95;
    public $object_type_hemo_item = 109;
    public $dialyMode             = false;
    private $object;
    private $user;
    private $systemSettingServices;
    private $dateServices;
    private $itemTreatmentServices;
    private $unitOfMeasureServices;
    private $itemServices;
    private $itemInventoryServices;
    private $accountJournalServices;
    private $accountServices;
    public function __construct(
        ObjectServices $objectService,
        UserServices $userServices,
        SystemSettingServices $systemSettingServices,
        DateServices $dateServices,
        ItemTreatmentServices $itemTreatmentServices,
        UnitOfMeasureServices $unitOfMeasureServices,
        ItemServices $itemServices,
        ItemInventoryServices $itemInventoryServices,
        AccountJournalServices $accountJournalServices,
        AccountServices $accountServices
    ) {
        $this->object                 = $objectService;
        $this->user                   = $userServices;
        $this->systemSettingServices  = $systemSettingServices;
        $this->dateServices           = $dateServices;
        $this->itemTreatmentServices  = $itemTreatmentServices;
        $this->unitOfMeasureServices  = $unitOfMeasureServices;
        $this->itemServices           = $itemServices;
        $this->itemInventoryServices  = $itemInventoryServices;
        $this->accountJournalServices = $accountJournalServices;
        $this->accountServices        = $accountServices;
    }

    public function Get(int $ID)
    {
        $data = Hemodialysis::where('ID', $ID)->first();
        if ($data) {
            return $data;
        }

        return [];
    }
    public function NullEmployee(int $ID)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'EMPLOYEE_ID' => null,
            ]);
    }
    public function UpdateDoctorOrder(int $ID, string $DOCTOR_ORDER = '')
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'DOCTOR_ORDER' => $DOCTOR_ORDER == '' ? null : $DOCTOR_ORDER,
            ]);
    }
    public function GetDoctorOrder($ID): string
    {

        return (string) Hemodialysis::where('ID', $ID)->first()->DOCTOR_ORDER ?? '';
    }
    public function GetHemoID($DATE, $PATIENT_ID, $LOCATION_ID): int
    {
        $dataRecord = Hemodialysis::where('CUSTOMER_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->first();

        if ($dataRecord) {
            return (int) $dataRecord->ID;
        }

        return 0;
    }
    public function IsNewHemo(int $CONTACT_ID, int $LOCATION_ID, string $DATE): bool
    {
        $count = Hemodialysis::where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', '<', $DATE)
            ->where('STATUS_ID', 2)
            ->count();

        if ($count == 0) {
            return true;
        }

        return false;
    }
    public function GetPost(int $CONTACT_ID, int $LOCATION_ID, string $DATE)
    {
        return Hemodialysis::where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->where('STATUS_ID', 2)
            ->first();
    }
    public function GetEmployeeName(int $EMP_ID): string
    {
        $data = Contacts::where('ID', $EMP_ID)->first();

        if ($data) {
            return $data->NAME ?? '';
        }

        return '';
    }
    private function getTime(bool $isStart, string $DATE, int $CONTACT_ID, int $LOCATION_ID): string
    {

        try {
            if ($isStart) {
                return Hemodialysis::query()
                    ->select('hemodialysis.TIME_START')
                    ->where('CUSTOMER_ID', $CONTACT_ID)
                    ->where('LOCATION_ID', $LOCATION_ID)
                    ->where('DATE', $DATE)
                    ->where('STATUS_ID', '2')
                    ->whereExists(function ($query) use (&$DATE, &$CONTACT_ID, &$LOCATION_ID) {
                        $query->select(DB::raw(1))
                            ->from('service_charges as sc')
                            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
                            ->where('sc.DATE', $DATE)
                            ->where('sc.PATIENT_ID', $CONTACT_ID)
                            ->where('sc.LOCATION_ID', $LOCATION_ID)
                            ->where('sc.USE_PHIC', '=', 0)
                            ->where('sci.ITEM_ID', 2);
                    })
                    ->first()
                    ->TIME_START;
            }

            $result = Hemodialysis::query()
                ->select([
                    'hemodialysis.TIME_START',
                    'hemodialysis.TIME_END',
                ])
                ->where('CUSTOMER_ID', $CONTACT_ID)
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('DATE', $DATE)
                ->where('STATUS_ID', '2')
                ->whereExists(function ($query) use (&$DATE, &$CONTACT_ID, &$LOCATION_ID) {
                    $query->select(DB::raw(1))
                        ->from('service_charges as sc')
                        ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
                        ->where('sc.DATE', $DATE)
                        ->where('sc.PATIENT_ID', $CONTACT_ID)
                        ->where('sc.LOCATION_ID', $LOCATION_ID)
                        ->where('sc.USE_PHIC', '=', 0)
                        ->where('sci.ITEM_ID', 2);
                })
                ->first();

            if ($result) {
                return $result->TIME_END ?? $result->TIME_START;
            }
            return '';
        } catch (\Throwable $th) {
            return '';
        }
    }
    public function GetStatus(int $HEMO_ID)
    {
        $data = Hemodialysis::where('ID', $HEMO_ID)
            ->select(['STATUS_ID'])
            ->first();

        if ($data) {
            return (int) $data->STATUS_ID;
        }

        return 0;
    }
    public function GetSummary(int $CONTACT_ID = 0, int $LOCATION_ID = 0, string $DATE_START = '', string $DATE_END = '')
    {
        $dataList = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'hemodialysis.DOCTOR_ORDER',
                'sci.ID as SCI_ID',
                'sci.ITEM_ID',
                'sci.AMOUNT',
                'sci.SERVICE_CHARGES_ID',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
            ])
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID');
                $join->on('s.DATE', '=', 'hemodialysis.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID', '=', 2)
            ->where('s.USE_PHIC', '=', 0)
            ->where('hemodialysis.CUSTOMER_ID', $CONTACT_ID)
            ->where('hemodialysis.LOCATION_ID', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '2')
            ->whereBetween('hemodialysis.DATE', [$DATE_START, $DATE_END])
            ->orderBy('hemodialysis.DATE', 'asc')
            ->get();

        return $dataList;
    }
    public function getDateTime(int $CONTACT_ID, int $LOCATION_ID)
    {
        $dates = Hemodialysis::query()
            ->select(DB::raw('MIN(DATE) AS first_date, MAX(DATE) AS last_date'))
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('STATUS_ID', 2)
            ->first();

        if ($dates) {
            $firstDate = $dates->first_date ?? null;
            $lastDate  = $dates->last_date ?? null;

            if ($firstDate != null && $lastDate != null) {
                $firstTime = $this->getTime(true, $firstDate, $CONTACT_ID, $LOCATION_ID);
                $lastTime  = $this->getTime(false, $lastDate, $CONTACT_ID, $LOCATION_ID);

                return [
                    'FIRST_DATE' => $firstDate,
                    'FIRST_TIME' => $firstTime,
                    'LAST_DATE'  => $lastDate,
                    'LAST_TIME'  => $lastTime,
                ];
            }
        }

        return [
            'FIRST_DATE' => '',
            'FIRST_TIME' => '',
            'LAST_DATE'  => '',
            'LAST_TIME'  => '',
        ];
    }
    public function HemoStatus()
    {
        $result = DB::table('hemo_status')
            ->select(['ID', 'DESCRIPTION'])
            ->whereNotIn('ID', ['4'])
            ->get();

        return $result;
    }
    public function getPostedFrom(string $DATE, int $LOCATION_ID)
    {
        return Hemodialysis::query()
            ->select(['ID'])
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', '>=', $DATE)
            ->where('STATUS_ID', 2)
            ->orderBy('DATE')
            ->get();
    }
    public function getDateTimeByRange(int $CONTACT_ID, int $LOCATION_ID, string $DT_FROM, string $DT_TO)
    {

        $dates = Hemodialysis::query()
            ->select(DB::raw('MIN(hemodialysis.DATE) AS first_date, MAX(hemodialysis.DATE) AS last_date'))
            ->join('service_charges as sc', function ($join) {
                $join->On('sc.DATE', '=', 'hemodialysis.DATE')
                    ->On('sc.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID')
                    ->On('sc.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
            })
            ->join('service_charges_items as  sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
            ->where('sci.ITEM_ID', '=', 2)
            ->where('hemodialysis.CUSTOMER_ID', '=', $CONTACT_ID)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->whereBetween('hemodialysis.DATE', [$DT_FROM, $DT_TO])
            ->where('hemodialysis.STATUS_ID', '=', 2)
            ->where(function ($query) use (&$CONTACT_ID, &$LOCATION_ID, &$DT_FROM, &$DT_TO) {
                $query->select(DB::raw(1))
                    ->from('service_charges as sc')
                    ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
                    ->whereBetween('sc.DATE', [$DT_FROM, $DT_TO])
                    ->where('sc.PATIENT_ID', '=', $CONTACT_ID)
                    ->where('sc.LOCATION_ID', '=', $LOCATION_ID)
                    ->where('sc.USE_PHIC', '=', 0)
                    ->where('sci.ITEM_ID', '=', 2);
            })
            ->whereNotExists(function ($query) use (&$DT_FROM, &$DT_TO) {
                $query->select(DB::raw(1))
                    ->from('philhealth as l')
                    ->whereColumn('l.CONTACT_ID', 'hemodialysis.CUSTOMER_ID')
                    ->whereColumn('l.LOCATION_ID', 'hemodialysis.LOCATION_ID')
                    ->where('l.DATE_ADMITTED', '>=', $DT_FROM)
                    ->where('l.DATE_DISCHARGED', '<=', $DT_TO);
            })
            ->first();

        if ($dates) {
            $firstDate = $dates->first_date ?? null;
            $lastDate  = $dates->last_date ?? null;

            if ($firstDate != null && $lastDate != null) {
                $firstTime = $this->getTime(true, $firstDate, $CONTACT_ID, $LOCATION_ID);
                $lastTime  = $this->getTime(false, $lastDate, $CONTACT_ID, $LOCATION_ID);

                return [
                    'FIRST_DATE' => $firstDate,
                    'FIRST_TIME' => $firstTime,
                    'LAST_DATE'  => $lastDate,
                    'LAST_TIME'  => $lastTime,
                ];
            }
        }

        return [
            'FIRST_DATE' => '',
            'FIRST_TIME' => '',
            'LAST_DATE'  => '',
            'LAST_TIME'  => '',
        ];
    }
    public function getDateTimeByDaily(int $CONTACT_ID, int $LOCATION_ID, string $DT_FROM)
    {

        $dates = Hemodialysis::query()
            ->select(DB::raw('MIN(hemodialysis.DATE) AS first_date, MAX(hemodialysis.DATE) AS last_date'))
            ->join('service_charges as sc', function ($join) {
                $join->On('sc.DATE', '=', 'hemodialysis.DATE')
                    ->On('sc.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID')
                    ->On('sc.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
            })
            ->join('service_charges_items as  sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
            ->where('sci.ITEM_ID', '=', 2)
            ->where('hemodialysis.CUSTOMER_ID', '=', $CONTACT_ID)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.DATE', '=', $DT_FROM)
            ->where('hemodialysis.STATUS_ID', '=', 2)
            ->where(function ($query) use (&$CONTACT_ID, &$LOCATION_ID, &$DT_FROM) {
                $query->select(DB::raw(1))
                    ->from('service_charges as sc')
                    ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 'sc.ID')
                    ->where('sc.DATE', $DT_FROM)
                    ->where('sc.PATIENT_ID', '=', $CONTACT_ID)
                    ->where('sc.LOCATION_ID', '=', $LOCATION_ID)
                    ->where('sc.USE_PHIC', '=', 0)
                    ->where('sci.ITEM_ID', '=', 2);
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('philhealth as l')
                    ->whereColumn('l.CONTACT_ID', 'hemodialysis.CUSTOMER_ID')
                    ->whereColumn('l.LOCATION_ID', 'hemodialysis.LOCATION_ID')
                    ->whereColumn('l.DATE_ADMITTED', '=', 'hemodialysis.DATE')
                    ->whereColumn('l.DATE_DISCHARGED', '=', 'hemodialysis.DATE');
            })
            ->first();

        if ($dates) {
            $firstDate = $dates->first_date ?? null;
            $lastDate  = $dates->last_date ?? null;

            if ($firstDate != null && $lastDate != null) {
                $firstTime = $this->getTime(true, $firstDate, $CONTACT_ID, $LOCATION_ID);
                $lastTime  = $this->getTime(false, $lastDate, $CONTACT_ID, $LOCATION_ID);

                return [
                    'FIRST_DATE' => $firstDate,
                    'FIRST_TIME' => $firstTime,
                    'LAST_DATE'  => $lastDate,
                    'LAST_TIME'  => $lastTime,
                ];
            }
        }

        return [
            'FIRST_DATE' => '',
            'FIRST_TIME' => '',
            'LAST_DATE'  => '',
            'LAST_TIME'  => '',
        ];
    }
    public function GetFirst(int $ID)
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'hemodialysis.MACHINE_NO',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', c.MIDDLE_NAME, IF(c.SALUTATION IS NOT NULL AND c.SALUTATION != '', CONCAT(' .', c.SALUTATION), '')) as CONTACT_NAME"),
                'c.DATE_OF_BIRTH',
                'c.PIN as PHIC_NO',
                'PRE_WEIGHT',
                'PRE_BLOOD_PRESSURE',
                'PRE_BLOOD_PRESSURE2',
                'PRE_HEART_RATE',
                'PRE_O2_SATURATION',
                'PRE_TEMPERATURE',
                'POST_WEIGHT',
                'POST_BLOOD_PRESSURE',
                'POST_BLOOD_PRESSURE2',
                'POST_HEART_RATE',
                'POST_O2_SATURATION',
                'POST_TEMPERATURE',
                'hemodialysis.CUSTOMER_ID',
                'hemodialysis.LOCATION_ID',
                'SE_DETAILS',
                'SO_DETAILS',
                'BFR',
                'DFR',
                'DURATION',
                'HEPARIN',
                'REUSE_NO',
                'FLUSHING',
                'DIALYZER',
                'DIALSATE_N',
                'DIALSATE_K',
                'DIALSATE_C',
                'PRE_AMBULATORY',
                'PRE_AMBULATORY_W_ASSIT',
                'PRE_WHEEL_CHAIR',
                'PRE_CONSCIOUS',
                'PRE_COHERENT',
                'PRE_DISORIENTED',
                'PRE_DROWSY',
                'PRE_CLEAR',
                'PRE_CRACKLES',
                'PRE_RHONCHI',
                'PRE_WHEEZES',
                'PRE_RALES',
                'PRE_DISTENDED_JUGULAR_VIEW',
                'PRE_ASCITES',
                'PRE_EDEMA',
                'PRE_LOCATION',
                'PRE_LOCATION_NOTES',
                'PRE_DEPTH',
                'PRE_DEPTH_NOTES',
                'PRE_REGULAR',
                'PRE_IRREGULAR',
                'POST_AMBULATORY',
                'POST_AMBULATORY_W_ASSIT',
                'POST_WHEEL_CHAIR',
                'POST_CONSCIOUS',
                'POST_COHERENT',
                'POST_DISORIENTED',
                'POST_DROWSY',
                'POST_CLEAR',
                'POST_CRACKLES',
                'POST_RHONCHI',
                'POST_WHEEZES',
                'POST_RALES',
                'POST_DISTENDED_JUGULAR_VIEW',
                'POST_ASCITES',
                'POST_EDEMA',
                'POST_LOCATION',
                'POST_LOCATION_NOTES',
                'POST_DEPTH',
                'POST_DEPTH_NOTES',
                'POST_REGULAR',
                'POST_IRREGULAR',
                'SC_MACHINE_TEST',
                'SC_SECURED_CONNECTIONS',
                'SC_SALINE_LINE_DOUBLE_CLAMP',
                'SC_CONDUCTIVITY',
                'SC_DIALYSATE_TEMP',
                'SC_RESIDUAL_TEST_NEGATIVE',
                'DB_STANDARD_HCOA',
                'DB_ACID',
                'UF_GOAL',
                'AT_FISTULA',
                'AT_GRAFT',
                'AT_RIGHT',
                'B_STRONG',
                'B_WEEK',
                'B_ABSENT',
                'T_STRONG',
                'T_WEAK',
                'T_ABSENT',
                'H_PRESENT',
                'H_ABSENT',
                'H_OTHER_NOTES',
                'CVC_SUBCATH',
                'CVC_JUGCATH',
                'CVC_FEMCATCH',
                'CVC_PERMACATH',
                'CVC_RIGHT',
                'CVC_LEFT',
                'CVC_GOOD_FLOW_A',
                'CVC_GOOD_FLOW_V',
                'CVC_W_RESISTANCE_A',
                'CVC_W_RESISTANCE_V',
                'CVC_CLOTTED_A',
                'CVC_CLOTTED_V',
                'AT_LEFT',
                'EMPLOYEE_ID',
                'DRY_WEIGHT',
                'RML',
                'HEPA_PROFILE',
                'CXR',
                'OTHER_INPUT',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->where('hemodialysis.ID', '=', $ID)
            ->first();

        return $result;
    }
    public function PreSave(string $DATE, string $CODE, int $CUSTOMER_ID, int $LOCATION_ID)
    {
        $NO_OF_TREATMENT = 0;
        $MACHINE_NO      = 0;
        $ID              = (int) $this->object->ObjectNextID('HEMODIALYSIS');
        $OBJECT_TYPE     = (int) $this->object->ObjectTypeID('HEMODIALYSIS');
        $isLocRef        = boolval($this->systemSettingServices->GetValue('IncRefNoByLocation'));

        Hemodialysis::create([
            'ID'              => $ID,
            'RECORDED_ON'     => $this->dateServices->Now(),
            'CODE'            => $CODE !== '' ? $CODE : $this->object->GetSequence($OBJECT_TYPE, $isLocRef ? $LOCATION_ID : null),
            'DATE'            => $DATE,
            'CUSTOMER_ID'     => $CUSTOMER_ID,
            'LOCATION_ID'     => $LOCATION_ID,
            'USER_ID'         => $this->user->UserId(),
            'NO_OF_TREATMENT' => $NO_OF_TREATMENT,
            'MACHINE_NO'      => $MACHINE_NO,
            'STATUS_ID'       => 1,
            'STATUS_DATE'     => $this->dateServices->Now(),
        ]);

        return $ID;
    }
    public function CheckingExistsThatDay(string $DATE, int $CUSTOMER_ID, int $LOCATION_ID): bool
    {
        $isExist = (bool) Hemodialysis::where('DATE', $DATE)
            ->where('CUSTOMER_ID', $CUSTOMER_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->exists();

        return $isExist;
    }
    private function GetPreviousTreatment(int $HEMO_ID, int $CUSTOMER_ID, string $DATE, int $LOCATION_ID)
    {
        $result = Hemodialysis::where('CUSTOMER_ID', '=', $CUSTOMER_ID)
            ->where('DATE', '<', $DATE)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('ID', '<>', $HEMO_ID)
            ->where('STATUS_ID', '=', '2')
            ->orderBy('DATE', 'desc')
            ->first();

        return $result;
    }
    public function GetOtherDetailsDefault(int $HEMO_ID, int $CUSTOMER_ID, string $DATE, int $LOCATION_ID)
    {
        //Get Previous
        $data = $this->GetPreviousTreatment($HEMO_ID, $CUSTOMER_ID, $DATE, $LOCATION_ID);
        if ($data) {
            Hemodialysis::where('CUSTOMER_ID', $CUSTOMER_ID)
                ->where('DATE', $DATE)
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('ID', $HEMO_ID)
                ->update([
                    'SE_DETAILS'      => $data->SE_DETAILS_NEXT ?? null,
                    'SE_DETAILS_NEXT' => '',
                    'SO_DETAILS'      => $data->ORDER_USE_NEXT == true ? $data->SO_DETAILS ?? null : null,
                    'BFR'             => $data->BFR ?? null,
                    'DFR'             => $data->DFR ?? null,
                    'DURATION'        => $data->DURATION ?? null,
                    'DIALYZER'        => $data->DIALYZER ?? null,
                    'HEPARIN'         => $data->HEPARIN ?? null,
                    'REUSE_NO'        => $data->REUSE_NEXT ?? null,
                    'FLUSHING'        => $data->FLUSHING ?? null,
                    'DIALSATE_N'      => $data->DIALSATE_N ?? null,
                    'DIALSATE_K'      => $data->DIALSATE_K ?? null,
                    'DIALSATE_C'      => $data->DIALSATE_C ?? null,
                    'DRY_WEIGHT'      => $data->DRY_WEIGHT ?? null,
                    'RML'             => $data->RML ?? null,
                    'HEPA_PROFILE'    => $data->HEPA_PROFILE ?? null,
                    'CXR'             => $data->CXR ?? null,
                ]);
        }
    }
    public function PreUpdate(int $ID, string $DATE, string $CODE, int $CUSTOMER_ID, int $LOCATION_ID)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'CODE'        => $CODE,
                'DATE'        => $DATE,
                'CUSTOMER_ID' => $CUSTOMER_ID,
                'LOCATION_ID' => $LOCATION_ID,
            ]);
    }
    public function Update(int $ID, string $PRE_WEIGHT, string $PRE_BLOOD_PRESSURE, string $PRE_BLOOD_PRESSURE2, string $PRE_HEART_RATE, string $PRE_O2_SATURATION, string $PRE_TEMPERATURE, string $POST_WEIGHT, string $POST_BLOOD_PRESSURE, string $POST_BLOOD_PRESSURE2, string $POST_HEART_RATE, string $POST_O2_SATURATION, string $POST_TEMPERATURE, string $TIME_START, string $TIME_END, bool $IS_INCOMPLETE)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'PRE_WEIGHT'           => $PRE_WEIGHT,
                'PRE_BLOOD_PRESSURE'   => $PRE_BLOOD_PRESSURE,
                'PRE_BLOOD_PRESSURE2'  => $PRE_BLOOD_PRESSURE2,
                'PRE_HEART_RATE'       => $PRE_HEART_RATE,
                'PRE_O2_SATURATION'    => $PRE_O2_SATURATION,
                'PRE_TEMPERATURE'      => $PRE_TEMPERATURE,
                'POST_WEIGHT'          => $POST_WEIGHT,
                'POST_BLOOD_PRESSURE'  => $POST_BLOOD_PRESSURE,
                'POST_BLOOD_PRESSURE2' => $POST_BLOOD_PRESSURE2,
                'POST_HEART_RATE'      => $POST_HEART_RATE,
                'POST_O2_SATURATION'   => $POST_O2_SATURATION,
                'POST_TEMPERATURE'     => $POST_TEMPERATURE,
                'TIME_START'           => $TIME_START != "" ? $TIME_START : null,
                'TIME_END'             => $TIME_END != "" ? $TIME_END : null,
                'IS_INCOMPLETE'        => $IS_INCOMPLETE,

            ]);
    }
    public function UpdateEmployee(int $ID, int $EMPLOYEE_ID)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'EMPLOYEE_ID' => $EMPLOYEE_ID > 0 ? $EMPLOYEE_ID : null,
            ]);
    }
    public function SaveOthers(
        int $ID,
        string $SE_DETAILS,
        string $SO_DETAILS,
        int $BFR,
        int $DFR,
        int $DURATION,
        string $DIALYZER,
        string $DIALSATE_N,
        string $DIALSATE_K,
        string $DIALSATE_C,
        bool $DETAILS_USE_NEXT,
        bool $ORDER_USE_NEXT,
        string $SE_DETAILS_NEXT,
        string $HEPARIN,
        string $REUSE_NO,
        string $REUSE_NEXT,
        string $FLUSHING,
        string $UF_GOAL,
        bool $DB_STANDARD_HCOA,
        bool $DB_ACID,
        bool $SC_MACHINE_TEST,
        bool $SC_SECURED_CONNECTIONS,
        bool $SC_SALINE_LINE_DOUBLE_CLAMP,
        string $SC_CONDUCTIVITY,
        string $SC_DIALYSATE_TEMP,
        bool $SC_RESIDUAL_TEST_NEGATIVE,
        bool $AT_FISTULA,
        bool $AT_GRAFT,
        bool $AT_RIGHT,
        bool $AT_LEFT,
        bool $B_STRONG,
        bool $B_WEEK,
        bool $B_ABSENT,
        bool $T_STRONG,
        bool $T_WEAK,
        bool $T_ABSENT,
        bool $H_PRESENT,
        bool $H_ABSENT,
        string $H_OTHER_NOTES,
        bool $CVC_SUBCATH,
        bool $CVC_JUGCATH,
        bool $CVC_FEMCATCH,
        bool $CVC_PERMACATH,
        bool $CVC_RIGHT,
        bool $CVC_LEFT,
        bool $CVC_GOOD_FLOW_A,
        bool $CVC_GOOD_FLOW_V,
        bool $CVC_W_RESISTANCE_A,
        bool $CVC_W_RESISTANCE_V,
        bool $CVC_CLOTTED_A,
        bool $CVC_CLOTTED_V,
        bool $PRE_AMBULATORY,
        bool $PRE_AMBULATORY_W_ASSIT,
        bool $PRE_WHEEL_CHAIR,
        bool $PRE_CONSCIOUS,
        bool $PRE_COHERENT,
        bool $PRE_DISORIENTED,
        bool $PRE_DROWSY,
        bool $PRE_CLEAR,
        bool $PRE_CRACKLES,
        bool $PRE_RHONCHI,
        bool $PRE_WHEEZES,
        bool $PRE_RALES,
        bool $PRE_DISTENDED_JUGULAR_VIEW,
        bool $PRE_ASCITES,
        bool $PRE_EDEMA,
        bool $PRE_LOCATION,
        string $PRE_LOCATION_NOTES,
        bool $PRE_DEPTH,
        string $PRE_DEPTH_NOTES,
        bool $PRE_REGULAR,
        bool $PRE_IRREGULAR,
        bool $POST_AMBULATORY,
        bool $POST_AMBULATORY_W_ASSIT,
        bool $POST_WHEEL_CHAIR,
        bool $POST_CONSCIOUS,
        bool $POST_COHERENT,
        bool $POST_DISORIENTED,
        bool $POST_DROWSY,
        bool $POST_CLEAR,
        bool $POST_CRACKLES,
        bool $POST_RHONCHI,
        bool $POST_WHEEZES,
        bool $POST_RALES,
        bool $POST_DISTENDED_JUGULAR_VIEW,
        bool $POST_ASCITES,
        bool $POST_EDEMA,
        bool $POST_LOCATION,
        string $POST_LOCATION_NOTES,
        bool $POST_DEPTH,
        string $POST_DEPTH_NOTES,
        bool $POST_REGULAR,
        bool $POST_IRREGULAR,
        int $MACHINE_NO,
        string $DRY_WEIGHT,
        string $RML,
        string $HEPA_PROFILE,
        string $CXR,
        string $OTHER_INPUT
    ) {
        Hemodialysis::where('ID', $ID)
            ->update([

                'PRE_AMBULATORY'              => $PRE_AMBULATORY,
                'PRE_AMBULATORY_W_ASSIT'      => $PRE_AMBULATORY_W_ASSIT,
                'PRE_WHEEL_CHAIR'             => $PRE_WHEEL_CHAIR,
                'PRE_CONSCIOUS'               => $PRE_CONSCIOUS,
                'PRE_COHERENT'                => $PRE_COHERENT,
                'PRE_DISORIENTED'             => $PRE_DISORIENTED,
                'PRE_DROWSY'                  => $PRE_DROWSY,
                'PRE_CLEAR'                   => $PRE_CLEAR,
                'PRE_CRACKLES'                => $PRE_CRACKLES,
                'PRE_RHONCHI'                 => $PRE_RHONCHI,
                'PRE_WHEEZES'                 => $PRE_WHEEZES,
                'PRE_RALES'                   => $PRE_RALES,
                'PRE_DISTENDED_JUGULAR_VIEW'  => $PRE_DISTENDED_JUGULAR_VIEW,
                'PRE_ASCITES'                 => $PRE_ASCITES,
                'PRE_EDEMA'                   => $PRE_EDEMA,
                'PRE_LOCATION'                => $PRE_LOCATION,
                'PRE_LOCATION_NOTES'          => $PRE_LOCATION_NOTES,
                'PRE_DEPTH'                   => $PRE_DEPTH,
                'PRE_DEPTH_NOTES'             => $PRE_DEPTH_NOTES,
                'PRE_REGULAR'                 => $PRE_REGULAR,
                'PRE_IRREGULAR'               => $PRE_IRREGULAR,
                'POST_AMBULATORY'             => $POST_AMBULATORY,
                'POST_AMBULATORY_W_ASSIT'     => $POST_AMBULATORY_W_ASSIT,
                'POST_WHEEL_CHAIR'            => $POST_WHEEL_CHAIR,
                'POST_CONSCIOUS'              => $POST_CONSCIOUS,
                'POST_COHERENT'               => $POST_COHERENT,
                'POST_DISORIENTED'            => $POST_DISORIENTED,
                'POST_DROWSY'                 => $POST_DROWSY,
                'POST_CLEAR'                  => $POST_CLEAR,
                'POST_CRACKLES'               => $POST_CRACKLES,
                'POST_RHONCHI'                => $POST_RHONCHI,
                'POST_WHEEZES'                => $POST_WHEEZES,
                'POST_RALES'                  => $POST_RALES,
                'POST_DISTENDED_JUGULAR_VIEW' => $POST_DISTENDED_JUGULAR_VIEW,
                'POST_ASCITES'                => $POST_ASCITES,
                'POST_EDEMA'                  => $POST_EDEMA,
                'POST_LOCATION'               => $POST_LOCATION,
                'POST_LOCATION_NOTES'         => $POST_LOCATION_NOTES,
                'POST_DEPTH'                  => $POST_DEPTH,
                'POST_DEPTH_NOTES'            => $POST_DEPTH_NOTES,
                'POST_REGULAR'                => $POST_REGULAR,
                'POST_IRREGULAR'              => $POST_IRREGULAR,
                'CVC_CLOTTED_V'               => $CVC_CLOTTED_V,
                'CVC_CLOTTED_A'               => $CVC_CLOTTED_A,
                'CVC_W_RESISTANCE_V'          => $CVC_W_RESISTANCE_V,
                'CVC_W_RESISTANCE_A'          => $CVC_W_RESISTANCE_A,
                'CVC_GOOD_FLOW_V'             => $CVC_GOOD_FLOW_V,
                'CVC_GOOD_FLOW_A'             => $CVC_GOOD_FLOW_A,
                'CVC_LEFT'                    => $CVC_LEFT,
                'CVC_RIGHT'                   => $CVC_RIGHT,
                'CVC_PERMACATH'               => $CVC_PERMACATH,
                'CVC_FEMCATCH'                => $CVC_FEMCATCH,
                'CVC_JUGCATH'                 => $CVC_JUGCATH,
                'CVC_SUBCATH'                 => $CVC_SUBCATH,
                'AT_FISTULA'                  => $AT_FISTULA,
                'AT_GRAFT'                    => $AT_GRAFT,
                'AT_RIGHT'                    => $AT_RIGHT,
                'AT_LEFT'                     => $AT_LEFT,
                'B_STRONG'                    => $B_STRONG,
                'B_WEEK'                      => $B_WEEK,
                'B_ABSENT'                    => $B_ABSENT,
                'T_STRONG'                    => $T_STRONG,
                'T_WEAK'                      => $T_WEAK,
                'T_ABSENT'                    => $T_ABSENT,
                'H_PRESENT'                   => $H_PRESENT,
                'H_ABSENT'                    => $H_ABSENT,
                'H_OTHER_NOTES'               => $H_OTHER_NOTES,
                'SE_DETAILS'                  => $SE_DETAILS,
                'SO_DETAILS'                  => $SO_DETAILS,
                'BFR'                         => $BFR,
                'DFR'                         => $DFR,
                'DURATION'                    => $DURATION,
                'DIALYZER'                    => $DIALYZER,
                'DIALSATE_N'                  => $DIALSATE_N,
                'DIALSATE_K'                  => $DIALSATE_K,
                'DIALSATE_C'                  => $DIALSATE_C,
                'DETAILS_USE_NEXT'            => $DETAILS_USE_NEXT,
                'ORDER_USE_NEXT'              => $ORDER_USE_NEXT,
                'SE_DETAILS_NEXT'             => $SE_DETAILS_NEXT,
                'HEPARIN'                     => $HEPARIN,
                'REUSE_NO'                    => $REUSE_NO,
                'REUSE_NEXT'                  => $REUSE_NEXT,
                'FLUSHING'                    => $FLUSHING,
                'UF_GOAL'                     => $UF_GOAL,
                'DB_STANDARD_HCOA'            => $DB_STANDARD_HCOA,
                'DB_ACID'                     => $DB_ACID,
                'SC_MACHINE_TEST'             => $SC_MACHINE_TEST,
                'SC_SECURED_CONNECTIONS'      => $SC_SECURED_CONNECTIONS,
                'SC_SALINE_LINE_DOUBLE_CLAMP' => $SC_SALINE_LINE_DOUBLE_CLAMP,
                'SC_CONDUCTIVITY'             => $SC_CONDUCTIVITY,
                'SC_DIALYSATE_TEMP'           => $SC_DIALYSATE_TEMP,
                'SC_RESIDUAL_TEST_NEGATIVE'   => $SC_RESIDUAL_TEST_NEGATIVE,
                'MACHINE_NO'                  => $MACHINE_NO,
                'DRY_WEIGHT'                  => $DRY_WEIGHT,
                'RML'                         => $RML,
                'HEPA_PROFILE'                => $HEPA_PROFILE,
                'CXR'                         => $CXR,
                'OTHER_INPUT'                 => $OTHER_INPUT,
            ]);
    }
    public function UpdatedSpecialOrder(int $ID): bool
    {
        $isBool = Hemodialysis::where('ID', $ID)->first()->DETAILS_USE_NEXT ?? false;

        if ($isBool) {
            Hemodialysis::where('ID', $ID)
                ->update(['DETAILS_USE_NEXT' => false]);
            return false;
        }

        Hemodialysis::where('ID', $ID)
            ->update(['DETAILS_USE_NEXT' => true]);

        return true;
    }
    public function UpdatedStandingOrder(int $ID): bool
    {
        $isBool = Hemodialysis::where('ID', $ID)->first()->ORDER_USE_NEXT ?? false;

        if ($isBool) {
            Hemodialysis::where('ID', $ID)->update(['ORDER_USE_NEXT' => false]);
            return false;
        }
        Hemodialysis::where('ID', $ID)->update(['ORDER_USE_NEXT' => true]);
        return true;
    }
    public function UpdateFile(int $ID, $FILE_NAME, $FILE_PATH)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'FILE_NAME' => $FILE_NAME,
                'FILE_PATH' => $FILE_PATH,
            ]);
    }
    public function StatusUpdate(int $ID, int $STATUS)
    {
        Hemodialysis::where('ID', $ID)
            ->update([
                'STATUS_ID'   => $STATUS,
                'STATUS_DATE' => $this->dateServices->Now(),
            ]);
    }
    public function Delete(int $id)
    {
        HemoNurseNotes::where('HEMO_ID', '=', $id)->delete();
        HemodialysisItems::where('HEMO_ID', '=', $id)->delete();
        Hemodialysis::where('ID', '=', $id)->delete();
    }
    public function SearchList($search, int $LOCATION_ID)
    {

        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as PATIENT_NAME"),
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                    ->orWhere('c.NAME', 'like', '%' . $search . '%')
                    ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
            })

            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->get();

        return $result;
    }
    public function SearchListbyShift($search, int $LOCATION_ID, int $SHIFT_ID, string $DATE)
    {

        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as PATIENT_NAME"),
                'sh.NAME as SHIFT',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->join('schedules as s', function ($join) {
                $join->On('s.CONTACT_ID', 'hemodialysis.CUSTOMER_ID');
                $join->On('s.SCHED_DATE', 'hemodialysis.DATE');
                $join->On('s.LOCATION_ID', 'hemodialysis.LOCATION_ID');
            })
            ->join('shift AS sh', 'sh.ID', '=', 's.SHIFT_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use (&$search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when($SHIFT_ID > 0, function ($query) use (&$SHIFT_ID) {
                $query->where('s.SHIFT_ID', $SHIFT_ID);
            })
            ->where('hemodialysis.DATE', $DATE)
            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->get();

        return $result;
    }
    public function Search($search, int $LOCATION_ID, int $perPage, $DATE)
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'c.ID as PATIENT_ID',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_BLOOD_PRESSURE2',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS',
                'hemodialysis.STATUS_ID',
                'hemodialysis.FILE_PATH',
                'hemodialysis.IS_INCOMPLETE',
                'e.NAME as NURSE_NAME',
                DB::raw(value: "(SELECT IF(COUNT(*) = 2, 'BOTH', MAX(t.DESCRIPTION)) FROM hemodialysis_items AS i JOIN item AS t ON t.id = i.ITEM_ID WHERE i.HEMO_ID = hemodialysis.ID AND i.ITEM_ID IN (6, 7) GROUP BY i.HEMO_ID) AS ACCESS_TYPE"),

                // DB::raw('(SELECT IF(count(*) > 0,true,false) from hemodialysis_items as i where i.HEMO_ID = hemodialysis.ID and i.IS_JUSTIFY = 1  ) as JUSTIFY'),
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->leftJoin('hemo_status as s', 's.ID', '=', 'hemodialysis.STATUS_ID')
            ->leftJoin('contact as e', 'e.ID', '=', 'hemodialysis.EMPLOYEE_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('e.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->when(! $search, function ($query) use ($DATE) {
                if ($DATE != null) {
                    $query->where('hemodialysis.DATE', '=', $DATE);
                }

            })

            ->orWhere(function ($q) use ($DATE, $LOCATION_ID) {
                $q->where('hemodialysis.DATE', '<=', $DATE)
                    ->where('hemodialysis.STATUS_ID', '=', 4)
                    ->where('hemodialysis.LOCATION_ID', $LOCATION_ID);
            })

            ->orderBy('hemodialysis.STATUS_ID', 'desc')
            ->orderBy('hemodialysis.DATE', 'desc')
            ->limit(100)
            ->get();

        return $result;
    }

    public function GetUnpostedTreatment()
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.DATE',
                'hemodialysis.CUSTOMER_ID',
                'hemodialysis.LOCATION_ID',
            ])
            ->where('hemodialysis.STATUS_ID', 4)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->get();

        return $result;
    }
    public function UnpostedTratment(int $LOCATION_ID, $search)
    {
        $result = Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as CONTACT_NAME"),
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_BLOOD_PRESSURE2',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS',
                'hemodialysis.STATUS_ID',
                'hemodialysis.FILE_PATH',
                'hemodialysis.IS_INCOMPLETE',
                DB::raw('(SELECT IF(count(sc.ID) > 0,true,false) from service_charges as sc where  sc.PATIENT_ID = hemodialysis.CUSTOMER_ID and sc.LOCATION_ID =  hemodialysis.LOCATION_ID and sc.DATE = hemodialysis.DATE ) as IS_SC'),
                'e.NAME as NURSE_NAME',
                DB::raw(value: "(SELECT IF(COUNT(*) = 2, 'BOTH', MAX(t.DESCRIPTION)) FROM hemodialysis_items AS i JOIN item AS t ON t.id = i.ITEM_ID WHERE i.HEMO_ID = hemodialysis.ID AND i.ITEM_ID IN (6, 7) GROUP BY i.HEMO_ID) AS ACCESS_TYPE"),

            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'hemodialysis.CUSTOMER_ID')
            ->leftJoin('hemo_status as s', 's.ID', '=', 'hemodialysis.STATUS_ID')
            ->leftJoin('contact as e', 'e.ID', '=', 'hemodialysis.EMPLOYEE_ID')
            ->join('location as l', function ($join) use (&$LOCATION_ID) {
                $join->on('l.ID', '=', 'hemodialysis.LOCATION_ID');
                if ($LOCATION_ID > 0) {
                    $join->where('l.ID', $LOCATION_ID);
                }
            })
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%')
                        ->orWhere('c.NAME', 'like', '%' . $search . '%')
                        ->orWhere('c.PRINT_NAME_AS', 'like', '%' . $search . '%')
                        ->orWhere('e.PRINT_NAME_AS', 'like', '%' . $search . '%');
                });
            })
            ->where('hemodialysis.STATUS_ID', 4)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->limit(20)
            ->get();

        return $result;
    }
    public function PatientRecord($search, int $CONTACT_ID, int $perPage, int $LOCK_LOCATION_ID)
    {

        return Hemodialysis::query()
            ->select([
                'hemodialysis.ID',
                'hemodialysis.CODE',
                'hemodialysis.DATE',
                'l.NAME as LOCATION_NAME',
                'hemodialysis.PRE_WEIGHT',
                'hemodialysis.PRE_BLOOD_PRESSURE',
                'hemodialysis.PRE_BLOOD_PRESSURE2',
                'hemodialysis.PRE_HEART_RATE',
                'hemodialysis.PRE_O2_SATURATION',
                'hemodialysis.PRE_TEMPERATURE',
                'hemodialysis.POST_WEIGHT',
                'hemodialysis.POST_BLOOD_PRESSURE',
                'hemodialysis.POST_BLOOD_PRESSURE2',
                'hemodialysis.POST_HEART_RATE',
                'hemodialysis.POST_O2_SATURATION',
                'hemodialysis.POST_TEMPERATURE',
                'hemodialysis.TIME_START',
                'hemodialysis.TIME_END',
                's.DESCRIPTION as STATUS',
                'hemodialysis.FILE_PATH',
            ])
            ->leftJoin('hemo_status as s', 's.ID', '=', 'hemodialysis.STATUS_ID')
            ->join('location as l', 'l.ID', '=', 'hemodialysis.LOCATION_ID')
            ->where('hemodialysis.CUSTOMER_ID', $CONTACT_ID)
            ->when($search, function ($query) use (&$search) {
                $query->where(function ($q) use ($search) {
                    $q->where('hemodialysis.CODE', 'like', '%' . $search . '%');
                });
            })
            ->when($LOCK_LOCATION_ID > 0, function ($query) use (&$LOCK_LOCATION_ID) {
                $query->where('hemodialysis.LOCATION_ID', $LOCK_LOCATION_ID);
            })
            ->orderBy('ID', 'desc')
            ->orderBy('hemodialysis.ID', 'desc')
            ->paginate($perPage);
    }
    public function QuickFilterByDaily(string $DATE, int $LOCATION_ID, $search): object
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT"),
                'contact.PIN',
                DB::raw('count(h.ID) as TOTAL_HEMO'),
                DB::raw('min(h.DATE) as FIRST_DATE'),
                DB::raw('max(h.DATE) as LAST_DATE'),

            ])
            ->join('hemodialysis as h', 'h.CUSTOMER_ID', '=', 'contact.ID')
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'h.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'h.LOCATION_ID');
                $join->on('s.DATE', '=', 'h.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID', 2)
            ->where('s.USE_PHIC', '=', 0)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.STATUS_ID', 2)
            ->where('h.DATE', $DATE)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('philhealth as l')
                    ->whereColumn('l.CONTACT_ID', '=', 'h.CUSTOMER_ID')
                    ->whereColumn('l.LOCATION_ID', '=', 'h.LOCATION_ID')
                    ->whereColumn('l.DATE_ADMITTED', '=', 'h.DATE')
                    ->whereColumn('l.DATE_DISCHARGED', '=', 'h.DATE');
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('contact.NAME', 'like', '%' . $search . '%');
            })
            ->groupBy(['contact.ID', 'contact.NAME', 'contact.PIN', 'contact.LAST_NAME', 'contact.FIRST_NAME', 'contact.MIDDLE_NAME', 'contact.SALUTATION'])
            ->orderBy('contact.LAST_NAME')
            ->get();
        return $result;
    }
    public function QuickFilterByDateRange(string $DATE_FORM, string $DATE_TO, int $LOCATION_ID, $search, bool $isExists = false): object
    {
        $result = Contacts::query()
            ->select([
                'contact.ID',
                DB::raw("CONCAT(contact.LAST_NAME, ', ', contact.FIRST_NAME, ' .', LEFT(contact.MIDDLE_NAME, 1), IF(contact.SALUTATION IS NOT NULL AND contact.SALUTATION != '', CONCAT(' .', contact.SALUTATION), '')) as PATIENT"),
                'contact.PIN',
                DB::raw('count(h.ID) as TOTAL_HEMO'),
                DB::raw('min(h.DATE) as FIRST_DATE'),
                DB::raw('max(h.DATE) as LAST_DATE'),

            ])
            ->join('hemodialysis as h', 'h.CUSTOMER_ID', '=', 'contact.ID')
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'h.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'h.LOCATION_ID');
                $join->on('s.DATE', '=', 'h.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID', 2)
            ->where('s.USE_PHIC', '=', 0)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.STATUS_ID', 2)
            ->whereBetween('h.DATE', [$DATE_FORM, $DATE_TO])
            ->whereNotExists(function ($query) use (&$DATE_FORM, &$DATE_TO) {
                $query->select(DB::raw(1))
                    ->from('philhealth as l')
                    ->whereColumn('l.CONTACT_ID', 'h.CUSTOMER_ID')
                    ->whereColumn('l.LOCATION_ID', 'h.LOCATION_ID')
                    ->where('l.DATE_ADMITTED', '>=', $DATE_FORM)
                    ->where('l.DATE_DISCHARGED', '<=', $DATE_TO);
            })
            ->when($search, function ($query) use (&$search) {
                $query->where('contact.NAME', 'like', '%' . $search . '%');
            })

            ->groupBy(['contact.ID', 'contact.NAME', 'contact.PIN', 'contact.LAST_NAME', 'contact.FIRST_NAME', 'contact.MIDDLE_NAME', 'contact.SALUTATION'])
            ->orderBy('contact.LAST_NAME')
            ->get();

        return $result;
    }
    public function ShowLastTreatment(int $CONTACT_ID, int $LOCATION_ID, string $DATE)
    {
        $result = Hemodialysis::query()
            ->select([
                'PRE_WEIGHT',
                'PRE_BLOOD_PRESSURE',
                'PRE_HEART_RATE',
                'PRE_O2_SATURATION',
                'PRE_TEMPERATURE',
                'POST_WEIGHT',
                'POST_BLOOD_PRESSURE',
                'POST_HEART_RATE',
                'POST_O2_SATURATION',
                'POST_TEMPERATURE',
                'PRE_BLOOD_PRESSURE2',
                'POST_BLOOD_PRESSURE2',
            ])
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', '<', $DATE)
            ->where('STATUS_ID', 2)
            ->orderBy('DATE', 'desc')
            ->first();

        return $result;
    }
    public function SetIsIncomplete(int $HEMO_ID, $isInCompleted = false)
    {
        Hemodialysis::where('ID', $HEMO_ID)->update(['IS_INCOMPLETE' => $isInCompleted]);
    }
    private function getLine($HEMO_ID): int
    {
        return (int) HemodialysisItems::where('HEMO_ID', $HEMO_ID)->max('LINE_NO');
    }
    private function getGetLastitemNew(int $ITEM_ID, $LOCATION_ID, $PATIENT_ID, $DATE_TREATMENT): string
    {
        $result = HemodialysisItems::query()
            ->select([
                'h.DATE',
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.CUSTOMER_ID', $PATIENT_ID)
            ->where('hemodialysis_items.IS_NEW', true)
            ->where('h.DATE', '<=', $DATE_TREATMENT)
            ->where('h.STATUS_ID', 2)
            ->orderBy('h.DATE', 'desc')
            ->first();

        return $result->DATE ?? '';
    }
    public function getItemTotalUsed(int $ITEM_ID, $LOCATION_ID, $PATIENT_ID, $DATE_TREATMENT): int
    {
        $newitembyDate = $this->getGetLastitemNew($ITEM_ID, $LOCATION_ID, $PATIENT_ID, $DATE_TREATMENT);

        if (! $newitembyDate) {

            return 0;
        }

        $result_new = HemodialysisItems::query()
            ->select([
                DB::raw('count(*) as total_count'),
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.CUSTOMER_ID', $PATIENT_ID)
            ->whereBetween('h.DATE', [$newitembyDate, $DATE_TREATMENT])
            ->first();

        return (int) $result_new->total_count ?? 0;
    }
    public function ItemStoreExists(int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW, bool $IS_DEFAULT): bool
    {
        try {
            $IsExist = HemodialysisItems::where('HEMO_ID', $HEMO_ID)
                ->where('ITEM_ID', $ITEM_ID)
                ->where('QUANTITY', $QUANTITY)
                ->where('UNIT_ID', $UNIT_ID > 0 ? $UNIT_ID : null)
                ->where('UNIT_BASE_QUANTITY', $UNIT_BASE_QUANTITY)
                ->where('IS_NEW', $IS_NEW)
                ->where('IS_DEFAULT', $IS_DEFAULT)
                ->exists();
        } catch (\Throwable $th) {
            $IsExist = true;
        }

        return $IsExist;
    }
    public function ItemStore(int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW, bool $IS_DEFAULT, bool $IS_CASHIER = false, $SC_ITEM_ID = null, $SK_LINE_ID = null, $IS_JUSTIFY = false, $JUSTIFY_NOTES = null): int
    {
        $ID = (int) $this->object->ObjectNextID('HEMODIALYSIS_ITEMS');

        $LINE_NO = (int) $this->getLine($HEMO_ID) + 1;

        HemodialysisItems::create([
            'ID'                 => $ID,
            'HEMO_ID'            => $HEMO_ID,
            'LINE_NO'            => $LINE_NO,
            'ITEM_ID'            => $ITEM_ID,
            'QUANTITY'           => $QUANTITY,
            'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
            'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
            'IS_NEW'             => $IS_NEW,
            'IS_DEFAULT'         => $IS_DEFAULT,
            'IS_POST'            => false,
            'SC_ITEM_ID'         => $SC_ITEM_ID,
            'IS_CASHIER'         => $IS_CASHIER,
            'SK_LINE_ID'         => $SK_LINE_ID,
            'IS_JUSTIFY'         => $IS_JUSTIFY,
            'JUSTIFY_NOTES'      => $JUSTIFY_NOTES,
        ]);

        return $ID;
    }
    public function ItemUpdate(int $ID, int $HEMO_ID, int $ITEM_ID, float $QUANTITY, int $UNIT_ID, float $UNIT_BASE_QUANTITY, bool $IS_NEW, bool $IS_DEFAULT, bool $ON_CHANGE_POST = false)
    {
        // Check if the item exists
        $dataCheck = HemodialysisItems::where('ID', '=', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('IS_DEFAULT', '=', $IS_DEFAULT);

        $getData = $dataCheck;

        $list = $getData->first();

        if ($list->IS_POST == false) {
            $dataCheck->update([
                'QUANTITY'           => $QUANTITY,
                'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                'IS_NEW'             => $IS_NEW,
            ]);

            return;
        }

        if ($ON_CHANGE_POST == true) {
            $dataCheck->update(
                [
                    'QUANTITY'           => $QUANTITY,
                    'UNIT_ID'            => $UNIT_ID > 0 ? $UNIT_ID : null,
                    'UNIT_BASE_QUANTITY' => $UNIT_BASE_QUANTITY,
                    'IS_NEW'             => $IS_NEW,
                    'IS_POST'            => false,
                ]
            );
        }
    }
    public function updateIsPost(int $ID, int $HEMO_ID)
    {

        $data = HemodialysisItems::where('ID', '=', $ID)->where('HEMO_ID', '=', $HEMO_ID);
        $data->update(['IS_POST' => false]);
        $firstData = $data->first();
        $hemo      = $this->Get($HEMO_ID);

        if ($hemo && $firstData) {
            $this->itemInventoryServices->DeleteInv($firstData->ITEM_ID, $hemo->LOCATION_ID, 27, $firstData->ID, $hemo->DATE);
        }

    }

    public function ItemUpdateSC_ITEM_ID(int $ID, int $HEMO_ID, int $ITEM_ID, int $SC_ITEM_ID)
    {
        HemodialysisItems::where('ID', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->update([
                'SC_ITEM_ID' => $SC_ITEM_ID,
            ]);
    }
    public function IsExist_SC_ITEM(int $SC_ITEM_ID): bool
    {
        return HemodialysisItems::where('SC_ITEM_ID', '=', $SC_ITEM_ID)
            ->where('IS_CASHIER', '=', true)
            ->exists();
    }
    public function ItemDelete(int $ID, int $HEMO_ID, int $ITEM_ID, bool $IS_DEFAULT)
    {
        $itemData = $this->ItemGet($ID);
        if ($itemData) {
            if ($itemData->IS_POST) {
                $data = $this->Get($HEMO_ID);
                if ($data) {
                    $this->itemInventoryServices->DeleteInv(
                        $ITEM_ID,
                        $data->LOCATION_ID,
                        27,
                        $ID,
                        $data->DATE
                    );
                }
            }
        }

        HemodialysisItems::where('ID', '=', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('IS_DEFAULT', '=', $IS_DEFAULT)
            ->delete();

    }

    public function ItemDeleteTrigger(int $ID, int $HEMO_ID)
    {
        $dataList = HemodialysisItems::where('SK_LINE_ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->get();

        foreach ($dataList as $list) {
            $itemData = $this->ItemGet($list->ID);
            if ($itemData) {
                if ($itemData->IS_POST) {
                    $data = $this->Get($HEMO_ID);
                    if ($data) {
                        $this->itemInventoryServices->DeleteInv(
                            $list->ITEM_ID,
                            $data->LOCATION_ID,
                            27,
                            $list->ID,
                            $data->DATE
                        );
                    }
                }
            }
        }

        HemodialysisItems::where('SK_LINE_ID', '=', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->delete();
    }

    public function ItemDelete2(int $HEMO_ID, int $ITEM_ID, int $UNIT_ID, bool $IS_DEFAULT)
    {
        HemodialysisItems::where('HEMO_ID', $HEMO_ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('UNIT_ID', '=', $UNIT_ID)
            ->where('IS_DEFAULT', '=', $IS_DEFAULT)
            ->delete();
    }
    public function ItemUnposted($HEMO_ID)
    {
        HemodialysisItems::where('HEMO_ID', '=', $HEMO_ID)
            ->update([
                'IS_POST' => false,
            ]);
    }
    public function ItemGet(int $ID)
    {
        $result = HemodialysisItems::where('ID', '=', $ID)
            ->first();
        return $result;
    }
    public function MainToJournalExpense(int $HEMO_ID)
    {
        try {
            $acctID = $this->accountServices->EXPENSE_ACCOUNT_ID;
            $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hemodialysis_items.ITEM_ID and l.ID = h.LOCATION_ID";
            $result = HemodialysisItems::query()
                ->select([
                    'h.ID',
                    'h.CUSTOMER_ID as SUBSIDIARY_ID',
                    DB::raw("$acctID as ACCOUNT_ID"),
                    DB::raw("sum(((($exSQL) * hemodialysis_items.UNIT_BASE_QUANTITY ) *  hemodialysis_items.QUANTITY )) as AMOUNT"),
                    DB::raw('0 as ENTRY_TYPE'),
                ])
                ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
                ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
                ->leftJoin('item_group as g', 'g.ID', '=', 'item.GROUP_ID')
                ->leftJoin('item_sub_class as s', 's.ID', '=', 'item.SUB_CLASS_ID')
                ->leftJoin('item_class as c', 'c.ID', '=', 's.CLASS_ID')
                ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'hemodialysis_items.UNIT_ID')
                ->leftJoin('item_treatment as t', function ($q) {
                    $q->on('t.ITEM_ID', '=', 'hemodialysis_items.ITEM_ID');
                    $q->on('t.LOCATION_ID', '=', 'h.LOCATION_ID');
                })
                ->whereBetween('item.TYPE', ['0', '1'])
                ->where('hemodialysis_items.HEMO_ID', '=', $HEMO_ID)
                ->groupBy(['ID', 'SUBSIDIARY_ID', 'ACCOUNT_ID'])
                ->get();

            return $result;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return null;
        }
    }
    public function ItemToJournalAsset(int $HEMO_ID)
    {
        $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hemodialysis_items.ITEM_ID and l.ID = h.LOCATION_ID";
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.ITEM_ID as SUBSIDIARY_ID',
                'item.ASSET_ACCOUNT_ID as ACCOUNT_ID',
                DB::raw("((($exSQL) * hemodialysis_items.UNIT_BASE_QUANTITY ) *  hemodialysis_items.QUANTITY ) as AMOUNT"),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->leftJoin('item_group as g', 'g.ID', '=', 'item.GROUP_ID')
            ->leftJoin('item_sub_class as s', 's.ID', '=', 'item.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 's.CLASS_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'hemodialysis_items.UNIT_ID')
            ->leftJoin('item_treatment as t', function ($q) {
                $q->on('t.ITEM_ID', '=', 'hemodialysis_items.ITEM_ID');
                $q->on('t.LOCATION_ID', '=', 'h.LOCATION_ID');
            })
            ->whereBetween('item.TYPE', ['0', '1'])
            ->where('hemodialysis_items.HEMO_ID', '=', $HEMO_ID)
            ->get();

        return $result;
    }

    public function ItemView(int $HEMO_ID)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_ID',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'hemodialysis_items.IS_NEW',
                'hemodialysis_items.IS_DEFAULT',
                'hemodialysis_items.IS_CASHIER',
                'hemodialysis_items.IS_JUSTIFY',
                'hemodialysis_items.JUSTIFY_NOTES',
                'hemodialysis_items.IS_POST',
                DB::raw('IFNULL(hemodialysis_items.SK_LINE_ID,0) as SK_LINE_ID'),
                'item.CODE',
                'item.DESCRIPTION',
                'u.NAME as UNIT_NAME',
                'u.SYMBOL',
                't.NO_OF_USED',
                'c.DESCRIPTION as CLASS_NAME',

            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->leftJoin('item_group as g', 'g.ID', '=', 'item.GROUP_ID')
            ->leftJoin('item_sub_class as s', 's.ID', '=', 'item.SUB_CLASS_ID')
            ->leftJoin('item_class as c', 'c.ID', '=', 's.CLASS_ID')
            ->leftJoin('unit_of_measure as u', 'u.ID', '=', 'hemodialysis_items.UNIT_ID')
            ->leftJoin('item_treatment as t', function ($q) {
                $q->on('t.ITEM_ID', '=', 'hemodialysis_items.ITEM_ID');
                $q->on('t.LOCATION_ID', '=', 'h.LOCATION_ID');
            })
            ->where('hemodialysis_items.HEMO_ID', '=', $HEMO_ID)
            ->get();

        return $result;
    }
    public function CountItems(int $HEMO_ID): int
    {
        return (int) HemodialysisItems::where('HEMO_ID', '=', $HEMO_ID)->count();
    }
    public function ItemInventory(int $ID)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'item.COST',
            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('hemodialysis_items.HEMO_ID', $ID)
            ->where('hemodialysis_items.IS_NEW', 1)
            ->get();

        return $result;
    }
    public function CallOutItemUnPosted(string $DATE)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.HEMO_ID',
                'hemodialysis.DATE',
                'hemodialysis.LOCATION_ID',
                'hemodialysis.CUSTOMER_ID',

            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('item.HEMO_NON_INVENTORY', 0)
            ->whereIn('hemodialysis.STATUS_ID', ['2', '4'])
            ->where('hemodialysis_items.IS_NEW', true)
            ->where('hemodialysis_items.IS_POST', false)
            ->where('hemodialysis.DATE', '<=', $DATE)
            ->orderBy('hemodialysis.DATE', 'asc')
            ->groupBy([
                'hemodialysis_items.HEMO_ID',
                'hemodialysis.DATE',
                'hemodialysis.LOCATION_ID',
                'hemodialysis.CUSTOMER_ID',
            ])
            ->get();

        return $result;
    }
    public function CallOutItemToBePosted(string $DATE)
    {
        HemodialysisItems::join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->whereIn('hemodialysis.STATUS_ID', ['2', '4'])
            ->where('hemodialysis_items.IS_NEW', true)
            ->where('hemodialysis_items.IS_POST', false)
            ->where('hemodialysis.DATE', '<=', $DATE)
            ->update([
                'hemodialysis_items.IS_POST' => true,
            ]);
    }
    public function UsageHistory(int $ITEM_ID, int $CONTACT_ID, string $DATE, int $LOCATION_ID)
    {

        $result = HemodialysisItems::query()
            ->select([
                'h.DATE',
                'hemodialysis_items.IS_NEW',
                'hemodialysis_items.QUANTITY',
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis_items.ITEM_ID', $ITEM_ID)
            ->where('h.CUSTOMER_ID', $CONTACT_ID)
            ->where('h.LOCATION_ID', $LOCATION_ID)
            ->where('h.DATE', '<=', $DATE)
            ->where('h.STATUS_ID', 2)
            ->orderBy('h.DATE', 'asc')
            ->get();

        return $result;
    }
    public function getTreatmentID(int $CONTACT_ID, string $DATE, int $LOCATION_ID)
    {
        $result = Hemodialysis::query()
            ->select([
                'ID',
                'PRE_WEIGHT',
                'PRE_BLOOD_PRESSURE',
                'PRE_HEART_RATE',
                'PRE_O2_SATURATION',
                'PRE_TEMPERATURE',
                'POST_WEIGHT',
                'POST_BLOOD_PRESSURE',
                'POST_HEART_RATE',
                'POST_O2_SATURATION',
                'POST_TEMPERATURE',
                'PRE_BLOOD_PRESSURE2',
                'POST_BLOOD_PRESSURE2',
                'TIME_START',
                'TIME_END',
                'STATUS_ID',
                'IS_INCOMPLETE',
                DB::raw('(select if(count(*) > 0, true, false) from hemodialysis_items where hemodialysis_items.HEMO_ID = hemodialysis.ID and hemodialysis_items.ITEM_ID = 176 ) as IS_PF '),
            ])
            ->where('CUSTOMER_ID', $CONTACT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('DATE', $DATE)
            ->whereBetween('STATUS_ID', [1, 4])
            ->first();

        if ($result) {
            return [
                'ID'                   => (int) $result->ID,
                'PRE_WEIGHT'           => (float) $result->PRE_WEIGHT ?? 0,
                'PRE_BLOOD_PRESSURE'   => (float) $result->PRE_BLOOD_PRESSURE ?? 0,
                'PRE_HEART_RATE'       => (float) $result->PRE_HEART_RATE ?? 0,
                'PRE_O2_SATURATION'    => (float) $result->PRE_O2_SATURATION ?? 0,
                'POST_WEIGHT'          => (float) $result->POST_WEIGHT ?? 0,
                'POST_BLOOD_PRESSURE'  => (float) $result->POST_BLOOD_PRESSURE ?? 0,
                'POST_HEART_RATE'      => (float) $result->POST_HEART_RATE ?? 0,
                'POST_O2_SATURATION'   => (float) $result->POST_O2_SATURATION ?? 0,
                'PRE_BLOOD_PRESSURE2'  => (float) $result->PRE_BLOOD_PRESSURE2 ?? 0,
                'POST_BLOOD_PRESSURE2' => (float) $result->POST_BLOOD_PRESSURE2 ?? 0,
                'TIME_START'           => $result->TIME_START ?? '',
                'TIME_END'             => $result->TIME_END ?? '',
                'STATUS_ID'            => $result->STATUS_ID ?? 0,
                'IS_INCOMPLETE'        => $result->IS_INCOMPLETE ?? false,
                'IS_PF'                => (bool) $result->IS_PF ?? false,
            ];
        }

        return [
            'ID'                   => 0,
            'PRE_WEIGHT'           => 0,
            'PRE_BLOOD_PRESSURE'   => 0,
            'PRE_HEART_RATE'       => 0,
            'PRE_O2_SATURATION'    => 0,
            'POST_WEIGHT'          => 0,
            'POST_BLOOD_PRESSURE'  => 0,
            'POST_HEART_RATE'      => 0,
            'POST_O2_SATURATION'   => 0,
            'PRE_BLOOD_PRESSURE2'  => 0,
            'POST_BLOOD_PRESSURE2' => 0,
            'TIME_START'           => '',
            'TIME_END'             => '',
            'STATUS_ID'            => 0,
            'IS_INCOMPLETE'        => false,
            'IS_PF'                => false,
        ];
    }
    public function GotNotTreatmentOnAvailment(int $CUSTOMER_ID, int $LOCATION_ID, string $DATE)
    {
        $year  = date('Y', strtotime($DATE)); // Extract the year from the provided date
        $trtNo = (int) Hemodialysis::where('CUSTOMER_ID', '=', $CUSTOMER_ID)
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID');
                $join->on('s.DATE', '=', 'hemodialysis.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID','=', 2)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('hemodialysis.DATE', '=', $year) // Add a condition to filter by year
            ->where('hemodialysis.DATE', '<=', $DATE)    // Add a condition to filter by year
            ->whereBetween('hemodialysis.STATUS_ID', [1, 2])
            ->count();
        $sc = (int) PhilhealthItemAdjustment::where('PATIENT_ID', '=', $CUSTOMER_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('YEAR', '=', $year)
            ->sum('NO_OF_USED');

        $number = $trtNo + $sc;

        return $number;
    }
    public function GetNoTreatment(int $CUSTOMER_ID, int $LOCATION_ID, string $DATE): int
    {
       $year = date('Y', strtotime($DATE)); // Extract the year from the provided date
       $trtNo = (int) Hemodialysis::where('CUSTOMER_ID', '=', $CUSTOMER_ID)
            ->join('service_charges as s', function ($join) {
                $join->on('s.PATIENT_ID', '=', 'hemodialysis.CUSTOMER_ID');
                $join->on('s.LOCATION_ID', '=', 'hemodialysis.LOCATION_ID');
                $join->on('s.DATE', '=', 'hemodialysis.DATE');
            })
            ->join('service_charges_items as sci', 'sci.SERVICE_CHARGES_ID', '=', 's.ID')
            ->where('sci.ITEM_ID','=', 2)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('hemodialysis.DATE', '=', $year) // Add a condition to filter by year
            ->where('hemodialysis.DATE', '<=', $DATE)    // Add a condition to filter by year
            ->whereBetween('hemodialysis.STATUS_ID', [1, 2])
            ->count();

        $sc = (int) PhilhealthItemAdjustment::where('PATIENT_ID', '=', $CUSTOMER_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->where('YEAR', '=', $year)
            ->sum('NO_OF_USED');

        $number = $trtNo + $sc;
        return $number;
    }
    public function codeIfExist(string $CODE): bool
    {
        return Hemodialysis::where('CODE', '=', $CODE)->exists();
    }
    public function UpdateQRFile($CODE, $FILE_NAME, $FILE_PATH): bool
    {
        $data = $this->codeIfExist($CODE);
        if ($data) {
            Hemodialysis::where('CODE', '=', $CODE)
                ->update([
                    'FILE_NAME' => $FILE_NAME,
                    'FILE_PATH' => $FILE_PATH,
                ]);

            return true;
        }

        return false;
    }
    public function ItemQuery(int $PATIENT_ID, string $DATE, int $LOCATION_ID, int $ITEM_ID, float $QTY, bool $IS_DELETE, int $UNIT_ID, int $SC_ITEM_ID, bool $ON_CHANGE_POST = false)
    {

        $itemDetails = $this->itemServices->get($ITEM_ID);
        if ($itemDetails) {
            $hasSubClass = ItemSubClass::where('ID', $itemDetails->SUB_CLASS_ID)->first();
            if ($hasSubClass) {
                if ($hasSubClass->IN_HEMO == false) {
                    return;
                }
            }
        }

        $dataItem = HemodialysisItems::select([
            'hemodialysis_items.ID',
            'hemodialysis_items.HEMO_ID',
        ])
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->where('hemodialysis.CUSTOMER_ID', '=', $PATIENT_ID)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.DATE', '=', $DATE)
            ->where('hemodialysis_items.ITEM_ID', '=', $ITEM_ID)
            ->where('hemodialysis_items.IS_DEFAULT', '=', false)
            ->where('hemodialysis_items.SC_ITEM_ID', '=', $SC_ITEM_ID)
            ->first();

        if ($dataItem) { // HEMO EXISTS
            if ($IS_DELETE) {
                $this->ItemDelete($dataItem->ID, $dataItem->HEMO_ID, $ITEM_ID, false); // deleted
                $this->ItemDeleteTrigger($dataItem->ID, $dataItem->HEMO_ID);
                return;
            }
            $unitRelated        = $this->unitOfMeasureServices->GetItemUnitDetails($ITEM_ID, $UNIT_ID);
            $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
            $this->ItemUpdate(
                $dataItem->ID,
                $dataItem->HEMO_ID,
                $ITEM_ID,
                $QTY,
                $UNIT_ID,
                $UNIT_BASE_QUANTITY,
                true,
                false,
                $ON_CHANGE_POST
            );
            $dataTrigger = HemodialysisItems::query()
                ->select([
                    'hemodialysis_items.ID',
                    'hemodialysis_items.HEMO_ID',
                    'hemodialysis_items.ITEM_ID',
                    'hemodialysis_items.UNIT_ID',
                    'hemodialysis_items.UNIT_BASE_QUANTITY',
                ])
                ->where('hemodialysis_items.SK_LINE_ID', '=', $dataItem->ID)
                ->get();

            foreach ($dataTrigger as $list) {
                $ORG_QTY               = $this->itemTreatmentServices->getItemTriggerQuantity($ITEM_ID, $LOCATION_ID, $UNIT_ID, $list->ITEM_ID, $list->UNIT_ID);
                $trUnitRelated         = $this->unitOfMeasureServices->GetItemUnitDetails($list->ITEM_ID, $list->UNIT_ID ?? 0);
                $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                $N_QTY                 = $ORG_QTY * $QTY;
                $this->ItemUpdate(
                    $list->ID,
                    $list->HEMO_ID,
                    $list->ITEM_ID,
                    $N_QTY,
                    $list->UNIT_ID,
                    $TR_UNIT_BASE_QUANTITY,
                    true,
                    true
                );
            }

            return;
        }
        // get ID on HEMO
        $hemoData = Hemodialysis::select(['ID'])
            ->where('hemodialysis.CUSTOMER_ID', '=', $PATIENT_ID)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.DATE', $DATE)
            ->first();

        if ($hemoData) { // if exists
            $unitRelated        = $this->unitOfMeasureServices->GetItemUnitDetails($ITEM_ID, $UNIT_ID);
            $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
            $SK_LINE_ID         = $this->ItemStore(
                $hemoData->ID,
                $ITEM_ID,
                $QTY,
                $UNIT_ID,
                $UNIT_BASE_QUANTITY,
                true,
                false,
                false,
                $SC_ITEM_ID,
                null
            ); // created

            if ($hemoData->DATE == $this->dateServices->NowDate()) {
                // only trigger can u if current date
                $dataTrigger = $this->itemTreatmentServices->getItemTrigger(
                    $ITEM_ID,
                    $LOCATION_ID,
                    $UNIT_ID
                );

                foreach ($dataTrigger as $list) {
                    $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails(
                        $list->ITEM_ID,
                        $list->UNIT_ID ?? 0
                    );

                    $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];
                    $N_QTY                 = $list->QUANTITY * $QTY;
                    $this->ItemStore(
                        $hemoData->ID,
                        $list->ITEM_ID,
                        $N_QTY,
                        $list->UNIT_ID ?? 0,
                        $TR_UNIT_BASE_QUANTITY,
                        true,
                        true,
                        false,
                        null,
                        $SK_LINE_ID
                    );
                }
            }
        }
    }

    public function IsRestrictedFromUnposted(string $DATE, int $LOCATION_ID): bool
    {
        return Hemodialysis::where('DATE', '<', $DATE)
            ->where('STATUS_ID', '=', 4)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->exists();
    }

    public function ItemListWithIsCashier(int $PATIENT_ID, int $LOCATION_ID, string $DATE)
    {
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.HEMO_ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_ID',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                'i.RATE',
                'i.TAXABLE',
                'i.COGS_ACCOUNT_ID',
                'i.ASSET_ACCOUNT_ID',
                'i.GL_ACCOUNT_ID',
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->join('item as i', 'i.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->where('h.CUSTOMER_ID', '=', $PATIENT_ID)
            ->where('h.LOCATION_ID', '=', $LOCATION_ID)
            ->where('h.DATE', '=', $DATE)
            ->where('IS_CASHIER', '=', true)
            ->orderBy('LINE_NO', 'asc')
            ->get();

        return $result;
    }
    public function AutoDefaultItem(int $NoTrtment, int $HEMO_ID, int $LOCATION_ID)
    {
        if ($NoTrtment <= 1) {                                                   // New
            $dataList = $this->itemTreatmentServices->NewAutoItemList($LOCATION_ID); // show add new items
        } else {
            $dataList = $this->itemTreatmentServices->AutoItemList($LOCATION_ID); // show add default items
        }

        foreach ($dataList as $data) {

            $IS_CASHIER         = (bool) $data->IS_CASHIER;
            $unitRelated        = $this->unitOfMeasureServices->GetItemUnitDetails($data->ITEM_ID, $data->UNIT_ID ?? 0);
            $UNIT_BASE_QUANTITY = (float) $unitRelated['QUANTITY'];
            $SK_LINE_ID         = $this->ItemStore(
                $HEMO_ID,
                $data->ITEM_ID,
                $data->QUANTITY,
                $data->UNIT_ID ?? 0,
                $UNIT_BASE_QUANTITY,
                true,
                true,
                $IS_CASHIER,
                null,
                null
            );

            $dataTrigger = $this->itemTreatmentServices->getItemTrigger(
                $data->ITEM_ID,
                $LOCATION_ID,
                $data->UNIT_ID
            );

            foreach ($dataTrigger as $list) {

                $trUnitRelated = $this->unitOfMeasureServices->GetItemUnitDetails(
                    $list->ITEM_ID,
                    $list->UNIT_ID ?? 0
                );

                $TR_UNIT_BASE_QUANTITY = (float) $trUnitRelated['QUANTITY'];

                $this->ItemStore(
                    $HEMO_ID,
                    $list->ITEM_ID,
                    $list->QUANTITY,
                    $list->UNIT_ID ?? 0,
                    $TR_UNIT_BASE_QUANTITY,
                    true,
                    true,
                    false,
                    null,
                    $SK_LINE_ID
                );
            }
        }
    }
    public function StoreNotes(int $HEMO_ID, string $TIME, string $BP_1, string $BP_2, string $HR, string $BFR, string $AP, string $VP, string $TFP, string $TMP, string $HEPARIN, string $FLUSHING, string $NOTES)
    {
        $ID = $this->object->ObjectNextID('HEMO_NURSE_NOTES');

        HemoNurseNotes::create([
            'ID'       => $ID,
            'HEMO_ID'  => $HEMO_ID,
            'TIME'     => $TIME,
            'BP_1'     => $BP_1,
            'BP_2'     => $BP_2,
            'HR'       => $HR,
            'BFR'      => $BFR,
            'AP'       => $AP,
            'VP'       => $VP,
            'TFP'      => $TFP,
            'TMP'      => $TMP,
            'HEPARIN'  => $HEPARIN,
            'FLUSHING' => $FLUSHING,
            'NOTES'    => $NOTES,
        ]);
    }

    public function UpdateNotes(int $ID, int $HEMO_ID, string $TIME, string $BP_1, string $BP_2, string $HR, string $BFR, string $AP, string $VP, string $TFP, string $TMP, string $HEPARIN, string $FLUSHING, string $NOTES)
    {

        HemoNurseNotes::where('ID', $ID)
            ->where('HEMO_ID', $HEMO_ID)
            ->update([
                'TIME'     => $TIME,
                'BP_1'     => $BP_1,
                'BP_2'     => $BP_2,
                'HR'       => $HR,
                'BFR'      => $BFR,
                'AP'       => $AP,
                'VP'       => $VP,
                'TFP'      => $TFP,
                'TMP'      => $TMP,
                'HEPARIN'  => $HEPARIN,
                'FLUSHING' => $FLUSHING,
                'NOTES'    => $NOTES,
            ]);
    }
    public function DeleteNotes(int $ID, int $HEMO_ID)
    {
        HemoNurseNotes::where('ID', '=', $ID)
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->delete();
    }
    public function ListNotes(int $HEMO_ID)
    {
        $result = HemoNurseNotes::query()
            ->select([
                'ID',
                'TIME',
                'BP_1',
                'BP_2',
                'HR',
                'BFR',
                'AP',
                'VP',
                'TFP',
                'TMP',
                'HEPARIN',
                'FLUSHING',
                'NOTES',
            ])
            ->where('HEMO_ID', '=', $HEMO_ID)
            ->orderBy('ID', 'asc')
            ->get();

        return $result;
    }
    public function GetNotes(int $ID)
    {
        $result = HemoNurseNotes::where('ID', '=', $ID)->first();

        if ($result) {
            return $result;
        }
        return [];
    }
    private function getItemInventory(int $HEMO_ID)
    {
        $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hemodialysis_items.ITEM_ID and l.ID = h.LOCATION_ID Limit 1";
        $result = HemodialysisItems::query()
            ->select([
                'hemodialysis_items.ID',
                'hemodialysis_items.ITEM_ID',
                'hemodialysis_items.QUANTITY',
                'hemodialysis_items.UNIT_BASE_QUANTITY',
                DB::raw("((($exSQL) * hemodialysis_items.UNIT_BASE_QUANTITY ) *  hemodialysis_items.QUANTITY ) as COST"),
            ])
            ->join('hemodialysis as h', 'h.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('hemodialysis_items.HEMO_ID', '=', $HEMO_ID)
            ->get();

        return $result;
    }
    public function makeItemInventory(int $HEMO_ID)
    {
        $SOURCE_REF_TYPE = 27;
        $hemoData        = $this->get($HEMO_ID);
        if ($hemoData) {
            $itemList = $this->getItemInventory($HEMO_ID);
            if ($itemList) {
                // If item list is not empty, execute inventory
                $this->itemInventoryServices->InventoryExecute(
                    $itemList,
                    $hemoData->LOCATION_ID,
                    $SOURCE_REF_TYPE,
                    $hemoData->DATE,
                    false
                );

                if ($hemoData->DATE != $this->dateServices->NowDate()) {
                    // If not current date, recompute onhand
                    foreach ($itemList as $list) {
                        // Recompute onhand for each item
                        $this->itemInventoryServices->RecomputedEndingOnhand(
                            $list->ID,
                            $SOURCE_REF_TYPE,
                            $hemoData->LOCATION_ID
                        );
                    }
                }

                $this->ItemfollowUpdateToBePosted($HEMO_ID);
            }
        }
    }
    private function ItemfollowUpdateToBePosted(int $HEMO_ID)
    {
        HemodialysisItems::join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->whereIn('hemodialysis.STATUS_ID', ['2', '4'])
            ->where('hemodialysis_items.IS_POST', '=', false)
            ->where('hemodialysis.ID', '=', $HEMO_ID)
            ->update([
                'hemodialysis_items.IS_POST' => true,
            ]);
    }
    public function FixTreatmentNumberFromStart(int $LOCATION_ID)
    {

        try {
            $NowYear  = $this->dateServices->NowYear();
            $dataList = Hemodialysis::query()
                ->select([
                    'ID',
                    'CUSTOMER_ID',
                    'DATE',
                ])
                ->where('LOCATION_ID', '=', $LOCATION_ID)
                ->whereYear('DATE', '=', $NowYear)
                ->orderBy('DATE', 'asc')
                ->whereBetween('STATUS_ID', [1, 2, 4])
                ->get();

            foreach ($dataList as $list) {

                $NO_OF_TREATMENT = $this->getFixTreatmentNumberOnly($list->CUSTOMER_ID, $LOCATION_ID, $list->DATE);

                Hemodialysis::where('ID', '=', $list->ID)
                    ->where('LOCATION_ID', '=', $LOCATION_ID)
                    ->where('DATE', '=', $list->DATE)
                    ->where('CUSTOMER_ID', '=', $list->CUSTOMER_ID)
                    ->update([
                        'NO_OF_TREATMENT' => $NO_OF_TREATMENT,
                    ]);
            }
            session()->flash('message', 'Successfully update treatment no.');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }
    private function getFixTreatmentNumberOnly(int $CUSTOMER_ID, int $LOCATION_ID, string $DATE): int
    {

        $NowYear = $this->dateServices->GetFirstDay_Year($DATE);
        $result  = (int) Hemodialysis::where('CUSTOMER_ID', '=', $CUSTOMER_ID)
            ->where('LOCATION_ID', '=', $LOCATION_ID)
            ->whereYear('DATE', '=', $NowYear)
            ->where('DATE', '<=', $DATE) // Add a condition to filter by year
            ->whereBetween('STATUS_ID', [1, 2])
            ->count();

        return $result;
    }
    public function ChangePatient(int $HEMO_ID, int $TRANSFER_CONTACT_ID)
    {
        Hemodialysis::where('ID', '=', $HEMO_ID)
            ->update([
                'CUSTOMER_ID' => $TRANSFER_CONTACT_ID,
            ]);
    }

    public function getHemoJournalByItemCredit(int $HEMO_ID)
    {
        $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hi.ITEM_ID and l.ID = h.LOCATION_ID limit 1";
        $result = HemoJournal::query()
            ->select([
                'hi.ID',
                'hemo_journal.CREDIT_ACCOUNT_ID as ACCOUNT_ID',
                'hi.ITEM_ID as SUBSIDIARY_ID',
                DB::raw("((($exSQL) * hi.UNIT_BASE_QUANTITY ) *  hi.QUANTITY ) as AMOUNT"),
                DB::raw('1 as ENTRY_TYPE'),
            ])
            ->join('item_sub_class as s', 's.CLASS_ID', '=', 'hemo_journal.ITEM_CLASS_ID')
            ->join('item as i', 'i.SUB_CLASS_ID', '=', 's.ID')
            ->join('hemodialysis_items as hi', 'hi.ITEM_ID', '=', 'i.ID')
            ->join('hemodialysis as h', 'h.ID', '=', 'hi.HEMO_ID')
            ->where('hi.HEMO_ID', '=', $HEMO_ID)
            ->whereIn('i.TYPE', ['0', '1'])
            ->orderBy('hi.ID', 'asc')
            ->get();

        return $result;

    }

    public function getHemoJournalByItemDebit(int $HEMO_ID)
    {
        $exSQL  = "select IFNULL(pll.CUSTOM_COST,0) from price_level_lines as pll inner join location as l on l.PRICE_LEVEL_ID =  pll.PRICE_LEVEL_ID where pll.ITEM_ID = hi.ITEM_ID and l.ID = h.LOCATION_ID Limit 1";
        $result = HemoJournal::query()
            ->select([
                'h.ID',
                'hemo_journal.DEBIT_ACCOUNT_ID as ACCOUNT_ID',
                DB::raw("SUM(((($exSQL) * hi.UNIT_BASE_QUANTITY ) *  hi.QUANTITY )) as AMOUNT"),
                'h.CUSTOMER_ID as SUBSIDIARY_ID',
                DB::raw('0 as ENTRY_TYPE'),
            ])
            ->join('item_sub_class as s', 's.CLASS_ID', '=', 'hemo_journal.ITEM_CLASS_ID')
            ->join('item as i', 'i.SUB_CLASS_ID', '=', 's.ID')
            ->join('hemodialysis_items as hi', 'hi.ITEM_ID', '=', 'i.ID')
            ->join('hemodialysis as h', 'h.ID', '=', 'hi.HEMO_ID')
            ->where('hi.HEMO_ID', '=', $HEMO_ID)
            ->whereIn('i.TYPE', ['0', '1'])
            ->orderBy('hemo_journal.DEBIT_ACCOUNT_ID', 'asc')
            ->groupBy([
                'h.ID',
                'hemo_journal.DEBIT_ACCOUNT_ID',
                'h.CUSTOMER_ID',
            ])
            ->get();

        return $result;

    }

    public function getMakeJournal(int $HEMO_ID)
    {
        $gotUpdate = false;
        try {
            $dataHemo = $this->get($HEMO_ID);
            if ($dataHemo) {
                $JOURNAL_NO = $this->accountJournalServices->getRecord($this->object_type_hemo, $HEMO_ID);
                if ($JOURNAL_NO == 0) {
                    $JOURNAL_NO = $this->accountJournalServices->getJournalNo($this->object_type_hemo, $HEMO_ID) + 1;
                } else {
                    // make adjustment
                    $gotUpdate = true;
                    $this->accountJournalServices->UpdatedJournalAmountZero($JOURNAL_NO);
                    // reset first
                }
                $resultDebit = $this->getHemoJournalByItemDebit($HEMO_ID);

                if ($gotUpdate) {

                    foreach ($resultDebit as $list) {
                        $acctID = $this->accountServices->EXPENSE_ACCOUNT_ID;
                        if ($acctID != $list->ACCOUNT_ID) {
                            $this->accountJournalServices->updateAccount(
                                $list->ID,
                                $this->object_type_hemo,
                                $dataHemo->DATE,
                                $dataHemo->LOCATION_ID,
                                $acctID,
                                $list->ACCOUNT_ID,
                            );
                        }

                    }
                }

                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $resultDebit,
                    $dataHemo->LOCATION_ID,
                    $this->object_type_hemo,
                    $dataHemo->DATE
                );

                $resultCredit = $this->getHemoJournalByItemCredit($HEMO_ID);

                if ($gotUpdate) {

                    foreach ($resultCredit as $list) {
                        $acctID = 6;

                        if ($list->ACCOUNT_ID != $acctID) {
                            $this->accountJournalServices->updateAccount(
                                $list->ID,
                                $this->object_type_hemo_item,
                                $dataHemo->DATE,
                                $dataHemo->LOCATION_ID,
                                $acctID,
                                $list->ACCOUNT_ID,
                            );
                        }
                    }
                }

                $this->accountJournalServices->JournalExecute(
                    $JOURNAL_NO,
                    $resultCredit,
                    $dataHemo->LOCATION_ID,
                    $this->object_type_hemo_item,
                    $dataHemo->DATE
                );

                $data       = $this->accountJournalServices->getSumDebitCredit($JOURNAL_NO);
                $debit_sum  = (float) $data['DEBIT'];
                $credit_sum = (float) $data['CREDIT'];

                if ($debit_sum == $credit_sum) {
                    return true;
                }

                return false;

            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return false;
        }
    }
    public function getCountUnposted(string $DATE, int $LOCATION_ID): int
    {
        return (int) Hemodialysis::query()
            ->where('hemodialysis.DATE', '=', $DATE)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '=', 4)
            ->count();
    }
    public function getCountPosted(string $DATE, int $LOCATION_ID): int
    {
        return (int) Hemodialysis::query()
            ->where('hemodialysis.DATE', '=', $DATE)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '=', 2)
            ->count();
    }
    public function getCountVoid(string $DATE, int $LOCATION_ID): int
    {
        return (int) Hemodialysis::query()
            ->where('hemodialysis.DATE', '=', $DATE)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '=', 3)
            ->count();
    }
    public function getCountItemRelease(string $DATE, int $LOCATION_ID)
    {
        return HemodialysisItems::query()
            ->select([
                'item.DESCRIPTION',
                DB::raw('SUM(hemodialysis_items.QUANTITY) as TOTAL_QUANTITY'),
            ])
            ->join('item', 'item.ID', '=', 'hemodialysis_items.ITEM_ID')
            ->join('hemodialysis', 'hemodialysis.ID', '=', 'hemodialysis_items.HEMO_ID')
            ->whereIn('item.TYPE', ['0', '1'])
            ->where('hemodialysis_items.IS_NEW', '=', true)
            ->where('hemodialysis_items.IS_POST', '=', true)
            ->where('hemodialysis.DATE', '=', $DATE)
            ->where('hemodialysis.LOCATION_ID', '=', $LOCATION_ID)
            ->where('hemodialysis.STATUS_ID', '=', 2)
            ->groupBy('item.DESCRIPTION')
            ->get();
    }
    public function getHemoItemIdViaServiceCharge(int $HEMO_ID, int $ITEM_ID, int $SC_ITEM_ID)
    {
        $result = HemodialysisItems::where('HEMO_ID', '=', $HEMO_ID)
            ->where('ITEM_ID', '=', $ITEM_ID)
            ->where('SC_ITEM_ID', '=', $SC_ITEM_ID)
            ->first();

        if ($result) {
            return $result->ID;
        }
        return null;
    }
    public function getExpensesAccountHemo()
    {
        $result = DB::table('account_journal as aj')
            ->select([
                'h.HEMO_ID',
                'aj.*',
            ])
            ->join('hemodialysis_items as h', 'h.ID', '=', 'aj.OBJECT_ID')
            ->whereIn('aj.OBJECT_TYPE', [$this->object_type_hemo_item, $this->object_type_hemo])
            ->whereIn('aj.ACCOUNT_ID', [$this->accountServices->EXPENSE_ACCOUNT_ID, 6])
            ->where('aj.AMOUNT', '>', 0)
            ->orderBy('aj.OBJECT_DATE', 'asc')
            ->first();

        // dd("stop");

        return $result;
    }
    public function getDelJournal(int $LOCATION_ID, int $JN, int $SUB_ID, int $OBJECT_ID, int $OBJECT_TYPE, string $OBJECT_DATE, int $ENTRY_TYPE)
    {
        $this->accountJournalServices->DeleteJournal(
            $this->accountServices->EXPENSE_ACCOUNT_ID,
            $LOCATION_ID,
            $JN,
            $SUB_ID,
            $OBJECT_ID,
            $OBJECT_TYPE,
            $OBJECT_DATE,
            $ENTRY_TYPE
        );
    }
}
