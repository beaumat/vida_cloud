<?php

namespace App\Livewire\Tax;

use App\Services\TaxServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Tax List')]
class TaxList extends Component
{

    public $taxList = [];
    public $search = '';
    public function updatedsearch(TaxServices $taxServices)
    {
        $this->taxList = $taxServices->Search($this->search);
    }
    public function delete($id, TaxServices $taxServices)
    {
        try {
            $taxServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->taxList = $taxServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(TaxServices $taxServices)
    {
        $this->taxList = $taxServices->Search($this->search);
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
        return view('livewire.tax.tax-list');
    }
}
