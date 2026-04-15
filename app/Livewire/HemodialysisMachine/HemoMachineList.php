<?php

namespace App\Livewire\HemodialysisMachine;

use App\Services\HemodialysisMachineServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Hemodailysis Machine')]
class HemoMachineList extends Component
{
    public $data = [];
    public $search = '';
    private $hemodialysisMachineServices;
    public function boot(HemodialysisMachineServices $hemodialysisMachineServices)
    {
        $this->hemodialysisMachineServices = $hemodialysisMachineServices;
    }
    public function delete(int $id)
    {
        $this->hemodialysisMachineServices->Delete($id);
    }
    public function render()
    {
        $this->data = $this->hemodialysisMachineServices->Search($this->search);
        return view('livewire.hemodialysis-machine.hemo-machine-list');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        session()->forget('message');
        session()->forget('error');
    }
}
