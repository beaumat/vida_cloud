<?php
namespace App\Livewire\Location;
use App\Services\LocationServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Location List')]
class LocationList extends Component
{   
    public $locations = [];
    public $search = '';
    public function updatedsearch(LocationServices $locationServices)
    {
        $this->locations = $locationServices->Search($this->search);
    }
    public function delete($id, LocationServices $locationServices)
    {
        try {
            $locationServices->Delete($id);
            session()->flash('message','Successfully deleted.');
            $this->locations = $locationServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error',$errorMessage);
        }        
    }
    public function mount(LocationServices $locationServices)
    {
        $this->locations = $locationServices->Search($this->search);
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
        return view('livewire.location.location-list');
    }
}
