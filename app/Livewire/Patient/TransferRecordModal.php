<?php

namespace App\Livewire\Patient;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\ServiceChargeServices;
use Livewire\Attributes\On;
use Livewire\Component;

class TransferRecordModal extends Component
{

    public $showModal = false;
    private $hemoServices;
    private $serviceChargeServices;
    private $contactServices;
    public bool $IS_TREATMENT = false;
    public int $TRANSACTION_ID = 0;
    public int $NEW_CONTACT_ID = 0;
    public int $LOCATION_ID = 0;
    public $contactList = [];
    public function boot(HemoServices $hemoServices, ServiceChargeServices $serviceChargeServices, ContactServices $contactServices)
    {
        $this->hemoServices = $hemoServices;
        $this->serviceChargeServices = $serviceChargeServices;
        $this->contactServices = $contactServices;
    }
    #[On('open-transfer-contact')]
    public function openModal($result)
    {

        $this->NEW_CONTACT_ID = 0;
        $this->LOCATION_ID = (int) $result['LOCATION_ID'];
        $this->IS_TREATMENT = (bool) $result['IS_TREATMENT'];
        $this->TRANSACTION_ID = (int) $result['TRANSACTION_ID'];
        $this->showModal = true;
    }
    public function SaveChange()
    {
        $this->validate(
            [
                'NEW_CONTACT_ID' => 'required|numeric|exists:contact,id'
            ],
            [],
            [
                'NEW_CONTACT_ID' => 'Patient name'
            ]
        );

        if ($this->IS_TREATMENT) {

            // Treatment
            $this->hemoServices->ChangePatient($this->TRANSACTION_ID, $this->NEW_CONTACT_ID);
            $this->dispatch('refresh-treatment-record');
            $this->showModal = false;
            return;
        }
            // Service Charges
        $this->serviceChargeServices->ChangePatient($this->TRANSACTION_ID, $this->NEW_CONTACT_ID);
        $this->dispatch('refresh-service-charge-record');
        $this->showModal = false;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        if ($this->showModal) {
            $this->contactList = $this->contactServices->getPatientList($this->LOCATION_ID);
        }
        return view('livewire.patient.transfer-record-modal');
    }
}
