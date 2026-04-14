<?php

namespace App\Livewire\PhilHealth;

use App\Services\PhilHealthServices;
use Livewire\Component;

class PrintComputation extends Component
{

    public float $P1_PHIC_AMOUNT;
    public float $DRUG_N_MEDINE_AMOUNT;
    public float $OPERATING_ROOM_FEE_AMOUNT;
    public float $ROOM_FEE;
    public float $SUPPLIES;
    public float $PROF_FEE_AMOUNT;
    public int $NO_OF_TREATMENT;
    public int $PRINT_ID;
   
    private $philHealthServices;
   
    public function boot(PhilHealthServices $philHealthServices)
    {
        $this->philHealthServices = $philHealthServices;

    }
    public function mount($PRINT_ID)
    {
        $this->PreLoad($PRINT_ID);
        $this->P1_PHIC_AMOUNT = $this->philHealthServices->P1_PHIC_AMOUNT;
        $this->DRUG_N_MEDINE_AMOUNT = $this->philHealthServices->DRUG_N_MEDINE_AMOUNT;
        $this->OPERATING_ROOM_FEE_AMOUNT = $this->philHealthServices->OPERATING_ROOM_FEE_AMOUNT;
        $this->ROOM_FEE = $this->philHealthServices->ROOM_FEE;
        $this->SUPPLIES = $this->philHealthServices->SUPPLIES;
        $this->PROF_FEE_AMOUNT = $this->philHealthServices->PROF_FEE_AMOUNT;


    }
    public function PreLoad($ID)
    {
        if (is_numeric($ID)) {
            $data = $this->philHealthServices->get($ID);
            if ($data) {
                $this->NO_OF_TREATMENT = $this->philHealthServices->getNumberOfTreatment($data->CONTACT_ID, $data->LOCATION_ID, $data->DATE_ADMITTED, $data->DATE_DISCHARGED);            
            }
        }
    }
    public function render()
    {
        return view('livewire.phil-health.print-computation');
    }
}
