<?php

namespace App\Livewire\Layouts;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class Header extends Component
{

    public function openChangePassword()
    {
        $this->dispatch('open-change-password');
    }

    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect(route('login'));
    }
    public function render()
    {
        return view('livewire.Layouts.header');
    }
}
