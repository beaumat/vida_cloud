<?php
namespace App\Services;

use App\Models\Contacts;
use App\Models\HemodialysisMachines;
use App\Models\Schedules;
use App\Models\ScheduleStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleServices
{

    private bool $MON;
    private bool $TUE;
    private bool $WEN;
    private bool $THU;
    private bool $FRI;
    private bool $SAT;
    private bool $SUN;

    private $object;
    private $dateServices;
    public function __construct(ObjectServices $objectService, DateServices $dateServices)
    {
        $this->object       = $objectService;
        $this->dateServices = $dateServices;
    }
    public function getInfo(int $Id)
    {
        return Schedules::where('ID', $Id)->first();
    }

    public function ContactListFromSchedules(string $Date, int $LOCATION_ID, bool $isCreated = true)
    {
        $result = Schedules::query()
            ->select([
                'schedules.CONTACT_ID as ID',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as NAME"),
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->where('c.TYPE', 3)
            ->whereDate('schedules.SCHED_DATE', $Date)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->when($isCreated, function ($q) {
                $q->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('service_charges as sc')
                        ->whereRaw('sc.`DATE` = schedules.`SCHED_DATE`')
                        ->whereRaw('sc.`PATIENT_ID` = schedules.`CONTACT_ID`')
                        ->whereRaw('sc.`LOCATION_ID`= schedules.`LOCATION_ID`');
                });
            })
            ->orderBy('c.LAST_NAME')
            ->get();

        return $result;

    }
    public function scheduleList($Date, int $LOCATION_ID)
    {
        return Schedules::query()
            ->select([
                'schedules.CONTACT_ID',
                'c.NAME aS CONTACT_NAME',
                's.NAME as SHIFT',
                't.DESCRIPTION as STATUS',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->leftJoin('shift as s', 's.ID', '=', 'schedules.SHIFT_ID')
            ->leftJoin('schedule_status as t', 't.ID', '=', 'schedules.SCHED_STATUS')
            ->where('c.TYPE', 3)
            ->whereDate('schedules.SCHED_DATE', $Date)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->orderBy('schedules.SHIFT_ID')
            ->get();
    }
    public function scheduleListByShift($Date, int $LOCATION_ID, int $shiftId, int $hemoId)
    {
        return Schedules::query()
            ->select([
                'schedules.CONTACT_ID',
                'c.LAST_NAME aS CONTACT_NAME',
                't.DESCRIPTION as STATUS',
                'schedules.HEMO_MACHINE_ID',
            ])
            ->leftJoin('contact as c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->leftJoin('schedule_status as t', 't.ID', '=', 'schedules.SCHED_STATUS')
            ->where('c.TYPE', 3)
            ->whereDate('schedules.SCHED_DATE', $Date)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->where('schedules.SHIFT_ID', $shiftId)
            ->where('schedules.HEMO_MACHINE_ID', $hemoId)
            ->limit(100)
            ->orderBy('c.LAST_NAME')
            ->get();
    }
    public function getSchedHemo($ContactId, $Date, int $LOCATION_ID)
    {
        try {
            $result = Schedules::query()
                ->select([
                    'ID',
                    'SHIFT_ID',
                    'CONTACT_ID',
                    'SCHED_DATE',
                    'SCHED_STATUS',
                    'STATUS_LOG',
                    'LOCATION_ID',
                    'HEMO_MACHINE_ID',
                    DB::raw("(select count(hemodialysis.ID) from hemodialysis where hemodialysis.CUSTOMER_ID = schedules.CONTACT_ID and hemodialysis.DATE = schedules.SCHED_DATE and hemodialysis.LOCATION_ID = schedules.LOCATION_ID ) as  EXIST_HEMO"),
                ])
                ->where('CONTACT_ID', '=', $ContactId)
                ->where('SCHED_DATE', '=', $Date)
                ->where('LOCATION_ID', '=', $LOCATION_ID)
                ->first();

            if ($result) {
                return $result;
            }

            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }
    public function get($ContactId, $Date, int $LOCATION_ID)
    {
        try {
            $result = Schedules::where('CONTACT_ID', '=', $ContactId)
                ->where('SCHED_DATE', '=', $Date)
                ->where('LOCATION_ID', '=', $LOCATION_ID)
                ->first();

            if ($result) {
                return $result;
            }

            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function Delete(int $ID, int $LOCATION_ID)
    {
        Schedules::where('ID', $ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->delete();
    }
    public function Update(int $CONTACT_ID, string $DATE, int $SHIFT_ID, int $STATUS, $LOG, int $LOCATION_ID, int $HEMO_MACHINE_ID)
    {
        Schedules::where('CONTACT_ID', $CONTACT_ID)
            ->where('SCHED_DATE', $DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'SHIFT_ID'        => $SHIFT_ID,
                'SCHED_STATUS'    => $STATUS,
                'STATUS_LOG'      => $LOG,
                'HEMO_MACHINE_ID' => $HEMO_MACHINE_ID,
                'UPDATED_AT'      => $this->dateServices->Now(),
            ]);
    }
    public function UpdateHemoMachine(int $CONTACT_ID, string $DATE, int $LOCATION_ID, int $HEMO_MACHINE_ID)
    {
        Schedules::where('CONTACT_ID', $CONTACT_ID)
            ->where('SCHED_DATE', $DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'HEMO_MACHINE_ID' => $HEMO_MACHINE_ID,
                'UPDATED_AT'      => $this->dateServices->Now(),
            ]);
    }
    public function StatusUpdate(int $CONTACT_ID, string $DATE, int $LOCATION_ID, int $STATUS)
    {
        Schedules::where('CONTACT_ID', $CONTACT_ID)
            ->where('SCHED_DATE', $DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->update([
                'SCHED_STATUS' => $STATUS,
                'UPDATED_AT'   => $this->dateServices->Now(),
            ]);
    }
    public function CheckingType(int $SHIFT_ID, int $CONTACT_ID, string $DATE, int $LOCATION_ID, int $HEMO_MACHINE_ID): int
    {
        $totalCount = Schedules::where('SHIFT_ID', $SHIFT_ID)
            ->where('SCHED_DATE', $DATE)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('HEMO_MACHINE_ID', $HEMO_MACHINE_ID)
            ->where('CONTACT_ID', '<>', $CONTACT_ID)
            ->count();

        return $totalCount;
    }
    public function Store(int $SHIFT_ID, int $CONTACT_ID, string $DATE, int $STATUS, $LOG, int $LOCATION_ID, int $HEMO_MACHINE_ID)
    {

        $ID = (int) $this->object->ObjectNextID('SCHEDULES');

        Schedules::create([
            'ID'              => $ID,
            'SHIFT_ID'        => $SHIFT_ID,
            'CONTACT_ID'      => $CONTACT_ID,
            'SCHED_DATE'      => $DATE,
            'SCHED_STATUS'    => $STATUS,
            'STATUS_LOG'      => $LOG,
            'LOCATION_ID'     => $LOCATION_ID,
            'HEMO_MACHINE_ID' => $HEMO_MACHINE_ID,
            'CREATED_AT'      => $this->dateServices->Now(),
            'UPDATED_AT'      => $this->dateServices->Now(),
        ]);
    }

    public function ContactSchedule(int $CONTACT_ID, int $LOCATION_ID, int $STATUS_ID, int $perPage)
    {
        $result = Schedules::query()
            ->select([
                'schedules.SCHED_DATE',
                'schedules.SHIFT_ID',
                'schedules.SCHED_STATUS',
                's.DESCRIPTION as STATUS',
                't.NAME as SHIFT',
                'h.DESCRIPTION as TYPE',
            ])
            ->leftJoin('schedule_status as s', 's.ID', '=', 'schedules.SCHED_STATUS')
            ->leftJoin('shift as t', 't.ID', '=', 'schedules.SHIFT_ID')
            ->leftJoin('hemodialysis_machine as h', 'h.ID', '=', 'schedules.HEMO_MACHINE_ID')
            ->where('schedules.CONTACT_ID', $CONTACT_ID)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->where('schedules.SCHED_STATUS', $STATUS_ID)
            ->orderBy('schedules.SCHED_DATE', 'asc')
            ->limit(250)
            ->paginate($perPage);

        return $result;
    }
    public function DailyContactSchedule($Date, $LOCATION_ID)
    {
        $subQuery = Schedules::query()
            ->select([
                'schedules.SHIFT_ID',
                DB::raw('IF(schedules.SCHED_STATUS = 0, COUNT(*), 0) AS W'),
                DB::raw('IF(schedules.SCHED_STATUS = 1, COUNT(*), 0) AS P'),
                DB::raw('IF(schedules.SCHED_STATUS = 2, COUNT(*), 0) AS A'),
                DB::raw('IF(schedules.SCHED_STATUS = 3, COUNT(*), 0) AS C'),
            ])
            ->join('contact AS c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->join('shift AS s', 's.ID', '=', 'schedules.SHIFT_ID')
            ->where('c.TYPE', 3)
            ->whereDate('schedules.SCHED_DATE', $Date)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->groupBy(['schedules.SHIFT_ID', 'schedules.SCHED_STATUS']);

        $result = DB::table(DB::raw("({$subQuery->toSql()}) as sched"))
            ->mergeBindings($subQuery->getQuery()) // you need to merge bindings
            ->select([
                'sched.SHIFT_ID',
                DB::raw('SUM(sched.W) AS W'),
                DB::raw('SUM(sched.P) AS P'),
                DB::raw('SUM(sched.A) AS A'),
                DB::raw('SUM(sched.C) AS C'),
            ])
            ->groupBy('sched.SHIFT_ID')
            ->get();

        return $result;
    }

    public function ScheduleStatusList()
    {
        return ScheduleStatus::query()->get();
    }
    public function AutoGenerateSchedule(int $PATIENT_ID, int $LOCATION_ID, int $YEAR, int $MONTH, array $weekDate = [], $shiftList)
    {
        $contact = Contacts::where('ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('TYPE', 3)
            ->whereNull('HIRE_DATE')
            ->where('INACTIVE', 0)
            ->first();

        if ($contact) {
            // Assuming $contact->FIX_MON, $contact->FIX_TUE, etc. are boolean attributes
            $this->MON = (bool) $contact->FIX_MON;
            $this->TUE = (bool) $contact->FIX_TUE;
            $this->WEN = (bool) $contact->FIX_WEN;
            $this->THU = (bool) $contact->FIX_THU;
            $this->FRI = (bool) $contact->FIX_FRI;
            $this->SAT = (bool) $contact->FIX_SAT;
            $this->SUN = (bool) $contact->FIX_SUN;

            $patientType = HemodialysisMachines::where('ID', $contact->PATIENT_TYPE_ID)
                ->where('LOCATION_ID', $contact->LOCATION_ID)
                ->first();

            if ($patientType) {
                $this->AutoProcess($PATIENT_ID, $LOCATION_ID, $weekDate, $patientType, $shiftList);
            }
        }
    }

    private function scheduleSet($shiftList, int $PATIENT_ID, int $LOCATION_ID, int $PATIENT_TYPE_ID, int $CAPACITY, $DATE)
    {
        //Check if Exists
        $data = Schedules::where('CONTACT_ID', $PATIENT_ID)
            ->where('LOCATION_ID', $LOCATION_ID)
            ->where('HEMO_MACHINE_ID', $PATIENT_TYPE_ID)
            ->where('SCHED_DATE', $DATE)
            ->first();

        if ($data) {
            return;
        }

        foreach ($shiftList as $list) {
            //Check if maximum base on capacity
            $count = Schedules::select(DB::raw('COUNT(*) as CAPACITY'))
                ->where('LOCATION_ID', $LOCATION_ID)
                ->where('HEMO_MACHINE_ID', $PATIENT_TYPE_ID)
                ->where('SCHED_DATE', $DATE)
                ->where('SHIFT_ID', $list->ID)
                ->first();

            if ($count && $count->CAPACITY <= $CAPACITY) {
                //Insert
                $this->Store($list->ID, $PATIENT_ID, $DATE, 0, null, $LOCATION_ID, $PATIENT_TYPE_ID);
                return;
            }
        }
    }

    private function AutoProcess(int $PATIENT_ID, int $LOCATION_ID, array $weekDate = [], $patientType, $shiftList)
    {

        $capacity        = (int) $patientType->CAPACITY;
        $patient_type_id = (int) $patientType->ID;

        foreach ($weekDate as $date) {
            $weekDateFormatted = (string) Carbon::parse($date)->format('Y-m-d');
            switch (Carbon::parse($date)->format('l')) {
                case 'Monday':
                    if ($this->MON) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                case 'Tuesday':
                    if ($this->TUE) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                case 'Wednesday':
                    if ($this->WEN) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                case 'Thursday':
                    if ($this->THU) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                case 'Friday':
                    if ($this->FRI) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                case 'Saturday':
                    if ($this->SAT) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                case 'Sunday':
                    if ($this->SUN) {
                        $this->scheduleSet($shiftList, $PATIENT_ID, $LOCATION_ID, $patient_type_id, $capacity, $weekDateFormatted);
                    }
                    break;
                default:
                    // Handle unexpected day
                    break;
            }
        }
    }
    public function PatientWeeklySchedule($SHIFT_ID, $DATE, $LOCATION_ID)
    {
        $demandOutput = [];
        $res          = HemodialysisMachines::where('LOCATION_ID', $LOCATION_ID)->get();
        $run          = 1;
        foreach ($res as $list) {

            for ($z = 1; $z <= $list->CAPACITY; $z++) {
                $demandOutput[] = ['ID' => $run, 'PATIENT_NAME' => '', 'TYPE_ID' => $list->ID];
                $run++;
            }
        }

        $result = Schedules::query()
            ->select([

                'hm.DESCRIPTION as PATIENT_TYPE',
                'hm.CAPACITY',
                'hm.ID as TYPE_ID',
                'c.LAST_NAME as PATIENT_NAME',
                'c.LONG_HRS_DURATION',
                'c.ADMITTED',
                'c.ID as PATIENT_ID',
            ])
            ->join('contact as c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->join('hemodialysis_machine as hm', 'hm.ID', '=', 'schedules.HEMO_MACHINE_ID')
            ->join('patient_status as ps', 'ps.ID', '=', 'c.PATIENT_STATUS_ID')
            ->where('schedules.SHIFT_ID', $SHIFT_ID)
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->where('hm.LOCATION_ID', $LOCATION_ID)
            ->where('schedules.SCHED_DATE', $DATE)
            ->orderBy('hm.ID')
            ->orderBy('c.LAST_NAME')
            ->get();

        foreach ($result as $list) {

            foreach ($demandOutput as &$item) {
                if ($item['TYPE_ID'] == $list->TYPE_ID) {
                    if ($item['PATIENT_NAME'] == '') {
                        $item['PATIENT_NAME'] = $list->PATIENT_NAME;
                        break; // Assuming each ID is unique, you can break out of the loop once the update is done
                    }
                }
            }
        }

        return $demandOutput;
    }

    public function GetScheduleList($DATE, int $LOCATION_ID, int $SHIFT_ID)
    {
        $result = Schedules::query()
            ->select([
                'schedules.ID',
                'hm.DESCRIPTION as PATIENT_TYPE',
                'hm.CAPACITY',
                'hm.ID as TYPE_ID',
                DB::raw("CONCAT(c.LAST_NAME, ', ', c.FIRST_NAME, ', ', LEFT(c.MIDDLE_NAME, 1)) as PATIENT_NAME"),
                'c.LONG_HRS_DURATION',
                'c.ADMITTED',
                'c.ID as PATIENT_ID',
                's.NAME as SHIFT',
            ])
            ->join('contact as c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->join('shift AS s', 's.ID', '=', 'schedules.SHIFT_ID')
            ->join('hemodialysis_machine as hm', 'hm.ID', '=', 'schedules.HEMO_MACHINE_ID')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('hemodialysis as h')
                    ->whereRaw('h.`DATE` = schedules.`SCHED_DATE`')
                    ->whereRaw('h.`CUSTOMER_ID` = schedules.`CONTACT_ID`')
                    ->whereRaw('h.`LOCATION_ID`= schedules.`LOCATION_ID`');
            })
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->where('hm.LOCATION_ID', $LOCATION_ID)
            ->where('schedules.SCHED_DATE', $DATE)
            ->when($SHIFT_ID > 0, function ($query) use (&$SHIFT_ID) {
                $query->where('schedules.SHIFT_ID', $SHIFT_ID);
            })
            ->orderBy('s.ID')
            ->orderBy('hm.ID')
            ->orderBy('c.LAST_NAME')
            ->get();

        return $result;
    }

    public function getCountScheduleList($DATE_FROM, $DATE_TO, int $LOCATION_ID): int
    {
        return Schedules::query()
            ->whereBetween('SCHED_DATE', [$DATE_FROM, $DATE_TO])
            ->where('LOCATION_ID','=', $LOCATION_ID)
            ->count();
    }
    public function getWaitingList(string $Date)
    {
        $data = Schedules::query()
            ->select([
                'SHIFT_ID',
                'CONTACT_ID',
                'LOCATION_ID',
                'HEMO_MACHINE_ID',
                'SCHED_DATE',
            ])
            ->where('SCHED_STATUS', 0)
            ->where('SCHED_DATE', '<=', $Date)
            ->orderBy('SCHED_DATE', 'asc')
            ->get();

        return $data;
    }
    public function getNewListSchedule(int $LOCATION_ID, string $dateFrom, string $dateTo)
    {
        // $result = DB::table('schedules as s')
        //     ->select(
        //         's.SCHED_DATE',
        //         's.SHIFT_ID',
        //         DB::raw('SUM(s.SCHED_STATUS = 0) AS W'),
        //         DB::raw('SUM(s.SCHED_STATUS = 1) AS P'),
        //         DB::raw('SUM(s.SCHED_STATUS = 2) AS A'),
        //         DB::raw('SUM(s.SCHED_STATUS = 3) AS C')
        //     )
        //     ->whereBetween('s.SCHED_DATE', [$dateFrom, $dateTo])
        //     ->where('s.LOCATION_ID', $LOCATION_ID)
        //     ->groupBy('s.SCHED_DATE', 's.SHIFT_ID')
        //     ->toSql();
        //         dd($result);


          $subQuery = Schedules::query()
            ->select([
                'schedules.SCHED_DATE',
                'schedules.SHIFT_ID',
                DB::raw('IF(schedules.SCHED_STATUS = 0, COUNT(*), 0) AS W'),
                DB::raw('IF(schedules.SCHED_STATUS = 1, COUNT(*), 0) AS P'),
                DB::raw('IF(schedules.SCHED_STATUS = 2, COUNT(*), 0) AS A'),
                DB::raw('IF(schedules.SCHED_STATUS = 3, COUNT(*), 0) AS C'),
            ])
            ->join('contact AS c', 'c.ID', '=', 'schedules.CONTACT_ID')
            ->join('shift AS s', 's.ID', '=', 'schedules.SHIFT_ID')
            ->where('c.TYPE', 3)
            ->whereBetween('schedules.SCHED_DATE', [$dateFrom, $dateTo])
            ->where('schedules.LOCATION_ID', $LOCATION_ID)
            ->groupBy(['schedules.SCHED_DATE','schedules.SHIFT_ID', 'schedules.SCHED_STATUS']);

        $result = DB::table(DB::raw("({$subQuery->toSql()}) as sched"))
            ->mergeBindings($subQuery->getQuery()) // you need to merge bindings
            ->select([
                'sched.SCHED_DATE',
                'sched.SHIFT_ID',
                DB::raw('SUM(sched.W) AS W'),
                DB::raw('SUM(sched.P) AS P'),
                DB::raw('SUM(sched.A) AS A'),
                DB::raw('SUM(sched.C) AS C'),
            ])
            ->groupBy(['sched.SCHED_DATE','sched.SHIFT_ID'])
            ->get();


        return $result;
    }
}
