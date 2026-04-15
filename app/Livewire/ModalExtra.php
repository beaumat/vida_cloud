<?php

namespace App\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class ModalExtra extends Component
{   
    #[Reactive]
    public bool $showModal = false;
    #[Reactive]
    public string $titleModal = '';

    public function closeModal() {
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.modal-extra');
    }
}
