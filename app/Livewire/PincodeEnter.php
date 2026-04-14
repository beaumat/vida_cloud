<?php

namespace App\Livewire;

use App\Services\ContactServices;
use Livewire\Attributes\On;
use Livewire\Component;

class PincodeEnter extends Component
{
    public string $PIN;
    public bool $showModal = false;
    private $contactServices;
    public function boot(ContactServices $contactServices)
    {
        $this->contactServices = $contactServices;
    }
    public function logpin()
    {
        $this->validate(
            ['PIN' => 'required|string'],
            [],
            ['PIN' => 'Pin password']
        );
        $EMP_ID =   $this->contactServices->pinLogin($this->PIN);
        if ($EMP_ID == 0) {
            session()->flash('error', 'Invalid pin password');
            return;
        }

        $result = [
            'EMPLOYEE_ID'  => $EMP_ID
        ];

        $this->dispatch('pin-login-success', result: $result);
        $this->closeModal();
    }
    #[On('open-pin-code')]
    public function openModal()
    {
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.pincode-enter');
    }
}
