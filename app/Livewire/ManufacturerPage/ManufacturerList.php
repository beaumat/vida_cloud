<?php

namespace App\Livewire\ManufacturerPage;

use App\Services\ManufacturerServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Manufacturer - List')]
class ManufacturerList extends Component
{
    public $manufacturer = [];
    public $search = '';
    public function updatedsearch(ManufacturerServices $manufacturerServices)
    {
        $this->manufacturer = $manufacturerServices->Search($this->search);
    }
    public function delete($id, ManufacturerServices $manufacturerServices)
    {
        try {
            $manufacturerServices->Delete($id);
            session()->flash('message','Successfully deleted.');
            $this->manufacturer = $manufacturerServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error',$errorMessage);
        }        
    }
    public function mount(ManufacturerServices $manufacturerServices)
    {
        $this->manufacturer = $manufacturerServices->Search($this->search);
    }
 
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

    public function render()
    {
        return view('livewire.manufacturer.manufacturer-list');
    }
}
