<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthServices;
use Livewire\Attributes\On;
use Livewire\Component;

class PrintModal extends Component
{

    public bool $BASE_PRESIGN = false;
    public bool $showModal = false;
    public int $PHILHEALTH_ID;
    public string $CODE;
    public int $LOCATION_ID;
    public string $DATE;
    private $philHealthServices;
    public string $PATIENT_NAME;
    public string $DATE_ADMITTED;
    public string $DATE_DISCHARGED;
    public string $FIRST_CASE;
    public string $FINAL_DIAGNOSIS;
    public int $HEMO_TOTAL;
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;
    }
    #[On('philhealth-print-data')]
    public function openModal($result)
    {

        $this->BASE_PRESIGN = false;
        $this->PHILHEALTH_ID = $result['PHILHEALTH_ID'];

        $data =  $this->philHealthServices->getPrint($this->PHILHEALTH_ID);

        if ($data) {
            $this->CODE = $data->CODE;
          //  $this->LOCATON_ID = $data->LOCATION_ID;
            $this->DATE = $data->DATE;
            $this->PATIENT_NAME = $data->CONTACT_NAME;
             $this->LOCATION_ID = $data->LOCATION_ID;
            $this->DATE_ADMITTED = $data->DATE_ADMITTED;
            $this->DATE_DISCHARGED = $data->DATE_DISCHARGED;
            $this->FIRST_CASE = $data->P1_TOTAL;
            $this->HEMO_TOTAL = $data->HEMO_TOTAL;
            $this->FINAL_DIAGNOSIS = $data->FINAL_DIAGNOSIS;
            $this->showModal = true;
        }
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.phil-health.print-modal');
    }
}
