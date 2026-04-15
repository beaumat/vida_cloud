<?php

namespace App\Livewire\LocationGroup;
use App\Services\LocationGroupServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location Group')]
class LocationGroupList extends Component
{
    public $locationGroup = [];
    public $search = '';
    public function updatedsearch(LocationGroupServices $locationGroupServices)
    {
        $this->locationGroup = $locationGroupServices->Search($this->search);
    }
    public function delete($id, LocationGroupServices $locationGroupServices)
    {
        try {
            $locationGroupServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->locationGroup = $locationGroupServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(LocationGroupServices $locationGroupServices)
    {
        $this->locationGroup = $locationGroupServices->Search($this->search);
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
        return view('livewire..location-group.location-group-list');
    }
}
