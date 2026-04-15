<?php

namespace App\Livewire\PhilHealth;

use App\Services\Cf4DoctorOrderServices;
use App\Services\DoctorOrderDefaultServices;
use App\Services\HemoServices;
use Livewire\Attributes\On;
use Livewire\Component;

class DoctorOrder extends Component
{

    public bool $showModal = false;
    public int $HEMO_ID  = 0;
    public int $LOCATION_ID;
    public string $DOCTOR_ORDER;
    private $hemoServices;
    private $cf4DoctorOrderServices;
    private $doctorOrderDefaultServices;
    public $dataList = [];
    public function boot(
        HemoServices $hemoServices,
        Cf4DoctorOrderServices $cf4DoctorOrderServices,
        DoctorOrderDefaultServices $doctorOrderDefaultServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->cf4DoctorOrderServices = $cf4DoctorOrderServices;
        $this->doctorOrderDefaultServices = $doctorOrderDefaultServices;
    }
    #[On('doctor-order-show')]
    public function openModal($result)
    {
        $this->HEMO_ID = (int) $result['HEMO_ID'];
        
        $data = $this->hemoServices->get($this->HEMO_ID); // virify
        if ($data) {
            $this->LOCATION_ID = $data->LOCATION_ID;
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function AutoSetDefault()
    {
        // check have item
        if ($this->cf4DoctorOrderServices->dataIsExists($this->HEMO_ID)) {
            return;
        }
        $data = $this->doctorOrderDefaultServices->getListByLocation($this->LOCATION_ID);
        foreach ($data as $item) {
            $this->cf4DoctorOrderServices->Store($this->HEMO_ID, $item->DESCRIPTION);
        }
    }
    public $editID = null;
    public function edit($ID)
    {
        $data = $this->cf4DoctorOrderServices->Get($ID);
        if ($data) {
            $this->editID = $data->ID;
            $this->DOCTOR_ORDER = $data->DESCRIPTION ?? '';
        }
    }

    public function cancel()
    {
        $this->editID = null;
    }
    public function save()
    {
        $this->cf4DoctorOrderServices->Update($this->editID, $this->DOCTOR_ORDER);
        session()->flash('message', 'Successfully save');
        $this->editID = null;
        $this->dispatch('refresh-treatment-summary');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        if ($this->showModal) {
            $this->dataList = $this->cf4DoctorOrderServices->GetList($this->HEMO_ID);
        }
        return view('livewire.phil-health.doctor-order');
    }
}
