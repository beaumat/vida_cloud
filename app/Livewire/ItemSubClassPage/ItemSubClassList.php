<?php

namespace App\Livewire\ItemSubClassPage;

use App\Services\ItemSubClassServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Item Sub-Class - List')]
class ItemSubClassList extends Component
{

    public $itemSubClass = [];
    public $search = '';

    private $itemSubClassServices;
    public function boot(ItemSubClassServices $itemSubClassServices)
    {
        $this->itemSubClassServices = $itemSubClassServices;
    }
    public function updatedsearch()
    {
        $this->itemSubClass =  $this->itemSubClassServices->Search($this->search);
    }
    public function delete($id)
    {
        try {
            $this->itemSubClassServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->itemSubClass =  $this->itemSubClassServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function mount()
    {
        $this->itemSubClass =  $this->itemSubClassServices->Search($this->search);
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
        return view('livewire.item-sub-class.item-sub-class-list');
    }
}
