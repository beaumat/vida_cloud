<?php

namespace App\Livewire\PhilHealth;

use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class TreatmentSummary extends Component
{
    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCATION_ID;
    #[Reactive]
    public string $DATE_ADMITTED;
    #[Reactive]
    public string $DATE_DISCHARGED;
    private $hemoServices;
    public int $i;
    public $hemoList = [];

    public $editId = null;
    public $editDoctorOrder = '';
    public function clickEdit($id)
    {
        $this->editId = $id;
        $this->editDoctorOrder =   $this->hemoServices->GetDoctorOrder($id);
    }
    public function clickCancel()
    {
        $this->editId = null;
        $this->editDoctorOrder = '';
    }
    public function clickSave()
    {
        $this->hemoServices->UpdateDoctorOrder($this->editId, $this->editDoctorOrder);
        $this->clickCancel();
    }
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }
    public function mount($CONTACT_ID, $LOCATION_ID, $DATE_ADMITTED, $DATE_DISCHARGED)
    {
        $this->CONTACT_ID = $CONTACT_ID;
        $this->LOCATION_ID = $LOCATION_ID;
        $this->DATE_ADMITTED = $DATE_ADMITTED;
        $this->DATE_DISCHARGED = $DATE_DISCHARGED;
    }
    public function OpenModify(int $ID)
    {
        $data = [
            'HEMO_ID' => $ID
        ];
        $this->dispatch('doctor-order-show', result: $data);
    }
    #[On('refresh-treatment-summary')]
    public function render()
    {
        $this->i = 0;
        $this->hemoList = $this->hemoServices->GetSummary($this->CONTACT_ID, $this->LOCATION_ID, $this->DATE_ADMITTED, $this->DATE_DISCHARGED);

        return view('livewire.phil-health.treatment-summary');
    }
}
