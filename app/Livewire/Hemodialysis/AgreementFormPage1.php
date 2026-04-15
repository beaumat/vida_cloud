<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use App\Services\PhicAgreementFormServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormPage1 extends Component
{

    #[Reactive]
    public int $HEMO_ID;
    public string $DATE;
    public int $NO_OF_TREATMENT = 0;
    public string $PHIC_INCHARGE_NAME = "";
    public string $PHIC_INCHARGE_POSITION ="";

    public $typeOneList = [];
    public $typeTwoList = [];
    public $typeThreeList = [];
    public $typeFourList = [];
    public $typeFiveData;
    private $hemoServices;
    private $locationServices;
    private $phicAgreementFormServices;
    private $contactServices;
    public function boot(
        HemoServices $hemoServices,
        LocationServices $locationServices,
        PhicAgreementFormServices $phicAgreementFormServices,
        ContactServices $contactServices
    ) {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->phicAgreementFormServices = $phicAgreementFormServices;
        $this->contactServices = $contactServices;
    }

    public function mount()
    {

        $data = $this->hemoServices->Get($this->HEMO_ID);
        if ($data) {
            $this->DATE = $data->DATE;
            $this->NO_OF_TREATMENT = $this->hemoServices->GotNotTreatmentOnAvailment($data->CUSTOMER_ID, $data->LOCATION_ID, $this->DATE);
          
            $locData = $this->locationServices->get($data->LOCATION_ID);
            if ($locData) {

                // $this->ADMIN_NAME ="";
                $conPHIC = $this->contactServices->get($locData->PHIC_INCHARGE2_ID > 0 ? $locData->PHIC_INCHARGE2_ID : Auth()->user()->contact_id, 2); // Employee
                if ($conPHIC) {
                    $this->PHIC_INCHARGE_NAME = $conPHIC->PRINT_NAME_AS ?? '';
                    $this->PHIC_INCHARGE_POSITION = $conPHIC->NICKNAME ?? '';
                }
                $this->TypeOne();
                $this->TypeTwo();
                $this->TypeThree();
                $this->TypeFour();

            }
        }

    }
    private function TypeOne()
    {
        $this->typeOneList = $this->phicAgreementFormServices->getTitleByType(1, $this->HEMO_ID);
    }
    private function TypeTwo()
    {
        $this->typeTwoList = $this->phicAgreementFormServices->getTitleByType(2, $this->HEMO_ID);
    }
    private function TypeThree()
    {
        $this->typeThreeList = $this->phicAgreementFormServices->getTitleByType(3, $this->HEMO_ID);
    }
    private function TypeFour()
    {
        $this->typeFourList = $this->phicAgreementFormServices->getTitleByType(4, $this->HEMO_ID);
    }

    public function render()
    {
        return view('livewire.hemodialysis.agreement-form-page1');
    }
}
