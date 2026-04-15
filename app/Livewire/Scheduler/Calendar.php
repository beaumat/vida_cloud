<?php
namespace App\Livewire\Scheduler;

use App\Models\Shift;
use App\Services\HemodialysisMachineServices;
use Carbon\Carbon;
use Livewire\Component;

class Calendar extends Component
{

    public $today;
    public int $year;

    public int $month;
    public $shiftList = [];
    public Carbon $currentDate;
    public $startDayOfWeek;
    public $daysInMonth;
    public $daysInPreviousMonth;
    public int $dayCounter = 1;
    public int $CONTACT_ID;
    public int $LOCATION_ID;
    public int $HEMO_MACHINE_ID;
    public $hemoMachineList = [];
    public string $contactName;
    public int $SHIFT_ID = 0;
    private $hemodialysisMachineServices;
    public function boot(HemodialysisMachineServices $hemodialysisMachineServices)
    {
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
    }
    public function mount(int $year, int $month, $contactid = null, $locationid = null, $hemomachineid = null)
    {
        $this->year            = $year;
        $this->month           = $month;
        $this->CONTACT_ID      = $contactid ? $contactid : 0;
        $this->LOCATION_ID     = $locationid ? $locationid : 0;
        $this->HEMO_MACHINE_ID = $hemomachineid ? $hemomachineid : 0;
    }

    private function reloadData()
    {
        $this->currentDate         = Carbon::create($this->year, $this->month, 1);
        $this->startDayOfWeek      = $this->currentDate->startOfMonth()->dayOfWeek;
        $this->daysInMonth         = $this->currentDate->daysInMonth;
        $this->daysInPreviousMonth = $this->currentDate->copy()->subMonth()->daysInMonth;
        $this->shiftList           = Shift::all();
    }
    public function updatedyear()
    {
        if ($this->year < 2020) {
            $this->year = 2020;
        }

        $this->reloadData();
    }
    public function updatedmonth()
    {
        $this->reloadData();
    }
    public function render()
    {
        $this->today = Carbon::now()->format('Y-m-d');
        $this->reloadData();
        $this->hemoMachineList = $this->hemodialysisMachineServices->GetList($this->LOCATION_ID);

        return view('livewire.scheduler.calendar');
    }
}
