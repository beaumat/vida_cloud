<?php

namespace App\Livewire;

use App\Services\UserServices;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ChangePasswordModal extends Component
{

    public bool $showModal;

    public string $CURRENT;
    public string $NEW;
    private $userServices;
    public int $USER_ID;
    public function boot(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    #[On('open-change-password')]
    public function openModal()
    {
        $this->USER_ID = Auth::user()->id;
        $this->showModal = true;
    }
    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        $this->validate(
            [
                'CURRENT' => 'required',
                'NEW' => [
                    'required',
                    'string', // Ensures the input is a string
                    'min:4',  // Minimum length of 4 characters
                    'max:12', // Maximum length of 12 characters
                    'regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/' // At least one letter and one number
                ]
            ],
            [],
            [
                'CURRENT' => 'Current Password',
                'NEW' => 'New Password'
            ]
        );

        $isCorrect =  $this->userServices->IsPasswordCorrect($this->USER_ID, $this->CURRENT);
        if (!$isCorrect) {
            session()->flash('error', 'Invalid current password');
            return;
        }

        $this->userServices->ChangePassword($this->USER_ID, $this->CURRENT, $this->NEW);
        $this->CURRENT = "";
        $this->NEW = "";
        session()->flash('message', 'Successfully change password');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
    public function render()
    {
        return view('livewire.change-password-modal');
    }
}
