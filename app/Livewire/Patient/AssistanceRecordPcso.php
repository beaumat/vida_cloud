<?php

namespace App\Livewire\Patient;

use App\Services\PatientPaymentServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AssistanceRecordPcso extends Component
{   

    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCK_LOCATION_ID;
    public $dataList = [];
    private $patientPaymentServices;
    public float $BALANCE = 0;
    public function boot(PatientPaymentServices $patientPaymentServices)
    {
        $this->patientPaymentServices = $patientPaymentServices;
    }
    public function reload()
    {
        $this->dataList = $this->patientPaymentServices->AssistanceByType($this->CONTACT_ID, 94, $this->LOCK_LOCATION_ID);
    }
    public function render()
    {
            $this->reload();
            
        return view('livewire.patient.assistance-record-pcso');
    }
}
