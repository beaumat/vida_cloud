<?php

namespace App\Livewire\PhilHealth;

use App\Services\ContactServices;
use App\Services\PhilHealthServices;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Others extends Component
{
    #[Reactive]
    public int $PHILHEALTH_ID;
    public string $RR_NO;
    public string $CF4_DD_NOTES;
    public string $CF4_AD_NOTES;
    public string $CF4_COMPLAINT;
    public string $CF4_HPI;
    public string $CF4_PPMH;


    public string $CHIEF_OF_COMPLAINT_DEFAULT ;
    public string $ADMITTING_DIAGNOSIS_DEFAULT;
    public string $FINAL_DIAGNOSIS_DEFAULT;
    public string $HISTORY_OF_PRESENT_ILLNESS_DEFAULT ;
    public string $FINAL_DIAGNOSIS;
    
    private $philHealthServices;
    private $contactServices;
    public function boot(PhilHealthServices $philHealthServices,ContactServices $contactServices)
    {
        $this->philHealthServices = $philHealthServices;
        $this->contactServices = $contactServices;
    }
    public function mount()
    {   
        $this->CHIEF_OF_COMPLAINT_DEFAULT = $this->philHealthServices->CHIEF_OF_COMPLAINT_DEFAULT;
        $this->ADMITTING_DIAGNOSIS_DEFAULT = $this->philHealthServices->ADMITTING_DIAGNOSIS_DEFAULT;
        $this->HISTORY_OF_PRESENT_ILLNESS_DEFAULT = $this->philHealthServices->HISTORY_OF_PRESENT_ILLNESS_DEFAULT;
        
        $data = $this->philHealthServices->getCF4($this->PHILHEALTH_ID);
        if ($data) {
            $this->RR_NO = $data->RR_NO ?? '';
            $this->CF4_AD_NOTES = $data->CF4_AD_NOTES ?? '';
            $this->CF4_DD_NOTES = $data->CF4_DD_NOTES ?? '';
            $this->CF4_COMPLAINT = $data->CF4_COMPLAINT ?? '';
            $this->CF4_HPI = $data->CF4_HPI ?? '';
            $this->CF4_PPMH = $data->CF4_PPMH ?? '';
            $contactData = $this->contactServices->get($data->CONTACT_ID,3);
            if($contactData) {
                $this->FINAL_DIAGNOSIS = $contactData->FINAL_DIAGNOSIS ?? '';
            }

        }
    }
    public function saveData()
    {
        $this->philHealthServices->setCF4Update(
            $this->PHILHEALTH_ID,
            $this->RR_NO,
            $this->CF4_AD_NOTES,
            $this->CF4_DD_NOTES,
            $this->CF4_COMPLAINT,
            $this->CF4_HPI,
            $this->CF4_PPMH
        );
        session()->flash('message', 'Successfully update');
    }
    public function render()
    {
        return view('livewire.phil-health.others');
    }
}
