<?php

namespace App\Livewire\Scheduler;

use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Print Schedule')]
class PrintSchedulesPrintOut extends Component
{
    public $LOCATION_ID;
    public int $YEAR;
    public int $MONTH;
    public int $SHIFT_ID;
    public $SHIFT_LIST = [];
    public int $WEEKLY_ID;
    private $shiftServices;
    public function boot(ShiftServices $shiftServices)
    {
        $this->shiftServices = $shiftServices;
    }
    public function mount($week, $location, $month, $year, $shift)
    {
        $this->SHIFT_ID = 0;
        $this->LOCATION_ID = $location;
        $this->YEAR = $year;
        $this->MONTH = $month;
        $this->SHIFT_ID = $shift;
        $this->WEEKLY_ID = $week;
        if ($shift == 0) {
            $this->SHIFT_LIST = $this->shiftServices->List();
        } else {
            $this->SHIFT_ID = $shift;
        }
        
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.scheduler.print-schedules-print-out');
    }
}
