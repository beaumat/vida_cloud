<?php

namespace App\Livewire\ItemPage;

use Livewire\Attributes\Reactive;
use Livewire\Component;

class ItemUnitPanel extends Component
{
    #[Reactive]
    public int $itemId = 0;
    public string $unitTabSelect ='related';

    public function tabSelect($tab)
    {
        $this->unitTabSelect = $tab;
    }
    public function mount($itemId)
    {
        $this->itemId = $itemId;
    }
    public function render()
    {
        return view('livewire.item-page.item-unit-panel');
    }
}
