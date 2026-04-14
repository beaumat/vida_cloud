<?php

namespace App\Livewire\Scheduler;

use App\Services\ScheduleServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class WeeklyPatient extends Component
{

    #[Reactive]
    public string $DATE;
    #[Reactive]
    public int $SHIFT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    public $patientList = [];
    private $scheduleServices;
    public int $CAP = 20;
    public int $i = 1;
    public int $n = 21;
    public int $v = 23;
    public function boot(ScheduleServices $scheduleServices)
    {
        $this->scheduleServices = $scheduleServices;
    }
    public function mount($DATE, $SHIFT_ID, $LOCATION_ID)
    {  
        $this->DATE = $DATE;
        $this->SHIFT_ID = $SHIFT_ID;
        $this->LOCATION_ID = $LOCATION_ID;
  
    }
    public function render()
    {
        $this->patientList = $this->scheduleServices->PatientWeeklySchedule($this->SHIFT_ID, $this->DATE, $this->LOCATION_ID);
       
        return view('livewire.scheduler.weekly-patient');
    }
}
