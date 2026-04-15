<?php
namespace App\Livewire\Hemodialysis;

use App\Services\HemoServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class DoctorsOrder extends Component
{
    #[Reactive]
    public int $HEMO_ID;

    #[Reactive]
    public bool $DOCTOR_DES_PROMP;

    public bool $showAlert = false;
    public string $DOCTOR_ORDER;
    private $hemoServices;
    public function boot(HemoServices $hemoServices)
    {
        $this->hemoServices = $hemoServices;
    }

    public function mount()
    {
        $this->DOCTOR_ORDER = $this->hemoServices->GetDoctorOrder($this->HEMO_ID);
        $this->showAlert    = false;
    }
    public function saveIt()
    {
        $this->hemoServices->UpdateDoctorOrder($this->HEMO_ID, $this->DOCTOR_ORDER);
        $this->dispatch('readfirst');
    }
    #[On('readfirst')]
    public function reloadPage()
    {
        return Redirect::route('patientshemo_edit', ['id' => $this->HEMO_ID]);
    }
    public function render()
    {

        if ($this->DOCTOR_DES_PROMP) {
            if ($this->DOCTOR_ORDER == '') {
                $this->showAlert = true;
            }
        }
        return view('livewire.hemodialysis.doctors-order');
    }
}
