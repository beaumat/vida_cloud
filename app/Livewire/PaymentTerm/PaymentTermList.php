<?php

namespace App\Livewire\PaymentTerm;

use App\Services\PaymentTermServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Terms')]
class PaymentTermList extends Component
{  
    public $paymentTerms = [];
    public $search = '';
    public function updatedsearch(PaymentTermServices $paymentTermServices)
    {
        $this->paymentTerms = $paymentTermServices->Search($this->search);
    }
    public function delete($id, PaymentTermServices $paymentTermServices)
    {
        try {
            $paymentTermServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->paymentTerms = $paymentTermServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function mount(PaymentTermServices $paymentTermServices)
    {
        $this->paymentTerms = $paymentTermServices->Search($this->search);
    }
    public function render()
    {
        return view('livewire.payment-term.payment-term-list');
    }
}
