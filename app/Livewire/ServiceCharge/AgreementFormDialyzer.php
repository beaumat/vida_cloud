<?php
namespace App\Livewire\ServiceCharge;

use App\Services\ContactServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class AgreementFormDialyzer extends Component
{

    #[Reactive()]
    public string $DATE;
    #[Reactive()]
    public int $PATIENT_ID;
    #[Reactive()]
    public int $LOCATION_ID;

    private $contactServices;

    public $itemList = [];
    public int $TOTAL_QTY = 0;
    public function boot(ContactServices $contactServices)
    {
        $this->contactServices = $contactServices;
    }
    public function mount()
    {
        $this->itemList = $this->contactServices->getPatientAvailmentListDialyzerQtyDetails($this->PATIENT_ID, $this->LOCATION_ID, $this->DATE);

    }
    public function render()
    {
        return view('livewire.service-charge.agreement-form-dialyzer');
    }
}
