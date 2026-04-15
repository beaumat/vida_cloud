<?php

namespace App\Livewire\Hemodialysis;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class AddDefaultModal extends Component
{
 
    
    #[Reactive]
    public int $HEMO_ID;
    #[Reactive]
    public int $LOCATION_ID;

    public bool $showModal = false;
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function openModal()
    {
        $this->showModal = true;
    }
    public function render()
    {
        return view('livewire.hemodialysis.add-default-modal');
    }
}
