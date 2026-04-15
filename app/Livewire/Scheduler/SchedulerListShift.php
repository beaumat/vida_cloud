<?php

namespace App\Livewire\Scheduler;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class SchedulerListShift extends Component
{

    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public string $DATE;
    public string $tab = 's1st';

    public function SelectTab(string $tab)
    {
        $this->tab = $tab;
    }

    // public  function updatedDate()
    // {
    //     $this->tab = 's1st';
    // }
    // public function updatedLocation()
    // {
    //     $this->tab = 's1st';
    // }
    public function render()
    {

        return view('livewire.scheduler.scheduler-list-shift');
    }
}
