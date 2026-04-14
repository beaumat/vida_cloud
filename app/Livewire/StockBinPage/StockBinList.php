<?php

namespace App\Livewire\StockBinPage;
use App\Services\StockBinServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Stock Bin - List')]
class StockBinList extends Component
{

    public $stockBin = [];
    public $search = '';
    public function updatedsearch(StockBinServices $stockBinServices)
    {
        $this->stockBin = $stockBinServices->Search($this->search);
    }
    public function delete($id, StockBinServices $stockBinServices)
    {
        try {
            $stockBinServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->stockBin = $stockBinServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(StockBinServices $stockBinServices)
    {
        $this->stockBin = $stockBinServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.stock-bin.stock-bin-list');
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
