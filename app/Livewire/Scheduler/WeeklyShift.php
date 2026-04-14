<?php

namespace App\Livewire\Scheduler;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class WeeklyShift extends Component
{
    #[Reactive]
    public $weekdays;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public int $SHIFT_ID;
    
    public function render()
    {
        return view('livewire.scheduler.weekly-shift');
    }
}
