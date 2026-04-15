<?php

namespace App\Livewire\Customer;
use App\Services\PaymentServices;
use Livewire\Component;

class CustomerPayments extends Component
{   
    public int $customerId;
    public $dataList = [];
    private $paymentServices;
    public function boot(PaymentServices $paymentServices)
    {
        $this->paymentServices = $paymentServices;
    }
    public function mount(int $id = 0)
    {
        $this->customerId = $id; // Initialize the customerId with the provided id or default to 0
        // Initialize any properties or services needed for the component
    }
    public function render()
    {   
        $this->dataList = $this->paymentServices->listViaContact($this->customerId);
        // Fetch the list of payments for the customerId
        return view('livewire.customer.customer-payments');
    }
}
