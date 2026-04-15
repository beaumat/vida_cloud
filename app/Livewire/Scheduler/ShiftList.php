<?php
namespace App\Livewire\Scheduler;

use App\Services\ScheduleServices;
use Carbon\Carbon;
use Livewire\Component;

class ShiftList extends Component
{
    private $scheduleServices;
    public Carbon $Date;
    public $select_date;
    public int $LOCATION_ID;
    public int $STATUS_ID;
    public $totalPatientsByShift = [];
    public function boot(ScheduleServices $scheduleServices)
    {
        $this->scheduleServices = $scheduleServices;
    }
    public function mount($date, $location_id, $select_date)
    {

        $this->Date        = Carbon::createFromFormat('Y-m-d', $date);
        $this->LOCATION_ID = $location_id;
        $this->select_date = $select_date;

    }
    public function getList()
    {
        $this->totalPatientsByShift = $this->scheduleServices->DailyContactSchedule($this->Date, $this->LOCATION_ID);
    }
    public function render()
    {
        $this->getList();
        return view('livewire.scheduler.shift-list');
    }
}
