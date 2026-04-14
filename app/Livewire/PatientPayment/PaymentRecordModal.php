<?php

namespace App\Livewire\PatientPayment;

use App\Services\UserServices;
use Livewire\Attributes\On;
use Livewire\Component;

class PaymentRecordModal extends Component
{
    public $showModal = false;
    public int $CONTACT_ID = 0;
    public int $LOCK_LOCATION_ID = 0;
    private $userServices;
    public function boot(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }
    #[On('open-assistance')]
    public function openModal($result)
    {
        $this->CONTACT_ID = $result['CONTACT_ID'];
        if ($this->userServices->isLocationLock()) {
            $this->LOCK_LOCATION_ID = $this->userServices->getLocationDefault();
        }

        $this->showModal = true;
    }
    public function closeModal()
    {

        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.patient-payment.payment-record-modal');
    }
}
