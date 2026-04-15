<?php

namespace App\Livewire\Patient;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class AssistanceRecord extends Component
{

    #[Reactive]
    public int $CONTACT_ID;
    #[Reactive]
    public int $LOCK_LOCATION_ID;

    public string $tab = 'dswd';
    public function SelectTab($tab)
    {
        $this->tab = $tab;
    }
    public function render()
    {   
        return view('livewire.patient.assistance-record');
    }
}
