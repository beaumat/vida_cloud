<?php

namespace App\Livewire\DashboardPage;

use App\Services\ModeServices;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard')]
class Dashboard extends Component
{

    public function mount()
    {
        if (ModeServices::GET() == 'H') {
            if (UserServices::GetUserRightAccess('direct-to-treatment')) {
                if (UserServices::GetUserRightAccess('patient.treatment.view')) {
                    return Redirect::route('patientshemo');
                }
            }
        }
    }
    public function render()
    {
        return view('livewire.dashboard-page.dashboard');
    }
}
