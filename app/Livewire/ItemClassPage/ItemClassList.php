<?php

namespace App\Livewire\ItemClassPage;

use App\Services\ItemClassServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Item Class List')]
class ItemClassList extends Component
{
    public $itemClass = [];
    public $search = '';

    public function updatedsearch(ItemClassServices $itemClassServices)
    {
        $this->itemClass = $itemClassServices->Search($this->search);
    }
    public function delete($id, ItemClassServices $itemClassServices)
    {
        try {
            $itemClassServices->Delete($id);

            session()->flash('message', 'Successfully deleted.');
            $this->itemClass = $itemClassServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $$errorMessage);
        }
    }
    public function mount(ItemClassServices $itemClassServices)
    {
        $this->itemClass = $itemClassServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.item-class.item-class-list');
    }
}
