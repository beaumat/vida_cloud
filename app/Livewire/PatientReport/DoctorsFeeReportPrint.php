<?php

namespace App\Livewire\PatientReport;

use App\Services\DoctorPFServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Doctors Professional Fee - Print')]
class DoctorsFeeReportPrint extends Component
{

    public $PRINT_ID = [];
    public int $locationid = 0;
    public function mount($id, $locationid)
    {   
        $this->locationid = $locationid;
        if (!$id) {
            $this->PRINT_ID = [];
            return;
        }

        $this->PRINT_ID = explode(',', $id);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.patient-report.doctors-fee-report-print');
    }
}
