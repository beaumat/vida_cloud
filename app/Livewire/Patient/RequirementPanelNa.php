<?php

namespace App\Livewire\Patient;

use App\Services\ContactRequirementServices;
use App\Services\ContactServices;

use Livewire\Component;

class RequirementPanelNa extends Component
{
    public bool $VALUE;
    public int $ID;
public int $CONTACT_ID;
    private $contactRequirementServices;
    private $contactServices;
    public function boot(ContactRequirementServices $contactRequirementServices, ContactServices $contactServices)
    {
        $this->contactRequirementServices = $contactRequirementServices;
        $this->contactServices = $contactServices;
    }
    public function mount($ID, $VALUE, $CONTACT_ID)
    {
        $this->ID = $ID;
        $this->VALUE = $VALUE;
        $this->CONTACT_ID = $CONTACT_ID;
    }
    public function updatedvalue()
    {
        $this->contactRequirementServices->UpdateNotApplicable($this->ID, $this->VALUE);
        $count = $this->contactRequirementServices->GetCountRequirement($this->CONTACT_ID);
        if ($count > 0) {
            $this->contactServices->UpdateIsCompleted($this->CONTACT_ID, false);
        } else {
            $this->contactServices->UpdateIsCompleted($this->CONTACT_ID, true);
        }
    }

    public function render()
    {
        return view('livewire.patient.requirement-panel-na');
    }
}
