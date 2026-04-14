<?php

namespace App\Livewire\Customer;

use App\Services\SalesReceiptServices;
use Livewire\Component;

class CustomerSalesReceipt extends Component
{

    public int $customerId;
    public $dataList = [];
    private $salesReceiptServices;
    public function boot(SalesReceiptServices $salesReceiptServices)
    {
        $this->salesReceiptServices = $salesReceiptServices;
    }

    public function mount(int $id = 0)
    {
        $this->customerId = $id;
    }

    public function render()
    {   
        $this->dataList = $this->salesReceiptServices->listViaContact($this->customerId);
        return view('livewire.customer.customer-sales-receipt');
    }
}
