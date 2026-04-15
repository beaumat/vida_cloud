<?php

namespace App\Livewire\PatientReport;

use Livewire\Attributes\On;
use Livewire\Component;

class PhilHealthAvailmentListPrint extends Component
{

    public $PATIENT_ID = [];
    public $LOCATION_ID;
    public $YEAR;
    public function mount($id, $locationid, $year)
    {
        $this->LOCATION_ID = $locationid;
        $this->YEAR = $year;

        if (!$id) {
            $this->PATIENT_ID = [];
            return;
        }

        $this->PATIENT_ID = explode(',', $id);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.patient-report.phil-health-availment-list-print');
    }
}
