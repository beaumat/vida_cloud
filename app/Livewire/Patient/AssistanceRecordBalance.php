<?php

namespace App\Livewire\Patient;

use App\Services\ServiceChargeServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AssistanceRecordBalance extends Component
{
    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCK_LOCATION_ID;
    public $dataList = [];
    public float $BALANCE = 0;
    private $serviceChargeServices;
    public function boot(ServiceChargeServices $serviceChargeServices)
    {
        $this->serviceChargeServices = $serviceChargeServices;
    }

    public function reload()
    {
        $this->dataList = $this->serviceChargeServices->getBalanceItem($this->CONTACT_ID, $this->LOCK_LOCATION_ID);
    }
    public function render()
    {

        $this->reload();

        return view('livewire.patient.assistance-record-balance');
    }
}
