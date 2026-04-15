<?php

namespace App\Livewire\Scheduler;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class PreviewPdf extends Component
{
    #[Reactive]
    public $weekdays = [];
    #[Reactive]
    public $LOCATION_ID;
    #[Reactive]
    public $shiftList;

    public function render()
    {
        return view('livewire.scheduler.preview-pdf');
    }
}
