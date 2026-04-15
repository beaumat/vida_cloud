<?php

namespace App\Livewire\PhilhealthPrint;

use App\Services\LocationServices;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('PhilHealth Printing SOA (Pre-sign)')]
class PrintOutSoaTemp extends Component
{

    public string $SOA_FORMAT = "";
    public $PRINT_ID = [];
    private $locationServices;
    public function boot(LocationServices $locationServices)
    {
        $this->locationServices = $locationServices;
    }
    public function mount($id)
    {

        $this->SOA_FORMAT = $this->locationServices->SOA_FORMAT(Auth::user()->location_id);

        if (!$id) {
            $this->PRINT_ID = [];
            return;
        }

        $this->PRINT_ID = explode(',', $id);
        $this->dispatch('preview_print');
    }
    #[On('preview_print')]
    public function print()
    {
        $this->dispatch('print');
    }
    public function render()
    {
        return view('livewire.philhealth-print.print-out-soa-temp');
    }
}
