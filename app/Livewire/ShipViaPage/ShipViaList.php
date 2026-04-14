<?php

namespace App\Livewire\ShipViaPage;

use App\Services\ShipViaServices;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Ship Via - List')]
class ShipViaList extends Component
{


    public $shipVia = [];
    public $search = '';

    public function updatedsearch(ShipViaServices $shipViaServices)
    {
        $this->shipVia = $shipViaServices->Search($this->search);
    }
    public function delete($id, ShipViaServices $shipViaServices)
    {

        try {

            $shipViaServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->shipVia = $shipViaServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(ShipViaServices $shipViaServices)
    {
        $this->shipVia = $shipViaServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.ship-via.ship-via-list');
    }
    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }







}
