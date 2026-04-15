<?php

namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;
use Livewire\WithFileUploads;

class FileUpload extends Component
{
    use WithFileUploads;
    #[Modelable]
    public $value = null;
    public $showFile = false;
    public function clickShow()
    {
        $this->showFile = $this->showFile ? false : true;
    }
    public function updatedvalue()
    {
        $this->validate([
            'value' => 'file|mimes:pdf|max:10240', // PDF file, max 10MB
        ]);
    }
    public function render()
    {
        return view('livewire.file-upload');
    }
}
