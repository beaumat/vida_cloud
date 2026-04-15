<?php

namespace App\Livewire\StatementOfAccount;

use Livewire\Attributes\Title;
use Livewire\Component;

class SoaModule extends Component
{
    #[Title('Statement Of Accounts')]
    public function render()
    {
        return view('livewire.statement-of-account.soa-module');
    }
}
