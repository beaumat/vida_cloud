<?php

namespace App\Livewire\Hemodialysis;

use App\Services\ContactServices;
use App\Services\HemoServices;
use App\Services\LocationServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class PrintBack extends Component
{

    #[Reactive]
    public int $HEMO_ID;
    public string $FULL_NAME;
    public string $CODE;
    public $DOB;
    public int $AGE;
    private $hemoServices;
    private $locationServices;
    public $REPORT_HEADER_1;
    public string $LOGO_FILE = '';
    private $contactServices;
    public function boot(HemoServices $hemoServices, LocationServices $locationServices, ContactServices $contactServices)
    {
        $this->hemoServices = $hemoServices;
        $this->locationServices = $locationServices;
        $this->contactServices = $contactServices;
    }
    public function mount()
    {

        $data = $this->hemoServices->GetFirst($this->HEMO_ID);
        $this->FULL_NAME = $data->CONTACT_NAME;
        $this->CODE = $data->CODE;
        $this->DOB = $data->DATE_OF_BIRTH;
        $this->AGE = $this->contactServices->calculateUserAge($this->DOB);
        $locData =  $this->locationServices->get($data->LOCATION_ID);
        if ($locData) {
            $this->REPORT_HEADER_1 = $locData->REPORT_HEADER_1 ?? '';
            $this->LOGO_FILE = $locData->LOGO_FILE ?? '';
        }
    }

    public function render()
    {

        return view('livewire.hemodialysis.print-back');
    }
}
