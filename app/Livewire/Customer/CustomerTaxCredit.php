<?php

namespace App\Livewire\Customer;


use App\Services\TaxCreditServices;
use Livewire\Component;

class CustomerTaxCredit extends Component
{
    public int $customerId;
    public $dataList = [];
    private $taxCreditServices;
    public function boot(TaxCreditServices $taxCreditServices)
    {
        $this->taxCreditServices = $taxCreditServices;
    }
    public function mount(int $id = 0)
    {
        $this->customerId = $id; // Initialize the customerId with the provided id or default to 0
        // Initialize any properties or services needed for the component
    }
    public function render()
    {
        $this->dataList = $this->taxCreditServices->listViaContact($this->customerId);

        return view('livewire.customer.customer-tax-credit');
    }
}
