<?php

namespace App\Livewire\StockBinPage;

use App\Models\StockBin;
use App\Services\StockBinServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Stock Bin - Form')]
class StockBinForm extends Component
{


    public int $ID;
    public string $CODE;
    public string $DESCRIPTION;

    public function mount($id = null)
    {
        if (is_numeric($id)) {

            $stockBin = StockBin::where('ID', $id)->first();

            if ($stockBin) {
                $this->ID = $stockBin->ID;
                $this->CODE = $stockBin->CODE;
                $this->DESCRIPTION = $stockBin->DESCRIPTION;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenanceinventorystock_bin')->with('error', $errorMessage);
        }

        $this->ID = 0;
        $this->CODE = '';
        $this->DESCRIPTION = '';
    }


    public function save(StockBinServices $stockBinServices)
    {
        $this->validate(
            [
                'CODE' => 'required|max:10|unique:stock_bin,code,' . $this->ID,
                'DESCRIPTION' => 'required|max:50|unique:stock_bin,description,' . $this->ID
            ],
            [],
            [
                'CODE' => 'Code',
                'DESCRIPTION' => 'Description'
            ]
        );

        try {
            if ($this->ID === 0) {
                $this->ID = $stockBinServices->Store($this->CODE, $this->DESCRIPTION);
                session()->flash('message', 'Successfully created.');
                return;
            }

            $stockBinServices->Update($this->ID, $this->CODE, $this->DESCRIPTION);
            session()->flash('message', 'Successfully updated.');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        return view('livewire.stock-bin.stock-bin-form');
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
