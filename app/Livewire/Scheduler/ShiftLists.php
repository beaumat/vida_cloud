<?php
namespace App\Livewire\Scheduler;

use App\Services\ScheduleServices;
use Carbon\Carbon;
use Livewire\Component;

class ShiftLists extends Component
{
    private $scheduleServices;
    public Carbon $Date;
    public $select_date;
    public int $LOCATION_ID;
    public int $STATUS_ID;
    public $totalPatientsByShift = [];
    public $dataList = [];
    public function boot(ScheduleServices $scheduleServices)
    {
        $this->scheduleServices = $scheduleServices;
    }

    public function mount($date, $location_id, $select_date, $dataList = [])
    {

        $this->Date        = Carbon::createFromFormat('Y-m-d', $date);
        $this->LOCATION_ID = $location_id;
        $this->select_date = $select_date;
        $this->dataList = $dataList;


    }
    public function render()
    { foreach ($this->dataList as $list) {
            if ( Carbon::createFromFormat('Y-m-d', $list->SCHED_DATE) == $this->Date) {

                $this->totalPatientsByShift[] = [
                    'SHIFT_ID' => $list->SHIFT_ID,
                    'W'        => $list->W,
                    'P'        => $list->P,
                    'A'        => $list->A,
                    'C'        => $list->C,
                ];
            }

        }
        //
        return view('livewire.scheduler.shift-lists');
    }
}
