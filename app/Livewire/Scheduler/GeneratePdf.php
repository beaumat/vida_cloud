<?php

namespace App\Livewire\Scheduler;

use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class GeneratePdf extends Component
{
    #[Reactive]
    public $weekdays;
    #[Reactive]
    public $LOCATION_ID;
    #[Reactive]
    public $shiftList;
    public function generatePdf()
    {
        $this->dispatch('schedule-modal-close');
        $this->dispatch('print-view');
    }

    #[On('print-view')]
    public function Proccess()
    {
        $pdfContent = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.scheduler.preview-pdf', [
            'weekdays' => $this->weekdays,
            'LOCATION_ID' => $this->LOCATION_ID,
            'shiftList' => $this->shiftList
        ])->setPaper('letter', 'landscape')->output();

        $data = response()->streamDownload(
            fn() => print ($pdfContent),
            "weeklyschedule.pdf"
        );

        // Return a Livewire response to indicate the download has started
        return $data;
    }
    public function render()
    {
        return view('livewire.scheduler.generate-pdf');
    }
}
