<?php

namespace App\Livewire;

use Livewire\Attributes\Reactive;
use Livewire\Component;
 use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateQRCode extends Component
{   
    #[Reactive]
    public string $code;

    public function render()
    {       
         $qrCode =  $this->code ? QrCode::size(100)->generate($this->code) : null;

        return view('livewire.generate-qrcode', ['qrcode' => $qrCode, ]);

        
    }
}
