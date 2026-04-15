<?php

namespace App\Livewire\Scheduler;

use App\Services\DateServices;
use App\Services\ShiftServices;
use Livewire\Component;

class PrintWeekly extends Component
{       
    public $weekly = [];
    public $shiftData ;
    private $dateServices;
    private $shiftServices;
    public int $LOCATION_ID;
    public int $SHIFT_ID;
    public function boot(DateServices $dateServices,ShiftServices $shiftServices)
    {
            $this->dateServices = $dateServices;
            $this->shiftServices = $shiftServices;
    }
    public function mount(int $week_id, int $year, int $month, int $locationid, int $shift)
    {       
            $this->shiftData = $this->shiftServices->get($shift);
            $this->LOCATION_ID = $locationid;
            $this->SHIFT_ID = $shift;
            $this->weekly = $this->dateServices->Get7Days($year,$month,$week_id);
    }
    
    public function render()
    {
        return view('livewire.scheduler.print-weekly');
    }
}
