<?php
namespace App\Livewire\ServiceCharge;

use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Component;

class AgreementFormModal extends Component
{

    public int $LOCATION_ID;
    public $HEMO_ID;
    public string $DATE         = "";
    public int $PATIENT_ID      = 0;
    public bool $showModal      = false;
    public $SHOW_DIALYZER_COUNT = false;

    private $locationServices;

    public function boot(LocationServices $locationServices)
    {
        $this->locationServices = $locationServices;

    }
    public function mount()
    {

    }

    #[On('open-agreement-form')]
    public function openModal($data)
    {

        $this->showModal           = true;
        $this->HEMO_ID             = (int) $data['HEMO_ID'];
        $this->DATE                = $data['DATE'];
        $this->PATIENT_ID          = $data['PATIENT_ID'];
        $this->LOCATION_ID         = $data['LOCATION_ID'];
        $this->SHOW_DIALYZER_COUNT = $this->locationServices->AgreementFormQtyAllowed($this->LOCATION_ID);

    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function render()
    {

        return view('livewire.service-charge.agreement-form-modal');
    }
}
