<?php

namespace App\Livewire\Patient;

use App\Services\ContactRequirementServices;
use App\Services\ContactServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class RequirementPanel extends Component
{
    #[Reactive]
    public int $CONTACT_ID;
    public $dataList = [];
    private $contactRequirementServices;
    private $contactServices;
    public bool $ALLOWED_TO_CONFIRM = false;
    public function boot(ContactRequirementServices $contactRequirementServices, ContactServices $contactServices)
    {
        $this->contactRequirementServices = $contactRequirementServices;
        $this->contactServices = $contactServices;
    }

    public function markAsCompleted(): void
    {
        foreach ($this->dataList as $data) {
            $this->contactRequirementServices->UpdateMarking($data->ID, true, false);
        }
        $this->contactServices->UpdateIsCompleted($this->CONTACT_ID, true);
        $this->dispatch('refresh-requirements');
    
    }
    public function markAsNotApplicable(): void
    {
        foreach ($this->dataList as $data) {
            $this->contactRequirementServices->UpdateMarking($data->ID, false, true);
        }
        $this->contactServices->UpdateIsCompleted($this->CONTACT_ID, false);
        $this->dispatch('refresh-requirements');

    }
    private function reloadData()
    {
        $this->dataList = $this->contactRequirementServices->GetList($this->CONTACT_ID);
    }
    public function render()
    {

        $this->reloadData();

        return view('livewire.patient.requirement-panel');
    }
}
