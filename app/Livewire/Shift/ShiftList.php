<?php

namespace App\Livewire\Shift;

use App\Services\ShiftServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Shift')]
class ShiftList extends Component
{
    public $search;
    public $shiftList = [];
    private $shiftServices;
    public function boot(ShiftServices $shiftServices){
        $this->shiftServices = $shiftServices;
    }
    public function delete(int $id)
    {
        $this->shiftServices->Delete($id);
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
        $this->shiftList = $this->shiftServices->Search($this->search);
        return view('livewire.shift.shift-list');
    }
    
}
