<?php

namespace App\Livewire\PriceLevelPage;

use App\Services\PriceLevelServices;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Price Level - List')]
class PriceLevelList extends Component
{
    public $priceLevels = [];
    public $search = '';
    public function updatedsearch(PriceLevelServices $priceLevelServices)
    {
        $this->priceLevels = $priceLevelServices->Search($this->search);
    }
    public function delete($id, PriceLevelServices $priceLevelServices)
    {
        try {
            $priceLevelServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->priceLevels = $priceLevelServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(PriceLevelServices $priceLevelServices)
    {
        $this->priceLevels = $priceLevelServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.price-level.price-level-list');
    }
}
