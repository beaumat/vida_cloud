<?php

namespace App\Livewire\PatientReport;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Patient Sales Report - Print')]
class PatientSalesReportPrint extends Component
{
    public int $LOCATION_ID;
    public string $DATE_TRANSACTION_FROM;
    public string $DATE_TRANSACTION_TO;

    public function mount($date_from, $date_to, $location_id)
    {
        $this->LOCATION_ID = $location_id;
        $this->DATE_TRANSACTION_FROM = $date_from;
        $this->DATE_TRANSACTION_TO = $date_to;

        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.patient-report.patient-sales-report-print');
    }
}
