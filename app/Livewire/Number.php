<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class Number extends Component
{
    #[Modelable]
    public $value = null;
    public string $name;
  
    public $isFocused = false;
    
    public function mount($name)
    {
        $this->name = $name;
    }
    public function render()
    {
        return view('livewire.number');
    }
}
