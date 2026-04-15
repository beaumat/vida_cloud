<?php

namespace App\Livewire;

use Livewire\Component;

class TableView extends Component
{

    public $headers = [];
    public $rows = [];
    public $actions = [];

    public function mount($headers = [], $rows = [], $actions = [])
    {
        $this->headers = $headers;
        $this->rows = $rows;
        $this->actions = $actions;
    }
    public function render()
    {
        return view('livewire.table-view');
    }
}
