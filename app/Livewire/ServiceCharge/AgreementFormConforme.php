<?php

namespace App\Livewire\ServiceCharge;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\UserServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormConforme extends Component
{

    #[Reactive]
    public int $HEMO_ID;
    public int $WITNESS_ID;
    public string $PATIENT_REP_NAME;
    public int $HD_FACILITY_REP_ID;
    public string $PHIC_INCHARGE_NAME;
    public int $PHIC_INCHARGE_ID;
    public int $LOCATION_ID;
    public int $PATIENT_ID;
    public $empList = [];
    public $phicList = [];
    private $contactServices;
    private $locationServices;
    private $hemoServices;
    private $userServices;
    public function boot(ContactServices $contactServices, LocationServices $locationServices, HemoServices $hemoServices, UserServices $userServices)
    {
        $this->contactServices = $contactServices;
        $this->locationServices = $locationServices;
        $this->hemoServices = $hemoServices;
        $this->userServices = $userServices;
    }
    public function mount()
    {
        $this->getInfo();
    }
    private function getInfo()
    {
        $hemoData = $this->hemoServices->Get($this->HEMO_ID);

        if ($hemoData) {
            $this->LOCATION_ID = $hemoData->LOCATION_ID;
            $this->PATIENT_ID = $hemoData->CUSTOMER_ID;

            $con = $this->contactServices->get($this->PATIENT_ID, 3);
            if ($con) {
                $this->WITNESS_ID = $con->WITNESS_ID ?? 0;
                $this->PATIENT_REP_NAME = $con->CONTACT_PERSON ?? 0;
            }

            $loc = $this->locationServices->get($this->LOCATION_ID);
            if ($loc) {
                $this->PHIC_INCHARGE_ID = (int) $loc->PHIC_INCHARGE2_ID > 0 ? $loc->PHIC_INCHARGE2_ID : 0;
                $this->phicList = $this->userServices->UserListByLocation($this->LOCATION_ID);

                // $this->PHIC_INCHARGE_NAME = $this->contactServices->getName($PHIC_INCHARGE_ID);
                $this->HD_FACILITY_REP_ID = $loc->HD_FACILITY_REP_ID ?? 0;
            }
            $this->empList = $this->contactServices->getList(2);
         
        }
    }
    public function updatedPHICINCHARGEID () {
        $this->locationServices->UpdatePhicIncharge($this->LOCATION_ID, $this->PHIC_INCHARGE_ID);
    }
    public function updatedHDFACILITYREPID()
    {
        $this->locationServices->UpdateHDFacifilityRep($this->LOCATION_ID, $this->HD_FACILITY_REP_ID);
    }
    public function updatedWITNESSID()
    {
            $this->contactServices->updateaWitNessOnly($this->PATIENT_ID,$this->WITNESS_ID);
    }
    public function render()
    {
        return view('livewire.service-charge.agreement-form-conforme');
    }
}
