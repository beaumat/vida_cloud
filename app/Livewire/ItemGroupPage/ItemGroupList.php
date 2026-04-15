<?php

namespace App\Livewire\ItemGroupPage;

use Livewire\Component;
use App\Services\ItemGroupServices;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

#[Title('Items Group - List')]
class ItemGroupList extends Component
{
    public $itemGroup = [];
    public $search = '';

    public function updatedsearch(ItemGroupServices $itemGroupServices)
    {
        $this->itemGroup =  $itemGroupServices->Search($this->search);
    }
    public function delete($id, ItemGroupServices $itemGroupServices)
    {
        try {
            $itemGroupServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->itemGroup =  $itemGroupServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(ItemGroupServices $itemGroupServices)
    {
        $this->itemGroup =  $itemGroupServices->Search($this->search);
    }
    public function render(ItemGroupServices $itemGroupServices)
    {
        $this->itemGroup =  $itemGroupServices->Search($this->search);
        return view('livewire.item-group.item-group-list');
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
