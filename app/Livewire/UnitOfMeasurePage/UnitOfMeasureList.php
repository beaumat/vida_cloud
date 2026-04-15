<?php

namespace App\Livewire\UnitOfMeasurePage;
use App\Services\UnitOfMeasureServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Unit of Measure List')]
class UnitOfMeasureList extends Component
{   
    public $unitOfMeasure = [];
    public $search = '';
    private $unitOfMeasureServices;
    public function boot(UnitOfMeasureServices $unitOfMeasureServices)
    {
        $this->unitOfMeasureServices = $unitOfMeasureServices;
    }

    #[On('clear-alert')]
    public function clearAlert()
    {
        $this->resetErrorBag();
        // Clear session message and error
        session()->forget('message');
        session()->forget('error');
    }

    public function delete(int $id)
    {
        try {
            $this->unitOfMeasureServices->Delete($id);
            session()->flash('message', 'Successfully deleted');
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $this->unitOfMeasure = $this->unitOfMeasureServices->Search($this->search);
        return view('livewire.unit-of-measure.unit-of-measure-list');
    }
}
