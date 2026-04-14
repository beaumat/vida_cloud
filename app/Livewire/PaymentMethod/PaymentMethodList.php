<?php

namespace App\Livewire\PaymentMethod;

use App\Services\PaymentMethodServices;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Payment Method')]
class PaymentMethodList extends Component
{

    public $paymentMethods = [];
    public $search = '';
    private $paymentMethodServices;
    public function boot(PaymentMethodServices $paymentMethodServices)
    {
        $this->paymentMethodServices = $paymentMethodServices;
    }
    public function delete($id)
    {
        try {
            $this->paymentMethodServices->Delete($id);
            session()->flash('message', 'Successfully deleted.');
            $this->paymentMethods = $this->paymentMethodServices->Search($this->search);
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }
    public function render()
    {
        $this->paymentMethods = $this->paymentMethodServices->Search($this->search);
        return view('livewire.payment-method.payment-method-list');
    }
}
